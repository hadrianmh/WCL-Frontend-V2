<?php

/////////////////////////////
// Personal config DataTables 
////////////////////////////
require '../dashboard/session.php';
require 'connect.php';
require 'history.php';
$tabel1 = 'preorder_customer';
$tabel2 = 'preorder_item';
$tabel3 = 'preorder_price';
$tabel4 = 'workorder_customer';
$tabel5 = 'workorder_item';
$tabel6 = 'delivery_orders_customer';
$tabel7 = 'delivery_orders_item';
$tabel8 = 'delivery_orders_status';
$tabel9 = 'invoice';
$tabel10 = 'status';
$tabel11 = 'customer';
$Query = 'action';
$slug = 'preorder';
$dataName = 'customer';

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
  		$action == 'resultAll_'.$slug ||
  		$action == 'sortdata_'.$slug ||
  		$action == 'add_'.$slug ||
  		$action == 'get_customer_'.$slug ||
  		$action == 'edit_customer_'.$slug ||
  		$action == 'get_item_'.$slug ||
  		$action == 'edit_item_'.$slug ||
  		$action == 'del_'.$slug ||
  		$action == 'detail' ||
  		$action == 'ongkir_get' || 
  		$action == 'ongkir_add' ||
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
		$query = "SELECT DISTINCT po_date FROM $tabel1 ORDER BY po_date DESC";
		$sql = $connect->query($query);

		if(!$sql){
			$result  = $error;
			$message = $qerror;
		} else {
			$result  = $success;
			$message = $qsuccess;

			$montly_list = '';

			while($row = $sql->fetch_array()){
				$montly = date("Y/m", strtotime($row['po_date']));
				if(!isset($montly_list[$montly])){
					$montly_list[$montly] = 1;
					$mysqli_data[] = array(
						'montly' => $montly
					);
				}
			}
		}

	} elseif ($action == 'result_'.$slug){

	  	///////////////////////
		// Load data
		//////////////////////

		$curMonth = str_replace('/', '-', $_GET['curMonth']);
	    
	    $query = "SELECT a.*, b.*, c.no_so, c.qore, c.lin, c.roll, c.ingredient, c.volume, c.annotation, c.porporasi, c.uk_bahan_baku, c.qty_bahan_baku, c.sources, c.merk, c.type, d.*, e.order_status, b.id AS id_customer, a.id AS id_item,  (a.price * a.qty) AS temp_ETD, f.isi, g.total_ongkir, g.id_sj, h.company FROM $tabel2 AS a LEFT JOIN $tabel1 AS b ON a.id_fk = b.id_fk LEFT JOIN $tabel5 AS c ON c.id_fk = a.id_fk AND c.item_to = a.item_to LEFT JOIN $tabel3 AS d ON a.id_fk = d.id_fk LEFT JOIN $tabel10 AS e ON a.id_fk = e.id_fk AND a.item_to = e.item_to LEFT JOIN setting AS f ON a.detail = f.id LEFT JOIN (SELECT id_fk, id_sj, SUM(cost) AS total_ongkir FROM delivery_orders_customer GROUP BY id_fk) AS g ON g.id_fk = b.id_fk LEFT JOIN company AS h ON h.id = b.id_company WHERE b.po_date LIKE '$curMonth%' ORDER BY a.id DESC";
	    $sql = $connect->query($query);
	    
	    if (!$sql){
	      $result  = $error;
	      $message = $qerror;
	    } else {
	      	$no = 1;
	      	$array = array();
	      	$arrays = array();
		    while($row = $sql->fetch_array()){
		    	$select = "SELECT name FROM user WHERE id = '".$row['input_by']."'";
		    	$result = $connect->query($select);
		        $fetch = $result->fetch_array();
		    
		    	$originalDate = $row['po_date'];
		    	$newDate = date("d/m/Y", strtotime($originalDate));
		    	$temp_TOTAL = array();
		    	$temp_PPN = array();
		    	$temp_invoice = array();
		    	$order_grade = array();

		    	$ex_no_so = explode("/", $row['no_so']);
			    $ex_sources = explode("|", $row['sources']);
		    	if($ex_sources[0] == 1){
		    		$sources = 'Internal';
		    	} elseif($ex_sources[0] == 2){
		    		$sources = 'SUBCONT ('.$ex_sources[1].', '.date("d/m/Y", strtotime($ex_sources[2])).')';
		    	} elseif($ex_sources[0] == 3){
		    		$sources = 'IN STOCK ('.$ex_sources[1].' '.$row['unit'].')';
		    	}

		    	if($row['order_grade'] > 0){
		    		$order_grade = "Spesial";
		    	} else {
		    		$order_grade = "Reguler";
		    	}
			    
		    	if($row['porporasi'] == 1){
		    		$porporasi = "YA";
		    	} else {
		    		$porporasi = "TIDAK";
		    	}
		    	
		    	if($row['ppn'] > 0){
		    		$temp_PPN = $row['temp_ETD']*11/100;
		    		$temp_TOTAL = $row['temp_ETD'] + $temp_PPN;
		    	} else {
		    		$temp_PPN = '0';
		    		$temp_TOTAL = $row['temp_ETD'];
		    	}
		    	
		     	$functions = "<div class='function_buttons'><ul>";
		     	if($_SESSION['id'] == $row['input_by']){
		     		$functions .= "<li class='function_edit-customer UbahCustomer'><a data-id='".$row['id_customer']."' title='Ubah Customer'><span>Ubah Customer</span></a></li>";
		     		$functions .= "<li class='function_edit-item UbahItem'><a data-id='".$row['id_item']."' title='Ubah Item Preorder'><span>Ubah Item Preorder</span></a></li>";
		     		$functions .= "<li class='function_delete HapusItem'><a data-id='".$row['id_item']."' data-name='".$row['item']."' title='Hapus Item'><span>Hapus</span></a></li>";
		     		if(!in_array($row['id_fk'], $arrays))
		     		{
		     			$functions .= "<li class='function_ongkir ongkirs'><a data-id='".$row['id_fk']."' title='Ongkir'><span>Ongkir</span></a></li>";
		     		}

			    } else {
			    	$functions .= "Not allowed";
			    }
			    $functions .= "</ul></div>";

			    $estimasi = date('d/m/Y', strtotime($originalDate. '+16 day'));

			    if(in_array($row['id_fk']."_".$row['id_sj']."_".$row['total_ongkir'], $array))
			    {
			    	$total_ongkir = '';

			    } else {
			    	$total_ongkir = rupiah($row['total_ongkir']);
			    }

		        $mysqli_data[] = array(
		          	"no"    		=> $no++,
		          	"po_date"     	=> $newDate,
		          	"customer"    	=> $row['customer'],
		          	"estimasi"     	=> $estimasi,
		          	"company"     	=> $row['company'],
		          	"order_grade"   => $order_grade,
		          	"po_customer" 	=> $row['po_customer'],
		          	"no_so"  		=> $ex_no_so[0]."/".$ex_no_so[1].$ex_no_so[2],
		          	"item"  		=> $row['item'],
		          	"detail"  		=> $row['isi'],
		          	"size"  		=> $row['size'],
		          	"merk"  		=> $row['merk'],
		          	"type"  		=> $row['type'],
		          	"uk_bahan_baku" => $row['uk_bahan_baku'],
		          	"qore"  		=> $row['qore'],
		          	"lin" 	 		=> $row['lin'],
		          	"qty_bahan_baku"=> $row['qty_bahan_baku'],
		          	"roll"  		=> $row['roll'],
		          	"ingredient"  	=> $row['ingredient'],
		          	"porporasi"  	=> $porporasi,
		          	"volume"  		=> $row['volume'],
		          	"qty"  			=> $row['qty'],
		          	"unit"  		=> $row['unit'],
		          	"price"  		=> rupiah($row['price']),
		          	"etd"  			=> rupiah($row['temp_ETD']),
		          	"ppn"  			=> rupiah($temp_PPN),
		          	"total"  		=> rupiah($temp_TOTAL),
		          	"annotation"  	=> $row['annotation'],
		          	"sources"  		=> $sources,
		          	"ongkir"  		=> $total_ongkir,
		          	"input"  		=> $fetch['name'],
		          	"functions"     => $functions
		        );

		        $result  = $success;
	      		$message = $qsuccess;
	      		$array[] = $row['id_fk']."_".$row['id_sj']."_".$row['total_ongkir'];
	      		$arrays[] = $row['id_fk'];
		    }

		    //print_r($arrays);
		}

  	} elseif($action == 'resultAll_'.$slug){

  		///////////////////////
		// Load data print
		//////////////////////

		$curMonth = str_replace('/', '-', $_GET['curMonth']);
	    
	    $query = "SELECT a.*, b.*, c.no_so, c.qore, c.lin, c.roll, c.ingredient, c.volume, c.annotation, c.porporasi, c.uk_bahan_baku, c.qty_bahan_baku, c.sources, c.merk, c.type, d.*, e.order_status, b.id AS id_customer, a.id AS id_item,  (a.price * a.qty) AS temp_ETD, f.isi, g.total_ongkir, g.id_sj, h.company FROM $tabel2 AS a LEFT JOIN $tabel1 AS b ON a.id_fk = b.id_fk LEFT JOIN $tabel5 AS c ON c.id_fk = a.id_fk AND c.item_to = a.item_to LEFT JOIN $tabel3 AS d ON a.id_fk = d.id_fk LEFT JOIN $tabel10 AS e ON a.id_fk = e.id_fk AND a.item_to = e.item_to LEFT JOIN setting AS f ON a.detail = f.id LEFT JOIN (SELECT id_fk, id_sj, SUM(cost) AS total_ongkir FROM delivery_orders_customer GROUP BY id_fk) AS g ON g.id_fk = b.id_fk LEFT JOIN company AS h ON h.id = b.id_company WHERE b.po_date LIKE '$curMonth%' ORDER BY a.id ASC";
	    $sql = $connect->query($query);
	    
	    if(!$sql){
	      $result  = $error;
	      $message = $qerror;
	    } else {
	      	$no = 1;
		    while($row = $sql->fetch_array()){
		    	$select = "SELECT name FROM user WHERE id = '".$row['input_by']."'";
		    	$result = $connect->query($select);
		        $fetch = $result->fetch_array();
		    	$originalDate = $row['po_date'];
		    	$newDate = date("d/m/Y", strtotime($originalDate));

		    	$temp_TOTAL = array();
		    	$temp_PPN = array();
		    	$order_grade = array();

		    	if($row['order_grade'] > 0){
		    		$order_grade = "Spesial";
		    	} else {
		    		$order_grade = "Reguler";
		    	}

		    	if($row['porporasi'] == 1){
		    		$porporasi = "YA";
		    	} else {
		    		$porporasi = "TIDAK";
		    	}

		    	if($row['ppn'] > 0){
		    		$temp_PPN = $row['temp_ETD']*11/100;
		    		$temp_TOTAL = $row['temp_ETD'] + $temp_PPN;
		    	} else {
		    		$temp_PPN = '0';
		    		$temp_TOTAL = $row['temp_ETD'];
		    	}

		    	$ex_no_so = explode("/", $row['no_so']);
		    	$ex_sources = explode("|", $row['sources']);
		    	if($ex_sources[0] == '1'){
		    		$sources = 'Internal';
		    	} elseif($ex_sources[0] == '2'){
		    		$sources = 'SUBCONT ('.$ex_sources[1].', '.date("d/m/Y", strtotime($ex_sources[2])).')';
		    	} elseif($ex_sources[0] == '3'){
		    		$sources = 'IN STOCK ('.$ex_sources[1].' '.$row['unit'].')';
		    	}

		    	$estimasi = date('d/m/Y', strtotime($originalDate. '+16 day'));

		    	if(in_array($row['id_fk']."_".$row['id_sj']."_".$row['total_ongkir'], $array))
			    {
			    	$total_ongkir = '';

			    } else {
			    	$total_ongkir = rupiah($row['total_ongkir']);
			    }

		        $mysqli_data[] = array(
		          	"no"    		=> $no++,
		          	"po_date"     	=> $newDate,
		          	"customer"    	=> $row['customer'],
		          	"estimasi"     	=> $estimasi,
		          	"company"    	=> $row['company'],
		          	"order_grade"   => $order_grade,
		          	"po_customer" 	=> $row['po_customer'],
		          	"no_so"  		=> $ex_no_so[0]."/".$ex_no_so[1].$ex_no_so[2],
		          	"item"  		=> $row['item'],
		          	"detail"  		=> $row['isi'],
		          	"size"  		=> $row['size'],
		          	"merk"  		=> $row['merk'],
		          	"type"  		=> $row['type'],
		          	"uk_bahan_baku" => $row['uk_bahan_baku'],
		          	"qore"  		=> $row['qore'],
		          	"lin"  			=> $row['lin'],
		          	"qty_bahan_baku"=> $row['qty_bahan_baku'],
		          	"roll"  		=> $row['roll'],
		          	"ingredient"  	=> $row['ingredient'],
		          	"porporasi"  	=> $porporasi,
		          	"volume"  		=> $row['volume'],
		          	"qty"  			=> $row['qty'],
		          	"unit"  		=> $row['unit'],
		          	"price"  		=> $row['price'],
		          	"etd"  			=> $row['temp_ETD'],
		          	"ppn"  			=> $temp_PPN,
		          	"total"  		=> $temp_TOTAL,
		          	"annotation"  	=> $row['annotation'],
		          	"sources"  		=> $sources,
		          	"ongkir"  		=> $total_ongkir,
		          	"input"  		=> $fetch['name'],
		        );

		        $result  = $success;
	      		$message = $qsuccess;
	      		$array[] = $row['id_fk']."_".$row['id_sj']."_".$row['total_ongkir'];
		    }
		}

  	} elseif ($action == 'periode_'.$slug){

	  	///////////////////////
		// Load data
		//////////////////////

		$dari = mysqli_real_escape_string($connect, $_GET['dari']);
		$sampai = mysqli_real_escape_string($connect, $_GET['sampai']);
	    
	    $query = "SELECT a.*, b.*, c.no_so, c.qore, c.lin, c.roll, c.ingredient, c.volume, c.annotation, c.porporasi, c.uk_bahan_baku, c.qty_bahan_baku, c.sources, c.merk, c.type, d.*, e.order_status, b.id AS id_customer, a.id AS id_item,  (a.price * a.qty) AS temp_ETD, f.isi, g.total_ongkir, g.id_sj, h.company FROM $tabel2 AS a LEFT JOIN $tabel1 AS b ON a.id_fk = b.id_fk LEFT JOIN $tabel5 AS c ON c.id_fk = a.id_fk AND c.item_to = a.item_to LEFT JOIN $tabel3 AS d ON a.id_fk = d.id_fk LEFT JOIN $tabel10 AS e ON a.id_fk = e.id_fk AND a.item_to = e.item_to LEFT JOIN setting AS f ON a.detail = f.id LEFT JOIN (SELECT id_fk, id_sj, SUM(cost) AS total_ongkir FROM delivery_orders_customer GROUP BY id_fk) AS g ON g.id_fk = b.id_fk LEFT JOIN company AS h ON h.id = b.id_company WHERE b.po_date BETWEEN '$dari' AND '$sampai' ORDER BY a.id ASC";
	    $sql = $connect->query($query);
	    
	    if(!$sql){
	      $result  = $error;
	      $message = $qerror;
	    } else {
	      	$no = 1;
	      	$array = array();
	      	$arrays = array();
		    while($row = $sql->fetch_array()){
		    	$select = "SELECT name FROM user WHERE id = '".$row['input_by']."'";
		    	$result = $connect->query($select);
		        $fetch = $result->fetch_array();
		    
		    	$originalDate = $row['po_date'];
		    	$newDate = date("d/m/Y", strtotime($originalDate));
		    	$temp_TOTAL = array();
		    	$temp_PPN = array();
		    	$temp_invoice = array();
		    	$order_grade = array();

		    	$ex_no_so = explode("/", $row['no_so']);
			    $ex_sources = explode("|", $row['sources']);
		    	if($ex_sources[0] == 1){
		    		$sources = 'Internal';
		    	} elseif($ex_sources[0] == 2){
		    		$sources = 'SUBCONT ('.$ex_sources[1].', '.date("d/m/Y", strtotime($ex_sources[2])).')';
		    	} elseif($ex_sources[0] == 3){
		    		$sources = 'IN STOCK ('.$ex_sources[1].' '.$row['unit'].')';
		    	}

		    	if($row['order_grade'] > 0){
		    		$order_grade = "Spesial";
		    	} else {
		    		$order_grade = "Reguler";
		    	}
			    
		    	if($row['porporasi'] == 1){
		    		$porporasi = "YA";
		    	} else {
		    		$porporasi = "TIDAK";
		    	}
		    	
		    	if($row['ppn'] > 0){
		    		$temp_PPN = $row['temp_ETD']*11/100;
		    		$temp_TOTAL = $row['temp_ETD'] + $temp_PPN;
		    	} else {
		    		$temp_PPN = '0';
		    		$temp_TOTAL = $row['temp_ETD'];
		    	}
		    	
		     	$functions = "<div class='function_buttons'><ul>";
		     	if($_SESSION['id'] == $row['input_by']){
		     		$functions .= "<li class='function_edit-customer UbahCustomer'><a data-id='".$row['id_customer']."' title='Ubah Customer'><span>Ubah Customer</span></a></li>";
		     		$functions .= "<li class='function_edit-item UbahItem'><a data-id='".$row['id_item']."' title='Ubah Item'><span>Ubah Item</span></a></li>";
		     		$functions .= "<li class='function_delete HapusItem'><a data-id='".$row['id_item']."' data-name='".$row['item']."' title='Hapus Item'><span>Hapus</span></a></li>";
		     		if(!in_array($row['id_fk'], $arrays))
		     		{
		     			$functions .= "<li class='function_ongkir ongkirs'><a data-id='".$row['id_fk']."' title='Ongkir'><span>Ongkir</span></a></li>";
		     		}

			    } else {
			    	$functions .= "Not allowed";
			    }
			    $functions .= "</ul></div>";

			    $estimasi = date('d/m/Y', strtotime($originalDate. '+16 day'));

			    if(in_array($row['id_fk']."_".$row['id_sj']."_".$row['total_ongkir'], $array))
			    {
			    	$total_ongkir = '';

			    } else {
			    	$total_ongkir = rupiah($row['total_ongkir']);
			    }

		        $mysqli_data[] = array(
		          	"no"    		=> $no++,
		          	"po_date"     	=> $newDate,
		          	"customer"    	=> $row['customer'],
		          	"estimasi"     	=> $estimasi,
		          	"company"     	=> $row['company'],
		          	"order_grade"   => $order_grade,
		          	"po_customer" 	=> $row['po_customer'],
		          	"no_so"  		=> $ex_no_so[0]."/".$ex_no_so[1].$ex_no_so[2],
		          	"item"  		=> $row['item'],
		          	"detail"  		=> $row['isi'],
		          	"size"  		=> $row['size'],
		          	"merk"  		=> $row['merk'],
		          	"type"  		=> $row['type'],
		          	"uk_bahan_baku" => $row['uk_bahan_baku'],
		          	"qore"  		=> $row['qore'],
		          	"lin" 	 		=> $row['lin'],
		          	"qty_bahan_baku"=> $row['qty_bahan_baku'],
		          	"roll"  		=> $row['roll'],
		          	"ingredient"  	=> $row['ingredient'],
		          	"porporasi"  	=> $porporasi,
		          	"volume"  		=> $row['volume'],
		          	"qty"  			=> $row['qty'],
		          	"unit"  		=> $row['unit'],
		          	"price"  		=> $row['price'],
		          	"etd"  			=> $row['temp_ETD'],
		          	"ppn"  			=> $temp_PPN,
		          	"total"  		=> $temp_TOTAL,
		          	"annotation"  	=> $row['annotation'],
		          	"sources"  		=> $sources,
		          	"ongkir"  		=> $total_ongkir,
		          	"input"  		=> $fetch['name'],
		          	"functions"     => $functions
		        );

		        $result  = $success;
	      		$message = $qsuccess;
	      		$array[] = $row['id_fk']."_".$row['id_sj']."_".$row['total_ongkir'];
	      		$arrays[] = $row['id_fk'];
		    }
		}

  	} elseif ($action == 'add_'.$slug){
  		$id_customer = mysqli_real_escape_string($connect, $_POST['id_customer']);
  		$id_company = mysqli_real_escape_string($connect, $_POST['company']);

  		if($id_customer > 0 && $id_company > 0){
  			////////////////////////////////////////////////////////////////////
		    // Add PO
		    ///////////////////////////////////////////////////////////////////

	  		$data = $_POST['data'];
	  		$cus = $_POST['customer'];
	  		$nopo = $_POST['po_customer'];
	  		$tgl = $_POST['po_date'];
	  		$c_time = date('ym');
	  		$ppn = mysqli_real_escape_string($connect, str_replace('.', '', $_POST['ppns']));

	  		$data_item1 = array();
	  		$data_item2 = array();
	  		$data_item3 = array();

	  		$query1 = "SELECT id_fk FROM $tabel1 ORDER BY id DESC LIMIT 1";
	  		$sql1 = $connect->query($query1);
	  		$data1 = $sql1->fetch_array();

	  		$query2 = "SELECT no_so FROM $tabel5 ORDER BY id DESC LIMIT 1";
	  		$sql2 = $connect->query($query2);
	  		$data2 = $sql2->fetch_array();
	  		$ex_data2 = explode("/", $data2['no_so']);

	  		if(empty($data1['id_fk']) OR $c_time > $ex_data2[1])
	  		{
	  			$id_fk = $data1['id_fk'] + 1;
	  			$insert1 = "INSERT INTO $tabel1 SET ";
	  			$insert2 = "INSERT INTO $tabel2 (id_fk, item_to, detail, item, size, price, qty, unit, input_by) VALUES ";
	  			$insert3 = "INSERT INTO $tabel5 (id_fk, item_to, detail, no_so, item, size, unit, qore, lin, roll, ingredient, qty, volume, total, annotation, porporasi, uk_bahan_baku, qty_bahan_baku, sources, merk, type) VALUES ";
			    $insert4 = "INSERT INTO $tabel3 SET ";
			    $insert5 = "INSERT INTO $tabel4 SET ";
			    $insert6 = "INSERT INTO $tabel10 (id_fk, item_to, order_status) VALUES ";

			    $insert1	.= "id_fk 	= '".$id_fk."',";
			    $insert1	.= "id_company 	= '".$id_company."',";
			    $insert1	.= "id_customer = '".$id_customer."',";
		    	$insert1	.= "customer 	= '". mysqli_real_escape_string($connect, $_POST['customer'])	."',";
		    	$insert1	.= "order_grade = '". mysqli_real_escape_string($connect, $_POST['order_grade'])."',";
			    $insert1	.= "po_date		= '". mysqli_real_escape_string($connect, $_POST['po_date'])	."',";
			    $insert1 	.= "po_customer	= '". mysqli_real_escape_string($connect, $_POST['po_customer'])."',";
			    $insert1 	.= "input_by  = '". $_SESSION['id'] ."'";

		    	$item_to = 0;
		    	foreach($data['item'] as $key => $value){
		    		$item_to = $item_to + 1;
		    		$price_filter1 = str_replace('.', '', $data['price'][$key]);
		    		$price_filter2 = str_replace(",", ".", $price_filter1);
		    		if(empty($data['item'][$key]))	{$item = '';} else {$item = $data['item'][$key];}
					if(empty($data['size'][$key]))	{$size = '';} else {$size = $data['size'][$key];}
					if(empty($data['uk_bahan_baku'][$key])){$sbaku = '';} else {$sbaku = $data['uk_bahan_baku'][$key];}
					if(empty($data['qty_bahan_baku'][$key]))  	{$qbaku = '';} else {$qbaku = $data['qty_bahan_baku'][$key];}
					if(empty($data['price'][$key]))	{$price = '';} else {$price = $price_filter2;}
					if(empty($data['qty'][$key]))	{$qty = '';} else {$qty = $data['qty'][$key];}
					if(empty($data['unit'][$key]))	{$unit = '';} else {$unit = $data['unit'][$key];}
					////////////////////////////////////////////////////////////////////////////////////////
					if(empty($data['qore'][$key]))      {$qore = '';} else {$qore = $data['qore'][$key];}
					if(empty($data['lin'][$key]))      	{$lin = '';} else {$lin = $data['lin'][$key];}
					if(empty($data['roll'][$key]))      {$roll = '';} else {$roll = $data['roll'][$key];}
					if(empty($data['ingredient'][$key])){$ingredient = '';} else {$ingredient = $data['ingredient'][$key];}
					if(empty($data['volume'][$key]))    {$volume = '';} else {$volume = $data['volume'][$key];}
					if(empty($data['annotation'][$key])){$annotation = '';} else {$annotation = $data['annotation'][$key];}
					if($unit === "PCS"){$total = $qty/$volume;}
					if($unit === "ROLL"){$total = $qty*$volume;}
					if($unit === "PAK"){$total = $qty*$volume;}
					if($unit === "CM"){$total = $qty*$volume;}
					if($unit === "MM"){$total = $qty*$volume;}
					if($unit === "METER"){$total = $qty*$volume;}
					if($unit === "DUSH"){$total = $qty*$volume;}
					if($unit === "BOTOL"){$total = $qty*$volume;}
					if($unit === "UNIT"){$total = $qty*$volume;}
					if($unit === "ONS"){$total = $qty*$volume;}
					if($unit === "KG"){$total = $qty*$volume;}
					if($unit === "LITER"){$total = $qty*$volume;}
					if(empty($data['porporasi'][$key]))	{$porporasi = '0';} else {$porporasi = $data['porporasi'][$key];}
					if(!empty($data['sources'][$key]) AND $data['sources'][$key] == '1'){
						$sources = $data['sources'][$key];
					}
					if(!empty($data['sources'][$key]) AND $data['sources'][$key] == '2'){
						$sources = $data['sources'][$key]."|".str_replace("|", '-', $data['etc1'][$key])."|".$data['etc2'][$key];
					}
					if(!empty($data['sources'][$key]) AND $data['sources'][$key] == '3'){
						$sources = $data['sources'][$key]."|".$data['etc1'][$key];
					}
					if(empty($data['detail'][$key]))    {$detail = '';} else {$detail = $data['detail'][$key];}
					if(empty($data['merk'][$key]))      {$merk = '';} else {$merk = $data['merk'][$key];}
					if(empty($data['type'][$key]))      {$type = '';} else {$type = $data['type'][$key];}

			    	$data_item1[] .= "('".$id_fk."', '".$item_to."', '".$detail."', '".$item."', '".$size."', '".$price."', '".$qty."', '".$unit."', '".$_SESSION['id']."')";
			    	$data_item2[] .= "('".$id_fk."', '".$item_to."', '".$detail."', 'WSO/".$c_time."/00".$item_to."', '".$item."', '".$size."', '".$unit."', '".$qore."', '".$lin."', '".$roll."', '".$ingredient."', '".$qty."', '".$volume."', '".$total."', '".$annotation."', '".$porporasi."', '".$sbaku."', '".$qbaku."', '".$sources."', '".$merk."', '".$type."')";
			    	$data_item3[] .= "('".$id_fk."', '".$item_to."', '0')";
			    	
			    }
			    $insert2 .= implode(",", $data_item1);
			    $insert3 .= implode(",", $data_item2);

			    $insert4	.= "id_fk 	= '".$id_fk."',";
			    if($ppn > 0){$ppns = '1';} else {$ppns = '0';}
			    $insert4 	.= "ppn		= '".$ppns."'";

			    $insert5	.= "id_fk 		= '".$id_fk."',";
			    $insert5	.= "po_date		= '". mysqli_real_escape_string($connect, $_POST['po_date'])	."',";
			    $insert5 	.= "po_customer = '". mysqli_real_escape_string($connect, $_POST['po_customer'])."',";
		    	$insert5	.= "customer 	= '". mysqli_real_escape_string($connect, $_POST['customer'])	."'";

			    $insert6	.= implode(",", $data_item3);

			    $sql1 = $connect->query($insert1);
			    $sql2 = $connect->query($insert2);
			    $sql3 = $connect->query($insert3);
			    $sql4 = $connect->query($insert4);
			    $sql5 = $connect->query($insert5);
			    $sql6 = $connect->query($insert6);
			    $sql7 = logger($connect,'Insert PO', 'Customer: '.$cus.' - No po: '.$nopo.' - Po date: '.$tgl);

			    if(!$sql1 OR !$sql2 OR !$sql3 OR !$sql4 OR !$sql5 OR !$sql6 OR !$sql7){
			      $result  = $error;
			      $message = $qerror;
			    } else {
			      $result  = $success;
			      $message = $qsuccess;
			    }


	  		} else {

	  			$urutSPK = array();
	  			for($i = $ex_data2[2]; $i<=999; $i++)
	  			{
	  				$urutSPK[] = str_pad($i, 3, "0", STR_PAD_LEFT);
	  			}

	  			$id_fk = $data1['id_fk'] + 1;
	  			$insert1 = "INSERT INTO $tabel1 SET ";
	  			$insert2 = "INSERT INTO $tabel2 (id_fk, item_to, detail, item, size, price, qty, unit, input_by) VALUES ";
	  			$insert3 = "INSERT INTO $tabel5 (id_fk, item_to, detail, no_so, item, size, unit, qore, lin, roll, ingredient, qty, volume, total, annotation, porporasi, uk_bahan_baku, qty_bahan_baku, sources, merk, type) VALUES ";
			    $insert4 = "INSERT INTO $tabel3 SET ";
			    $insert5 = "INSERT INTO $tabel4 SET ";
			    $insert6 = "INSERT INTO $tabel10 (id_fk, item_to, order_status) VALUES ";

			    $insert1	.= "id_fk 	= '".$id_fk."',";
			    $insert1	.= "id_company 	= '".$id_company."',";
			    $insert1	.= "id_customer = '".$id_customer."',";
		    	$insert1	.= "customer 	= '". mysqli_real_escape_string($connect, $_POST['customer'])	."',";
		    	$insert1	.= "order_grade = '". mysqli_real_escape_string($connect, $_POST['order_grade'])."',";
			    $insert1	.= "po_date		= '". mysqli_real_escape_string($connect, $_POST['po_date'])	."',";
			    $insert1 	.= "po_customer	= '". mysqli_real_escape_string($connect, $_POST['po_customer'])."',";
			    $insert1 	.= "input_by  = '". $_SESSION['id'] ."'";

		    	$item_to = 0;
		    	foreach($data['item'] as $key => $value){
		    		$item_to = $item_to + 1;
		    		$price_filter1 = str_replace('.', '', $data['price'][$key]);
		    		$price_filter2 = str_replace(",", ".", $price_filter1);
		    		if(empty($data['item'][$key]))	{$item = '';} else {$item = $data['item'][$key];}
					if(empty($data['size'][$key]))	{$size = '';} else {$size = $data['size'][$key];}
					if(empty($data['uk_bahan_baku'][$key])){$sbaku = '';} else {$sbaku = $data['uk_bahan_baku'][$key];}
					if(empty($data['qty_bahan_baku'][$key]))  	{$qbaku = '';} else {$qbaku = $data['qty_bahan_baku'][$key];}
					if(empty($data['price'][$key]))	{$price = '';} else {$price = $price_filter2;}
					if(empty($data['qty'][$key]))	{$qty = '';} else {$qty = $data['qty'][$key];}
					if(empty($data['unit'][$key]))	{$unit = '';} else {$unit = $data['unit'][$key];}
					////////////////////////////////////////////////////////////////////////////////////////
					if(empty($data['qore'][$key]))      {$qore = '';} else {$qore = $data['qore'][$key];}
					if(empty($data['lin'][$key]))      	{$lin = '';} else {$lin = $data['lin'][$key];}
					if(empty($data['roll'][$key]))      {$roll = '';} else {$roll = $data['roll'][$key];}
					if(empty($data['ingredient'][$key])){$ingredient = '';} else {$ingredient = $data['ingredient'][$key];}
					if(empty($data['volume'][$key]))    {$volume = '';} else {$volume = $data['volume'][$key];}
					if(empty($data['annotation'][$key])){$annotation = '';} else {$annotation = $data['annotation'][$key];}
					if($unit === "PCS"){$total = $qty/$volume;}
					if($unit === "ROLL"){$total = $qty*$volume;}
					if($unit === "PAK"){$total = $qty*$volume;}
					if($unit === "CM"){$total = $qty*$volume;}
					if($unit === "MM"){$total = $qty*$volume;}
					if($unit === "METER"){$total = $qty*$volume;}
					if($unit === "DUSH"){$total = $qty*$volume;}
					if($unit === "BOTOL"){$total = $qty*$volume;}
					if($unit === "UNIT"){$total = $qty*$volume;}
					if($unit === "ONS"){$total = $qty*$volume;}
					if($unit === "KG"){$total = $qty*$volume;}
					if($unit === "LITER"){$total = $qty*$volume;}
					if(empty($data['porporasi'][$key]))	{$porporasi = '0';} else {$porporasi = $data['porporasi'][$key];}
					if(!empty($data['sources'][$key]) AND $data['sources'][$key] == '1'){
						$sources = $data['sources'][$key];
					}
					if(!empty($data['sources'][$key]) AND $data['sources'][$key] == '2'){
						$sources = $data['sources'][$key]."|".str_replace("|", '-', $data['etc1'][$key])."|".$data['etc2'][$key];
					}
					if(!empty($data['sources'][$key]) AND $data['sources'][$key] == '3'){
						$sources = $data['sources'][$key]."|".$data['etc1'][$key];
					}
					if(empty($data['detail'][$key]))    {$detail = '';} else {$detail = $data['detail'][$key];}
					if(empty($data['merk'][$key]))      {$merk = '';} else {$merk = $data['merk'][$key];}
					if(empty($data['type'][$key]))      {$type = '';} else {$type = $data['type'][$key];}

			    	$data_item1[] .= "('".$id_fk."', '".$item_to."', '".$detail."', '".$item."', '".$size."', '".$price."', '".$qty."', '".$unit."', '".$_SESSION['id']."')";
			    	$data_item2[] .= "('".$id_fk."', '".$item_to."', '".$detail."', 'WSO/".$c_time."/".$urutSPK[$item_to]."', '".$item."', '".$size."', '".$unit."', '".$qore."', '".$lin."', '".$roll."', '".$ingredient."', '".$qty."', '".$volume."', '".$total."', '".$annotation."', '".$porporasi."', '".$sbaku."', '".$qbaku."', '".$sources."', '".$merk."', '".$type."')";
			    	$data_item3[] .= "('".$id_fk."', '".$item_to."', '0')";
			    	
			    }
			    $insert2 .= implode(",", $data_item1);
			    $insert3 .= implode(",", $data_item2);

			    $insert4	.= "id_fk 	= '".$id_fk."',";
			    if($ppn > 0){$ppns = '1';} else {$ppns = '0';}
			    $insert4 	.= "ppn		= '".$ppns."'";

			    $insert5	.= "id_fk 		= '".$id_fk."',";
			    $insert5	.= "po_date		= '". mysqli_real_escape_string($connect, $_POST['po_date'])	."',";
			    $insert5 	.= "po_customer = '". mysqli_real_escape_string($connect, $_POST['po_customer'])."',";
		    	$insert5	.= "customer 	= '". mysqli_real_escape_string($connect, $_POST['customer'])	."'";

			    $insert6	.= implode(",", $data_item3);

			    $sql1 = $connect->query($insert1);
			    $sql2 = $connect->query($insert2);
			    $sql3 = $connect->query($insert3);
			    $sql4 = $connect->query($insert4);
			    $sql5 = $connect->query($insert5);
			    $sql6 = $connect->query($insert6);
			    $sql7 = logger($connect,'Insert PO', 'Customer: '.$cus.' - No po: '.$nopo.' - Po date: '.$tgl);

			    if(!$sql1 OR !$sql2 OR !$sql3 OR !$sql4 OR !$sql5 OR !$sql6 OR !$sql7){
			      $result  = $error;
			      $message = $qerror;
			    } else {
			      $result  = $success;
			      $message = $qsuccess;
			    }
	  		} 

  		} else {
  			$result  = 'invalid';
	      	$message = 'ID pelanggan tidak valid';
  		}

  	} elseif ($action == 'get_customer_'.$slug){
		// Get data by id
    	if($id == ''){
      		$result  = $error;
      		$message = 'ID '.$missing;
    	} else {
    		$id = mysqli_real_escape_string($connect, $id);
      		$query = "SELECT a.*, b.*, c.id AS id_company, c.company FROM $tabel1 AS a LEFT JOIN $tabel3 AS b ON a.id_fk = b.id_fk LEFT JOIN company AS c ON a.id_company = c.id WHERE a.id = '".$id."'";
      		$sql = $connect->query($query);
      		if(!$sql){
        		$result  = $error;
        		$message = $qerror;
      		} else {
        		$result  = $success;
        		$message = $qsuccess;
        		while($row = $sql->fetch_array()){
	          		$mysqli_data[] = array(
	          			"company"    	=> $row['company'],
	          			"id_company"   	=> $row['id_company'],
	          			"customer"    	=> $row['customer'],
	          			"id_customer"   => $row['id_customer'],
	          			"order_grade"   => $row['order_grade'],
			          	"po_date"     	=> $row['po_date'],
			          	"po_customer" 	=> $row['po_customer'],
			          	"ppn"  			=> $row['ppn']
	            	);
        		}
      		}
    	}
  
  	}

  	elseif($action == 'edit_customer_'.$slug){
		// Edit
    	if ($id == ''){
      		$result  = $error;
      		$message = 'ID '.$missing;
    	} else {
    		$id_cus = mysqli_real_escape_string($connect, $id);
    		$id_item = mysqli_real_escape_string($connect, $_GET['id_item']);
    		$cus = mysqli_real_escape_string($connect, $_GET['customer']);
    		$id_customer = mysqli_real_escape_string($connect, $_GET['id_customer']);
    		$order_status = mysqli_real_escape_string($connect, $_GET['order_grade']);
    		$nopo = mysqli_real_escape_string($connect, $_GET['po_customer']);
    		$tgl = mysqli_real_escape_string($connect, $_GET['po_date']);
    		$jumlah = mysqli_real_escape_string($connect, $_GET['etd']);
    		$total = mysqli_real_escape_string($connect, $_GET['total']);
    		$ppn = mysqli_real_escape_string($connect, $_GET['ppns']);
    		$company = mysqli_real_escape_string($connect, $_GET['company']);

    		$select = "SELECT id_fk FROM $tabel1 WHERE id = '".$id_cus."'";
    		$sql = $connect->query($select);
    		$data = $sql->fetch_array();

    		$update1 = "UPDATE $tabel1 SET ";
    		$update2 = "UPDATE $tabel4 SET ";
    		$update3 = "UPDATE $tabel3 SET ";

    		if (isset($_GET['customer']))	{ $update1 .= "customer     = '" . $cus	."',";}
    		if (isset($_GET['company']))	{ $update1 .= "id_company  	= '" . $company	."',";}
    		if (isset($_GET['id_customer'])){ $update1 .= "id_customer  = '" . $id_customer	."',";}
    		if (isset($_GET['order_grade'])){ $update1 .= "order_grade  = '" . $order_grade	."',";}
	    	if (isset($_GET['po_date']))	{ $update1 .= "po_date 		= '" . $tgl	."',";}
	    	if (isset($_GET['po_customer'])){ $update1 .= "po_customer	= '" . $nopo."'";}
	    	$update1 .= "WHERE id = '".$id_cus."'";

	    	if (isset($_GET['po_date']))	{ $update2 .= "po_date 		= '" . $tgl."',";}
	    	if (isset($_GET['po_customer'])){ $update2 .= "po_customer	= '" . $nopo."',";}
	    	if (isset($_GET['customer']))	{ $update2 .= "customer     = '" . $cus	."'";}
	    	$update2 .= "WHERE id_fk = '".$data['id_fk']."'";
	    	
	    	if (isset($_GET['ppns']))		{ $update3 .= "ppn		= '". $ppn ."'";}
	    	$update3 .= "WHERE id_fk = '".$data['id_fk']."'";

      		$sql1  = $connect->query($update1);
      		$sql2  = $connect->query($update2);
      		$sql3  = $connect->query($update3);
      		$logger = logger($connect,'Edit PO', 'Customer: '.$cus.' - No po: '.$nopo.' - Po date: '.$tgl.' - PPN: '.$ppn);

      		if (!$sql1 OR !$sql2 OR !$sql3 OR !$logger){
        		$result  = $error;
        		$message = $qerror;
      		} else {
        		$result  = $success;
        		$message = $qsuccess;
      		}
    	}

  	} elseif ($action == 'get_item_'.$slug){
		// Get data by id
    	if ($id == ''){
      		$result  = $error;
      		$message = 'ID '.$missing;
    	} else {
    		$id = mysqli_real_escape_string($connect, $id);
      		$query = "SELECT a.*, b.id AS id_wo, b.qore, b.lin, b.roll, b.ingredient, b.volume, b.annotation, b.porporasi, b.uk_bahan_baku, b.qty_bahan_baku, b.sources, b.detail, b.type, b.merk FROM $tabel2 AS a LEFT JOIN $tabel5 AS b ON a.id_fk = b.id_fk AND a.item_to = b.item_to WHERE a.id = '".$id."'";
      		$sql = $connect->query($query);
      		if (!$sql){
        		$result  = $error;
        		$message = $qerror;
      		} else {
        		$result  = $success;
        		$message = $qsuccess;
        		while($row = $sql->fetch_array()){
        			$price_filter = str_replace('.', ',', $row['price']);
	          		$mysqli_data[] = array(
	          			"id_wo"    	=> $row['id_wo'],
	          			"item"    	=> $row['item'],
			          	"size"     	=> $row['size'],
			          	"uk_bahan_baku" => $row['uk_bahan_baku'],
			          	"qore"     	=> $row['qore'],
			          	"lin"     	=> $row['lin'],
			          	"qty_bahan_baku" => $row['qty_bahan_baku'],
			          	"roll"     	=> $row['roll'],
			          	"ingredient"=> $row['ingredient'],
			          	"unit"		=> $row['unit'],
			          	"volume"	=> $row['volume'],
			          	"annotation"=> $row['annotation'],
			          	"price" 	=> $price_filter,
			          	"qty"  		=> $row['qty'],
			          	"unit"  	=> $row['unit'],
			          	"sources"  	=> $row['sources'],
			          	"porporasi" => $row['porporasi'],
			          	"detail" 	=> $row['detail'],
			          	"merk" 		=> $row['merk'],
			          	"type" 		=> $row['type'],

	            	);
        		}
      		}
    	}
  
  	} elseif($action == 'edit_item_'.$slug){
		// Edit item
    	if ($id == ''){
      		$result  = $error;
      		$message = 'ID '.$missing;
    	} else {
    		$id = mysqli_real_escape_string($connect, $id);
    		$id_wo = mysqli_real_escape_string($connect, $_GET['id_wo']);
    		$item = mysqli_real_escape_string($connect, $_GET['item']);
    		$detail = mysqli_real_escape_string($connect, $_GET['detail']);
    		$merk = mysqli_real_escape_string($connect, $_GET['merk']);
    		$type = mysqli_real_escape_string($connect, $_GET['type']);
    		$size = mysqli_real_escape_string($connect, $_GET['size']);
    		$unit = mysqli_real_escape_string($connect, $_GET['unit']);
    		$volume = mysqli_real_escape_string($connect, $_GET['volume']);
    		$qty = mysqli_real_escape_string($connect, $_GET['qty']);
    		$price_filter = str_replace('.', '', $_GET['price']);
		    $price = str_replace(",", ".", $price_filter);

    		$update1 = "UPDATE $tabel2 SET ";
    		if (isset($_GET['item']))	{ $update1 .= "item     = '" . $item ."',";}
	    	if (isset($_GET['size']))	{ $update1 .= "size 	= '" . $size ."',";}
	    	if (isset($_GET['qty']))	{ $update1 .= "qty		= '" . $qty ."',";}
	    	if (isset($_GET['unit'])) 	{ $update1 .= "unit   	= '" . $unit ."',";}
	    	if (isset($_GET['price'])) 	{ $update1 .= "price 	= '" . $price ."'";}
	    	$update1 .= " WHERE id = '".$id."'";

	    	$update2 = "UPDATE $tabel5 SET ";
    		if (isset($_GET['item']))	{ $update2 .= "item     = '" . $item ."',";}
    		if (isset($_GET['detail']))	{ $update2 .= "detail   = '" . $detail ."',";}
    		if (isset($_GET['merk']))	{ $update2 .= "merk   	= '" . $merk ."',";}
    		if (isset($_GET['type']))	{ $update2 .= "type   	= '" . $type ."',";}
	    	if (isset($_GET['size']))	{ $update2 .= "size 	= '" . $size ."',";}
	    	if (isset($_GET['unit'])) 	{ $update2 .= "unit   	= '" . $unit ."',";}
	    	if (isset($_GET['qty']))	{ $update2 .= "qty		= '" . $qty ."',";}
	    	if (isset($_GET['volume'])) { $update2 .= "volume   = '" . $volume ."',";}
	    	if ($unit == "PCS")			{ $update2 .= "total 	= '" . $qty/$volume ."',";}
	    	if ($unit == "ROLL")		{ $update2 .= "total 	= '" . $qty*$volume ."',";}
			if ($unit == "PAK")			{ $update2 .= "total 	= '" . $qty*$volume ."',";}
			if ($unit == "CM")			{ $update2 .= "total 	= '" . $qty*$volume ."',";}
			if ($unit == "MM")			{ $update2 .= "total 	= '" . $qty*$volume ."',";}
			if ($unit == "METER")		{ $update2 .= "total 	= '" . $qty*$volume ."',";}
			if ($unit == "DUSH")		{ $update2 .= "total 	= '" . $qty*$volume ."',";}
			if ($unit == "BOTOL")		{ $update2 .= "total 	= '" . $qty*$volume ."',";}
			if ($unit == "UNIT")		{ $update2 .= "total 	= '" . $qty*$volume ."',";}
			if ($unit == "ONS")			{ $update2 .= "total 	= '" . $qty*$volume ."',";}
			if ($unit == "KG")			{ $update2 .= "total 	= '" . $qty*$volume ."',";}
			if ($unit == "LITER")		{ $update2 .= "total 	= '" . $qty*$volume ."',";}
	    	if (isset($_GET['uk_bahan_baku'])) { $update2 .= "uk_bahan_baku   	= '" . mysqli_real_escape_string($connect, $_GET['uk_bahan_baku'])	."',";}
	    	if (isset($_GET['qty_bahan_baku'])){ $update2 .= "qty_bahan_baku   = '" . mysqli_real_escape_string($connect, $_GET['qty_bahan_baku'])	."',";}
	    	if (isset($_GET['qore'])) 	{ $update2 .= "qore   	= '" . mysqli_real_escape_string($connect, $_GET['qore'])	."',";}
	    	if (isset($_GET['lin'])) 	{ $update2 .= "lin   	= '" . mysqli_real_escape_string($connect, $_GET['lin'])	."',";}
	    	if (isset($_GET['roll'])) 	{ $update2 .= "roll   	= '" . mysqli_real_escape_string($connect, $_GET['roll'])	."',";}
	    	if (isset($_GET['ingredient'])){ $update2 .= "ingredient = '" . mysqli_real_escape_string($connect, $_GET['ingredient'])."',";}
	    	if (isset($_GET['annotation'])){ $update2 .= "annotation = '" . mysqli_real_escape_string($connect, $_GET['annotation'])."',";}
	    	if (isset($_GET['porporasi'])) { $update2 .= "porporasi = '" . mysqli_real_escape_string($connect, $_GET['porporasi'])."',";}
			if(!empty($_GET['sources']) AND $_GET['sources'] == '1'){
				$update2 .= "sources = '" .mysqli_real_escape_string($connect, $_GET['sources'])."'";
			}
			if(!empty($_GET['sources']) AND $_GET['sources'] == '2'){
				$update2 .= "sources = '" .mysqli_real_escape_string($connect, $_GET['sources'])."|".mysqli_real_escape_string($connect, $_GET['etc1'])."|".mysqli_real_escape_string($connect, $_GET['etc2'])."'";
			}
			if(!empty($_GET['sources']) AND $_GET['sources'] == '3'){
				$update2 .= "sources = '" .mysqli_real_escape_string($connect, $_GET['sources'])."|".mysqli_real_escape_string($connect, $_GET['etc1'])."'";
			}
	    	$update2 .= " WHERE id = '".$id_wo."'";

	    	//memeriksa $qty jika lebih dari jumlah pengiriman status berubah 6, sedangkan kurang 
	    	//atau cukup akan menjadi 7 
	    	$select = "SELECT a.id_fk, a.item_to, sum(b.send_qty) AS total_send_qty FROM $tabel2 AS a LEFT JOIN $tabel7 AS b ON b.id_fk = a.id_fk AND b.item_to = a.item_to WHERE a.id = '$id'";
	    	$sql = $connect->query($select);
	    	$fetch = $sql->fetch_array();
	    	if(empty($fetch['total_send_qty'])){
	    		$update3 = "UPDATE $tabel10 SET order_status = 0 WHERE id_fk = '".$fetch['id_fk']."' AND item_to = '".$fetch['item_to']."'";
	    		$exe1 = $connect->query($update3);
	    	} elseif($qty > $fetch['total_send_qty']){
	    		$update3 = "UPDATE $tabel10 SET order_status = 2 WHERE id_fk = '".$fetch['id_fk']."' AND item_to = '".$fetch['item_to']."'";
	    		$exe1 = $connect->query($update3);
	    	} else {
	    		$update3 = "UPDATE $tabel10 SET order_status = 1 WHERE id_fk = '".$fetch['id_fk']."' AND item_to = '".$fetch['item_to']."'";
	    		$exe1 = $connect->query($update3);
	    	}
	      		
      		$exe2  = $connect->query($update1);
      		$exe3  = $connect->query($update2);
      		$logger = logger($connect,'Edit PO (item)', 'Item: '.$item.' - Size: '.$size.' - Qty: '.$qty.' - Unit: '.$unit.' - Price: '.$price);

	    	if(!$exe1 OR !$exe2 OR !$exe3 OR !$logger){
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
    		$data1 = array();
    		$data2 = array();
    		$id = mysqli_real_escape_string($connect, $id);
    		
    		//ambil id_fk dan item_to dari tabel PO_item
    		$select1 = "SELECT id_fk, item_to FROM $tabel2 WHERE id = '".$id."'";
    		$query1 = $connect->query($select1);
    		$fetch1 = $query1->fetch_array();

    		//ambil data customer dari tabel PO_customer berdasarkan id_fk diatas
    		$select2 = "SELECT customer, po_customer, po_date FROM $tabel1 WHERE id_fk = '".$fetch1['id_fk']."'";
    		$query2 = $connect->query($select2);
    		$fetch2 = $query2->fetch_array();

    		//Menyortir id berdasarkan tabel po_customer, po_item, po_ppn, wo_customer, wo_item, status
    		$select3 = "SELECT a.id AS id_po_item, b.id AS id_po_customer, c.id AS id_po_price, d.id AS id_wo_item, e.id_fk AS id_wo_customer, f.no AS id_status, g.jml_item_dlm_wo FROM $tabel2 AS a LEFT JOIN $tabel1 AS b ON b.id_fk = a.id_fk LEFT JOIN $tabel3 as c ON c.id_fk = a.id_fk LEFT JOIN $tabel4 AS e ON e.id_fk = a.id_fk LEFT JOIN $tabel5 AS d ON d.id_fk AND a.id_fk AND d.item_to = a.item_to LEFT JOIN $tabel10 AS f ON f.id_fk = a.id_fk AND f.item_to = a.item_to LEFT JOIN (SELECT id_fk, count(id) AS jml_item_dlm_wo FROM $tabel5 WHERE id_fk = '".$fetch1['id_fk']."' GROUP BY id_fk) AS g ON g.id_fk = a.id_fk WHERE a.id_fk = '".$fetch1['id_fk']."' AND a.item_to = '".$fetch1['item_to']."'";
    		$query3 = $connect->query($select3);
    		while($row1 = $query3->fetch_array()){
    			$data1[] = $row1;
    		}

    		//Menyortir id berdasarkan tabel do_customer, do_item, invoice
    		$select4 = "SELECT a.id AS id_wo_item, a.item, b.id AS id_do_item, b.id_fk, b.item_to, b.no_delivery, c.jml_item_dlm_tiap_sj, d.id AS id_do_customer, d.id_sj, e.id AS id_invoice, e.invoice_date FROM $tabel5 AS a LEFT JOIN $tabel7 AS b ON b.id_fk LEFT JOIN (SELECT id_fk, no_delivery, COUNT(id) AS jml_item_dlm_tiap_sj FROM $tabel7 WHERE id_fk = '".$fetch1['id_fk']."' GROUP BY no_delivery) AS c ON c.id_fk = a.id_fk AND c.no_delivery = b.no_delivery LEFT JOIN $tabel6 AS d ON d.id_fk = a.id_fk AND d.id_sj = b.id_sj LEFT JOIN $tabel9 AS e ON e.id_fk = a.id_fk AND e.id_sj = d.id_sj WHERE a.id_fk = '".$fetch1['id_fk']."' AND a.item_to = '".$fetch1['item_to']."' GROUP BY b.id";
    		$query4 = $connect->query($select4);
    		while($row2 = $query4->fetch_array()){
    			$data2[] = $row2;
    		}

    		//Menghapus bagian po, wo, dan status
    		foreach ($data1 as $key => $value) {
    			$trigger1 = $data1[$key]['jml_item_dlm_wo'];
    			if($trigger1 < 2){
    				//menghapus data berdasarkan id_Fk dan item_to pada semua tabel
	    			$del1 = "DELETE FROM $tabel1 WHERE id_fk = '".$fetch1['id_fk']."'";
	    			$del2 = "DELETE FROM $tabel2 WHERE id_fk = '".$fetch1['id_fk']."' AND item_to = '".$fetch1['item_to']."'";
	    			$del3 = "DELETE FROM $tabel3 WHERE id_fk = '".$fetch1['id_fk']."'";
	    			$del4 = "DELETE FROM $tabel4 WHERE id_fk = '".$fetch1['id_fk']."'";
	    			$del5 = "DELETE FROM $tabel5 WHERE id_fk = '".$fetch1['id_fk']."' AND item_to = '".$fetch1['item_to']."'";
	    			$del6 = "DELETE FROM $tabel10 WHERE id_fk = '".$fetch1['id_fk']."' AND item_to = '".$fetch1['item_to']."'";
	    			$sql1 = $connect->query($del1);
	    			$sql2 = $connect->query($del2);
	    			$sql3 = $connect->query($del3);
	    			$sql4 = $connect->query($del4);
	    			$sql5 = $connect->query($del5);
	    			$sql6 = $connect->query($del6);
	    			
	    			if(!$sql1 OR !$sql2 OR !$sql3 OR !$sql4 OR !$sql5 OR !$sql6){
		        		$result  = $error;
		        		$message = $qerror;
		      		} else {
		        		$result  = $success;
		        		$message = $qsuccess;
		      		}

    			} else {
    				//menghapus data berdasarkan id_Fk dan item_to pada tabel PO_item, WO_item
    				$del1 = "DELETE FROM $tabel2 WHERE id_fk = '".$fetch1['id_fk']."' AND item_to = '".$fetch1['item_to']."'";
    				$del2 = "DELETE FROM $tabel5 WHERE id_fk = '".$fetch1['id_fk']."' AND item_to = '".$fetch1['item_to']."'";
    				$del3 = "DELETE FROM $tabel10 WHERE id_fk = '".$fetch1['id_fk']."' AND item_to = '".$fetch1['item_to']."'";
    				$sql1 = $connect->query($del1);
    				$sql2 = $connect->query($del2);
    				$sql3 = $connect->query($del3);

    				if (!$sql1 OR !$sql2 OR !$sql3){
		        		$result  = $error;
		        		$message = $qerror;
		      		} else {
		        		$result  = $success;
		        		$message = $qsuccess;
		      		}
    			}
    		}

    		$logger = logger($connect,'Delete PO (item)', 'Customer: '.$fetch2['customer'].' - No po: '.$fetch2['po_customer'].' - Po date: '.$fetch2['po_date']);

    		//menghapus DO customer, DO item dan invoice berdasarkan kondisi
    		foreach($data2 as $keys => $values){
		        $trigger2 = $data2[$keys]['jml_item_dlm_tiap_sj'];
		        if($trigger2 < 2){
		        	if($fetch1['id_fk'] == $data2[$keys]['id_fk'] AND $fetch1['item_to'] == $data2[$keys]['item_to']){
		        		$hapus1 = "DELETE FROM $tabel6 WHERE id = '".$data2[$keys]['id_do_customer']."'";
			            $hapus2 = "DELETE FROM $tabel7 WHERE id = '".$data2[$keys]['id_do_item']."'";
			            $hapus3 = "DELETE FROM $tabel9 WHERE id = '".$data2[$keys]['id_invoice']."'";
			            $run1 = $connect->query($hapus1);
			            $run2 = $connect->query($hapus2);
			            $run3 = $connect->query($hapus3);
			        }
		        } else {
		        	if($fetch1['id_fk'] == $data2[$keys]['id_fk'] AND $fetch1['item_to'] == $data2[$keys]['item_to']){
			            $hapus1 = "DELETE FROM $tabel7 WHERE id = '".$data2[$keys]['id_do_item']."'";
			            $run1 = $connect->query($hapus1);
			        }
			    }
			}
    	}

  	} elseif ($action == 'detail') {
  		$query = "SELECT id, isi FROM setting WHERE ket = 'SO_ITEM'";
  		$sql = $connect->query($query);
  		if(!$sql){
  			$result  = $error;
		    $message = $qerror;

  		} else {
  			$result  = $success;
  			$message = $qsuccess;
  			while ($row = $sql->fetch_array()) {
  				$mysqli_data[] = array(
  					'id'	=> $row['id'],
  					'item'	=> $row['isi'],
  				);
  			}
  		}

  	} elseif ($action == 'ongkir_get') {
  		if ($id == ''){
  			$result  = $error;
      		$message = 'ID '.$missing;
    	} else {
    		$query = "SELECT a.id, a.courier, a.no_tracking, a.cost, a.ekspedisi, a.uom, a.jml, b.no_delivery, b.send_qty FROM delivery_orders_customer AS a LEFT JOIN delivery_orders_item AS b ON a.id_fk = b.id_fk AND a.id_sj = b.id_sj WHERE a.id_fk = '$id' GROUP BY b.no_delivery ORDER BY b.no_delivery ASC";
	  		$sql = $connect->query($query);
	  		if(!$sql){
	  			$result  = $error;
			    $message = $qerror;
	  		} else {
	  			$result  = $success;
	  			$message = $qsuccess;
	  			while ($row = $sql->fetch_array()) {
	  				if($row['send_qty'] > 0)
	  				{
	  					if(empty($row['no_delivery']))	{$no_delivery = '';} else {$no_delivery = 'SJ: '.$row['no_delivery'];}
		  				if(empty($row['courier']))		{$courier = '';} else {$courier = ' - Kurir: '.$row['courier'];}
		  				if(empty($row['no_tracking']))	{$no_tracking = '';} else {$no_tracking = ' - No Tracking: '.$row['no_tracking'];}
		  				if(empty($row['cost']))			{$cost = " - Ongkir: ".rupiah('0');} else {$cost = ' - Ongkir: '.rupiah($row['cost']);}
		  				$mysqli_data[] = array(
		  					'detail'	=> $no_delivery.$courier.$no_tracking.$cost,
		  					'cost'		=> $row['cost'],
		  					'id'		=> $row['id'],
		  					'ekspedisi'	=> $row['ekspedisi'],
		  					'uom'		=> $row['uom'],
		  					'jml'		=> $row['jml'],
		  				);
	  				}
	  			}
	  		}
    	}

  	} elseif($action == 'ongkir_add'){
  		$id = mysqli_real_escape_string($connect, $_GET['surat_jalan']);
  		$ongkos_kirim = str_replace('.', '', mysqli_real_escape_string($connect, $_GET['ongkos_kirim']));
  		$ekspedisi = mysqli_real_escape_string($connect, $_GET['ekspedisi']);
  		$uom = mysqli_real_escape_string($connect, $_GET['uom']);
  		$jml = mysqli_real_escape_string($connect, $_GET['jml']);

  		if(!is_numeric($id) || $id == '')
  		{
  			$result  = $error;
  			$message = $qerror;

  		} else {
  			$query1 = "SELECT a.courier, a.no_tracking, b.no_delivery FROM delivery_orders_customer AS a LEFT JOIN delivery_orders_item AS b ON a.id_fk = b.id_fk AND a.id_sj = b.id_sj WHERE a.id = '".$id."' GROUP BY b.no_delivery";
  			$sql1 = $connect->query($query1);
  			$fetch = $sql1->fetch_array();

  			$query2 = "UPDATE delivery_orders_customer SET cost = '".$ongkos_kirim."', ekspedisi = '".$ekspedisi."', uom = '".$uom."', jml = '".$jml."' WHERE id = '".$id."'";
  			$sql2 = $connect->query($query2);

  			$logger = logger($connect,'Add Ongkir', 'SJ: '.$fetch["no_delivery"].' - Kurir: '.$fetch["courier"].' - No Tracking: '.$fetch["no_tracking"].' - Ongkir: '.$ongkos_kirim);

  			if(!$sql1 || !$sql2 || !$logger)
  			{
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
	$json_data = json_encode($data);
	print $json_data;

} else {
	echo "Not allowed.";
}
?>