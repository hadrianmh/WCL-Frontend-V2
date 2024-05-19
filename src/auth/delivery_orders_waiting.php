<?php

/////////////////////////////
// Personal config DataTables 
////////////////////////////
require_once '../dashboard/session.php';
require_once 'connect.php';
require_once 'history.php';
$tabel1 = 'workorder_customer';
$tabel2 = 'workorder_item';
$tabel3 = 'preorder_item';
$tabel4 = 'status';
$tabel5 = 'delivery_orders_customer';
$tabel6 = 'delivery_orders_item';
$tabel7 = 'preorder_customer';
$tabel8 = 'customer';
$Query = 'action';
$slug = 'delivery_orders_waiting';

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
  		$action == 'get_'.$slug ||
  		$action == 'no_sj_'.$slug ||
  		$action == 'add_'.$slug 
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

	    $query = "SELECT a.id, a.spk_date, a.customer, a.po_customer, a.duration, GROUP_CONCAT(CONCAT(SUBSTRING_INDEX(c.no_so,'/',2),SUBSTRING_INDEX(c.no_so,'/',-1)) SEPARATOR ', ') AS no_so FROM $tabel1 AS a LEFT JOIN $tabel4 AS b ON a.id_fk = b.id_fk LEFT JOIN $tabel2 AS c ON c.id_fk = a.id_fk AND c.item_to = b.item_to WHERE b.order_status BETWEEN 2 AND 3 GROUP BY a.id_fk ORDER BY a.id ASC";
	    $sql = $connect->query($query);
	    
	    if(!$sql){
	      $result  = $error;
	      $message = $qerror;
	    } else {
	    	$result  = $success;
	      	$message = $qsuccess;
	      	$no = 1;
		    while($row = $sql->fetch_array()){
		    	$functions  = '<div class="function_buttons"><ul>';
		    	if($_SESSION['role'] == '5'){
		    		$functions  = '<li>Not Allowed</li>';
		        } else {
		        	$functions .= '<li class="function_process"><a data-id="'.$row['id'].'" title="Proses"><span>Proses</span></a></li>';
		        	  
		        }
		        $functions .= '</ul></div>';

		    	$originalDate1 = $row['spk_date'];
		    	$newDate1 = date("d/m/Y", strtotime($originalDate1));
		    	$originalDate2 = $row['duration'];
		    	$newDate2 = date("d/m/Y", strtotime($originalDate2));

		        $mysqli_data[] = array(
		          	"no"    	=> $no++,
		          	"customer"  => $row['customer'],
		          	"spk_date"  => $newDate1,
		          	"duration"  => $newDate2,
		          	"no_spk"    => $row['no_so'],
		          	"po_customer" => $row['po_customer'],
		          	"functions" => $functions
		        );
		    }
		}

  	} elseif($action == 'no_sj_'.$slug ){

  		///////////////////////////
	    // Get no sj
	    ///////////////////////////

  		$select1 = "SELECT id_sj, sj_date FROM $tabel5 ORDER BY id DESC LIMIT 1";
		$query1 = $connect->query($select1);
		$fetch1 = $query1->fetch_array();
		$cur_time = date('y');
		$ex_fetch = explode('-', $fetch1['sj_date']);
		$urutSJ = array();

		if(!$query1){
			$result  = $error;
	     	$message = $qerror;
		} else {
			$result  = $success;
	      	$message = $qsuccess;
	      	if(empty($fetch1['id_sj']) OR $cur_time > date("y", strtotime($ex_fetch[0]))){
	      		$mysqli_data = $cur_time."000001";
	      	} else {	
	      		$select2 = "SELECT no_delivery FROM $tabel6 ORDER BY id DESC LIMIT 1";
				$query2 = $connect->query($select2);
				$fetch2 = $query2->fetch_array();
				$ex_nosj = explode('/', $fetch2['no_delivery']);
				
				$substr = substr($ex_nosj[0], 2);
				$index = $substr + 1;
				for($i = $index; $i<=999999; $i++){
					$urutSJ[] = str_pad($i, 6, "0", STR_PAD_LEFT);
				}

	      		$mysqli_data = $cur_time.$urutSJ[0];
	      	}
		}

  	} elseif ($action == 'get_'.$slug){
  		if ($id == ''){
  			$result  = $error;
  			$message = 'ID '.$missing;
  		} else {
  			$result  = $success;
		  	$message = $qsuccess;
		  	$id = mysqli_real_escape_string($connect, $id);

		  	$query1 = "SELECT a.*, b.no_so, b.item, b.unit, b.qty, c.item_to, c.order_status, d.total_send_qty, e.shipto, g.alamat, g.kota, g.provinsi, g.negara, g.kodepos, g.s_alamat, g.s_kota, g.s_provinsi, g.s_negara, g.s_kodepos FROM $tabel1 AS a LEFT JOIN $tabel2 AS b ON a.id_fk = b.id_fk LEFT JOIN $tabel4 AS c ON a.id_fk = c.id_fk AND b.item_to = c.item_to LEFT JOIN (SELECT y.id_fk, y.item_to, sum(y.send_qty) AS total_send_qty FROM $tabel1 AS x LEFT JOIN $tabel6 AS y ON x.id_fk = y.id_fk WHERE x.id = '".$id."' GROUP BY y.item_to) AS d ON a.id_fk = d.id_fk AND b.item_to = d.item_to LEFT JOIN (SELECT id_fk, shipto FROM $tabel5 ORDER BY id DESC LIMIT 1) AS e ON a.id_fk = e.id_fk LEFT JOIN $tabel7 AS f ON f.id_fk = a.id_fk LEFT JOIN $tabel8 AS g ON g.id = f.id_customer WHERE a.id = '".$id."' AND c.order_status BETWEEN 2 AND 3";
		  	$sql1 = $connect->query($query1);

		  	while($row = $sql1->fetch_array()){
		  		if(empty($row['shipto']))
		  		{
		  			if(empty($row['s_alamat']))	{ $s_alamat = $row['alamat'].". "; } else { $s_alamat = $row['s_alamat'].". "; }
		  			if(empty($row['s_kota'])) 	{ $s_kota = $row['kota']." - "; } else { $s_kota = $row['s_kota']." - "; }
		  			if(empty($row['s_provinsi'])){ $s_provinsi = $row['provinsi'].", "; } else { $s_provinsi = $row['s_provinsi'].", "; }
		  			if(empty($row['s_negara']))	{ $s_negara = $row['negara'].". "; } else { $s_negara = $row['s_negara'].". "; }
		  			if(empty($row['s_kodepos'])){ $s_kodepos = $row['kodepos']; } else { $s_kodepos = $row['s_kodepos']; }
		  			$shipto = $s_alamat.$s_kota.$s_provinsi.$s_negara.$s_kodepos;
		  			
		  		} else {
		  			$shipto = $row['shipto'];
		  		}

		  		if(!empty($row['total_send_qty'])){
		  			if($row['total_send_qty'] > $row['qty']){
		  				$total_send_qty = '0';
		  			} else {
		  				$total_send_qty = $row['qty'] - $row['total_send_qty'];
		  			}
		  		} else {
		  			$total_send_qty = $row['qty'];
		  		}

		  		$ex_no_so = explode("/", $row['no_so']);

		  		$mysqli_data[] = array(
		  			'spk_date'		=> $row['spk_date'],
		  			'customer'		=> $row['customer'],
		  			'po_customer'	=> $row['po_customer'],
		  			'no_so'			=> $ex_no_so[0]."/".$ex_no_so[1].$ex_no_so[2],
		  			'item'			=> $row['item'],
		  			'unit'			=> $row['unit'],
		  			'req_qty'		=> $total_send_qty,
		  			'item_to'		=> $row['item_to'],
		  			'shipto'		=> $shipto,
		  		);
		  	}
		}

  	} elseif ($action == 'add_'.$slug){
  		if ($id == ''){
  			$result  = $error;
  			$message = 'ID '.$missing;
  		} else {

      		//inisialisasi data yang dikirim submit form
      		$id = mysqli_real_escape_string($connect, $id);
      		$customer = mysqli_real_escape_string($connect, $_GET['customer']);
      		$tgl_sj = mysqli_real_escape_string($connect, $_GET['tanggal']);
      		$no_sj = mysqli_real_escape_string($connect, $_GET['no_sj']);
      		$shipto = mysqli_real_escape_string($connect, $_GET['shipto']);
      		$kurir = mysqli_real_escape_string($connect, $_GET['nama_kurir']);
      		$no_resi = mysqli_real_escape_string($connect, $_GET['no_resi']);
      		$data = $_GET['data'];

      		//inisialisasi array
      		$data_customer = array();
      		$data_item = array();

      		//mengambil id_fk berdasarkan id
      		$select1 = "SELECT id_fk FROM $tabel1 WHERE id = '".$id."'";
      		$query1 = $connect->query($select1);
      		$fetch1 = $query1->fetch_array();

      		//memeriksa id_sj berdasarkan id_fk
      		$select2 = "SELECT id_sj FROM $tabel5 WHERE id_fk = '".$fetch1['id_fk']."' ORDER BY id DESC LIMIT 1";
      		$query2 = $connect->query($select2);
      		$fetch2 = $query2->fetch_array();

      		//menginput data ke tabel DO_customer
      		$insert1 = "INSERT INTO $tabel5 (id_fk, id_sj, sj_date, shipto, courier, no_tracking, input_by) VALUES ";
      		//membuat kondisi jika id_sj berdasarkan id_fk ditemukan maka value fetch + 1, sedangkan 0 maka akan diberi value 1
      		if($fetch2['id_sj'] > 0){
      			$id_sj = $fetch2['id_sj'] + 1;
      			$data_customer[] = "('".$fetch1['id_fk']."', '".$id_sj."', '".$tgl_sj."', '".$shipto."', '".$kurir."', '".$no_resi."', '".$_SESSION['id']."')";
      		} else {
      			$data_customer[] = "('".$fetch1['id_fk']."', '1', '".$tgl_sj."', '".$shipto."', '".$kurir."', '".$no_resi."', '".$_SESSION['id']."')";
      		}
      		$insert1 .= implode(',', $data_customer);

      		//menginput data ke tabel DO_item
      		$insert2 = "INSERT INTO $tabel6 (id_fk, id_sj, item_to, no_delivery, send_qty) VALUES ";
      		foreach($data['item_to'] as $key => $val){
      			if(empty($data['item_to'][$key]))	{$item_to = '';} else {$item_to = $data['item_to'][$key];}
      			if(empty($data['qty'][$key]))		{$qty = '0';} else {$qty = $data['qty'][$key];}
      			if($fetch2['id_sj'] > 0){
	      			$id_sj = $fetch2['id_sj'] + 1;
	      			$data_item[] = "('".$fetch1['id_fk']."', '".$id_sj."', '".$item_to."', '".$no_sj."', '".$qty."')";
	      		} else {
	      			$data_item[] = "('".$fetch1['id_fk']."', '1', '".$item_to."', '".$no_sj."', '".$qty."')";
	      		}

	      		//Menjumlah total tiap item yg dikirim berdasarkan id_fk
	      		$select3 = "SELECT a.qty AS req_qty, sum(b.send_qty) AS send_qty FROM preorder_item AS a LEFT JOIN delivery_orders_item AS b ON a.id_fk = b.id_fk AND a.item_to = b.item_to WHERE a.id_fk = '".$fetch1['id_fk']."' AND a.item_to = '".$item_to."'";
	      		$query3 = $connect->query($select3);
	      		$fetch3 = $query3->fetch_array();

	      		//Mengubah jika send qty sudah dikirim seadanya, mencukupi atau lebih dari req qty
	      		$total = $fetch3['send_qty'] + $qty;
	      		if($total >= $fetch3['req_qty']){
	      			$update = "UPDATE $tabel4 SET order_status = '1' WHERE id_fk = '".$fetch1['id_fk']."' AND item_to = '".$item_to."'";
	      			$query4 = $connect->query($update);
	      		} else {
	      			$update = "UPDATE $tabel4 SET order_status = '2' WHERE id_fk = '".$fetch1['id_fk']."' AND item_to = '".$item_to."'";
	      			$query4 = $connect->query($update);
	      		}
      		}
      		$insert2 .= implode(',', $data_item);

      		//eksekusi setiap query
      		$sql1 = $connect->query($insert1);
      		$sql2 = $connect->query($insert2);
      		$logger = logger($connect,'Create DO (waiting list)', 'Customer: '.$customer.' - Do date: '.$tgl_sj.' - No delivery: '.$no_sj);

      		//membuat kondisi untuk message query error atau success
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