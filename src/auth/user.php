<?php

/////////////////////////////
// Personal config DataTables 
////////////////////////////
require '../dashboard/session.php';
require 'connect.php';
$tabel = 'user';
$Query = 'action';
$slug = 'user';
$WhereBY = 'id';
$dataName = 'name';

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
	///////////////////////
	// Execute all action
	//////////////////////
  	if ($action == 'result_'.$slug){

	    ///////////////////////////
	    // Get pre order data
	    ///////////////////////////
	    
	    $query = "SELECT * FROM $tabel";
	    $sql = $connect->query($query);
	    
	    if (!$sql){
	      $result  = $error;
	      $message = $qerror;
	    } else {
	    	$result  = $success;
	      	$message = $qsuccess;
	      	$no = 1;
		    while($row = $sql->fetch_array()){

          if($row['role'] == 1){
            $role = "Root";
          } if($row['role'] == 2){
            $role = "Administrator";
          } if ($row['role'] == 3) {
            $role = "Sales Order";
          } if ($row['role'] == 4) {
            $role = "Finance";
          } if ($row['role'] == 5) {
            $role = "Guest";
          } if ($row['role'] == 6) {
            $role = "Production";
          }  if($row['status'] == 0) {
            $status = "Not Verified";
          } if($row['status'] == 1) {
            $status = "Verified";
          } if($row['account'] == 0) {
            $account = "Inactive";
          } if($row['account'] == 1) {
            $account = "Active";
          }

		    	$functions  = '<div class="function_buttons"><ul>';
		     	$functions .= '<li class="function_edit"><a data-id="'.$row['id'].'" data-name="'.$row[$dataName].'"><span>Edit</span></a></li>';
	        $functions .= '<li class="function_delete"><a data-id="'.$row['id'].'" data-name="'.$row[$dataName].'"><span>Delete</span></a></li>';
	        $functions .= '</ul></div>';

	        $mysqli_data[] = array(
	          	"no"    	  => $no++,
	          	"name"    	=> $row['name'],
	          	"email"     => $row['email'],
	          	"role" 		  => $role,
	          	"status"  	=> $status,
	          	"account"   => $account,
	          	"functions" => $functions
	        );
		    }
		}

  	} elseif ($action == 'add_'.$slug){

	    /////////////////
	    // Add PO
	    ////////////////
  		$email = mysqli_real_escape_string($connect, $_GET['email']);
  		$query = "SELECT email FROM user WHERE email='$email'";
  		$sql = $connect->query($query);	
  		if($sql->num_rows > 0){
  			$result  = 'registered';
  			$message = $email.' is registered.';
  		} else {
		    $query = "INSERT INTO $tabel SET ";

		    if (isset($_GET['name']))		  { $query .= "name		= '" . mysqli_real_escape_string($connect, $_GET['name'])		."',";}
		    if (isset($_GET['email']))		{ $query .= "email 		= '" . mysqli_real_escape_string($connect, $_GET['email'])		."',";}
		    if (isset($_GET['password']))	{ $query .= "password	= '" . mysqli_real_escape_string($connect, md5($_GET['password']))	."',";}
		    if (isset($_GET['role']))		  { $query .= "role   	= '" . mysqli_real_escape_string($connect, $_GET['role'])		."',";}
		    if (isset($_GET['status']))		{ $query .= "status 	= '" . mysqli_real_escape_string($connect, $_GET['status'])		."',";}
		    if (isset($_GET['account']))  { $query .= "account	= '" . mysqli_real_escape_string($connect, $_GET['account'])	."'";}
		    
		    $sql = $connect->query($query);
		    if (!$sql){
		      $result  = $error;
		      $message = $qerror;
		    } else {
		      $result  = $success;
		      $message = $qsuccess;
		    }  			
  		}    

	} elseif ($action == 'get_'.$slug){
		// Get data by id
    	if ($id == ''){
      		$result  = $error;
      		$message = 'ID'.$missing;
    	} else {
        $id = mysqli_real_escape_string($connect, $id);
    		$query = "SELECT * FROM $tabel WHERE $WhereBY = '".$id."'";
    		$sql = $connect->query($query);
    		if (!$sql){
      		$result  = $error;
      		$message = $qerror;
    		} else {
      		$result  = $success;
      		$message = $qsuccess;
      		while($row = $sql->fetch_array()){
        		$mysqli_data[] = array(
        			"name"    	=> $row['name'],
          		"email"     => $row['email'],
          		"role" 		  => $row['role'],
          		"status"  	=> $row['status'],
          		"account"   => $row['account'],
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
	    	if (isset($_GET['email']))	{ $query .= "email  = '" . mysqli_real_escape_string($connect, $_GET['email'])	."',";}
	    	if (isset($_GET['role']))		{ $query .= "role   = '" . mysqli_real_escape_string($connect, $_GET['role'])		."',";}
	    	if (isset($_GET['status']))	{ $query .= "status = '" . mysqli_real_escape_string($connect, $_GET['status'])	."',";}
	    	if (isset($_GET['account'])){ $query .= "account= '" . mysqli_real_escape_string($connect, $_GET['account'])."'";}
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

  	} elseif ($action == 'del_'.$slug){
  		// Delete data
    	if ($id == ''){
      		$result  = $error;
      		$message = 'ID'.$missing;
    	} else {
      		$query = "DELETE FROM $tabel WHERE $WhereBY = '".mysqli_real_escape_string($connect,$id)."'";
      		$sql = $connect->query($query);
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