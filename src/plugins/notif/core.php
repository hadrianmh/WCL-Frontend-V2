<?php

session_start();

/////////////////////////////
// Personal config DataTables 
////////////////////////////
require_once '../../auth/connect.php';
$tabel1 = 'workorder_customer';
$tabel2 = 'delivery_orders_customer';
$tabel3 = 'invoice';
$tabel4 = 'status';

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
if (isset($_GET['action'])){
	$action = $_GET['action'];
  	if ($action == 'check'){
  		$action = 'check';
  	} else {
    	$action = '';
  	}
}

$mysqli_data = array();

if($action != ''){
	///////////////////////
	// Execute all action
	//////////////////////

	if($action == 'check'){
		$result  = $success;
	    $message = $qsuccess;

		$select1 = "SELECT count(id_fk) as jml1 FROM $tabel4 WHERE order_status = 0";
		$select2 = "SELECT a.id_fk FROM $tabel2 AS a LEFT JOIN $tabel4 AS b ON a.id_fk = b.id_fk LEFT JOIN $tabel3 AS c ON a.id_fk = c.id_fk WHERE b.order_status BETWEEN 1 AND 2 AND c.id_fk IS NULL GROUP BY a.id_fk";
		$select3 = "SELECT count(a.id) AS jml3 FROM $tabel1 AS a LEFT JOIN $tabel4 AS b ON a.id_fk = b.id_fk WHERE a.duration >= '".date('Y-m-d')."' AND b.order_status BETWEEN 3 AND 2";
		$select4 = "SELECT count(a.id) AS jml4 FROM $tabel1 AS a LEFT JOIN $tabel4 AS b ON a.id_fk = b.id_fk WHERE a.duration < '".date('Y-m-d')."' AND b.order_status BETWEEN 3 AND 2";
		$select5 = "SELECT a.id_fk FROM $tabel2 AS a LEFT JOIN $tabel4 AS b ON a.id_fk = b.id_fk LEFT JOIN $tabel3 AS c ON a.id_fk = c.id_fk WHERE b.order_status BETWEEN 1 AND 2 AND c.id_fk IS NOT NULL AND c.duration < '".date('Y-m-d')."' GROUP BY a.id_fk";

		$sql1 = $connect->query($select1);
		$sql2 = $connect->query($select2);
		$sql3 = $connect->query($select3);
		$sql4 = $connect->query($select4);
		$sql5 = $connect->query($select5);
		
		$fetch1 = $sql1->fetch_array();
		$fetch3 = $sql3->fetch_array();
		$fetch4 = $sql4->fetch_array();


		if($_SESSION['role'] == 1){
			$count1 = $fetch1['jml1'] + $sql2->num_rows + $fetch3['jml3'] + $fetch4['jml4'] + $sql5->num_rows;
			if($sql2->num_rows > 0){
				$new_invoice = "<li class='looping-notif'><a href='index.php?page=invoice_waiting'><i class='fa fa-sticky-note-o text-yellow'></i> ".$sql2->num_rows." Faktur baru</a></li>";
			}

			if($sql5->num_rows > 0){
				$jatuhtempo = "<li class='looping-notif'><a href='index.php?page=invoice_duedate'><i class='fa fa-sticky-note-o text-yellow'></i> ".$sql5->num_rows." Faktur jatuh tempo</a></li>";
			}
			if($fetch1['jml1'] > 0){
				$wo_pending = "<li class='looping-notif'><a href='index.php?page=workorder'><i class='fa fa-send-o text-aqua'></i> ".$fetch1['jml1']." SPK baru</a></li>";
			}
			if($fetch4['jml4'] > 0){
				$ngaret = "<li class='looping-notif'><a href='index.php?page=delivery_orders_waiting'><i class='fa fa-clock-o text-red'></i> ".$fetch4['jml4']." SPK masuk tenggat waktu</a></li>";
			}
			if($fetch3['jml3'] > 0){
				$sj_pending = "<li class='looping-notif'><a href='index.php?page=delivery_orders_waiting'><i class='fa fa-truck text-green'></i> ".$fetch3['jml3']." Surat jalan belum diproses</a></li>";
			}

			$mysqli_data[] = array(
				"count" => $count1,
				"item" => array($new_invoice, $jatuhtempo, $wo_pending, $ngaret, $sj_pending)
			);

		} elseif($_SESSION['role'] == 2 AND $_SESSION['account'] == 1){
			$count1 = $fetch1['jml1'] + $fetch3['jml3'] + $fetch4['jml4'];
			if($fetch1['jml1'] > 0){
				$wo_pending = "<li class='looping-notif'><a href='index.php?page=workorder'><i class='fa fa-send-o text-aqua'></i> ".$fetch1['jml1']." SPK baru</a></li>";
			}
			if($fetch4['jml4'] > 0){
				$ngaret = "<li class='looping-notif'><a href='index.php?page=delivery_orders_waiting'><i class='fa fa-clock-o text-red'></i> ".$fetch4['jml4']." SPK masuk tenggat waktu</a></li>";
			}
			if($fetch3['jml3'] > 0){
				$sj_pending = "<li class='looping-notif'><a href='index.php?page=delivery_orders_waiting'><i class='fa fa-truck text-green'></i> ".$fetch3['jml3']." Surat jalan belum diproses</a></li>";
			}
			$mysqli_data[] = array(
				"count" => $count1,
				"item" => array($wo_pending,$ngaret,$sj_pending)
			);

		} elseif($_SESSION['role'] == 4 AND $_SESSION['account'] == 1){
			$count1 = $sql2->num_rows + $sql5->num_rows;
			if($sql2->num_rows > 0){
				$new_invoice = "<li class='looping-notif'><a href='index.php?page=invoice_waiting'><i class='fa fa-sticky-note-o text-yellow'></i> ".$sql2->num_rows." Faktur baru</a></li>";
			}

			if($sql5->num_rows > 0){
				$jatuhtempo = "<li class='looping-notif'><a href='index.php?page=invoice_duedate'><i class='fa fa-sticky-note-o text-yellow'></i> ".$sql5->num_rows." Faktur jatuh tempo</a></li>";
			}
			$mysqli_data[] = array(
				"count" => $count1,
				"item" => array($new_invoice,$jatuhtempo)
			);

		} elseif($_SESSION['role'] == 5 AND $_SESSION['account'] == 1){
			$count1 = $fetch1['jml1'] + $sql2->num_rows + $fetch3['jml3'] + $fetch4['jml4'] + $sql5->num_rows;
			if($sql2->num_rows > 0){
				$new_invoice = "<li class='looping-notif'><a href='index.php?page=invoice_waiting'><i class='fa fa-sticky-note-o text-yellow'></i> ".$sql2->num_rows." Faktur baru</a></li>";
			}

			if($sql5->num_rows > 0){
				$jatuhtempo = "<li class='looping-notif'><a href='index.php?page=invoice_duedate'><i class='fa fa-sticky-note-o text-yellow'></i> ".$sql5->num_rows." Faktur jatuh tempo</a></li>";
			}
			if($fetch1['jml1'] > 0){
				$wo_pending = "<li class='looping-notif'><a href='index.php?page=workorder'><i class='fa fa-send-o text-aqua'></i> ".$fetch1['jml1']." SPK baru</a></li>";
			}
			if($fetch4['jml4'] > 0){
				$ngaret = "<li class='looping-notif'><a href='index.php?page=delivery_orders_waiting'><i class='fa fa-clock-o text-red'></i> ".$fetch4['jml4']." SPK masuk tenggat waktu</a></li>";
			}
			if($fetch3['jml3'] > 0){
				$sj_pending = "<li class='looping-notif'><a href='index.php?page=delivery_orders_waiting'><i class='fa fa-truck text-green'></i> ".$fetch3['jml3']." Surat jalan belum diproses</a></li>";
			}
			$mysqli_data[] = array(
				"count" => $count1,
				"item" => array($new_invoice, $jatuhtempo, $wo_pending, $ngaret, $sj_pending)
			);
		}

	} else {
		$result  = $error;
	    $message = $qerror;
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