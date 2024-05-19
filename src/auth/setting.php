<?php

/////////////////////////////
// Personal config DataTables 
////////////////////////////
require '../dashboard/session.php';
require 'connect.php';
$tabel = 'setting';
$Query = 'action';

//Message alert
$error  = 'error';
$qerror = 'query error';
$success = 'success';
$qsuccess = 'query success';

$action = '';
if (isset($_GET[$Query])){
	$action = $_GET[$Query];
  	if ($action == 'simpan' || 
      $action == 'sinkronisasi' || 
      $action == 'simpanBANK' || 
      $action == 'result' || 
      $action == 'so_attribute' ||
      $action == 'po_attribute' ||
      $action == 'item_result' ||
      $action == 'item_add' ||
      $action == 'item_get' ||
      $action == 'item_edit' ||
      $action == 'item_del'
    ){
      if(isset($_GET['id'])){
          $id = $_GET['id'];
          if (!is_numeric($id)){
            $id = '';
        }
      }
  	} else {
    	$action = '';
  	}
}

$mysqli_data = array();

if($action != ''){

  if ($action == 'simpan')
  {
    $email = mysqli_real_escape_string($connect, $_GET['email']);
    $password = mysqli_real_escape_string($connect, $_GET['password']);
    $url = mysqli_real_escape_string($connect, $_GET['url']);
    $file = fopen('../api.info','w');
    fwrite($file,$email."\n".$password."\n".$url);
    fclose($file);
    $result  = $success;
    $message = $qsuccess; 

	} elseif ($action == 'sinkronisasi') {
    $email = mysqli_real_escape_string($connect, $_GET['email']);
    $password = mysqli_real_escape_string($connect, $_GET['password']);
    $url = mysqli_real_escape_string($connect, $_GET['url']);

    $ch1 = curl_init();
    curl_setopt($ch1,CURLOPT_URL,$url.'?action=check&email='.$email.'&password='.$password);
    curl_setopt($ch1,CURLOPT_RETURNTRANSFER,true);
    $output1=curl_exec($ch1);
    curl_close($ch1);

    $dump = json_decode($output1, true);
    echo $dump['data'][0]['preorder_customer'];

  } elseif($action == 'simpanBANK') {
    $ket = 'BANK';
    $data_bank = array();
    $data = $_POST['data'];
    $query1 = "SELECT ket FROM $tabel WHERE ket = '".$ket."'";
    $insert1 = "INSERT INTO $tabel (ket, isi) VALUES ";
    $sql1 = $connect->query($query1);

    if($sql1->num_rows > 0)
    {
      $query2 = "DELETE FROM $tabel WHERE ket = '".$ket."'";
      $sql2 = $connect->query($query2);

      foreach($data['bank'] as $key => $value)
      {
        if(empty($data['bank'][$key]))  {$bank = 'NULL';} else {$bank = $data['bank'][$key];}
        if(empty($data['norek'][$key]))  {$norek = 'NULL';} else {$norek = $data['norek'][$key];}
        if(empty($data['atasnama'][$key])){$atasnama = 'NULL';} else {$atasnama = $data['atasnama'][$key];}

        $data_bank[] = "('BANK', '".$bank."-".$norek."-".$atasnama."')";
      }

      $insert1 .= implode(",", $data_bank);
      $sql3 = $connect->query($insert1);

      if(!$sql2 AND !$sql3)
      {
        $result  = $error;
        $message = $qerror;

      } else {
        $result  = $success;
        $message = $qsuccess;
      }

    } else {

      foreach($data['bank'] as $key => $value)
      {
        if(empty($data['bank'][$key]))  {$bank = 'NULL';} else {$bank = $data['bank'][$key];}
        if(empty($data['norek'][$key]))  {$norek = 'NULL';} else {$norek = $data['norek'][$key];}
        if(empty($data['atasnama'][$key])){$atasnama = 'NULL';} else {$atasnama = $data['atasnama'][$key];}

        $data_bank[] = "('BANK', '".$bank."-".$norek."-".$atasnama."')";
      }

      $insert1 .= implode(",", $data_bank);
      $sql3 = $connect->query($insert1);

      if(!$sql3)
      {
        $result  = $error;
        $message = $qerror;
      } else {
        $result  = $success;
        $message = $qsuccess;
      }
    }

  } elseif ($action == 'result') {
    $select = "SELECT * FROM log ORDER BY id DESC";
    $sql = $connect->query($select);
    if (!$sql){
      $result  = $error;
      $message = $qerror;
    } else {
      $result  = $success;
      $message = $qsuccess;
      $no = 1;
      while($row = $sql->fetch_array()){
        $mysqli_data[] = array(
          "no"     => $no++,
          "date"   => $row['date'],
          "user"   => $row['user'],
          "query"  => $row['query'],
          "data"   => $row['data']
        );
      }
    }

  } elseif ($action == 'so_attribute') {
    $query1 = "SHOW COLUMNS FROM workorder_item";
    $sql1 = $connect->query($query1);

    $query2 = "SHOW COLUMNS FROM preorder_item";
    $sql2 = $connect->query($query2);

    if(!$sql1 || !$sql2){
      $result  = $error;
      $message = $qerror;
    } else {
      $result  = $success;
      $message = $qsuccess;
      $array = array();

      while ($row = $sql1->fetch_array())
      {
        if($row['Field'] !== 'id' && $row['Field'] !== 'id_fk' && $row['Field'] !== 'item_to' && $row['Field'] !== 'sources' && $row['Field'] !== 'detail' && $row['Field'] !== 'qty' && $row['Field'] !== 'annotation' && $row['Field'] !== 'input_by' && $row['Field'] !== 'total')
          {
            if($row['Field'] == 'merk' OR $row['Field'] == 'type' OR $row['Field'] == 'no_so')
            {
              $array['print'][] = $row['Field'];
            }

            $array['input'][] = $row['Field'];
          }
      }

      while($baris = $sql2->fetch_array())
      {
        if($baris['Field'] == 'item' || $baris['Field'] == 'size' || $baris['Field'] == 'qty' || $baris['Field'] == 'price' || $baris['Field'] == 'unit')
        {
          $array['print'][] = $baris['Field'];
        }
      }

      $mysqli_data[] = $array;
    }

  } elseif ($action == 'po_attribute') {
    $query1 = "SHOW COLUMNS FROM po_item";
    $sql1 = $connect->query($query1);

    if(!$sql1){
      $result  = $error;
      $message = $qerror;
    } else {
      $result  = $success;
      $message = $qsuccess;
      $array = array();

      while($baris = $sql1->fetch_array())
      {
        if($baris['Field'] === 'detail' || $baris['Field'] === 'size' || $baris['Field'] === 'qty' || $baris['Field'] === 'price_1' || $baris['Field'] === 'price_2' || $baris['Field'] === 'unit' || $baris['Field'] === 'merk' || $baris['Field'] === 'type' || $baris['Field'] === 'core' || $baris['Field'] === 'gulungan' || $baris['Field'] === 'bahan')
        {
          if($baris['Field'] === 'detail' || $baris['Field'] === 'size' || $baris['Field'] === 'price_2' || $baris['Field'] === 'unit' || $baris['Field'] === 'merk' || $baris['Field'] === 'type' || $baris['Field'] === 'core' || $baris['Field'] === 'gulungan' || $baris['Field'] === 'bahan')
          {
            $array['input'][] = $baris['Field'];
          }

          $array['print'][] = $baris['Field'];
        }
      }

      $mysqli_data[] = $array;
    }

  } elseif($action == 'item_result') {
    $query = "SELECT * FROM setting WHERE ket = 'SO_ITEM' OR ket = 'PO_ITEM' ORDER BY id ASC";
    $sql = $connect->query($query);
    if(!$sql)
    {
      $result  = $error;
      $message = $qerror;

    } else {
      $result  = $success;
      $message = $qsuccess;
      $no = 1;
      while($row = $sql->fetch_array())
      {
        $functions  = '<div class="function_buttons"><ul>';
        $functions .= '<li class="function_edit"><a class="btn-edit" data-id="'.$row['id'].'" data-name="'.$row['isi'].'" title="Ubah"><span>Ubah</span></a></li>';
        $functions .= '<li class="function_delete"><a class="btn-del" data-id="'.$row['id'].'" data-name="'.$row['isi'].'" title="Hapus"><span>Hapus</span></a></li>';
        $functions .= '</ul></div>';

        $obj = json_decode($row['value']);
        if($row['ket'] === 'SO_ITEM'){ $ket = 'Sales Order'; } else { $ket = 'Purchase Order'; }

        $mysqli_data[] = array(
          'no'    => $no++,
          'item'  => $row['isi'],
          'type'  => $ket,
          'input' => str_replace(',', ', ', $obj->{'input'}),
          'print' => str_replace(',', ', ', $obj->{'print'}),
          "functions" => $functions
        );
      }
    }

  } elseif($action == 'item_add'){

    $field_input = mysqli_real_escape_string($connect, $_GET['attribute_input']);
    $field_print = mysqli_real_escape_string($connect, $_GET['attribute_print']);
    $type = mysqli_real_escape_string($connect, $_GET['type']);

    $value = array(
      'input' => $field_input,
      'print' => $field_print,
    );

    $query = "INSERT INTO $tabel SET ";
    if(isset($_GET['item'])) { $query .= "ket   = '". $type ."',";}
    if(isset($_GET['item'])) { $query .= "isi   = '" .mysqli_real_escape_string($connect, $_GET['item'])  ."',";}
    $query .= "value   = '" . json_encode($value) ."'";
    $sql = $connect->query($query);
    if(!$sql)
    {
      $result  = $error;
      $message = $qerror;

    } else {
      $result  = $success;
      $message = $qsuccess;
    }

  } elseif ($action == 'item_get'){
    if($id == ''){
      $result  = $error;
      $message = 'ID'.$missing;
    } else {
      $idx = mysqli_real_escape_string($connect, $id);
      $query = "SELECT isi, value, ket FROM setting WHERE id = '".$idx."'";
      $sql = $connect->query($query);
      if(!$sql)
      {
        $result  = $error;
        $message = $qerror;

      } else {
        $result  = $success;
        $message = $qsuccess;
        while($row = $sql->fetch_array()){
          $obj = json_decode($row['value']);
          $mysqli_data[] = array(
            "item"   => $row['isi'],
            "input"  => $obj->{'input'},
            "print"  => $obj->{'print'},
            "type"   => $row['ket'],
          );
        }
      }
    }
  
  } elseif($action == 'item_edit'){
    if($id == ''){
      $result  = $error;
      $message = 'ID '.$missing;
    } else {
      $item = mysqli_real_escape_string($connect, $_GET['item']);
      $input = mysqli_real_escape_string($connect, $_GET['attribute_input']);
      $print = mysqli_real_escape_string($connect, $_GET['attribute_print']);
      $type = mysqli_real_escape_string($connect, $_GET['type']);
      $id = mysqli_real_escape_string($connect, $id);

      $value = array(
        'input'   => $input,
        'print'   => $print,
      );

      $query = "UPDATE $tabel SET ";
      if(isset($_GET['item'])) { $query .= "isi = '" .$item. "',";}
      if(isset($_GET['type'])) { $query .= "ket = '" .$type. "',";}
      $query .= "value = '" .json_encode($value). "' WHERE id = '".$id."'";
      $sql  = $connect->query($query);
      if(!$sql)
      {
        $result  = $error;
        $message = $qerror;

      } else {
        $result  = $success;
        $message = $qsuccess;
      }
    }

  } elseif ($action == 'item_del'){
    if($id == '')
    {
      $result  = $error;
      $message = 'ID'.$missing;

    } else {
      $idx = mysqli_real_escape_string($connect,$id);
      $query = "DELETE FROM setting WHERE id = '".$idx."'";
      $sql = $connect->query($query);
      if(!$sql)
      {
        $result  = $error;
        $message = $qerror;
      } else {
        $result  = $success;
        $message = $qsuccess;
      }
    }
    
  } else {
    $result  = $error;
    $message = $qerror;
  }

	mysqli_close($connect);

	$data = array(
		"result"  => $result,
		"message" => $message,
		"data"    => $mysqli_data
	);

	$json_data = json_encode($data);
	print $json_data;

} else {
	echo "Not allowed.";
}
?>