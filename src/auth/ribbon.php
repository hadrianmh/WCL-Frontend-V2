<?php

/////////////////////////////
// Personal config DataTables 
////////////////////////////
require '../utils/connectV1.php';
require 'history.php';

$Query = 'action';
$slug = 'ribbon';
$WhereBY = 'id';
$dataName = 'size';

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
      	$action == 'histori_ribbon' ||
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

if($action != ''){
	///////////////////////
	// Execute all action
	//////////////////////
  	if ($action == 'result_'.$slug){

	    ///////////////////////////
	    // Get pre order data
	    ///////////////////////////
	    
	    $query = "SELECT a.*, SUM(b.s_masuk) AS masuk, SUM(b.s_keluar) AS keluar, c.name FROM ribbon AS a LEFT JOIN histori_ribbon AS b ON a.id_fk = b.id_fk LEFT JOIN user AS c ON c.id = a.input_by GROUP BY a.id_fk ORDER BY a.id DESC";
	    $sql = $connect->query($query);
	    
	    if(!$sql){
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
  		     	$functions .= '<li class="function_ins"><a data-id="'.$row['id'].'" data-name="'.$row[$dataName].'" title="Masuk stok"><span>(+)</span></a></li>';
  		     	$functions .= '<li class="function_outs"><a data-id="'.$row['id'].'" data-name="'.$row[$dataName].'" title="Keluar stok"><span>(-)</span></a></li>';
  		     	$functions .= '<li class="function_edit"><a data-id="'.$row['id'].'" data-name="'.$row[$dataName].'" title="Ubah"><span>Ubah</span></a></li>';
  		      $functions .= '<li class="function_delete"><a data-id="'.$row['id'].'" data-name="'.$row[$dataName].'" title="Hapus"><span>Hapus</span></a></li>';
  		      $functions .= '</ul></div>';
          }

		      $mysqli_data[] = array(
            "no"    	  => $no++,
            "rak"       => $row['rak'],
		        "customer"  => $row['customer'],
		        "product"   => $row['product'],
            "size"      => $row['size'],
		        "fi-fo" 		=> $row['fi_fo'],
            "stock"		  => $row['masuk'] - $row['keluar'],
            "input"     => $row['name'],
            "functions" => $functions
		      );
	      }
	    } 
  	} elseif ($action == 'add_'.$slug){
    
	    /////////////////
	    // Add PO
	    ////////////////

      $QUERY1 = "SELECT id_fk FROM ribbon ORDER BY id_fk DESC LIMIT 1";
      $SQL1 = $connect->query($QUERY1);
      $FETCH = $SQL1->fetch_array();
      $ID_FK = $FETCH['id_fk'] + 1;

	    $QUERY2 = "INSERT INTO ribbon SET ";
      $QUERY3 = "INSERT INTO histori_ribbon SET ";
      
      if(isset($ID_FK))             { $QUERY2 .= "id_fk    = '" .$ID_FK."',";}
      if(isset($_GET['customer']))  { $QUERY2 .= "customer = '" .mysqli_real_escape_string($connect, $_GET['customer'])."',";}
      if(isset($_GET['rak']))       { $QUERY2 .= "rak      = '" .mysqli_real_escape_string($connect, $_GET['rak'])."',";}
      if(isset($_GET['product']))   { $QUERY2 .= "product  = '" .mysqli_real_escape_string($connect, $_GET['product'])  ."',";}
      if(isset($_GET['size']))      { $QUERY2 .= "size     = '" .mysqli_real_escape_string($connect, $_GET['size'])  ."',";}
      if(isset($_GET['fi-fo']))     { $QUERY2 .= "fi_fo    = '" .mysqli_real_escape_string($connect, $_GET['fi-fo']) ."', input_by = '".$_SESSION['id']."'";}

      if(isset($ID_FK))              { $QUERY3 .= "id_fk    = '" .$ID_FK."',";}
      if(isset($ID_FK))              { $QUERY3 .= "date     = '" .date('Y-m-d')."',";}
      if(isset($ID_FK))              { $QUERY3 .= "s_masuk  = '" .mysqli_real_escape_string($connect, $_GET['stock'])."', input_by = '".$_SESSION['id']."'";}	   

      $SQL2 = $connect->query($QUERY2);
	    $SQL3 = $connect->query($QUERY3);
      $SQL4 = logger($connect,'Insert Ribbon', 'Rak: '.$_GET['rak'].' - Customer: '.$_GET['customer'].' - Product: '.$_GET['product'].' - Size: '.$_GET['size'].' - Stock: '.$_GET['stock']);
	    
      if(!$SQL2 OR !$SQL3 OR !$SQL4){
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
  		
      $QUERY1 = "SELECT a.*, SUM(b.s_masuk) AS masuk, SUM(b.s_keluar) AS keluar FROM ribbon AS a LEFT JOIN histori_ribbon AS b ON a.id_fk = b.id_fk WHERE a.id = '".$id."'";
  		$SQL1 = $connect->query($QUERY1);
  		
      if(!$SQL1){
    		$result  = $error;
    		$message = $qerror;
  		} else {
    		$result  = $success;
    		$message = $qsuccess;
    		while($row = $SQL1->fetch_array()){
        	$mysqli_data[] = array(
            "rak"     => $row['rak'],
        		"customer"=> $row['customer'],
	          "product" => $row['product'],
	          "size" 		=> $row['size'],
				    "fi_fo"  	=> $row['fi_fo'],
	          "stock"		=> $row['masuk'] - $row['keluar'],
          );
    		}
  		}
    }
  
  } elseif($action == 'edit_'.$slug){

    if ($id == ''){
  		$result  = $error;
  		$message = 'ID '.$missing;
  	} else {
      $query = "UPDATE ribbon SET ";
      if (isset($_GET['customer'])){ $query .= "customer = '" .mysqli_real_escape_string($connect, $_GET['customer'])."',";}
  		if (isset($_GET['rak']))	   { $query .= "rak      = '" .mysqli_real_escape_string($connect, $_GET['rak'])    ."',";}
  	  if (isset($_GET['product'])) { $query .= "product  = '" .mysqli_real_escape_string($connect, $_GET['product'])."',";}
  	  if (isset($_GET['size']))    { $query .= "size		 = '" .mysqli_real_escape_string($connect, $_GET['size'])	  ."',";}
  	  if (isset($_GET['fi-fo']))   { $query .= "fi_fo 	 = '" .mysqli_real_escape_string($connect, $_GET['fi-fo'])	."'";}
      $query .= " WHERE id = '".mysqli_real_escape_string($connect, $id)."'";
  		$SQL1  = $connect->query($query);
      $SQL2 = logger($connect,'Edit Ribbon', 'Rak: '.$_GET['rak'].' - Customer: '.$_GET['customer'].' - Product: '.$_GET['product'].' - Size: '.$_GET['size']);

      if(!$SQL1 OR !$SQL2){
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
      $QUERY1 = "SELECT * FROM ribbon WHERE id = '".$id."'";
      $SQL1 = $connect->query($QUERY1);
      $FETCH = $SQL1->fetch_array();
    	
      $QUERY2 = "DELETE FROM ribbon WHERE id = '".$id."'";
      $QUERY3 = "DELETE FROM histori_ribbon WHERE id_fk = '".$FETCH['id_fk']."'";

      $SQL2 = $connect->query($QUERY2);
      $SQL3 = $connect->query($QUERY3);
      $SQL4 = logger($connect,'Delete Ribbon (all)', 'Rak: '.$FETCH['rak'].'- Customer: '.$FETCH['customer'].' - Product: '.$FETCH['product'].' - Size: '.$FETCH['size']);
  		
      if(!$SQL2 OR !$SQL3 OR !$SQL4){
    		$result  = $error;
    		$message = $qerror;
  		} else {
    		$result  = $success;
    		$message = $qsuccess;
  		}
  	}

	} elseif ($action == 'ins_'.$slug){
    // Get data by id
    if($id == ''){
      $result  = $error;
      $message = 'ID'.$missing;
    } else {
      $QUERY1 = "SELECT rak, customer, product, size, id_fk FROM ribbon WHERE id = '".$id."'";
      $SQL1 = $connect->query($QUERY1);
      $FETCH = $SQL1->fetch_array();
      $ID_FK = $FETCH['id_fk'];

      $QUERY2 = "INSERT INTO histori_ribbon SET ";
      if(isset($ID_FK))           { $QUERY2 .= "id_fk     = '" . $ID_FK ."',";}
      if(isset($_GET['customer'])){ $QUERY2 .= "customer  = '" . mysqli_real_escape_string($connect, $_GET['customer'])."',";}
      if(isset($_GET['tgl_']))    { $QUERY2 .= "date      = '" . mysqli_real_escape_string($connect, $_GET['tgl_'])."',";}
      if(isset($_GET['nosj_']))   { $QUERY2 .= "no_sj     = '" . mysqli_real_escape_string($connect, $_GET['nosj_'])."',";}
      if(isset($_GET['nopo_']))   { $QUERY2 .= "no_po     = '" . mysqli_real_escape_string($connect, $_GET['nopo_'])."',";}
      if(isset($_GET['gulungan_'])){ $QUERY2 .= "gulungan = '" . mysqli_real_escape_string($connect, $_GET['gulungan_'])."',";}
      if(isset($_GET['roll_']))   { $QUERY2 .= "roll      = '" . mysqli_real_escape_string($connect, $_GET['roll_'])."',";}
      if(isset($_GET['s_masuk'])) { $QUERY2 .= "s_masuk = '" . mysqli_real_escape_string($connect, $_GET['s_masuk'])."', status = 1, input_by = '".$_SESSION['id']."'";}

      $SQL2 = $connect->query($QUERY2);
      $SQL3 = logger($connect,'Ins Stock Ribbon', 'Rak: '.$FETCH['rak'].' - Customer: '.$FETCH['customer'].' - Product: '.$FETCH['product'].' - Size: '.$FETCH['size'].' - Stock: '.$FETCH['s_masuk']);
      
      if(!$SQL2 OR !$SQL3){
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
  		$QUERY1 = "SELECT rak, customer, product, size, id_fk FROM ribbon WHERE id = '".$id."'";
      $SQL1 = $connect->query($QUERY1);
      $FETCH = $SQL1->fetch_array();
      $ID_FK = $FETCH['id_fk'];

      $QUERY2 = "INSERT INTO histori_ribbon SET ";
      if(isset($ID_FK))           { $QUERY2 .= "id_fk     = '" . $ID_FK ."',";}
      if(isset($_GET['customer'])){ $QUERY2 .= "customer  = '" . mysqli_real_escape_string($connect, $_GET['customer'])."',";}
      if(isset($_GET['tgl_']))    { $QUERY2 .= "date      = '" . mysqli_real_escape_string($connect, $_GET['tgl_'])."',";}
      if(isset($_GET['nosj_']))   { $QUERY2 .= "no_sj     = '" . mysqli_real_escape_string($connect, $_GET['nosj_'])."',";}
      if(isset($_GET['nopo_']))   { $QUERY2 .= "no_po     = '" . mysqli_real_escape_string($connect, $_GET['nopo_'])."',";}
      if(isset($_GET['gulungan_'])){ $QUERY2 .= "gulungan = '" . mysqli_real_escape_string($connect, $_GET['gulungan_'])."',";}
      if(isset($_GET['roll_']))   { $QUERY2 .= "roll      = '" . mysqli_real_escape_string($connect, $_GET['roll_'])."',";}
      if(isset($_GET['s_keluar'])) { $QUERY2 .= "s_keluar = '" . mysqli_real_escape_string($connect, $_GET['s_keluar'])."', status = 2, input_by = '".$_SESSION['id']."'";}

      $SQL2 = $connect->query($QUERY2);
      $SQL3 = logger($connect,'Out Stock Ribbon', 'Rak: '.$FETCH['rak'].' - Customer: '.$FETCH['customer'].' - Product: '.$FETCH['product'].' - Size: '.$FETCH['size'].' - Stock: '.$FETCH['s_masuk']);
      
      if(!$SQL2 OR !$SQL3){
    		$result  = $error;
    		$message = $qerror;
  		} else {
    		$result  = $success;
    		$message = $qsuccess;
  		}
  	}

  } elseif ($action == 'histori_ribbon'){

    $curMonth = str_replace('/', '-', $_GET['curMonth']);
    $query = "SELECT a.*, b.rak, b.product, b.size, b.fi_fo, c.name FROM histori_ribbon AS a LEFT JOIN ribbon AS b ON a.id_fk = b.id_fk LEFT JOIN user AS c ON c.id = a.input_by WHERE a.date LIKE '$curMonth%' ORDER BY a.id ASC";
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
            "fi-fo"   => $row['fi_fo'],
            "gulungan"=> $row['gulungan'],
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
            "fi-fo"   => $row['fi_fo'],
            "gulungan"=> $row['gulungan'],
            "roll"    => $row['roll'],
            "s_awal"  => '0',
            "s_masuk" => $row['s_masuk'],
            "s_keluar"=> $row['s_keluar'],
            "s_akhir" => '',
            "input"   => $row['name'],
            "functions" => $functions
          );
        }
      }
    }

  } elseif($action == 'sortdata'){
    $query = "SELECT DISTINCT date FROM histori_ribbon ORDER BY date DESC";
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
      $query1 = "SELECT a.rak, a.customer, a.product, a.size FROM ribbon AS a LEFT JOIN histori_ribbon AS b ON b.id_fk = a.id_fk WHERE b.id = '".$id."'";
      $sql1 = $connect->query($query1);
      $fetch = $sql1->fetch_array();

      $query2 = "DELETE FROM histori_ribbon WHERE id = '".$id."'";
      $sql2 = $connect->query($query2);
      
      $sql3 = logger($connect,'Delete Ribbon (history)', 'Rak: '.$fetch['rak'].'- Customer: '.$fetch['customer'].' - Product: '.$fetch['product'].' - Size: '.$fetch['size']);
      
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