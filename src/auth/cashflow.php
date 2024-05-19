<?php

/////////////////////////////
// Personal config DataTables 
////////////////////////////
require '../dashboard/session.php';
require 'connect.php';
$tabel1 = 'cash_flow_data';
$tabel2 = 'cash_flow_money';
$Query = 'action';
$slug = 'cashflow';

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
      $action == 'masuk_'.$slug ||
  		$action == 'keluar_'.$slug ||
      $action == 'get_'.$slug ||
      $action == 'edit_'.$slug ||
      $action == 'del_'.$slug ||
      $action == 'sortdata_'.$slug){
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
	
  if($action == 'sortdata_'.$slug){
    $query = "SELECT DISTINCT id_fk FROM $tabel2 ORDER BY id_fk DESC";
    $sql = $connect->query($query);

    if(!$sql){
      $result  = $error;
      $message = $qerror;
    } else {
      $result  = $success;
      $message = $qsuccess;

      while($row = $sql->fetch_array()){
        $mysqli_data[] = array(
          'montly' => $row['id_fk']
        );
      }
    }

  } elseif ($action == 'result_'.$slug){

    ///////////////////////////
    // Get pre order data
    ///////////////////////////

    $curMonth = $_GET['curMonth'];
    
    $query = "SELECT $tabel1.id, $tabel1.id_fk, $tabel1.tgl, $tabel1.nama, $tabel1.tujuan, $tabel1.keterangan, $tabel1.masuk, $tabel1.keluar, $tabel1.sisa, $tabel2.id_fk, $tabel2.saldo FROM $tabel1 LEFT JOIN $tabel2 ON $tabel1.id_fk = $tabel2.id_fk WHERE $tabel1.id_fk='$curMonth' ORDER BY $tabel1.id ASC";
    $sql = $connect->query($query);
    
    if(!$sql){
      $result  = $error;
      $message = $qerror;
    } else {
    	$result  = $success;
    	$message = $qsuccess;

      $no = 1;
	    while($row = $sql->fetch_array()){
        $originalDate = $row['tgl'];
        $tgl = date("d-M-Y", strtotime($originalDate));
        if($_SESSION['role'] == '5'){
            $functions  = '<div class="function_buttons"><ul><li>Not Allowed</li></ul></div>';
        } else {
		    	$functions  = '<div class="function_buttons"><ul>';
  		    $functions .= '<li class="function_edit"><a data-id="'.$row['id'].'" data-name="'.$row['keterangan'].'"><span>Ubah</span></a></li>';
          $functions .= '<li class="function_delete"><a data-id="'.$row['id'].'" data-name="'.$row['keterangan'].'"><span>Hapus</span></a></li>';
	        $functions .= '</ul></div>';
        }

        $mysqli_data[] = array(
          "no"        => $no++,
          "id_fk"  		=> $row['id_fk'],
          "tgl"       => $tgl,
          "nama"      => $row['nama'],
          "tujuan"    => $row['tujuan'],
          "keterangan"=> $row['keterangan'],
          "masuk"     => $row['masuk'],
          "keluar"    => $row['keluar'],
          "sisa"      => $row['sisa'],
          "saldo"     => $row['saldo'],
          "functions" => $functions
        );
      }
    }

	} elseif ($action == 'masuk_'.$slug){

      $uang = str_replace('.', '', mysqli_real_escape_string($connect, $_GET['uang']));
      $tgl = mysqli_real_escape_string($connect, $_GET['tgl']);
      $originalDate = $tgl;
      $newDate = date("Y/m", strtotime($originalDate));
      $select = "SELECT * FROM $tabel1 WHERE id_fk = '".$newDate."' ORDER BY id DESC LIMIT 1";
      $sql1 = $connect->query($select);
      $fetch = $sql1->fetch_array();

      if($sql1->num_rows > 0){
        $sisa = $fetch['sisa'] + $uang;

        $query1 = "INSERT INTO $tabel1 SET ";
        $query1 .= "id_fk = '".$newDate."', tgl = '".$tgl."', masuk = '".$uang."', keluar = '0', sisa = '".$sisa."',";
        if (isset($_GET['nama']))     { $query1 .= "nama       = '".mysqli_real_escape_string($connect, $_GET['nama'])."',";}
        if (isset($_GET['tujuan']))   { $query1 .= "tujuan     = '".mysqli_real_escape_string($connect, $_GET['tujuan'])."',";}
        if (isset($_GET['keterangan'])){ $query1 .= "keterangan  = '".mysqli_real_escape_string($connect, $_GET['keterangan'])."'";}

        $query2 = "UPDATE $tabel2 SET saldo = saldo + ".$uang." WHERE id_fk = '".$newDate."'";

        $sql2 = $connect->query($query1);
        $sql3 = $connect->query($query2);
        if (!$sql2 AND !$sql3){
          $result  = $error;
          $message = $qerror;
        } else {
          $result  = $success;
          $message = $qsuccess;
        }

      } else {

        $query1 = "INSERT INTO $tabel1 SET ";
        $query1 .= "id_fk = '".$newDate."', tgl = '".$tgl."', masuk = '".$uang."', keluar = '0', sisa = '".$uang."',";
        if (isset($_GET['nama']))     { $query1 .= "nama       = '".mysqli_real_escape_string($connect, $_GET['nama'])."',";}
        if (isset($_GET['tujuan']))   { $query1 .= "tujuan     = '".mysqli_real_escape_string($connect, $_GET['tujuan'])."',";}
        if (isset($_GET['keterangan'])){ $query1 .= "keterangan= '".mysqli_real_escape_string($connect, $_GET['keterangan'])."'";}
        
        $query2 = "INSERT INTO $tabel2 SET id_fk = '".$newDate."', saldo = '".$uang."'";
        
        $sql2 = $connect->query($query1);
        $sql3 = $connect->query($query2);
        if (!$sql2 OR !$sql3){
          $result  = $error;
          $message = $qerror;
        } else {
          $result  = $success;
          $message = $qsuccess;
        }
      }

  } elseif ($action == 'keluar_'.$slug){

      $uang = str_replace('.', '', mysqli_real_escape_string($connect, $_GET['uang']));
      $tgl = mysqli_real_escape_string($connect, $_GET['tgl']);
      $originalDate = $tgl;
      $newDate = date("Y/m", strtotime($originalDate));
      $select = "SELECT * FROM $tabel1 WHERE id_fk = '".$newDate."' ORDER BY id DESC LIMIT 1";
      $sql1 = $connect->query($select);
      $fetch = $sql1->fetch_array();

      if($sql1->num_rows > 0){
        $sisa = $fetch['sisa'] - $uang;

        $query1 = "INSERT INTO $tabel1 SET ";
        $query1 .= "id_fk = '".$newDate."', tgl = '".$tgl."', masuk = '0', keluar = '".$uang."', sisa = '".$sisa."', type = '1',";
        if (isset($_GET['nama']))     { $query1 .= "nama       = '".mysqli_real_escape_string($connect, $_GET['nama'])."',";}
        if (isset($_GET['tujuan']))   { $query1 .= "tujuan     = '".mysqli_real_escape_string($connect, $_GET['tujuan'])."',";}
        if (isset($_GET['keterangan'])){ $query1 .= "keterangan  = '".mysqli_real_escape_string($connect, $_GET['keterangan'])."'";}

        $query2 = "UPDATE $tabel2 SET saldo = saldo - ".$uang." WHERE id_fk = '".$newDate."'";

        $sql2 = $connect->query($query1);
        $sql3 = $connect->query($query2);
        if (!$sql2 AND !$sql3){
          $result  = $error;
          $message = $qerror;
        } else {
          $result  = $success;
          $message = $qsuccess;
        }

      } else {

        $query1 = "INSERT INTO $tabel1 SET ";
        $query1 .= "id_fk = '".$newDate."', tgl = '".$tgl."', masuk = '0', keluar = '".$uang."', sisa = sisa - '".$uang."', type = '1',";
        if (isset($_GET['nama']))     { $query1 .= "nama       = '".mysqli_real_escape_string($connect, $_GET['nama'])."',";}
        if (isset($_GET['tujuan']))   { $query1 .= "tujuan     = '".mysqli_real_escape_string($connect, $_GET['tujuan'])."',";}
        if (isset($_GET['keterangan'])){ $query1 .= "keterangan= '".mysqli_real_escape_string($connect, $_GET['keterangan'])."'";}
        
        $query2 = "INSERT INTO $tabel2 SET id_fk = '".$newDate."', saldo = saldo - '".$uang."'";
        
        $sql2 = $connect->query($query1);
        $sql3 = $connect->query($query2);
        if (!$sql2 OR !$sql3){
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
      $query = "SELECT * FROM $tabel1 WHERE id = '".$id."'";
      $sql = $connect->query($query);
      $fetch = $sql->fetch_array();

      if($sql){

        if($fetch['type'] == 0){
          $result  = $success;
          $message = $qsuccess;
          $mysqli_data[] = array(
            "tgl"      => $fetch['tgl'],
            "nama"     => $fetch['nama'],
            "tujuan"   => $fetch['tujuan'],
            "keterangan"=> $fetch['keterangan'],
            "masuk"    => $fetch['masuk'],
            "sisa"     => $fetch['sisa'],
            "type"     => $fetch['type']
          );

        } elseif($fetch['type'] == 1){
          $result  = $success;
          $message = $qsuccess;
          $mysqli_data[] = array(
            "tgl"      => $fetch['tgl'],
            "nama"     => $fetch['nama'],
            "tujuan"   => $fetch['tujuan'],
            "keterangan"=> $fetch['keterangan'],
            "keluar"   => $fetch['keluar'],
            "sisa"     => $fetch['sisa'],
            "type"     => $fetch['type']
          );

        } else {
          $result  = $error;
          $message = $qerror;
        }

      } else {
        $result  = $error;
        $message = $qerror;
      }
    }
  
  } elseif($action == 'edit_'.$slug){
      if ($id == ''){
          $result  = $error;
          $message = 'ID '.$missing;
      } else {
        $id = mysqli_real_escape_string($connect, $id);
        $type = mysqli_real_escape_string($connect, $_GET['type']);
        $uang = str_replace('.', '', mysqli_real_escape_string($connect, $_GET['uang']));

        if($type == 0){
          $select = "SELECT masuk,id_fk FROM $tabel1 WHERE id = '".$id."'";
          $sql = $connect->query($select);
          $fetch = $sql->fetch_array();

          if($uang >= $fetch['masuk']){
            $masuk = $uang - $fetch['masuk'];

            $update1 = "UPDATE $tabel1 SET ";
            if (isset($_GET['tujuan'])){$update1 .= "tujuan = '".mysqli_real_escape_string($connect, $_GET['tujuan'])."',";}
            if (isset($_GET['keterangan'])){$update1 .= "keterangan = '".mysqli_real_escape_string($connect, $_GET['keterangan'])."',";}
            $update1 .= "masuk = '".$uang."' WHERE id = '".$id."'";

            $update2 = "UPDATE $tabel2 SET saldo = saldo + ".$masuk." WHERE id_fk = '".$fetch['id_fk']."'";

            $update3 = "UPDATE $tabel1 SET sisa = sisa + ".$masuk." WHERE id_fk = '".$fetch['id_fk']."' AND id >= '".$id."'";

            $run1 = $connect->query($update1);
            $run2 = $connect->query($update2);
            $run3 = $connect->query($update3);

            if (!$run1 OR !$run2 OR !$run3){
              $result  = $error;
              $message = $qerror;
            } else {
              $result  = $success;
              $message = $qsuccess;
            }

          } else {

            $masuk = $fetch['masuk'] - $uang;

            $update1 = "UPDATE $tabel1 SET ";
            if (isset($_GET['tujuan'])){$update1 .= "tujuan = '".mysqli_real_escape_string($connect, $_GET['tujuan'])."',";}
            if (isset($_GET['keterangan'])){$update1 .= "keterangan = '".mysqli_real_escape_string($connect, $_GET['keterangan'])."',";}
            $update1 .= "masuk = '".$uang."' WHERE id = '".$id."'";

            $update2 = "UPDATE $tabel2 SET saldo = saldo - ".$masuk." WHERE id_fk = '".$fetch['id_fk']."'";

            $update3 = "UPDATE $tabel1 SET sisa = sisa - ".$masuk." WHERE id_fk = '".$fetch['id_fk']."' AND id >= '".$id."'";

            $run1 = $connect->query($update1);
            $run2 = $connect->query($update2);
            $run3 = $connect->query($update3);

            if (!$run1 OR !$run2 OR !$run3){
              $result  = $error;
              $message = $qerror;
            } else {
              $result  = $success;
              $message = $qsuccess;
            }
          }

        } else if($type == 1){
          $select = "SELECT keluar,id_fk FROM $tabel1 WHERE id = '".$id."'";
          $sql = $connect->query($select);
          $fetch = $sql->fetch_array();

          if($uang >= $fetch['keluar']){

            $keluar = $uang - $fetch['keluar'];

            $update1 = "UPDATE $tabel1 SET ";
            if (isset($_GET['tujuan'])){$update1 .= "tujuan = '".mysqli_real_escape_string($connect, $_GET['tujuan'])."',";}
            if (isset($_GET['keterangan'])){$update1 .= "keterangan = '".mysqli_real_escape_string($connect, $_GET['keterangan'])."',";}
            $update1 .= "keluar = '".$uang."' WHERE id = '".$id."'";

            $update2 = "UPDATE $tabel2 SET saldo = saldo - ".$keluar." WHERE id_fk = '".$fetch['id_fk']."'";

            $update3 = "UPDATE $tabel1 SET sisa = sisa - ".$keluar." WHERE id_fk = '".$fetch['id_fk']."' AND id >= '".$id."'";

            $run1 = $connect->query($update1);
            $run2 = $connect->query($update2);
            $run3 = $connect->query($update3);

            if (!$run1 OR !$run2 OR !$run3){
              $result  = $error;
              $message = $qerror;
            } else {
              $result  = $success;
              $message = $qsuccess;
            }

          } else {

            $keluar = $fetch['keluar'] - $uang;

            $update1 = "UPDATE $tabel1 SET ";
            if (isset($_GET['tujuan'])){$update1 .= "tujuan = '".mysqli_real_escape_string($connect, $_GET['tujuan'])."',";}
            if (isset($_GET['keterangan'])){$update1 .= "keterangan = '".mysqli_real_escape_string($connect, $_GET['keterangan'])."',";}
            $update1 .= "keluar = '".$uang."' WHERE id = '".$id."'";

            $update2 = "UPDATE $tabel2 SET saldo = saldo + ".$keluar." WHERE id_fk = '".$fetch['id_fk']."'";

            $update3 = "UPDATE $tabel1 SET sisa = sisa + ".$keluar." WHERE id_fk = '".$fetch['id_fk']."' AND id >= '".$id."'";

            $run1 = $connect->query($update1);
            $run2 = $connect->query($update2);
            $run3 = $connect->query($update3);

            if (!$run1 OR !$run2 OR !$run3){
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
      }

    } elseif ($action == 'del_'.$slug){
      // Delete data
      if ($id == ''){
        $result  = $error;
        $message = 'ID'.$missing;
      } else {
        $id = mysqli_real_escape_string($connect,$id);
        $select1 = "SELECT * FROM $tabel1 WHERE id = '".$id."'";
        $sql1 = $connect->query($select1);
        $fetch = $sql1->fetch_array();
        $masuk = $fetch['masuk'];
        $keluar = $fetch['keluar'];
        $id_fk = $fetch['id_fk'];

        $select2 = "SELECT * FROM $tabel1 WHERE id_fk = '".$id_fk."'";
        $sql2 = $connect->query($select2);

        if($sql2->num_rows > 1){

          if($fetch['type'] == 0){
            $del = "DELETE FROM $tabel1 WHERE id = '".$id."'";
            $update1 = "UPDATE $tabel1 SET sisa = sisa - '".$masuk."' WHERE id_fk = '".$id_fk."' AND id > '".$id."'";
            $update2 = "UPDATE $tabel2 SET saldo = saldo - '".$masuk."' WHERE id_fk = '".$id_fk."'";

            $run1 = $connect->query($del);
            $run2 = $connect->query($update1);
            $run3 = $connect->query($update2);

            if (!$run1 OR !$run2 OR !$run3){
              $result  = $error;
              $message = $qerror;
            } else {
              $result  = $success;
              $message = $qsuccess;
            }

          } elseif($fetch['type'] == 1){
            $del = "DELETE FROM $tabel1 WHERE id = '".$id."'";
            $update1 = "UPDATE $tabel1 SET sisa = sisa + '".$keluar."' WHERE id_fk = '".$id_fk."' AND id > '".$id."'";
            $update2 = "UPDATE $tabel2 SET saldo = saldo + '".$keluar."' WHERE id_fk = '".$id_fk."'";

            $run1 = $connect->query($del);
            $run2 = $connect->query($update1);
            $run3 = $connect->query($update2);

            if (!$run1 OR !$run2 OR !$run3){
              $result  = $error;
              $message = $qerror;
            } else {
              $result  = $success;
              $message = $qsuccess;
            }

          } else {
            $result  = $error;
            $message = $qerror;
          }

        } else {
          $del1 = "DELETE FROM $tabel1 WHERE id = '".$id."'";
          $del2 = "DELETE FROM $tabel2 WHERE id_fk = '".$id_fk."'";

          $run1 = $connect->query($del1);
          $run2 = $connect->query($del2);

          if (!$del2 OR !$del2){
            $result  = $error;
            $message = $qerror;
          } else {
            $result  = $success;
            $message = $qsuccess;
          }
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