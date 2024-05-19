<?php

/////////////////////////////
// Personal config DataTables 
////////////////////////////
require '../dashboard/session.php';
require 'connect.php';
require 'history.php';
$tabel = 'company';
$Query = 'action';
$slug = 'company';

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
        $action == 'upload_logo_'.$slug  ||
      	$action == 'remove_logo_'.$slug  ||
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
          $functions .= "<li class='function_edit edit_company'><a data-id='".$row['id']."' title='Edit'><span>Edit</span></a></li>";
          //$functions .= "<li class='function_delete delete_company'><a data-id='".$row['id']."' data-name='".$row['company']."' title='Delete'><span>Delete</span></a></li>";
        } else {
          $functions .= "Not allowed";
        }
        $functions .= "</ul></div>";
        
        $mysqli_data[] = array(
            "no"        => $no++,
            "company"   => $row['company'],
            "address"   => $row['address'],
            "email"     => $row['email'],
            "phone"     => $row['phone'],
            "logo"      => $row['logo'],
            "functions" => $functions
        );

        $result  = $success;
        $message = $qsuccess;
      }
    }

  } elseif ($action == 'add_'.$slug){
    $company = mysqli_real_escape_string($connect, trim(preg_replace('!\s+!',' ', strip_tags($_POST['company']))));
    $address = mysqli_real_escape_string($connect, trim(preg_replace('!\s+!',' ', strip_tags($_POST['address']))));
    $email = mysqli_real_escape_string($connect, trim(preg_replace('!\s+!',' ', strip_tags($_POST['email']))));
    $phone = mysqli_real_escape_string($connect, trim(preg_replace('!\s+!',' ', strip_tags($_POST['phone']))));
    
    $file_tmp = $_FILES['logo']['tmp_name'];
    $file = $_FILES['logo']['name'];
    $size_file = $_FILES['logo']['size'];
    $extension_allowed = array('png','jpg');
    $path = '../files/uploads';
    $size_allowed = '1024000';

    if(!empty($_FILES))
    {
      if(is_uploaded_file($file_tmp))
      { 
        sleep(1);
        $ex = explode('.', $file);
        $newName = round(microtime(true)).".".end($ex);
        $extension = strtolower(end($ex));
        if(in_array($extension, $extension_allowed) === true){
          if($size_file < $size_allowed)
          {
            if(move_uploaded_file($file_tmp, $path."/".$newName))
            {
              $query = "INSERT INTO $tabel SET ";
              if (isset($_POST['company']))   { $query .= "company   = '" . $company ."',";}
              if (isset($_POST['address']))   { $query .= "address   = '" . $address."',";}
              if (isset($_POST['email']))     { $query .= "email   = '" . $email."',";}
              if (isset($_POST['phone']))     { $query .= "phone     = '" . $phone."',";}
              $query .= "logo     = '" . $path."/".$newName ."',";
              $query .= "input_by = '".$_SESSION['id']."'";

              $sql = $connect->query($query);
              $logger = logger($connect,'Add Company', 'Company: '.$company.' - Address: '.$address.' - Phone: '.$phone);
              if(!$sql OR !$logger){
                $result  = $error;
                $message = 'Gagal memasukan data';

              } else {
                $result  = $success;
                $message = 'Berhasil memasukan data';
              }

            } else {
              $result  = 'error';
              $message = 'Gagal upload gambar';
            }

          } else {
            $result  = 'error';
            $message = 'Ukuran file terlalu besar';
          }

        } else {
          $result  = 'error';
          $message = 'Ekstensi gambar tidak diizinkan';
        }

      } else {

        $query = "INSERT INTO $tabel SET ";
        if (isset($_POST['company']))   { $query .= "company   = '" . $company ."',";}
        if (isset($_POST['address']))   { $query .= "address   = '" . $address."',";}
        if (isset($_POST['email']))     { $query .= "email   = '" . $email."',";}
        if (isset($_POST['phone']))     { $query .= "phone     = '" . $phone."',";}
        $query .= "logo     = '',";
        $query .= "input_by = '".$_SESSION['id']."'";

        $sql = $connect->query($query);
        $logger = logger($connect,'Add Company', 'Company: '.$company.' - Address: '.$address.' - Phone: '.$phone);
        if(!$sql OR !$logger){
          $result  = $error;
          $message = 'Gagal memasukan data';

        } else {
          $result  = $success;
          $message = 'Berhasil memasukan data';
        }
      }

    } else {
      $result  = 'error';
      $message = 'Tidak ada gambar yang dipilih.';
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
      			"company"    => $row['company'],
            "address"    => $row['address'],
            "email"      => $row['email'],
            "phone"      => $row['phone'],
            "logo"       => $row['logo'],
        	);
        }
      }
    }

  } elseif($action == 'edit_'.$slug){
    if ($id == ''){
    	$result  = $error;
    	$message = 'ID '.$missing;
  	} else {

      $company = mysqli_real_escape_string($connect, trim(preg_replace('!\s+!',' ', strip_tags($_POST['company']))));
      $address = mysqli_real_escape_string($connect, trim(preg_replace('!\s+!',' ', strip_tags($_POST['address']))));
      $email = mysqli_real_escape_string($connect, trim(preg_replace('!\s+!',' ', strip_tags($_POST['email']))));
      $phone = mysqli_real_escape_string($connect, trim(preg_replace('!\s+!',' ', strip_tags($_POST['phone']))));
      $tmp_logo = mysqli_real_escape_string($connect, trim(preg_replace('!\s+!',' ', strip_tags($_POST['tmp_logo']))));
      $data_img = mysqli_real_escape_string($connect, trim(preg_replace('!\s+!',' ', strip_tags($_GET['img']))));
      
      $file_tmp = $_FILES['logo']['tmp_name'];
      $file = $_FILES['logo']['name'];
      $size_file = $_FILES['logo']['size'];
      $extension_allowed = array('png','jpg');
      $path = '../files/uploads';
      $size_allowed = '1024000';
      $ex = explode('.', $file);
      $newName = round(microtime(true)).".".end($ex);
      $extension = strtolower(end($ex));

      if(!empty($_FILES))
      {
        if(is_uploaded_file($file_tmp))
        { 
          sleep(1);
          if(in_array($extension, $extension_allowed) === true){
            if($size_file < $size_allowed)
            {
              if(move_uploaded_file($file_tmp, $path."/".$newName))
              {
                $query = "UPDATE $tabel SET ";
                if (isset($_POST['company']))   { $query .= "company    = '" . $company  ."',";}
                if (isset($_POST['address']))   { $query .= "address   = '" . $address."',";}
                if (isset($_POST['email']))     { $email .= "email     = '" . $email."',";}
                if (isset($_POST['phone']))     { $query .= "phone     = '" . $phone."',";}
                $query .= "logo     = '" . $path."/".$newName ."'";
                $query .= " WHERE id = '".mysqli_real_escape_string($connect, $id)."'";
                $sql = $connect->query($query);
                $logger = logger($connect,'Edit Company', 'Company: '.$company.' - Address: '.$address.' - Phone: '.$phone);
                if(!$sql OR !$logger){
                  $result  = $error;
                  $message = 'Gagal mengubah data';

                } else {
                  $result  = $success;
                  $message = 'Berhasil mengubah data';
                  unlink($tmp_logo);
                }

              } else {
                $result  = 'error';
                $message = 'Gagal upload gambar';
              }

            } else {
              $result  = 'error';
              $message = 'Ukuran file terlalu besar';
            }

          } else {
            $result  = 'error';
            $message = 'Ekstensi gambar tidak diizinkan';
          }

        } else {

          if(empty($tmp_logo))
          {
            $query = "UPDATE $tabel SET ";
            if (isset($_POST['company']))   { $query .= "company    = '" . $company  ."',";}
            if (isset($_POST['address']))   { $query .= "address   = '" . $address."',";}
            if (isset($_POST['email']))     { $email .= "email     = '" . $email."',";}
            if (isset($_POST['phone']))     { $query .= "phone     = '" . $phone."',";}
            $query .= "logo     = ''";
            $query .= " WHERE id = '".mysqli_real_escape_string($connect, $id)."'";
            $sql = $connect->query($query);
            $logger = logger($connect,'Edit Company', 'Company: '.$company.' - Address: '.$address.' - Phone: '.$phone);
            if(!$sql OR !$logger){
              $result  = $error;
              $message = 'Gagal mengubah data';

            } else {
              $result  = $success;
              $message = 'Berhasil mengubah data';
              unlink($data_img);
            }

          } else {

            $query = "UPDATE $tabel SET ";
            if (isset($_POST['company']))   { $query .= "company    = '" . $company  ."',";}
            if (isset($_POST['address']))   { $query .= "address   = '" . $address."',";}
            if (isset($_POST['email']))     { $email .= "email     = '" . $email."',";}
            if (isset($_POST['phone']))     { $query .= "phone     = '" . $phone."',";}
            $query .= "logo     = '" . $tmp_logo ."'";
            $query .= " WHERE id = '".mysqli_real_escape_string($connect, $id)."'";
            $sql = $connect->query($query);
            $logger = logger($connect,'Edit Company', 'Company: '.$company.' - Address: '.$address.' - Phone: '.$phone);
            if(!$sql OR !$logger){
              $result  = $error;
              $message = 'Gagal mengubah data';

            } else {
              $result  = $success;
              $message = 'Berhasil mengubah data';
            }
          }
        }

      } else {
        $result  = 'error';
        $message = 'Tidak ada gambar yang dipilih.';
      }
    }

  } elseif ($action == 'del_'.$slug){
    if($id == ''){
      $result  = $error;
      $message = 'ID '.$missing;
    } else {
      
      $id = mysqli_real_escape_string($connect,$id);
      $select = "SELECT company, address, phone FROM $tabel WHERE id = '$id'";
      $sql1 = $connect->query($select);
      $fetch = $sql1->fetch_array();

      $del = "DELETE FROM $tabel WHERE id = '".$id."'";
      $sql2 = $connect->query($del);
      $logger = logger($connect,'Delete Company', 'Company: '.$fetch['company'].' - Address: '.$fetch['address'].' - Phone: '.$fetch['phone']);  		
      
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