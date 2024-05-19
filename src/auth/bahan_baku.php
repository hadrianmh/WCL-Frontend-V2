<?php

/////////////////////////////
// Personal config DataTables 
////////////////////////////
require '../dashboard/session.php';
require 'connect.php';
require 'history.php';

$tabel = 'bahan_baku';
$Query = 'action';
$slug = 'bahan_baku';
$WhereBY = 'id';

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
        $action == 'ins_'.$slug  ||
        $action == 'outs_'.$slug  ||
      	$action == 'histori_bahanbaku' ||
        $action == 'sortdata' ||
        $action == 'delhis' ||
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
	    
	    $query = "SELECT a.id, a.ingredient, a.size, a.color, a.input_by, b.note, SUM(b.s_masuk - b.s_keluar) AS stock, b.unit, c.name FROM bahan_baku AS a LEFT JOIN histori_bahan AS b ON a.id_fk = b.id_fk LEFT JOIN user AS c ON c.id = a.input_by GROUP BY a.id_fk ORDER BY a.id DESC";
	    $sql = $connect->query($query);
	    
	    if (!$sql){
	      $result  = $error;
	      $message = $qerror;
	    } else {
	    	$result  = $success;
      	$message = $qsuccess;
      	$no = 1;
	      while($row = $sql->fetch_array()){

          if($_SESSION['role'] == '5'){
            $functions  = '<div class="function_buttons"><ul><li>Not Allowed</li></ul></div>';
          } else {
  		    	$functions  = '<div class="function_buttons"><ul>';
  		     	$functions .= '<li class="function_ins"><a data-id="'.$row['id'].'" data-name="'.$row['size'].'" title="Masuk stok"><span>(+)</span></a></li>';
  		     	$functions .= '<li class="function_outs"><a data-id="'.$row['id'].'" data-name="'.$row['size'].'" title="Keluar stok"><span>(-)</span></a></li>';
  		     	$functions .= '<li class="function_edit"><a data-id="'.$row['id'].'" data-name="'.$row['size'].'" title="Ubah"><span>Ubah</span></a></li>';
  		      $functions .= '<li class="function_delete"><a data-id="'.$row['id'].'" data-name="'.$row['size'].'" title="Hapus"><span>Hapus</span></a></li>';
  		      $functions .= '</ul></div>';
          }

		      $mysqli_data[] = array(
            "no"    	=> $no++,
		        "ukuran"  => $row['size'],
		        "bahan"   => $row['ingredient'],
            "warna"   => $row['color'],
            "ket"		  => $row['note'],
            "stock"   => $row['stock'],
            "satuan"   => $row['unit'],
            "input"   => $row['name'],
            "functions" => $functions
		      );
	      }
	    } 
  	} elseif ($action == 'add_'.$slug){
    
	    /////////////////
	    // Add PO
	    ////////////////

	    $stock = mysqli_real_escape_string($connect, $_GET['stock']);
      $size = mysqli_real_escape_string($connect, $_GET['size']);
      $ingredient = mysqli_real_escape_string($connect, $_GET['ingredient']);
      $color = mysqli_real_escape_string($connect, $_GET['color']);
      $note = mysqli_real_escape_string($connect, $_GET['note']);
      $unit = mysqli_real_escape_string($connect, $_GET['unit']);

      $select = "SELECT id_fk FROM bahan_baku ORDER BY id_fk DESC LIMIT 1";
      $sql1 = $connect->query($select);
      $fetch = $sql1->fetch_array();
      $id_fk = $fetch['id_fk'] + 1;

	    $insert1 = "INSERT INTO bahan_baku SET ";
	    $insert1 .= "id_fk = '" .$id_fk. "',";
      if (isset($_GET['size']))      { $insert1 .= "size    = '" .$size."',";}
	    if (isset($_GET['ingredient'])){ $insert1 .= "ingredient = '" . $ingredient	."',";}
	    if (isset($_GET['color']))     { $insert1 .= "color 	= '". $color ."',";}
      $insert1 .= "input_by = '".$_SESSION['id']."'";

      $insert2 = "INSERT INTO histori_bahan SET ";
      $insert2 .= "id_fk = '" .$id_fk. "',";
      $insert2 .= "date  = '" .date('Y-m-d'). "',";
      if (isset($_GET['unit'])){ $insert2 .= "unit    = '" . $unit ."',";}
      if (isset($_GET['s_masuk'])){ $insert2 .= "s_masuk    = '" . $stock ."',";}
      if (isset($_GET['note'])){ $insert2 .= "note    = '" . $note ."',";}
      $insert2 .= "input_by = '".$_SESSION['id']."'";

      $sql2 = $connect->query($insert1);
	    $sql3 = $connect->query($insert2);
      $logger = logger($connect,'Insert Bahan Baku', 'Size: '.$size.' - Bahan: '.$ingredient.' - Warna: '.$color.' - Stock: '.$stock);

	    if(!$sql2 || !$sql3 || !$logger){
	      $result  = $error;
	      $message = $qerror;
	    } else {
	      $result  = $success;
	      $message = $qsuccess;
	    }

	} elseif ($action == 'get_'.$slug){
		// Get data by id
    if ($id == ''){
  		$result  = $error;
  		$message = 'ID'.$missing;
    } else {
      $id = mysqli_real_escape_string($connect, $id);
  		$query = "SELECT a.size, a.ingredient, a.color, b.note, SUM(b.s_masuk - b.s_keluar) AS total, b.unit FROM bahan_baku AS a LEFT JOIN histori_bahan AS b ON b.id_fk = a.id_fk WHERE a.id = '".$id."'";
  		$sql = $connect->query($query);
  		if(!$sql){
    		$result  = $error;
    		$message = $qerror;
  		} else {
    		$result  = $success;
    		$message = $qsuccess;
    		while($row = $sql->fetch_array()){
        	$mysqli_data[] = array(
	          "size" 		    => $row['size'],
				    "ingredient"  => $row['ingredient'],
				    "color"  	=> $row['color'],
            "note"    => $row['note'],
            "stock"   => $row['total'],
	          "satuan"		=> $row['unit'],
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
      $size = mysqli_real_escape_string($connect, $_GET['size']);
      $ingredient = mysqli_real_escape_string($connect, $_GET['ingredient']);
      $color = mysqli_real_escape_string($connect, $_GET['color']);
      $note = mysqli_real_escape_string($connect, $_GET['note']);
      $unit = mysqli_real_escape_string($connect, $_GET['unit']);

      $select = "SELECT b.id FROM bahan_baku AS a LEFT JOIN histori_bahan AS b ON b.id_fk = a.id_fk WHERE a.id = '$id' ORDER BY a.id ASC LIMIT 1";
      $sql = $connect->query($select);
      $fetch = $sql->fetch_array();

  		$query1 = "UPDATE bahan_baku SET ";
  	  if (isset($_GET['size']))     { $query1 .= "size		  = '" . $size ."',";}
  	  if (isset($_GET['ingredient'])){ $query1 .= "ingredient = '" . $ingredient . "',";}
      if (isset($_GET['color'])) 		{ $query1 .= "color 		= '" . $color ."'";}
  		$query1 .= " WHERE id = '".$id."'";

      $query2 = "UPDATE histori_bahan SET ";
      $query2 .= "date = '" .date('Y-m-d')."',";
      if(isset($_GET['unit'])){ $query2 .= "unit = '" . $unit . "',";}
      if(isset($_GET['note'])){ $query2 .= "note = '" . $note . "'";}
      $query2 .= " WHERE id = '".$fetch['id']."'";
  		
      $sql1  = $connect->query($query1);
      $sql2  = $connect->query($query2);
      $logger = logger($connect,'Edit Bahan Baku', 'Size: '.$size.' - Bahan: '.$ingredient.' - Warna: '.$color);

  		if(!$sql1 || !$sql2 || !$logger){
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
      $id = mysqli_real_escape_string($connect,$id);
      $select = "SELECT id_fk, size, ingredient, color FROM bahan_baku WHERE id = '$id'";
      $sql = $connect->query($select);
      $fetch = $sql->fetch_array();

      $del1 = "DELETE FROM histori_bahan WHERE id_fk = '".$fetch['id_fk']."'";
  		$del2 = "DELETE FROM bahan_baku WHERE id = '".$id."'";

      $sql1 = $connect->query($del1);
  		$sql2 = $connect->query($del2);
      $logger = logger($connect,'Hapus Bahan Baku', 'Size: '.$fetch['size'].' - Bahan: '.$fetch['ingredient'].' - Warna: '.$fetch['color']);

  		if(!$sql1 || !$sql2 || !$logger){
    		$result  = $error;
    		$message = $qerror;
  		} else {
    		$result  = $success;
    		$message = $qsuccess;
  		}
  	}
    
	} elseif ($action == 'ins_'.$slug){
   // Get data by id
    if ($id == ''){
      $result  = $error;
      $message = 'ID'.$missing;
    } else {
      $id = mysqli_real_escape_string($connect, $id);
      $date = mysqli_real_escape_string($connect, $_GET['date']);
      $customer = mysqli_real_escape_string($connect, $_GET['customer']);
      $nopo = mysqli_real_escape_string($connect, $_GET['nopo']);
      $ukuran = mysqli_real_escape_string($connect, $_GET['ukuran']);
      $s_masuk = mysqli_real_escape_string($connect, $_GET['s_masuk']);
      $unit = mysqli_real_escape_string($connect, $_GET['unit']);
      $note = mysqli_real_escape_string($connect, $_GET['note']);

      $select = "SELECT id_fk, size, ingredient, color FROM bahan_baku WHERE id = '$id'";
      $sql = $connect->query($select);
      $fetch = $sql->fetch_array();

      $insert = "INSERT INTO histori_bahan SET ";
      $insert .= "id_fk = '" .$fetch['id_fk']. "',";
      if(isset($_GET['customer']))  { $insert .= "customer= '" . $customer ."',";}
      if(isset($_GET['date']))      { $insert .= "date    = '" . $date ."',";}
      if(isset($_GET['ukuran']))  { $insert .= "ukuran  = '" . $ukuran ."',";}
      if(isset($_GET['nopo']))    { $insert .= "no_po    = '" . $nopo ."',";}
      if(isset($_GET['unit']))    { $insert .= "unit    = '" . $unit ."',";}
      if(isset($_GET['s_masuk'])) { $insert .= "s_masuk = '" . $s_masuk ."',";}
      if(isset($_GET['note']))    { $insert .= "note    = '" . $note ."',";}
      $insert .= "status = '1',";
      $insert .= "input_by = '".$_SESSION['id']."'";

      $sql1 = $connect->query($insert);
      $logger = logger($connect,'In Stock Bahan Baku', 'Size: '.$fetch['size'].' - Bahan: '.$fetch['ingredient'].' - Customer: '.$customer.' - No PO: '.$nopo.' - In: '.$s_masuk);

      if(!$sql1 || !$logger){
        $result  = $error;
        $message = $qerror;
      } else {
        $result  = $success;
        $message = $qsuccess;
      }
    }

  } elseif ($action == 'outs_'.$slug){
    // Get data by id
    if ($id == ''){
      $result  = $error;
      $message = 'ID'.$missing;
    } else {
      $id = mysqli_real_escape_string($connect, $id);
      $date = mysqli_real_escape_string($connect, $_GET['date']);
      $customer = mysqli_real_escape_string($connect, $_GET['customer']);
      $nopo = mysqli_real_escape_string($connect, $_GET['nopo']);
      $ukuran = mysqli_real_escape_string($connect, $_GET['ukuran']);
      $s_keluar = mysqli_real_escape_string($connect, $_GET['s_keluar']);
      $unit = mysqli_real_escape_string($connect, $_GET['unit']);
      $note = mysqli_real_escape_string($connect, $_GET['note']);

      $select = "SELECT id_fk, size, ingredient, color FROM bahan_baku WHERE id = '$id'";
      $sql = $connect->query($select);
      $fetch = $sql->fetch_array();

      $insert = "INSERT INTO histori_bahan SET ";
      $insert .= "id_fk = '" .$fetch['id_fk']. "',";
      if(isset($_GET['customer']))  { $insert .= "customer= '" . $customer ."',";}
      if(isset($_GET['date']))      { $insert .= "date    = '" . $date ."',";}
      if(isset($_GET['ukuran']))  { $insert .= "ukuran  = '" . $ukuran ."',";}
      if(isset($_GET['nopo']))    { $insert .= "no_po    = '" . $nopo ."',";}
      if(isset($_GET['unit']))    { $insert .= "unit    = '" . $unit ."',";}
      if(isset($_GET['s_keluar'])) { $insert .= "s_keluar = '" . $s_keluar ."',";}
      if(isset($_GET['note']))     { $insert .= "note    = '" . $note ."',";}
      $insert .= "status = '2',";
      $insert .= "input_by = '".$_SESSION['id']."'";

      $sql1 = $connect->query($insert);
      $logger = logger($connect,'Out Stock Bahan Baku', 'Size: '.$fetch['size'].' - Bahan: '.$fetch['ingredient'].' - Customer: '.$customer.' - No PO: '.$nopo.' - Out: '.$s_keluar);

      if(!$sql1 || !$logger){
        $result  = $error;
        $message = $qerror;
      } else {
        $result  = $success;
        $message = $qsuccess;
      }
    }

	} elseif ($action == 'histori_bahanbaku'){

    $curMonth = str_replace('/', '-', $_GET['curMonth']);
    $query = "SELECT a.*, b.size, b.ingredient, b.color, c.name FROM histori_bahan AS a LEFT JOIN bahan_baku AS b ON a.id_fk = b.id_fk LEFT JOIN user AS c ON c.id = a.input_by WHERE a.date LIKE '$curMonth%' ORDER BY a.id ASC";
    $sql = $connect->query($query);

    if(!$sql){
      $result  = $error;
      $message = $qerror;

    } else {
      $result  = $success;
      $message = $qsuccess;
      $no = 1;
      while($row = $sql->fetch_array()){

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
          $functions .= '<li class="delhis"><a data-id="'.$row['id'].'" data-name="'.$row['size'].'"><span>Hapus</span></a></li>';
          $functions .= '</ul></div>';
        }

        $newDate = date("d/m/Y", strtotime($row['date']));

        if($row['status'] == 0)
        {
          $mysqli_data[] = array(
            "no"      => $no++,
            "date"    => $newDate,
            "size"    => $row['size'],
            "ingredient"=> $row['ingredient'],
            "color"   => $row['color'],
            "customer"=> $row['customer'],
            "nopo"    => $row['no_po'],
            "status"  => $status,
            "ukuran"  => $row['ukuran'],
            "note"    => $row['note'],
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
            "size"    => $row['size'],
            "ingredient"=> $row['ingredient'],
            "color"   => $row['color'],
            "customer"=> $row['customer'],
            "nopo"    => $row['no_po'],
            "status"  => $status,
            "ukuran"  => $row['ukuran'],
            "note"    => $row['note'],
            "unit"    => $row['unit'],
            "s_awal"  => '0',
            "s_masuk" => $row['s_masuk'],
            "s_keluar"=> $row['s_keluar'],
            "s_akhir" => '',
            "input"   => $row['name'],
            "functions" => $functions
          );
        }
        //$array_awal[] = $row['s_masuk'] - $row['s_keluar'];
        
        //$array_masuk[] = $row['s_masuk'];
        //$array_keluar[] = $row['s_keluar'];
      }
    }

  } elseif($action == 'sortdata'){
    $query = "SELECT DISTINCT date FROM histori_bahan ORDER BY date DESC";
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
      $id = mysqli_real_escape_string($connect, $id);

      $query1 = "SELECT a.size, a.ingredient, a.color, b.* FROM bahan_baku AS a LEFT JOIN histori_bahan AS b ON b.id_fk = a.id_fk WHERE b.id = '".$id."'";
      $sql1 = $connect->query($query1);
      $fetch = $sql1->fetch_array();

      $query2 = "DELETE FROM histori_bahan WHERE id = '".$id."'";
      $sql2 = $connect->query($query2);
      
      $logger = logger($connect,'Delete Bahan Baku (history)', 'Size: '.$fetch['size'].' - Bahan: '.$fetch['ingredient'].' - Warna: '.$fetch['color'].' - Customer: '.$fetch['customer'].' - Bahan: '.$fetch['no_po'].' - Satuan: '.$fetch['unit']);
      
      if(!$sql1 OR !$sql2 OR !$logger){
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