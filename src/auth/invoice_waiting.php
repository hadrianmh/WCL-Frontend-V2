<?php

function rupiah($angka){
  $output = "Rp. ".number_format($angka, 2);
  return $output;
}

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
$tabel5 = 'preorder_item';
$tabel6 = 'preorder_price';
$tabel7 = 'preorder_customer';
$tabel8 = 'invoice';
$tabel9 = "workorder_item";
$Query = 'action';
$slug = 'invoice_waiting';

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
	if ($action == 'invoice' ||
      $action == 'result_'.$slug ||
    $action == 'resultAll_'.$slug || 
    $action == 'create_'.$slug ||
    $action == 'create_custom_'.$slug 
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

$mysqli_data = array();

if($action != ''){
	///////////////////////
	// Execute all action
	//////////////////////

	if ($action == 'result_'.$slug){

    ///////////////////////////
    // Get pre order data
    ///////////////////////////

    $query = "SELECT a.id_fk, a.id_sj, a.no_delivery, a.send_qty, c.price, c.unit, d.no_so, e.customer, e.po_customer, f.sj_date, f.cost, g.ppn FROM delivery_orders_item AS a LEFT JOIN invoice AS b ON b.id_fk = a.id_fk AND b.id_sj = a.id_sj LEFT JOIN preorder_item AS c ON c.id_fk = a.id_fk AND c.item_to = a.item_to LEFT JOIN workorder_item AS d ON d.id_fk = a.id_fk AND d.item_to = a.item_to LEFT JOIN preorder_customer AS e ON e.id_fk = a.id_fk LEFT JOIN delivery_orders_customer AS f ON f.id_fk = a.id_fk AND f.id_sj = a.id_sj LEFT JOIN preorder_price AS g ON g.id_fk = a.id_fk LEFT JOIN status AS h ON h.id_fk = a.id_fk AND h.item_to = a.item_to WHERE h.order_status BETWEEN 1 AND 2 AND b.id IS NULL ORDER BY a.id DESC";
    $sql = $connect->query($query);
    if(!$sql){
      $result  = $error;
      $message = $qerror;
    } else {
      $result  = $success;
    	$message = $qsuccess;
    	$no = 1;

      $data_idx = array();
      $data_cost = array();
      while($row = $sql->fetch_array()){
        $functions  = '<div class="function_buttons"><ul>';
        if($_SESSION['role'] != '5'){
          if(!in_array($row['id_fk'].'-'.$row['id_sj'], $data_idx)){
            $functions .= '<li class="function_process"><a data-id="'.$row['id_fk'].'-'.$row['id_sj'].'" data-name="'.$row['no_delivery'].'" title="Create Invoice"><span>Create Invoice</span></a></li>';
          }
        } else {
          $functions .= '<li>Not allowed</li>';
        }
        $functions .= '</ul></div>';

        if(!in_array($row['id_fk'].'-'.$row['id_sj'].'-'.$row['cost'], $data_cost)){
          $biaya_kirim = $row['cost'];
        } else {
          $biaya_kirim = '';
        }

        $sj_date = date("d-m-Y", strtotime($row['sj_date']));
        $ex_no_so = explode("/", $row['no_so']);
        $tagihan = $row['send_qty'] * $row['price'];
        $tagihanFormatted = number_format($tagihan, 2, '.', ',');
        if($row['ppn'] > 0){
          $ppn = $tagihanFormatted*11/100;
          $total = $tagihanFormatted + $ppn;
        } else {
          $ppn = '0';
          $total = $tagihanFormatted;
        }

        if($row['send_qty'] > 0)
        {
          $mysqli_data[] = array(
            "id"            => $row['id_fk'].'-'.$row['id_sj'],
            "no"            => $no++,
            "sj_date"       => $sj_date,
            "customer"      => $row['customer'],
            "no_po"         => $row['po_customer'],
            "no_so"         => $ex_no_so[0]."/".$ex_no_so[1].$ex_no_so[2],
            "no_sj"         => $row['no_delivery'],
            "send_qty"      => $row['send_qty'],
            "unit"          => $row['unit'],
            "price"         => $row['price'],
            "bill"          => $tagihanFormatted,
            "ppn"           => $ppn,
            "total"         => $total,
            "shipping_costs"=> $biaya_kirim,
            "functions"     => $functions,
          );
          
          $data_idx[] = $row['id_fk'].'-'.$row['id_sj'];
          $data_cost[] = $row['id_fk'].'-'.$row['id_sj'].'-'.$row['cost'];
        }
	    }
	  }

	} elseif($action == 'resultAll_'.$slug){

    ///////////////////////
    // Load data print
    //////////////////////

    $query = "SELECT a.id_fk, a.id_sj, a.no_delivery, a.send_qty, c.price, c.unit, d.no_so, e.customer, e.po_customer, f.sj_date, f.cost, g.ppn FROM delivery_orders_item AS a LEFT JOIN invoice AS b ON b.id_fk = a.id_fk AND b.id_sj = a.id_sj LEFT JOIN preorder_item AS c ON c.id_fk = a.id_fk AND c.item_to = a.item_to LEFT JOIN workorder_item AS d ON d.id_fk = a.id_fk AND d.item_to = a.item_to LEFT JOIN preorder_customer AS e ON e.id_fk = a.id_fk LEFT JOIN delivery_orders_customer AS f ON f.id_fk = a.id_fk AND f.id_sj = a.id_sj LEFT JOIN preorder_price AS g ON g.id_fk = a.id_fk LEFT JOIN status AS h ON h.id_fk = a.id_fk AND h.item_to = a.item_to WHERE h.order_status BETWEEN 1 AND 2 AND b.id IS NULL ORDER BY a.id ASC";
    $sql = $connect->query($query);
      
    if(!$sql){
      $result  = $error;
      $message = $qerror;
    } else {
      $result  = $success;
      $message = $qsuccess;
      $no = 1;

      $data_cost = array();
      while($row = $sql->fetch_array()){
        if(!in_array($row['id_fk'].'-'.$row['id_sj'].'-'.$row['cost'], $data_cost)){
          $biaya_kirim = $row['cost'];
        } else {
          $biaya_kirim = '';
        }
        $data_cost[] = $row['id_fk'].'-'.$row['id_sj'].'-'.$row['cost'];
        $sj_date = date("d-m-Y", strtotime($row['sj_date']));
        $ex_no_so = explode("/", $row['no_so']);
        $tagihan = $row['send_qty'] * $row['price'];
        $tagihanFormatted = number_format($tagihan, 2, '.', ',');
        if($row['ppn'] > 0){
          $ppn = $tagihanFormatted*11/100;
          $total = $tagihanFormatted + $ppn;
        } else {
          $ppn = '0';
          $total = $tagihanFormatted;
        }

        if($row['send_qty'] > 0)
        {
          $mysqli_data[] = array(
            "no"            => $no++,
            "sj_date"       => $sj_date,
            "customer"      => $row['customer'],
            "no_po"         => $row['po_customer'],
            "no_so"         => $ex_no_so[0]."/".$ex_no_so[1].$ex_no_so[2],
            "no_sj"         => $row['no_delivery'],
            "send_qty"      => $row['send_qty'],
            "unit"          => $row['unit'],
            "price"         => $row['price'],
            "bill"          => $tagihanFormatted,
            "ppn"           => $ppn,
            "total"         => $total,
            "shipping_costs"=> $biaya_kirim
          );
        }
      }
    }

  } elseif($action == 'create_'.$slug){

    $id_fk = mysqli_real_escape_string($connect, $_POST['id']);
    $id_sj = mysqli_real_escape_string($connect, $_POST['id_sj']);
    $invoice_date = mysqli_real_escape_string($connect, $_POST['date']);
    $duration = date('Y-m-d', strtotime($invoice_date. '+30 day'));
    $urutInvoice = array();
    $no_invoice = '';
    $cur_time = date('y');

    //mencari data terakhir no invoice dan invoice date
    $query = "SELECT invoice_date, no_invoice FROM $tabel8 ORDER BY id DESC LIMIT 1";
    $sql = $connect->query($query);
    $ambil = $sql->fetch_array();

    $select = "SELECT a.customer, b.no_delivery FROM $tabel7 AS a LEFT JOIN $tabel3 AS b ON a.id_fk = b.id_fk WHERE b.id_fk = '".$id_fk."' AND b.id_sj = '".$id_sj."' GROUP BY b.id_sj";
    $sql1 = $connect->query($select);
    $fetch = $sql1->fetch_array();

    // mengambil no invoice dan invoice date
    $ex_podate = explode('-', $ambil['invoice_date']);
    $ex_invoice = explode('/', $ambil['no_invoice']);

    //memeriksa apakah nilai no invoice, invoice date, atau waktu ssat ini lebih besar dari invoice date
    if(empty($ambil['no_invoice']) OR empty($ambil['invoice_date']) OR $cur_time > date("y", strtotime($ex_podate[0]))){
      $no_invoice = $cur_time."000001";
    } else {
      $substr = substr($ex_invoice[0], 2);
      $index = $substr + 1;
      for($i = $index; $i<=999999; $i++){
        $urutInvoice[] = str_pad($i, 6, "0", STR_PAD_LEFT);
      }
      $no_invoice = $cur_time.$urutInvoice[0];
    }

    $insert = "INSERT INTO $tabel8 (id_fk, id_sj, no_invoice, invoice_date, duration, input_by) VALUES ";
    $insert .= "('".$id_fk."', '".$id_sj."', '".$no_invoice."', '".$invoice_date."', '".$duration."', '".$_SESSION['id']."')";

    $sql2 = $connect->query($insert);
    $logger = logger($connect,'Create Invoice', 'Customer: '.$fetch['customer'].' - Invoice date: '.$invoice_date.' - No invoice: '.$no_invoice.' - Surat jalan: '.$fetch['no_delivery']);
    
    if(!$sql1 OR !$sql2 OR !$logger){
      $result  = $error;
      $message = $qerror;
    } else {
      $result  = $success;
      $message = $qsuccess;
    }

  } elseif($action == 'create_custom_'.$slug){
    //inisialisasi id, tanggal, duration dll
    $id = mysqli_real_escape_string($connect, $_POST['id']);
    $invoice_date = mysqli_real_escape_string($connect, $_POST['date']);
    $duration = date('Y-m-d', strtotime($invoice_date. '+30 day'));
    $cur_time = date('Y-m');
    $urutInvoice = array();
    $no_invoice = '';
    $cur_time = date('y');

    //mencari data terakhir no invoice dan invoice date
    $query = "SELECT invoice_date, no_invoice FROM $tabel8 ORDER BY id DESC LIMIT 1";
    $sql = $connect->query($query);
    $ambil = $sql->fetch_array();

    // mengambil no invoice dan invoice date
    $ex_podate = explode('-', $ambil['invoice_date']);
    $ex_invoice = explode('/', $ambil['no_invoice']);

    //memeriksa apakah nilai no invoice, invoice date, atau waktu ssat ini lebih besar dari invoice date
    if(empty($ambil['no_invoice']) OR empty($ambil['invoice_date']) OR $cur_time > date("y", strtotime($ex_podate[0]))){
      $no_invoice = $cur_time."000001";
    } else {
      $substr = substr($ex_invoice[0], 2);
      $index = $substr + 1;
      for($i = $index; $i<=999999; $i++){
        $urutInvoice[] = str_pad($i, 6, "0", STR_PAD_LEFT);
      }
      $no_invoice = $cur_time.$urutInvoice[0];
    }

    //iterasi $_GET['id']
    $idx = explode(',', $id);
    $array = array();
    foreach ($idx as $val){$ex = explode("-", $val);$array[] = $ex[0];}
    if(count(array_unique($array)) > 1){
      $result  = 'invalid';
      $message = 'customer berbeda.';
    } else {
      foreach(array_unique($idx) as $key => $val){
        $ex_id = explode("-", $val);

        //mengambil nama customer, no delivery untuk keperluan logger
        $select = "SELECT a.customer, b.no_delivery FROM $tabel7 AS a LEFT JOIN $tabel3 AS b ON a.id_fk = b.id_fk WHERE b.id_fk = '".$ex_id[0]."' AND b.id_sj = '".$ex_id[1]."' GROUP BY b.id_sj";
        $sql1 = $connect->query($select);
        $fetch = $sql1->fetch_array();

        //menginput invoice
        $insert = "INSERT INTO $tabel8 (id_fk, id_sj, no_invoice, invoice_date, duration, input_by) VALUES ";
        $insert .= "('".$ex_id[0]."', '".$ex_id[1]."', '".$no_invoice."', '".$invoice_date."', '".$duration."', '".$_SESSION['id']."')";

        //proses dari query
        $sql2 = $connect->query($insert);
        $logger = logger($connect,'Create Invoice', 'Customer: '.$fetch['customer'].' - Invoice date: '.$invoice_date.' - No invoice: '.$no_invoice.' - Surat jalan: '.$fetch['no_delivery']);
        
        if(!$sql1 OR !$sql2 OR !$logger){
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