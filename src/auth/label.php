<?php

/////////////////////////////
// Personal config DataTables 
////////////////////////////
require '../dashboard/session.php';
require 'connect.php';
require 'history.php';

$Query = 'action';
$slug = 'label';
$WhereBY = 'id';
$dataName = 'product';

//Message alert
$error  = 'error';
$qerror = 'query error';
$success = 'success';
$qsuccess = 'query success';
$missing = 'Missing';

/////////////////////////
// Get action (and id)
////////////////////////
$action = '';
$id  = '';
if(isset($_GET[$Query])){
	$action = $_GET[$Query];
  	if ($action == 'result_'.$slug ||
  		$action == 'get_'.$slug   ||
      	$action == 'add_'.$slug   ||
      	$action == 'edit_'.$slug  ||
      	$action == 'ins_'.$slug  ||
      	$action == 'outs_'.$slug  ||
      	$action == 'del_'.$slug ||
        $action == 'histori_label' ||
        $action == 'sortdata'||
        $action == 'delhis'){
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

if($action != '')
{
	///////////////////////
	// Execute all action
	//////////////////////
	if ($action == 'result_'.$slug)
  {

    ///////////////////////////
    // Get pre order data
    ///////////////////////////
    
    $query = "SELECT a.*, SUM(b.s_masuk) AS masuk, SUM(b.s_keluar) AS keluar, c.name FROM label AS a LEFT JOIN histori_label AS b ON a.id_fk = b.id_fk LEFT JOIN user AS c ON c.id = a.input_by GROUP BY a.id_fk ORDER BY a.id DESC";
    $sql = $connect->query($query);
    
    if (!$sql)
    {
      $result  = $error;
      $message = $qerror;
    } else {
    	$result  = $success;
    	$message = $qsuccess;
    	$no = 1;
	    while($row = $sql->fetch_array())
      {
        if($_SESSION['role'] == '5')
        {
          $functions  = '<div class="function_buttons"><ul><li>Not Allowed</li></ul></div>';
        } else {
          $functions  = '<div class="function_buttons"><ul>';
          $functions .= '<li class="function_ins"><a data-id="'.$row['id'].'" data-name="'.$row[$dataName].'" title="Masuk stok"><span>(+)</span></a></li>';
          $functions .= '<li class="function_outs"><a data-id="'.$row['id'].'" data-name="'.$row[$dataName].'" title="Keluar stok"><span>(-)</span></a></li>';
          $functions .= '<li class="function_edit"><a data-id="'.$row['id'].'" data-name="'.$row[$dataName].'"><span>Ubah</span></a></li>';
          $functions .= '<li class="function_delete"><a data-id="'.$row['id'].'" data-name="'.$row[$dataName].'"><span>Hapus</span></a></li>';
          $functions .= '</ul></div>';
        }

        $mysqli_data[] = array(
          "no"      => $no++,
          "rak"     => $row['rak'],
          "customer"=> $row['customer'],
          "product" => $row['product'],
          "size"    => $row['size'],
          "material"=> $row['material'],
          "core"    => $row['core'],
          "line"    => $row['line'],
          "per_roll"=> $row['per_roll'],
          "stock"   => $row['masuk'] - $row['keluar'],
          "input"   => $row['name'],
          "functions" => $functions
        );
      }
    }

	} elseif ($action == 'add_'.$slug){
  
    /////////////////
    // Add PO
    ////////////////

    $query1 = "SELECT id_fk FROM label ORDER BY id_fk DESC LIMIT 1";
    $sql1 = $connect->query($query1);
    $fetch = $sql1->fetch_array();
    $id_fk = $fetch['id_fk'] + 1;

    $query2 = "INSERT INTO label SET ";
    $query3 = "INSERT INTO histori_label SET ";

    if (isset($id_fk))            { $query2 .= "id_fk     = '" . $id_fk ."',";}
    if (isset($_GET['rak']))      { $query2 .= "rak       = '" . mysqli_real_escape_string($connect, $_GET['rak'])     ."',";}
    if (isset($_GET['customer']))	{ $query2 .= "customer  = '" . mysqli_real_escape_string($connect, $_GET['customer'])."',";}
    if (isset($_GET['product']))	{ $query2 .= "product 	 = '" . mysqli_real_escape_string($connect, $_GET['product'])	."',";}
    if (isset($_GET['size']))     { $query2 .= "size      = '" . mysqli_real_escape_string($connect, $_GET['size'])    ."',";}
    if (isset($_GET['material']))	{ $query2 .= "material  = '" . mysqli_real_escape_string($connect, $_GET['material'])	."',";}
    if (isset($_GET['core']))     { $query2 .= "core      = '" . mysqli_real_escape_string($connect, $_GET['core'])    ."',";}
    if (isset($_GET['line']))     { $query2 .= "line      = '" . mysqli_real_escape_string($connect, $_GET['line'])    ."',";}
    if (isset($_GET['per_roll'])) { $query2 .= "per_roll  = '" . mysqli_real_escape_string($connect, $_GET['per_roll'])."', input_by = '".$_SESSION['id']."'";}	    

    if (isset($id_fk))            { $query3 .= "id_fk     = '" . $id_fk ."',";}
    if (isset($_GET['customer'])) { $query3 .= "date      = '" . date('Y-m-d') ."',";}
    if (isset($_GET['stock']))    { $query3 .= "s_masuk   = '" . mysqli_real_escape_string($connect, $_GET['stock'])."', input_by = '".$_SESSION['id']."'";}

    $sql2 = $connect->query($query2);
    $sql3 = $connect->query($query3);
    $sql4 = logger($connect,'Insert Label', 'Rak: '.$_GET['rak'].'- Customer: '.$GET['customer'].' - Product: '.$_GET['product'].' - Size: '.$_GET['size'].' - Stock: '.$_GET['stock']);

    if (!$sql2 OR ! $sql3 OR !$sql4){
      $result  = $error;
      $message = $qerror;
    } else {
      $result  = $success;
      $message = $qsuccess;
    }

  } elseif ($action == 'get_'.$slug){
    if ($id == '')
    {
      $result  = $error;
      $message = 'ID'.$missing;
    } else {
      $idx = mysqli_real_escape_string($connect, $id);
      $query = "SELECT a.*, SUM(b.s_masuk) AS t_masuk, SUM(b.s_keluar) AS t_keluar FROM label as a LEFT JOIN histori_label AS b ON a.id_fk = b.id_fk WHERE a.id = '".$idx."'";
      $sql = $connect->query($query);
      if (!$sql)
      {
        $result  = $error;
        $message = $qerror;
      } else {
        $result  = $success;
        $message = $qsuccess;
        while($row = $sql->fetch_array())
        {
          $mysqli_data[] = array(
            "rak"     => $row['rak'],
            "customer"=> $row['customer'],
            "product" => $row['product'],
            "size"    => $row['size'],
            "material"=> $row['material'],
            "core"    => $row['core'],
            "line"    => $row['line'],
            "per_roll"=> $row['per_roll'],
            "stock"   => $row['t_masuk'] - $row['t_keluar'],
          );
        }
      }
    }

  } elseif($action == 'edit_'.$slug){
    if ($id == '')
    {
      $result  = $error;
      $message = 'ID '.$missing;
  	} else {
      $query = "UPDATE label SET ";
      if (isset($_GET['rak']))      { $query .= "rak       = '" . mysqli_real_escape_string($connect, $_GET['rak'])     ."',";}
    	if (isset($_GET['customer']))	{ $query .= "customer  = '" . mysqli_real_escape_string($connect, $_GET['customer'])."',";}
	    if (isset($_GET['product']))	{ $query .= "product 	 = '" . mysqli_real_escape_string($connect, $_GET['product'])	."',";}
      if (isset($_GET['size']))     { $query .= "size      = '" . mysqli_real_escape_string($connect, $_GET['size'])    ."',";}
	    if (isset($_GET['material']))	{ $query .= "material  = '" . mysqli_real_escape_string($connect, $_GET['material'])."',";}
      if (isset($_GET['core']))     { $query .= "core      = '" . mysqli_real_escape_string($connect, $_GET['core'])    ."',";}
      if (isset($_GET['line']))     { $query .= "line      = '" . mysqli_real_escape_string($connect, $_GET['line'])    ."',";}
      if (isset($_GET['per_roll'])) { $query .= "per_roll  = '" . mysqli_real_escape_string($connect, $_GET['per_roll'])."'";}
      $query .= "WHERE id = '".mysqli_real_escape_string($connect, $id)."'";
      $sql1  = $connect->query($query);
      $sql2 = logger($connect,'Edit Label', 'Rak: '.$_GET['rak'].'- Customer: '.$GET['customer'].' - Product: '.$_GET['product'].' - Size: '.$_GET['size']);
      if (!$sql1 OR !$sql2)
      {
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
  		$message = 'ID'.$missing;
  	} else {
      $query1 = "SELECT rak, customer, product, size, id_fk FROM label WHERE id = '".$id."'";
      $sql1 = $connect->query($query1);
      $fetch = $sql1->fetch_array();
      $id_fk = $fetch['id_fk'];

      $query2 = "DELETE FROM label WHERE id = '".$id."'";
      $query3 = "DELETE FROM histori_label WHERE id_fk = '".$id_fk."' AND type = 'label'";
      $sql2 = $connect->query($query2);
  		$sql3 = $connect->query($query3);
      $sql4 = logger($connect,'Delete Label (all)', 'Rak: '.$fetch['rak'].'- Customer: '.$fetch['customer'].' - Product: '.$fetch['product'].' - Size: '.$fetch['size']);
  		
      if(!$sql2 OR !$sql3 OR !$sql4){
    		$result  = $error;
    		$message = $qerror;
  		} else {
    		$result  = $success;
    		$message = $qsuccess;
  		}
    }

	} elseif ($action == 'ins_'.$slug){

    if ($id == ''){
      $result  = $error;
      $message = 'ID '.$missing;
    } else {

      $query1 = "SELECT rak, customer, product, size, id_fk FROM label WHERE id = '".$id."'";
      $sql1 = $connect->query($query1);
      $fetch = $sql1->fetch_array();
      $id_fk = $fetch['id_fk'];

      $query2 = "INSERT INTO histori_label SET ";
      if (isset($id_fk))         { $query2 .= "id_fk     = '" . $id_fk ."',";}
      if (isset($_GET['nama_'])) { $query2 .= "customer  = '" . mysqli_real_escape_string($connect, $_GET['nama_'])."',";}
      if (isset($_GET['tgl_']))  { $query2 .= "date      = '" . mysqli_real_escape_string($connect, $_GET['tgl_'])."',";}
      if (isset($_GET['nosj_'])) { $query2 .= "no_sj     = '" . mysqli_real_escape_string($connect, $_GET['nosj_'])."',";}
      if (isset($_GET['nopo_'])) { $query2 .= "no_po     = '" . mysqli_real_escape_string($connect, $_GET['nopo_'])."',";}
      if (isset($_GET['roll_'])) { $query2 .= "roll      = '" . mysqli_real_escape_string($connect, $_GET['roll_'])."',";}
      if (isset($_GET['content_'])){ $query2 .= "content = '" . mysqli_real_escape_string($connect, $_GET['content_'])."',";}
      if (isset($_GET['unit_'])){ $query2 .= "unit       = '" . mysqli_real_escape_string($connect, $_GET['unit_'])."',";}
      if (isset($_GET['smasuk_'])) { $query2 .= "s_masuk = '" . mysqli_real_escape_string($connect, $_GET['smasuk_'])."', status = 1, input_by = '".$_SESSION['id']."'";}

      $sql2 = $connect->query($query2);
      $sql3 = logger($connect,'In Stock Label', 'Rak: '.$fetch['rak'].' - Customer: '.$fetch['customer'].' - Product: '.$fetch['product'].' - Size: '.$fetch['size'].' - In: '.$_GET['smasuk_']);

      if (!$sql2 OR !$sql3){
        $result  = $error;
        $message = $qerror;
      } else {
        $result  = $success;
        $message = $qsuccess;
      }
    }

  } elseif ($action == 'outs_'.$slug){
    if ($id == ''){
      $result  = $error;
      $message = 'ID '.$missing;
    } else {

      $query1 = "SELECT rak, product, size, id_fk FROM label WHERE id = '".$id."'";
      $sql1 = $connect->query($query1);
      $fetch = $sql1->fetch_array();
      $id_fk = $fetch['id_fk'];

      $query2 = "INSERT INTO histori_label SET ";
      if (isset($id_fk))         { $query2 .= "id_fk     = '" . $id_fk ."',";}
      if (isset($_GET['nama_'])) { $query2 .= "customer  = '" . mysqli_real_escape_string($connect, $_GET['nama_'])."',";}
      if (isset($_GET['tgl_']))  { $query2 .= "date      = '" . mysqli_real_escape_string($connect, $_GET['tgl_'])."',";}
      if (isset($_GET['nosj_'])) { $query2 .= "no_sj     = '" . mysqli_real_escape_string($connect, $_GET['nosj_'])."',";}
      if (isset($_GET['nopo_'])) { $query2 .= "no_po     = '" . mysqli_real_escape_string($connect, $_GET['nopo_'])."',";}
      if (isset($_GET['roll_'])) { $query2 .= "roll      = '" . mysqli_real_escape_string($connect, $_GET['roll_'])."',";}
      if (isset($_GET['content_'])){ $query2 .= "content = '" . mysqli_real_escape_string($connect, $_GET['content_'])."',";}
      if (isset($_GET['unit_'])){ $query2 .= "unit       = '" . mysqli_real_escape_string($connect, $_GET['unit_'])."',";}
      if (isset($_GET['skeluar_'])){ $query2 .= "s_keluar= '" . mysqli_real_escape_string($connect, $_GET['skeluar_'])."', status = 2, input_by = '".$_SESSION['id']."'";}
      
      $sql2 = $connect->query($query2);
      $sql3 = logger($connect,'Out Stock Label', 'Rak: '.$fetch['rak'].' - Product: '.$fetch['product'].' - Size: '.$fetch['size'].' - Out: '.$_GET['skeluar_']);

  		if(!$sql2 OR !$sql3){
    		$result  = $error;
    		$message = $qerror;
  		} else {
    		$result  = $success;
    		$message = $qsuccess;
  		}
  	}

	} elseif ($action == 'histori_label'){

    $curMonth = str_replace('/', '-', $_GET['curMonth']);
    $query = "SELECT a.*, b.rak, b.product, b.size, b.core, b.line, b.material, b.per_roll, c.name FROM histori_label AS a LEFT JOIN label AS b ON a.id_fk = b.id_fk LEFT JOIN user AS c ON c.id = a.input_by WHERE a.date LIKE '$curMonth%' ORDER BY a.id ASC";
    $sql = $connect->query($query);

    if(!$sql)
    {
      $result  = $error;
      $message = $qerror;

    } else {
      $result  = $success;
      $message = $qsuccess;
      $no = 1;
      while($row = $sql->fetch_array()){

        $select = "SELECT name FROM user WHERE id = '".$row['input_by']."'";
        $hasil = $connect->query($select);
        $user = $hasil->fetch_array();

        if($row['status'] == 0){ $status = 'STOK'; }
        if($row['status'] == 1){ $status = 'MASUK'; }
        if($row['status'] == 2){ $status = 'KELUAR'; }

        if($_SESSION['id'] != $row['input_by'])
        {
          $functions  = '<div class="function_buttons"><ul><li>Not Allowed</li></ul></div>';

        } else if($row['status'] == 0){
          $functions  = '<div class="function_buttons"><ul><li></li></ul></div>';
          
        } else {
          $functions  = '<div class="function_buttons"><ul>';
          $functions .= '<li class="delhis"><a data-id="'.$row['id'].'" data-name="'.$row['product'].'"><span>Hapus</span></a></li>';
          $functions .= '</ul></div>';
        }

        $newDate = date("d/m/Y", strtotime($row['date']));

        if($row['status'] == 0)
        {
          $mysqli_data[] = array(
            "no"      => $no++,
            "date"    => $newDate,
            "rak"     => $row['rak'],
            "customer"=> $row['customer'],
            "nosj"    => $row['no_sj'],
            "nopo"    => $row['no_po'],
            "status"  => $status,
            "product" => $row['product'],
            "size"    => $row['size'],
            "material"=> $row['material'],
            "core"    => $row['core'],
            "line"    => $row['line'],
            "roll"    => $row['roll'],
            "content" => $row['content'],
            "unit"    => $row['unit'],
            "s_awal"  => $row['s_masuk'],
            "s_masuk" => '0',
            "s_keluar"=> $row['s_keluar'],
            "s_akhir" => '',
            "input"   => $row['name'],
            "functions" => $functions
          );

        } else {
          $mysqli_data[] = array(
            "no"      => $no++,
            "date"    => $newDate,
            "rak"     => $row['rak'],
            "customer"=> $row['customer'],
            "nosj"    => $row['no_sj'],
            "nopo"    => $row['no_po'],
            "status"  => $status,
            "product" => $row['product'],
            "size"    => $row['size'],
            "material"=> $row['material'],
            "core"    => $row['core'],
            "line"    => $row['line'],
            "roll"    => $row['roll'],
            "content" => $row['content'],
            "unit"    => $row['unit'],
            "s_awal"  => '0',
            "s_masuk" => $row['s_masuk'],
            "s_keluar"=> $row['s_keluar'],
            "s_akhir" => '',
            "input"   => $user['name'],
            "functions" => $functions
          );

        }
      }
    }

  } elseif($action == 'sortdata'){
    $query = "SELECT DISTINCT date FROM histori_label ORDER BY date DESC";
    $sql = $connect->query($query);

    if(!$sql){
      $result  = $error;
      $message = $qerror;
    } else {
      $result  = $success;
      $message = $qsuccess;

      $montly_list = '';

      while($row = $sql->fetch_array()){
        $montly = date("Y/m", strtotime($row['date']));
        if(!isset($montly_list[$montly])){
          $montly_list[$montly] = 1;
          $mysqli_data[] = array(
            'montly' => $montly
          );
        }
      }
    }

  } elseif ($action == 'delhis'){
    if($id == ''){
      $result  = $error;
      $message = 'ID'.$missing;
    } else {
      $query1 = "SELECT a.rak, a.customer, a.product, a.size FROM label AS a LEFT JOIN histori_label AS b ON b.id_fk = a.id_fk WHERE b.id = '".$id."'";
      $sql1 = $connect->query($query1);
      $fetch = $sql1->fetch_array();

      $query2 = "DELETE FROM histori_label WHERE id = '".$id."'";
      $sql2 = $connect->query($query2);
      
      $sql3 = logger($connect,'Delete Label (history)', 'Rak: '.$fetch['rak'].'- Customer: '.$fetch['customer'].' - Product: '.$fetch['product'].' - Size: '.$fetch['size']);
      
      if(!$sql2 OR !$sql3){
        $result  = $error;
        $message = $qerror;
      } else {
        $result  = $success;
        $message = $qsuccess;
      }
    }

  }

	mysqli_close($connect);

	///////////////////////
	// Prepare data
	///////////////////////
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