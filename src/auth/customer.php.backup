<?php

/////////////////////////////
// Personal config DataTables 
////////////////////////////
require '../dashboard/session.php';
require 'connect.php';
require 'history.php';
$tabel = 'customer';
$tabel2 = 'preorder_customer';
$Query = 'action';
$slug = 'customer';

//Message alert
$error  = 'error';
$qerror = 'query error';
$success = 'success';
$qsuccess = 'query success';
$missing = 'Missing';

$action = '';
$id  = '';
if (isset($_GET[$Query])){
	$action = $_GET[$Query];
  	if ($action == 'result_'.$slug ||
  		$action == 'get_'.$slug   ||
      	$action == 'add_'.$slug   ||
      	$action == 'edit_'.$slug  ||
      	$action == 'del_'.$slug){
    	if (isset($_GET['id'])){
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

  if($action == 'result_'.$slug){
    $query = "SELECT * FROM $tabel ORDER BY nama ASC";
    $sql = $connect->query($query);
    if(!$sql){
      $result  = $error;
      $message = $qerror;
    } else {
      $no = 1;
      while($row = $sql->fetch_array()){
        $select = "SELECT id FROM $tabel2 WHERE id_customer = '".$row['id']." ORDER BY id DESC LIMIT 1'";
        $sql2 = $connect->query($select);

        $functions = "<div class='function_buttons'><ul>";
        if($_SESSION['id'] == $row['input_by']){
          $functions .= "<li class='function_edit edit_cs'><a data-id='".$row['id']."' title='Edit'><span>Edit</span></a></li>";
          if($sql2->num_rows == 0){ $functions .= "<li class='function_delete delete_cs'><a data-id='".$row['id']."' data-name='".$row['nama']."' title='Delete'><span>Delete</span></a></li>"; }
        } else {
          $functions .= "Not allowed";
        }

        if(empty($row['alamat']))   { $alamat = ''; } else { $alamat = $row['alamat'].". "; }
        if(empty($row['propinsi'])) { $provinsi = ''; } else { $provinsi = $row['propinsi'].". "; }
        if(empty($row['negara']))   { $negara = ''; } else { $negara = $row['negara'].". "; }
        if(empty($row['kodepos']))  { $kodepos = ''; } else { $kodepos = $row['kodepos'].". "; }
        if(empty($row['telp']))  { $telp = ''; } else { $telp = "Telp ".$row['telp'].". "; }

        if(empty($row['s_alamat']))   { $s_alamat = ''; } else { $s_alamat = $row['s_alamat'].". "; }
        if(empty($row['s_provinsi'])) { $s_provinsi = ''; } else { $s_provinsi = $row['s_provinsi'].". "; }
        if(empty($row['s_negara']))   { $s_negara = ''; } else { $s_negara = $row['s_negara'].". "; }
        if(empty($row['s_kodepos']))  { $s_kodepos = ''; } else { $s_kodepos = $row['s_kodepos'].". "; }
        if(empty($row['s_telp']))  { $s_telp = ''; } else { $s_telp = "Telp ".$row['s_telp'].". "; }

        $functions .= "</ul></div>";
        $mysqli_data[] = array(
            "no"        => $no++,
            "b_nama"    => $row['nama'],
            "b_alamat"  => $alamat.$provinsi.$negara.$kodepos.$telp,
            "s_nama"    => $row['s_nama'],
            "s_alamat"  => $s_alamat.$s_provinsi.$s_negara.$s_kodepos.$s_telp,
            "functions" => $functions
        );

        $result  = $success;
        $message = $qsuccess;
      }
    }

  } elseif ($action == 'add_'.$slug){
    $b_nama = mysqli_real_escape_string($connect, trim(preg_replace('!\s+!',' ', strip_tags($_GET['b_nama']))));
    $b_alamat = mysqli_real_escape_string($connect, trim(preg_replace('!\s+!',' ', strip_tags($_GET['b_alamat']))));
    $b_kota = mysqli_real_escape_string($connect, trim(preg_replace('!\s+!',' ', strip_tags($_GET['b_kota']))));
    $b_negara = mysqli_real_escape_string($connect, trim(preg_replace('!\s+!',' ', strip_tags($_GET['b_negara']))));
    $b_provinsi = mysqli_real_escape_string($connect, trim(preg_replace('!\s+!',' ', strip_tags($_GET['b_provinsi']))));
    $b_kodepos = mysqli_real_escape_string($connect, trim(preg_replace('!\s+!',' ', strip_tags($_GET['b_kodepos']))));
    $b_telp = mysqli_real_escape_string($connect, trim(preg_replace('!\s+!',' ', strip_tags($_GET['b_telp']))));

    $s_nama = mysqli_real_escape_string($connect, trim(preg_replace('!\s+!',' ', strip_tags($_GET['s_nama']))));
    $s_alamat = mysqli_real_escape_string($connect, trim(preg_replace('!\s+!',' ', strip_tags($_GET['s_alamat']))));
    $s_kota = mysqli_real_escape_string($connect, trim(preg_replace('!\s+!',' ', strip_tags($_GET['s_kota']))));
    $s_negara = mysqli_real_escape_string($connect, trim(preg_replace('!\s+!',' ', strip_tags($_GET['s_negara']))));
    $s_provinsi = mysqli_real_escape_string($connect, trim(preg_replace('!\s+!',' ', strip_tags($_GET['s_provinsi']))));
    $s_kodepos = mysqli_real_escape_string($connect, trim(preg_replace('!\s+!',' ', strip_tags($_GET['s_kodepos']))));
    $s_telp = mysqli_real_escape_string($connect, trim(preg_replace('!\s+!',' ', strip_tags($_GET['s_telp']))));

    $query = "INSERT INTO $tabel SET ";
    if (isset($_GET['b_nama']))     { $query .= "nama     = '" . $b_nama	."',";}
    if (isset($_GET['b_alamat']))   { $query .= "alamat   = '" . $b_alamat."',";}
    if (isset($_GET['b_kota']))     { $query .= "kota     = '" . $b_kota."',";}
    if (isset($_GET['b_negara']))   { $query .= "negara   = '" . $b_negara."',";}
    if (isset($_GET['b_provinsi'])) { $query .= "provinsi = '" . $b_provinsi."',";}
    if (isset($_GET['b_kodepos']))	{ $query .= "kodepos 	= '" . $b_kodepos."',";}
    if (isset($_GET['b_telp']))		  { $query .= "telp		  = '" . $b_telp."',";}
    if (isset($_GET['s_nama']))     { $query .= "s_nama     = '" . $s_nama  ."',";}
    if (isset($_GET['s_alamat']))   { $query .= "s_alamat   = '" . $s_alamat."',";}
    if (isset($_GET['s_kota']))     { $query .= "s_kota     = '" . $s_kota."',";}
    if (isset($_GET['s_negara']))   { $query .= "s_negara   = '" . $s_negara."',";}
    if (isset($_GET['s_provinsi'])) { $query .= "s_provinsi = '" . $s_provinsi."',";}
    if (isset($_GET['s_kodepos']))  { $query .= "s_kodepos  = '" . $s_kodepos."',";}
    if (isset($_GET['s_telp']))      { $query .= "s_telp     = '" . $s_telp."',";}
    $query .= "input_by = '".$_SESSION['id']."'";

    $sql = $connect->query($query);
    $logger = logger($connect,'Add Customer', 'Customer: '.$b_nama.' - Address: '.$b_alamat.' - Phone: '.$b_telp);
    
    if (!$sql OR !$logger){
      $result  = $error;
      $message = $qerror;
    } else {
      $result  = $success;
      $message = $qsuccess;
    }

	} elseif ($action == 'get_'.$slug){

    if ($id == ''){
      $result  = $error;
      $message = 'ID '.$missing;
    } else {
      $query = "SELECT * FROM $tabel WHERE id = '".mysqli_real_escape_string($connect, $id)."'";
      $sql = $connect->query($query);
      if(!$sql){
        $result  = $error;
        $message = $qerror;
      } else {
        $result  = $success;
        $message = $qsuccess;
        while($row = $sql->fetch_array()){
          $mysqli_data[] = array(
      			"b_nama"      => $row['nama'],
            "b_alamat"    => $row['alamat'],
            "b_kota"      => $row['kota'],
            "b_negara"    => $row['negara'],
            "b_provinsi"  => $row['provinsi'],
            "b_kodepos"   => $row['kodepos'],
            "b_telp"      => $row['telp'],
            "s_nama"      => $row['s_nama'],
            "s_alamat"    => $row['s_alamat'],
            "s_kota"      => $row['s_kota'],
            "s_negara"    => $row['s_negara'],
            "s_propinsi"  => $row['s_propinsi'],
            "s_kodepos"   => $row['s_kodepos'],
            "s_telp"      => $row['s_telp'],
        	);
        }
      }
    }

  } elseif($action == 'edit_'.$slug){

    if ($id == ''){
    		$result  = $error;
    		$message = 'ID '.$missing;
  	} else {
      $b_nama = mysqli_real_escape_string($connect, trim(preg_replace('!\s+!',' ', strip_tags($_GET['b_nama']))));
      $b_alamat = mysqli_real_escape_string($connect, trim(preg_replace('!\s+!',' ', strip_tags($_GET['b_alamat']))));
      $b_kota = mysqli_real_escape_string($connect, trim(preg_replace('!\s+!',' ', strip_tags($_GET['b_kota']))));
      $b_negara = mysqli_real_escape_string($connect, trim(preg_replace('!\s+!',' ', strip_tags($_GET['b_negara']))));
      $b_provinsi = mysqli_real_escape_string($connect, trim(preg_replace('!\s+!',' ', strip_tags($_GET['b_provinsi']))));
      $b_kodepos = mysqli_real_escape_string($connect, trim(preg_replace('!\s+!',' ', strip_tags($_GET['b_kodepos']))));
      $b_telp = mysqli_real_escape_string($connect, trim(preg_replace('!\s+!',' ', strip_tags($_GET['b_telp']))));

      $s_nama = mysqli_real_escape_string($connect, trim(preg_replace('!\s+!',' ', strip_tags($_GET['s_nama']))));
      $s_alamat = mysqli_real_escape_string($connect, trim(preg_replace('!\s+!',' ', strip_tags($_GET['s_alamat']))));
      $s_kota = mysqli_real_escape_string($connect, trim(preg_replace('!\s+!',' ', strip_tags($_GET['s_kota']))));
      $s_negara = mysqli_real_escape_string($connect, trim(preg_replace('!\s+!',' ', strip_tags($_GET['s_negara']))));
      $s_provinsi = mysqli_real_escape_string($connect, trim(preg_replace('!\s+!',' ', strip_tags($_GET['s_provinsi']))));
      $s_kodepos = mysqli_real_escape_string($connect, trim(preg_replace('!\s+!',' ', strip_tags($_GET['s_kodepos']))));
      $s_telp = mysqli_real_escape_string($connect, trim(preg_replace('!\s+!',' ', strip_tags($_GET['s_telp']))));

      $query = "UPDATE $tabel SET ";
      if (isset($_GET['b_nama']))     { $query .= "nama     = '" . $b_nama  ."',";}
      if (isset($_GET['b_alamat']))   { $query .= "alamat   = '" . $b_alamat."',";}
      if (isset($_GET['b_kota']))     { $query .= "kota     = '" . $b_kota."',";}
      if (isset($_GET['b_negara']))   { $query .= "negara   = '" . $b_negara."',";}
      if (isset($_GET['b_provinsi'])) { $query .= "provinsi = '" . $b_provinsi."',";}
      if (isset($_GET['b_kodepos']))  { $query .= "kodepos  = '" . $b_kodepos."',";}
      if (isset($_GET['b_telp']))     { $query .= "telp     = '" . $b_telp."',";}
      if (isset($_GET['s_nama']))     { $query .= "s_nama     = '" . $s_nama  ."',";}
      if (isset($_GET['s_alamat']))   { $query .= "s_alamat   = '" . $s_alamat."',";}
      if (isset($_GET['s_kota']))     { $query .= "s_kota     = '" . $s_kota."',";}
      if (isset($_GET['s_negara']))   { $query .= "s_negara   = '" . $s_negara."',";}
      if (isset($_GET['s_provinsi'])) { $query .= "s_provinsi = '" . $s_provinsi."',";}
      if (isset($_GET['s_kodepos']))  { $query .= "s_kodepos  = '" . $s_kodepos."',";}
      if (isset($_GET['s_telp']))      { $query .= "s_telp     = '" . $s_telp."'";}
      $query .= " WHERE id = '".mysqli_real_escape_string($connect, $id)."'";

      $sql  = $connect->query($query);
      $logger = logger($connect,'Edit Customer', 'Customer: '.$b_nama.' - Address: '.$b_alamat.' - Phone: '.$b_telp);
      if(!$sql || !$logger){
        $result  = $error;
        $message = $qerror;
      } else {
        $result  = $success;
        $message = $qsuccess;
      }
    }

  } elseif ($action == 'del_'.$slug){
    if($id == ''){
      $result  = $error;
      $message = 'ID '.$missing;
    } else {
      $id = mysqli_real_escape_string($connect,$id);

      $select = "SELECT nama, alamat, telp FROM $tabel WHERE id = '$id'";
      $sql1 = $connect->query($select);
      $fetch = $sql1->fetch_array();

      $del = "DELETE FROM $tabel WHERE id = '".mysqli_real_escape_string($connect,$id)."'";
      $sql2 = $connect->query($del);
      $logger = logger($connect,'Delete Customer', 'Customer: '.$fetch['nama'].' - Address: '.$fetch['alamat'].' - Phone: '.$fetch['telp']);
  		
      if (!$sql2 OR !$logger){
    		$result  = $error;
    		$message = $qerror;
  		} else {
    		$result  = $success;
    		$message = $qsuccess;
  		}
    }
  }

	mysqli_close($connect);
	$data = array(
    "result"  => $result,
    "message" => $message,
    "data"    => $mysqli_data
	);

  ///////////////////////////
  // Convert PHP array to JSON array
	//////////////////////////
	$json_data = json_encode($data);
	print $json_data;

} else {
	echo "Not allowed.";
}
?>