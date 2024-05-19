<?php

/////////////////////////////
// Personal config DataTables 
////////////////////////////
require '../dashboard/session.php';
require 'connect.php';
require 'history.php';
$tabel = 'vendor';
$Query = 'action';
$slug = 'vendor';

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
    $query = "SELECT * FROM $tabel ORDER BY id ASC";
    $sql = $connect->query($query);
    if(!$sql){
      $result  = $error;
      $message = $qerror;
    } else {
      $no = 1;
      while($row = $sql->fetch_array()){

        $functions = "<div class='function_buttons'><ul>";
        if($_SESSION['id'] == $row['input_by']){
          $functions .= "<li class='function_edit edit_vendor'><a data-id='".$row['id']."' title='Edit'><span>Edit</span></a></li>";
          $functions .= "<li class='function_delete delete_vendor'><a data-id='".$row['id']."' data-name='".$row['vendor']."' title='Delete'><span>Delete</span></a></li>";
        } else {
          $functions .= "Not allowed";
        }
        $functions .= "</ul></div>";
        
        $mysqli_data[] = array(
            "no"        => $no++,
            "vendor"    => $row['vendor'],
            "address"   => $row['address'],
            "phone"     => $row['phone'],
            "functions" => $functions
        );

        $result  = $success;
        $message = $qsuccess;
      }
    }

  } elseif ($action == 'add_'.$slug){
    $vendor = mysqli_real_escape_string($connect, trim(preg_replace('!\s+!',' ', strip_tags($_GET['vendor']))));
    $address = mysqli_real_escape_string($connect, trim(preg_replace('!\s+!',' ', strip_tags($_GET['address']))));
    $phone = mysqli_real_escape_string($connect, trim(preg_replace('!\s+!',' ', strip_tags($_GET['phone']))));

    $query = "INSERT INTO $tabel SET ";
    if (isset($_GET['vendor']))    { $query .= "vendor    = '" . $vendor	."',";}
    if (isset($_GET['address']))   { $query .= "address   = '" . $address."',";}
    if (isset($_GET['phone']))     { $query .= "phone     = '" . $phone."',";}
    $query .= "input_by = '".$_SESSION['id']."'";

    $sql = $connect->query($query);
    $logger = logger($connect,'Add Vendor', 'Vendor: '.$vendor.' - Address: '.$address.' - Phone: '.$phone);
    
    if(!$sql OR !$logger){
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
      			"vendor"      => $row['vendor'],
            "address"    => $row['address'],
            "phone"      => $row['phone'],
        	);
        }
      }
    }

  } elseif($action == 'edit_'.$slug){

    if ($id == ''){
    		$result  = $error;
    		$message = 'ID '.$missing;
  	} else {
      $vendor = mysqli_real_escape_string($connect, trim(preg_replace('!\s+!',' ', strip_tags($_GET['vendor']))));
      $address = mysqli_real_escape_string($connect, trim(preg_replace('!\s+!',' ', strip_tags($_GET['address']))));
      $phone = mysqli_real_escape_string($connect, trim(preg_replace('!\s+!',' ', strip_tags($_GET['phone']))));

      $query = "UPDATE $tabel SET ";
      if (isset($_GET['vendor']))    { $query .= "vendor    = '" . $vendor  ."',";}
      if (isset($_GET['address']))   { $query .= "address   = '" . $address."',";}
      if (isset($_GET['phone']))     { $query .= "phone     = '" . $phone."'";}
      $query .= " WHERE id = '".mysqli_real_escape_string($connect, $id)."'";
      $sql  = $connect->query($query);
      $logger = logger($connect,'Edit Vendor', 'Vendor: '.$vendor.' - Address: '.$address.' - Phone: '.$phone);
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
      $select = "SELECT vendor, address, phone FROM $tabel WHERE id = '$id'";
      $sql1 = $connect->query($select);
      $fetch = $sql1->fetch_array();

      $del = "DELETE FROM $tabel WHERE id = '".$id."'";
      $sql2 = $connect->query($del);
      $logger = logger($connect,'Delete Vendor', 'Vendor: '.$fetch['vendor'].' - Address: '.$fetch['address'].' - Phone: '.$fetch['phone']);  		
      
      if(!$sql1 || !$sql2 || !$logger){
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