<?php

/////////////////////////////
// Personal config DataTables 
////////////////////////////
require_once '../dashboard/session.php';
require_once 'connect.php';
require_once 'history.php';
$tabel1 = 'status';
$tabel2 = 'delivery_orders_customer';
$tabel3 = 'delivery_orders_item';
$tabel4 = 'workorder_customer';
$tabel5 = 'workorder_item';
$tabel6 = 'user';
$tabel7 = 'preorder_item';
$tabel8 = 'invoice';
$Query = 'action';
$slug = 'delivery_orders_delivery';

//Message alert
$error  = 'error';
$qerror = 'query error';
$success = 'success';
$qsuccess = 'query success';
$missing = 'missing';

/////////////////////////
// Get action (and id)
////////////////////////
$action = '';
$id  = '';
if (isset($_GET[$Query])){
	$action = $_GET[$Query];
  	if ($action == 'result_'.$slug ||
      $action == 'resultAll_'.$slug ||
      $action == 'sortdata_'.$slug ||
      $action == 'get_print_'.$slug || 
      $action == 'print' || 
      $action == 'del_'.$slug ||
      $action == 'periode_'.$slug
    ){
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

function rupiah($angka){
  $output = "Rp. ".number_format($angka, 2, ',', '.');
  return $output;
}

$mysqli_data = array();

if($action != ''){
  	if($action == 'sortdata_'.$slug){
    $query = "SELECT DISTINCT sj_date FROM $tabel2 ORDER BY sj_date DESC";
    $sql = $connect->query($query);

    if(!$sql){
      $result  = $error;
      $message = $qerror;
    } else {
      $result  = $success;
      $message = $qsuccess;

      $montly_list = '';

      while($row = $sql->fetch_array()){
        $montly = date("Y/m", strtotime($row['sj_date']));
        if(!isset($montly_list[$montly])){
          $montly_list[$montly] = 1;
          $mysqli_data[] = array(
            'montly' => $montly
          );
        }
      }
    }

  } elseif ($action == 'result_'.$slug){

    ///////////////////////////
    // Get pre order data
    ///////////////////////////
    
    $curMonth = str_replace('/', '-', $_GET['curMonth']);
    $query = "SELECT a.*, b.order_status, c.sj_date, c.shipto, c.courier, c.no_tracking, c.cost, c.input_by, d.customer, d.po_customer, e.no_so, e.item, e.unit, f.name FROM $tabel3 AS a LEFT JOIN $tabel1 AS b ON a.id_fk = b.id_fk AND a.item_to = b.item_to LEFT JOIN $tabel2 AS c ON a.id_fk = c.id_fk AND a.id_sj = c.id_sj LEFT JOIN $tabel4 AS d ON a.id_fk = d.id_fk LEFT JOIN $tabel5 AS e ON a.id_fk = e.id_fk AND a.item_to = e.item_to LEFT JOIN $tabel6 AS f ON f.id = c.input_by WHERE b.order_status BETWEEN 1 AND 2 AND c.sj_date LIKE '$curMonth%' GROUP BY a.id ORDER BY a.id DESC";
    $sql = $connect->query($query);
    
    if(!$sql){
      $result  = $error;
      $message = $qerror;
    } else {
    	$result  = $success;
    	$message = $qsuccess;
    	$no = 1;
      $array = array();
	    while($row = $sql->fetch_array()){

        $functions  = '<div class="function_buttons"><ul>';
        if($_SESSION['role'] == '5'){
          $functions  = '<li>Not Allowed</li>';
          } else {
            $functions .= '<li class="function_print"><a data-id="'.$row['id'].'-'.$row['id_fk'].'-'.$row['id_sj'].'" title="Print"><span>Print</span></a></li>';
            $functions .= '<li class="function_delete customerDel"><a data-id="'.$row['id'].'-'.$row['id_fk'].'-'.$row['item_to'].'-'.$row['id_sj'].'" data-name="'.$row['item'].'"><span>Hapus</span></a></li>';
        }

	    	$originalDate1 = $row['sj_date'];
        $newDate1 = date("d/m/Y", strtotime($originalDate1));
        $ex_no_so = explode("/", $row['no_so']);

        if($row['send_qty'] > 0)
        {
          $mysqli_data[] = array(
            "no"          => $no++,
            "customer"    => $row['customer'],
            "po_customer" => $row['po_customer'],
            "no_spk"      => $ex_no_so[0]."/".$ex_no_so[1].$ex_no_so[2],
            "no_delivery" => $row['no_delivery'],
            "sj_date"     => $newDate1,
            "shipto"      => $row['shipto'],
            "item"        => $row['item'],
            "send_qty"    => $row['send_qty'],
            "unit"        => $row['unit'],
            "courier"     => $row['courier'],
            "no_tracking" => $row['no_tracking'],
            "ongkir"      => rupiah($row['cost']),
            "input_by"    => $row['name'],
            "functions"   => $functions
          );
        }
	    }
    }

  } elseif($action == 'resultAll_'.$slug){

    ///////////////////////
    // Load data print
    //////////////////////

    $curMonth = str_replace('/', '-', $_GET['curMonth']);
    $query = "SELECT a.*, b.order_status, c.sj_date, c.shipto, c.courier, c.no_tracking, c.cost, c.input_by, d.customer, d.po_customer, e.no_so, e.item, e.unit, f.name FROM $tabel3 AS a LEFT JOIN $tabel1 AS b ON a.id_fk = b.id_fk AND a.item_to AND b.item_to LEFT JOIN $tabel2 AS c ON a.id_fk = c.id_fk AND a.id_sj = c.id_sj LEFT JOIN $tabel4 AS d ON a.id_fk = d.id_fk LEFT JOIN $tabel5 AS e ON a.id_fk = e.id_fk AND a.item_to = e.item_to LEFT JOIN $tabel6 AS f ON f.id = c.input_by WHERE b.order_status BETWEEN 1 AND 2 AND c.sj_date LIKE '$curMonth%' GROUP BY a.id ORDER BY a.id ASC";
    $sql = $connect->query($query);
      
    if(!$sql){
      $result  = $error;
      $message = $qerror;
    } else {
      $no = 1;
      while($row = $sql->fetch_array()){
        $originalDate1 = $row['sj_date'];
        $newDate1 = date("d/m/Y", strtotime($originalDate1));
        $ex_no_so = explode("/", $row['no_so']);
        if($row['send_qty'] > 0)
        {
          $mysqli_data[] = array(
            "no"          => $no++,
            "customer"    => $row['customer'],
            "po_customer" => $row['po_customer'],
            "no_spk"      => $ex_no_so[0]."/".$ex_no_so[1].$ex_no_so[2],
            "no_delivery" => $row['no_delivery'],
            "sj_date"     => $newDate1,
            "shipto"      => $row['shipto'],
            "item"        => $row['item'],
            "send_qty"    => $row['send_qty'],
            "unit"        => $row['unit'],
            "courier"     => $row['courier'],
            "no_tracking" => $row['no_tracking'],
            "ongkir"      => $row['cost'],
            "input_by"    => $row['name']
          );
        }
      }
    }

  } elseif ($action == 'periode_'.$slug){

    ///////////////////////////
    // Get pre order data
    ///////////////////////////
    
    $dari = mysqli_real_escape_string($connect, $_GET['dari']);
    $sampai = mysqli_real_escape_string($connect, $_GET['sampai']);

    $query = "SELECT a.*, b.order_status, c.sj_date, c.shipto, c.courier, c.no_tracking, c.cost, c.input_by, d.customer, d.po_customer, e.no_so, e.item, e.unit, f.name FROM $tabel3 AS a LEFT JOIN $tabel1 AS b ON a.id_fk = b.id_fk AND a.item_to = b.item_to LEFT JOIN $tabel2 AS c ON a.id_fk = c.id_fk AND a.id_sj = c.id_sj LEFT JOIN $tabel4 AS d ON a.id_fk = d.id_fk LEFT JOIN $tabel5 AS e ON a.id_fk = e.id_fk AND a.item_to = e.item_to LEFT JOIN $tabel6 AS f ON f.id = c.input_by WHERE b.order_status BETWEEN 1 AND 2 AND c.sj_date BETWEEN '$dari' AND '$sampai' GROUP BY a.id ORDER BY a.id ASC";
    $sql = $connect->query($query);
    
    if(!$sql){
      $result  = $error;
      $message = $qerror;
    } else {
      $result  = $success;
      $message = $qsuccess;
      $no = 1;
      $array = array();
      while($row = $sql->fetch_array()){

        $functions  = '<div class="function_buttons"><ul>';
        if($_SESSION['role'] == '5'){
          $functions  = '<li>Not Allowed</li>';
          } else {
            $functions .= '<li class="function_print"><a data-id="'.$row['id'].'-'.$row['id_fk'].'-'.$row['id_sj'].'" title="Print"><span>Print</span></a></li>';
            $functions .= '<li class="function_delete customerDel"><a data-id="'.$row['id'].'-'.$row['id_fk'].'-'.$row['item_to'].'-'.$row['id_sj'].'" data-name="'.$row['item'].'"><span>Hapus</span></a></li>';
        }

        $originalDate1 = $row['sj_date'];
        $newDate1 = date("d/m/Y", strtotime($originalDate1));
        $ex_no_so = explode("/", $row['no_so']);

        if($row['send_qty'] > 0)
        {
          $mysqli_data[] = array(
            "no"          => $no++,
            "customer"    => $row['customer'],
            "po_customer" => $row['po_customer'],
            "no_spk"      => $ex_no_so[0]."/".$ex_no_so[1].$ex_no_so[2],
            "no_delivery" => $row['no_delivery'],
            "sj_date"     => $newDate1,
            "shipto"      => $row['shipto'],
            "item"        => $row['item'],
            "send_qty"    => $row['send_qty'],
            "unit"        => $row['unit'],
            "courier"     => $row['courier'],
            "no_tracking" => $row['no_tracking'],
            "ongkir"      => $row['cost'],
            "input_by"    => $row['name'],
            "functions"   => $functions
          );
        }
      }
    }

  } elseif ($action == 'get_print_'.$slug){
    if($id == ''){
      $result  = $error;
      $message = 'ID'.$missing;
    } else {
      $id = mysqli_real_escape_string($connect, $id);
      $id_fk = mysqli_real_escape_string($connect, $_GET['id_fk']);
      $id_sj = mysqli_real_escape_string($connect, $_GET['id_sj']);

      $select1 = "SELECT a.no_delivery, a.send_qty, b.shipto, b.sj_date, c.customer, c.po_customer, e.item, e.unit, e.ingredient, e.size, e.volume FROM $tabel3 AS a LEFT JOIN $tabel2 AS b ON a.id_fk = b.id_fk AND a.id_sj = b.id_sj LEFT JOIN $tabel4 AS c ON a.id_fk = c.id_fk LEFT JOIN $tabel5 AS e ON a.id_fk = e.id_fk AND a.item_to = e.item_to WHERE a.id_fk = '$id_fk' AND a.id_sj = '$id_sj'";
      $sql1 = $connect->query($select1);

      if(!$sql1){
        $result  = $error;
        $message = $qerror;
      } else {
        $result  = $success;
        $message = $qsuccess;
        while($row = $sql1->fetch_array()){
          if($row['send_qty'] > 0)
          {
            $mysqli_data[] = array(
              "customer"    => $row['customer'],
              "sj_date"     => $row['sj_date'],
              "shipto"      => $row['shipto'],
              "no_delivery" => $row['no_delivery'],
              "po_customer" => $row['po_customer'],
              "item"        => strtoupper($row['item']),
              "qty"         => $row['send_qty'],
              "unit"        => $row['unit']
            );
          }
        }
      }
    }

  } elseif ($action == 'print'){
		if($id == ''){
      $result  = $error;
      $message = 'ID'.$missing;
    } else {
      $id = mysqli_real_escape_string($connect, $id);
      $id_fk = mysqli_real_escape_string($connect, $_GET['id_fk']);
      $id_sj = mysqli_real_escape_string($connect, $_GET['id_sj']);

      $select2 = "SELECT a.no_so, a.item, a.unit, a.ingredient, a.size, a.volume, b.send_qty, d.company, d.address, d.logo, d.phone FROM $tabel5 AS a LEFT JOIN $tabel3 AS b ON b.id_fk = a.id_fk AND b.item_to = a.item_to LEFT JOIN preorder_customer AS c ON c.id_fk = '$id_fk' LEFT JOIN company AS d ON d.id = c.id_company WHERE a.id_fk = '$id_fk' AND b.id_sj = '$id_sj' GROUP BY a.id";
      $sql2 = $connect->query($select2);

      $no = 1;
      $sj_date = mysqli_real_escape_string($connect, $_POST['sj_date']);
      $po_customer = mysqli_real_escape_string($connect, $_POST['no_po_pratinjau']);
      $no_delivery = mysqli_real_escape_string($connect, $_POST['no_delivery']);
      $customer = mysqli_real_escape_string($connect, $_POST['custom']);
      $filter_shipto = mysqli_real_escape_string($connect, $_POST['shipto']);
      $shipto = str_replace("\\r\\n"," ", $filter_shipto);
      $ttd = mysqli_real_escape_string($connect, $_POST['ttd']);
      $tgl = date("d F Y", strtotime($sj_date));

      while($row = $sql2->fetch_array()){
        if($row['send_qty'] > 0)
        {
          $mysqli_data[] = array(
            "no"        => $no++,
            "item"      => strtoupper($row['item']),
            "qty"       => $row['send_qty'],
            "unit"      => $row['unit'],
            "sj_date"     => $tgl,
            "po_customer" => $po_customer,
            "no_delivery" => $no_delivery,
            "customer"    => $customer,
            "shipto"    => $shipto,
            "ttd"       => $ttd,
            "no_so"     => $row['no_so'],
            "company"   => strtoupper($row['company']),
            "address"   => $row['address'],
            "phone"     => $row['phone'],
            "logo"      => $row['logo'],
          );
        }
      }

      $logger = logger($connect,'Print DO (done)', 'Customer: '.$custom.' - No delivery: '.$no_delivery.' - Delivery date: '.$sj_date);
      if(!$logger){
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
        $message = 'ID '.$missing;
    } else {
      $id = mysqli_real_escape_string($connect,$id);
      $id_fk = mysqli_real_escape_string($connect,$_GET['id_fk']);
      $item_to = mysqli_real_escape_string($connect,$_GET['item_to']);
      $id_sj = mysqli_real_escape_string($connect,$_GET['id_sj']);

      $select1 = "SELECT id FROM $tabel3 WHERE id_fk = '$id_fk' AND id_sj = '$id_sj'";
      $sql1 = $connect->query($select1);

      $select2 = "SELECT a.no_delivery, b.sj_date, c.customer, d.item FROM $tabel3 AS a LEFT JOIN $tabel2 AS b ON a.id_fk = b.id_fk LEFT JOIN $tabel4 AS c ON a.id_fk = c.id_fk LEFT JOIN $tabel5 AS d ON a.id_fk =d.id_fk AND a.item_to = d.item_to WHERE a.id_fk = '$id_fk' AND a.item_to = '$item_to'";
      $sql2 = $connect->query($select2);
      $fetch = $sql2->fetch_array();

      $select3 = "SELECT id FROM $tabel3 WHERE id_fk = '$id_fk' AND item_to = '$item_to'";
      $cek = $connect->query($select3);

      if($cek->num_rows < 2){
        $update = "UPDATE $tabel1 SET order_status = 3 WHERE id_fk = '$id_fk' AND item_to = '$item_to'";
        $sql4 = $connect->query($update);
      }

      $del1 = "DELETE FROM $tabel3 WHERE id = '".$id."'";
      $sql3 = $connect->query($del1);

      $query = "UPDATE $tabel1 SET order_status = 2 WHERE id_fk = '$id_fk' AND item_to = '$item_to'";
      $change_status = $connect->query($query);

      if($sql1->num_rows < 2){
        $del2 = "DELETE FROM $tabel2 WHERE id_fk = '$id_fk' AND id_sj = '$id_sj'";
        $sql5 = $connect->query($del2);

        $del3 = "DELETE FROM $tabel8 WHERE id_fk = '$id_fk' AND id_sj = '$id_sj'";
        $sql6 = $connect->query($del3);
      } 

      $logger = logger($connect,'Delete DO (procces)', 'Customer: '.$fetch['customer'].' - Delivery date: '.$fetch['sj_date'].' - No delivery: '.$fetch['no_delivery'].' - Item: '.$fetch['item']);

      if(!$sql1 OR !$sql2 OR !$sql3 OR !$change_status OR !$logger){
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