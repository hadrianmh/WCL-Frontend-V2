<?php

/////////////////////////////
// Personal config DataTables 
////////////////////////////
require '../dashboard/session.php';
require 'connect.php';
$tabel = 'user';
$Query = 'action';
$slug = 'profile';

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
if (isset($_GET[$Query])){
	$action = $_GET[$Query];
  	if ($action == 'get_'.$slug   ||
      	$action == 'edit_'.$slug){
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
	///////////////////////
	// Execute all action
	//////////////////////
  if ($action == 'get_'.$slug){
		// Get data by id
  	if ($id == ''){
    		$result  = $error;
    		$message = 'ID '.$missing;
  	} else {
      $id = mysqli_real_escape_string($connect, $id);
  		$query = "SELECT * FROM $tabel WHERE id = '".$id."'";
  		$sql = $connect->query($query);
  		if (!$sql){
    		$result  = $error;
    		$message = $qerror;
  		} else {
    		$result  = $success;
    		$message = $qsuccess;
    		while($row = $sql->fetch_array()){
      		$mysqli_data[] = array(
      			"nama"    	=> $row['name'],
        		"email"     => $row['email'],
        	);
    		}
  		}
  	}

	} elseif($action == 'edit_'.$slug){
		// Edit
    	if ($id == ''){
    		$result  = $error;
    		$message = 'ID '.$missing;
    	} else {
        $id = mysqli_real_escape_string($connect, $id);
    		$query = "UPDATE $tabel SET ";
      	if (isset($_GET['name']))		{ $query .= "name		= '" . mysqli_real_escape_string($connect, $_GET['name'])	."',";}
        if (isset($_GET['email']))  { $query .= "email  = '" . mysqli_real_escape_string($connect, $_GET['email'])  ."',";}
	    	if (isset($_GET['password'])){ $query .= "password  = '" . md5(mysqli_real_escape_string($connect, $_GET['password']))."'";}
    		$query .= "WHERE id = '".$id."'";
    		$sql  = $connect->query($query);
    		if (!$sql){
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