	<?php

/////////////////////////////
// Personal config DataTables 
////////////////////////////
require '../dashboard/session.php';
require 'connect.php';
require 'history.php';
$Query = 'action';
$slug = 'purchase';

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
  		$action == 'po_type' ||
  		$action == 'add_'.$slug ||
  		$action == 'get_vendor_'.$slug ||
  		$action == 'edit_vendor_'.$slug ||
  		$action == 'get_item_'.$slug ||
  		$action == 'edit_item_'.$slug ||
  		$action == 'del_'.$slug ||
  		$action == 'get_print_'.$slug ||
  		$action == 'print' ||
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

function priceFilter($price) {
	$filter = str_replace('.', '', $price);
	$filter = str_replace(",", ".", $filter);
	return $filter;
}

$mysqli_data = array();

if($action != ''){

	if($action == 'sortdata_'.$slug){
		$query = "SELECT DISTINCT po_date FROM po_customer ORDER BY po_date DESC";
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

	} elseif ($action == 'po_type') {
  		$query = "SELECT id, isi FROM setting WHERE ket = 'PO_ITEM'";
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

  	} elseif ($action == 'result_'.$slug){

	  	///////////////////////
		// Load data
		//////////////////////

		$curMonth = str_replace('/', '-', $_GET['curMonth']);
	    
	    $query = "SELECT a.id AS id_po, a.po_date, a.nopo, a.note, a.ppn, a.input_by, b.vendor, c.id AS id_po_item, c.detail, c.size, c.price_1, c.price_2, c.qty, c.unit, c.merk, c.type, c.core, c.gulungan, c.bahan, d.id, d.name, e.company, f.isi FROM po_customer AS a LEFT JOIN vendor AS b ON a.id_vendor = b.id LEFT JOIN po_item AS c ON a.id = c.id_fk LEFT JOIN user AS d ON a.input_by = d.id LEFT JOIN company AS e ON e.id = a.id_company LEFT JOIN setting AS f ON f.id = a.type WHERE a.po_date LIKE '$curMonth%' ORDER BY a.id DESC";
	    $sql = $connect->query($query);
	    
	    if(!$sql){
	      $result  = $error;
	      $message = $qerror;
	    } else {
	      	$no = 1;
		    while($row = $sql->fetch_array()){
		    	$date = $row['po_date'];
		    	$po_date = date("d/m/Y", strtotime($date));

		     	$functions = "<div class='function_buttons'><ul>";
		     	if($_SESSION['id'] == $row['input_by']){
		     		$functions .= "<li class='function_edit-customer UbahVendor'><a data-id='".$row['id_po']."' title='Ubah Vendor'><span>Ubah Vendor</span></a></li>";
		     		$functions .= "<li class='function_edit-item UbahItem'><a data-id='".$row['id_po_item']."' title='Ubah Item'><span>Ubah Item</span></a></li>";
		     		$functions .= '<li class="function_print PrintView"><a data-id="'.$row['id_po'].'" title="Print View"><span>Print</span></a></li>';
		     		$functions .= "<li class='function_delete HapusItem'><a data-id='".$row['id_po_item']."' data-name='".$row['detail']."' title='Hapus'><span>Hapus</span></a></li>";
			    } else {
			    	$functions .= "Not allowed";
			    }
			    $functions .= "</ul></div>";

			    if($row['price_2'] > 0){
		    		$subtotal = $row['qty'] * $row['price_2'];
		    	} else {
		    		$subtotal = $row['qty'] * $row['price_1'];
		    	}

		    	if($row['ppn'] > 0){ $tax = $subtotal/10; } else { $tax = 0; }
		    	$total = $subtotal + $tax;

		        $mysqli_data[] = array(
		          	"no"    		=> $no++,
		          	"po_date"     	=> $po_date,
		          	"company"    	=> $row['company'],
		          	"vendor"    	=> $row['vendor'],
		          	"nopo" 			=> $row['nopo'],
		          	"po_type" 		=> $row['isi'],
		          	"detail"  		=> $row['detail'],
		          	"size"  		=> $row['size'],
		          	"price_1"  		=> rupiah($row['price_1']),
		          	"price_2"  		=> rupiah($row['price_2']),
		          	"qty"  			=> $row['qty'],
		          	"unit"  		=> $row['unit'],
		          	"merk"  		=> $row['merk'],
		          	"type"  		=> $row['type'],
		          	"core"  		=> $row['core'],
		          	"gulungan"  	=> $row['gulungan'],
		          	"bahan"		  	=> $row['bahan'],
		          	"note"  		=> $row['note'],
		          	"subtotal"  	=> rupiah($subtotal),
		          	"tax"  			=> rupiah($tax),
		          	"total"  		=> rupiah($total),
		          	"input"  		=> $row['name'],
		          	"functions"     => $functions
		        );

		        $result  = $success;
	      		$message = $qsuccess;
		    }
		}

  	} elseif($action == 'resultAll_'.$slug){

  		///////////////////////
		// Load data print
		//////////////////////

		$curMonth = str_replace('/', '-', $_GET['curMonth']);
	    
	    $query = "SELECT a.id AS id_po, a.po_date, a.nopo, a.note, a.ppn, a.input_by, b.vendor, c.id AS id_po_item, c.detail, c.size, c.price_1, c.price_2, c.qty, c.unit, c.merk, c.type, c.core, c.gulungan, c.bahan, d.id, d.name, e.company, f.isi FROM po_customer AS a LEFT JOIN vendor AS b ON a.id_vendor = b.id LEFT JOIN po_item AS c ON a.id = c.id_fk LEFT JOIN user AS d ON a.input_by = d.id LEFT JOIN company AS e ON e.id = a.id_company LEFT JOIN setting AS f ON f.id = a.type WHERE a.po_date LIKE '$curMonth%' ORDER BY a.id ASC";
	    $sql = $connect->query($query);
	    
	    if(!$sql){
	      $result  = $error;
	      $message = $qerror;
	    } else {
	      	$no = 1;
		    while($row = $sql->fetch_array()){
		    	$date = $row['po_date'];
		    	$po_date = date("d/m/Y", strtotime($date));
		    	if($row['price_2'] > 0){
		    		$subtotal = $row['qty'] * $row['price_2'];
		    	} else {
		    		$subtotal = $row['qty'] * $row['price_1'];
		    	}

		    	if($row['ppn'] > 0){ $tax = $subtotal/10; } else { $tax = 0; }
		    	$total = $subtotal + $tax;

		        $mysqli_data[] = array(
		          	"no"    		=> $no++,
		          	"po_date"     	=> $po_date,
		          	"company"    	=> $row['company'],
		          	"vendor"    	=> $row['vendor'],
		          	"nopo" 			=> $row['nopo'],
		          	"po_type" 		=> $row['isi'],
		          	"detail"  		=> $row['detail'],
		          	"size"  		=> $row['size'],
		          	"price_1"  		=> $row['price_1'],
		          	"price_2"  		=> $row['price_2'],
		          	"qty"  			=> $row['qty'],
		          	"unit"  		=> $row['unit'],
		          	"merk"  		=> $row['merk'],
		          	"type"  		=> $row['type'],
		          	"core"  		=> $row['core'],
		          	"gulungan"  	=> $row['gulungan'],
		          	"bahan"		  	=> $row['bahan'],
		          	"note"  		=> $row['note'],
		          	"subtotal"  	=> $subtotal,
		          	"tax"  			=> $tax,
		          	"total"  		=> $total,
		          	"input"  		=> $row['name'],
		        );

		        $result  = $success;
	      		$message = $qsuccess;
		    }
		}

  	} elseif ($action == 'periode_'.$slug){

	  	///////////////////////
		// Load data
		//////////////////////

		$dari = mysqli_real_escape_string($connect, $_GET['dari']);
		$sampai = mysqli_real_escape_string($connect, $_GET['sampai']);
	    
	    $query = "SELECT a.id AS id_po, a.po_date, a.nopo, a.note, a.ppn, a.input_by, b.vendor, c.id AS id_po_item, c.detail, c.size, c.price_1, c.price_2, c.qty, c.unit, c.merk, c.type, c.core, c.gulungan, c.bahan, d.id, d.name, e.company, f.isi FROM po_customer AS a LEFT JOIN vendor AS b ON a.id_vendor = b.id LEFT JOIN po_item AS c ON a.id = c.id_fk LEFT JOIN user AS d ON a.input_by = d.id LEFT JOIN company AS e ON e.id = a.id_company LEFT JOIN setting AS f ON f.id = a.type WHERE a.po_date BETWEEN '$dari' AND '$sampai' ORDER BY a.id ASC";
	    $sql = $connect->query($query);
	    
	    if(!$sql){
	      $result  = $error;
	      $message = $qerror;
	    } else {
	      	$no = 1;
		    while($row = $sql->fetch_array()){
		    	$date = $row['po_date'];
		    	$po_date = date("d/m/Y", strtotime($date));

		     	$functions = "<div class='function_buttons'><ul>";
		     	if($_SESSION['id'] == $row['input_by']){
		     		$functions .= "<li class='function_edit-customer UbahVendor'><a data-id='".$row['id_po']."' title='Ubah Vendor'><span>Ubah Vendor</span></a></li>";
		     		$functions .= "<li class='function_edit-item UbahItem'><a data-id='".$row['id_po_item']."' title='Ubah Item'><span>Ubah Item</span></a></li>";
		     		$functions .= '<li class="function_print PrintView"><a data-id="'.$row['id_po'].'" title="Print View"><span>Print</span></a></li>';
		     		$functions .= "<li class='function_delete HapusItem'><a data-id='".$row['id_po_item']."' data-name='".$row['detail']."' title='Hapus'><span>Hapus</span></a></li>";
			    } else {
			    	$functions .= "Not allowed";
			    }
			    $functions .= "</ul></div>";

			    if($row['price_2'] > 0){
		    		$subtotal = $row['qty'] * $row['price_2'];
		    	} else {
		    		$subtotal = $row['qty'] * $row['price_1'];
		    	}

		    	if($row['ppn'] > 0){ $tax = $subtotal/10; } else { $tax = 0; }
		    	$total = $subtotal + $tax;

		        $mysqli_data[] = array(
		          	"no"    		=> $no++,
		          	"po_date"     	=> $po_date,
		          	"company"    	=> $row['company'],
		          	"vendor"    	=> $row['vendor'],
		          	"nopo" 			=> $row['nopo'],
		          	"po_type" 		=> $row['isi'],
		          	"detail"  		=> $row['detail'],
		          	"size"  		=> $row['size'],
		          	"price_1"  		=> $row['price_1'],
		          	"price_2"  		=> $row['price_2'],
		          	"qty"  			=> $row['qty'],
		          	"unit"  		=> $row['unit'],
		          	"merk"  		=> $row['merk'],
		          	"type"  		=> $row['type'],
		          	"core"  		=> $row['core'],
		          	"gulungan"  	=> $row['gulungan'],
		          	"bahan"		  	=> $row['bahan'],
		          	"note"  		=> $row['note'],
		          	"subtotal"  	=> $subtotal,
		          	"tax"  			=> $tax,
		          	"total"  		=> $total,
		          	"input"  		=> $row['name'],
		          	"functions"     => $functions
		        );

		        $result  = $success;
	      		$message = $qsuccess;
		    }
		}

  	} elseif ($action == 'add_'.$slug){
  		$id_vendor = mysqli_real_escape_string($connect, $_POST['id_vendor']);
  		$id_company = mysqli_real_escape_string($connect, $_POST['company']);

  		if($id_vendor > 0 AND $id_company > 0){

  			////////////////////////////////////////////////////////////////////
		    // Add PO
		    ///////////////////////////////////////////////////////////////////

	  		$vendor = mysqli_real_escape_string($connect,$_POST['vendor']);
	  		$po_date = mysqli_real_escape_string($connect,$_POST['po_date']);
	  		$data = $_POST['data'];
	  		$ppns = mysqli_real_escape_string($connect,$_POST['ppns']);
	  		$note = mysqli_real_escape_string($connect,$_POST['note']);
	  		$po_type = mysqli_real_escape_string($connect,$_POST['po_type']);
	  		$month = date('n');
	  		$bulan = date('m');
	  		$year = date('y');
	  		$c_time = $year.''.$bulan;
	  		$waktu = '';
	  		$item_to = 0;
	  		$array = array();

	  		$select2 = "SELECT AUTO_INCREMENT AS ai FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME = 'po_customer'";
	  		$sql2 = $connect->query($select2);
	  		$fetch2 = $sql2->fetch_array();


	  		$select3 = "SELECT po_date, nopo FROM po_customer ORDER BY id DESC LIMIT 1";
	  		$sql3 = $connect->query($select3);
	  		$fetch3 = $sql3->fetch_array();
	  		$nomor = substr($fetch3['nopo'],7);

	  		$antrian = $nomor + 1;

	  		if($c_time > date("ym", strtotime($fetch3['po_date'])))
	  		{
	  			$waktu = 'PO '.$c_time.'1';
	  		
	  		} else {
	  			$waktu = 'PO '.date("ym", strtotime($fetch3['po_date'])).''.$antrian;
	  		}

	  		$insert1 = "INSERT INTO po_customer SET ";
	  		$insert1 .= "id_vendor	= '". $id_vendor ."',";
	  		$insert1 .= "id_company	= '". $id_company ."',";
	  		$insert1 .= "po_date	= '". $po_date ."',";
	  		$insert1 .= "nopo		= '". $waktu ."',";
	  		$insert1 .= "note		= '". $note ."',";
	  		$insert1 .= "ppn		= '". $ppns ."',";
	  		$insert1 .= "type		= '". $po_type ."',";
	  		$insert1 .= "input_by	= '". $_SESSION['id'] ."'";

	  		$insert2 = "INSERT INTO po_item (id_fk, item_to, detail, size, price_1, price_2, qty, unit, merk, type, core, gulungan, bahan) VALUES ";
	  		foreach($data['detail'] as $key => $value)
	  		{
	  			if(empty($data['detail'][$key]))	{ $id_fk = ''; } else { $id_fk = $fetch2['ai'];}
	  			if(empty($data['detail'][$key]))	{ $item_to = '';} else {$item_to = $item_to + 1;}
	  			if(empty($data['detail'][$key]))	{ $detail = '';} else {$detail = $data['detail'][$key];}
	  			if(empty($data['size'][$key]))	{ $size = '';} else {$size = $data['size'][$key];}
	  			if(empty($data['price_1'][$key])){ $price_1 = '0';} else {$price_1 = priceFilter($data['price_1'][$key]);}
	  			if(empty($data['price_2'][$key])){ $price_2 = '0';} else {$price_2 = priceFilter($data['price_2'][$key]);}
	  			if(empty($data['qty'][$key]))	{ $qty = '0';} else {$qty = $data['qty'][$key];}
	  			if(empty($data['unit'][$key]))	{ $unit = '';} else {$unit = $data['unit'][$key];}
	  			if(empty($data['merk'][$key]))	{ $merk = '';} else {$merk = $data['merk'][$key];}
	  			if(empty($data['type'][$key]))	{ $type = '';} else {$type = $data['type'][$key];}
	  			if(empty($data['core'][$key]))	{ $core = '';} else {$core = $data['core'][$key];}
	  			if(empty($data['gulungan'][$key]))	{ $gulungan = '';} else {$gulungan = $data['gulungan'][$key];}
	  			if(empty($data['bahan'][$key]))	{ $bahan = '';} else {$bahan = $data['bahan'][$key];}
	  			
	  			$array[] = "('".$id_fk."','".$item_to."','".$detail."','".$size."','".$price_1."','".$price_2."','".$qty."','".$unit."','".$merk."','".$type."','".$core."','".$gulungan."','".$Bahan."')";
	  		}
	  		$insert2 .= implode(',', $array);

	  		$sql3 = $connect->query($insert1);
	  		$sql4 = $connect->query($insert2);
	  		$logger = logger($connect,'Insert Purchase Order', 'Vendor Name: '.$vendor.' - PO No: '. $waktu .' - Date: '.date('Y-m-d'));

	  		if(!$sql3 OR !$sql4 OR !$logger){
		      $result  = $error;
		      $message = $qerror;
		    } else {
		      $result  = $success;
		      $message = $qsuccess;
		    }

  		} else {
  			$result  = 'invalid';
	      	$message = 'ID vendor tidak valid';
  		}

  	} elseif ($action == 'get_vendor_'.$slug){
		// Get data by id
    	if($id == ''){
      		$result  = $error;
      		$message = 'ID '.$missing;
    	} else {
    		$id = mysqli_real_escape_string($connect, $id);
      		$query = "SELECT a.vendor, b.id_vendor, b.id_company, b.nopo, b.po_date, b.note, b.type, b.ppn FROM vendor AS a LEFT JOIN po_customer AS b ON a.id = b.id_vendor WHERE b.id = '".$id."'";
      		$sql = $connect->query($query);
      		if(!$sql){
        		$result  = $error;
        		$message = $qerror;
      		} else {
        		$result  = $success;
        		$message = $qsuccess;
        		while($row = $sql->fetch_array()){
	          		$mysqli_data[] = array(
	          			"id_company"	=> $row['id_company'],
	          			"vendor"    	=> $row['vendor'],
	          			"id_vendor"   	=> $row['id_vendor'],
			          	"po_date"     	=> $row['po_date'],
			          	"po_type"     	=> $row['type'],
			          	"note"  		=> $row['note'],
			          	"ppn"  			=> $row['ppn']
	            	);
        		}
      		}
    	}
  
  	} elseif($action == 'edit_vendor_'.$slug){
		// Edit
    	if($id == ''){
      		$result  = $error;
      		$message = 'ID '.$missing;
    	} else {
    		$id = mysqli_real_escape_string($connect, $id);
    		$company = mysqli_real_escape_string($connect, $_GET['company']);
    		$vendor = mysqli_real_escape_string($connect, $_GET['vendor']);
    		$id_vendor = mysqli_real_escape_string($connect, $_GET['id_vendor']);
    		$po_date = mysqli_real_escape_string($connect, $_GET['po_date']);
    		$po_type = mysqli_real_escape_string($connect, $_GET['po_type']);
    		$note = mysqli_real_escape_string($connect, $_GET['note']);
    		$ppns = mysqli_real_escape_string($connect, $_GET['ppns']);

    		if($id_vendor > 0 AND $company > 0)
    		{
    			$update1 = "UPDATE po_customer SET ";
    			if (isset($_GET['id_vendor']))	{ $update1 .= "id_vendor	= '" . $id_vendor."',";}
    			if (isset($_GET['company']))	{ $update1 .= "id_company	= '" . $company."',";}
    			if (isset($_GET['po_date']))	{ $update1 .= "po_date		= '" . $po_date	."',";}
    			if (isset($_GET['po_type']))	{ $update1 .= "type			= '" . $po_type	."',";}
    			if (isset($_GET['note']))		{ $update1 .= "note			= '" . $note	."',";}
    			if (isset($_GET['ppns']))		{ $update1 .= "ppn			= '" . $ppns	."'";}
    			$update1 .= ' WHERE id = "'.$id.'"';

    			$sql1  = $connect->query($update1);
    			$logger = logger($connect,'Edit Purchase Order', 'Vendor Name: '.$vendor.' - Date: '.$po_date.' - PPN: '.$ppns.' - Note: '.$note);

    			if (!$sql1 OR !$logger){
	        		$result  = $error;
	        		$message = $qerror;
	      		} else {
	        		$result  = $success;
	        		$message = $qsuccess;
	      		}

    		} else {
    			$result  = 'invalid';
	        	$message = 'ID pelanggan tidak valid';
    		}
    	}

  	} elseif ($action == 'get_item_'.$slug){
		// Get data by id
    	if($id == ''){
      		$result  = $error;
      		$message = 'ID '.$missing;
    	} else {
    		$id = mysqli_real_escape_string($connect, $id);
      		$query = "SELECT a.*, c.value FROM po_item AS a LEFT JOIN po_customer AS b ON b.id = a.id_fk LEFT JOIN setting AS c ON c.id = b.type WHERE a.id = '".$id."'";
      		$sql = $connect->query($query);
      		if(!$sql){
        		$result  = $error;
        		$message = $qerror;
      		} else {
        		$result  = $success;
        		$message = $qsuccess;
        		$input = array();
        		while($row = $sql->fetch_array()){
	          		$mysqli_data['value'][] = array(
	          			"detail"    => $row['detail'],
			          	"size"     	=> $row['size'],
			          	"price_1"  	=> $row['price_1'],
			          	"price_2"  	=> $row['price_2'],
			          	"qty"  		=> $row['qty'],
			          	"unit"		=> $row['unit'],
			          	"merk"  	=> $row['merk'],
			          	"type"  	=> $row['type'],
			          	"core"  	=> $row['core'],
			          	"gulungan"  => $row['gulungan'],
			          	"bahan"		=> $row['bahan'],
	            	);

	            	$obj = json_decode($row['value']);
	            	$input = $obj->{'input'};
        		}

        		$mysqli_data['input'][] = array(
        			'attribute' => $input
        		);
      		}
    	}
  
  	} elseif($action == 'edit_item_'.$slug){
		// Edit item
    	if($id == ''){
      		$result  = $error;
      		$message = 'ID '.$missing;
    	} else {
    		$id = mysqli_real_escape_string($connect, $id);
    		$detail = mysqli_real_escape_string($connect, $_GET['detail']);
    		$size = mysqli_real_escape_string($connect, $_GET['size']);
    		$price_1 = mysqli_real_escape_string($connect, $_GET['price_1']);
    		$price_2 = mysqli_real_escape_string($connect, $_GET['price_2']);
    		$qty = mysqli_real_escape_string($connect, $_GET['qty']);
    		$unit = mysqli_real_escape_string($connect, $_GET['unit']);
    		$merk = mysqli_real_escape_string($connect, $_GET['merk']);
    		$type = mysqli_real_escape_string($connect, $_GET['type']);
    		$core = mysqli_real_escape_string($connect, $_GET['core']);
    		$gulungan = mysqli_real_escape_string($connect, $_GET['gulungan']);
    		$bahan = mysqli_real_escape_string($connect, $_GET['bahan']);

    		$update1 = "UPDATE po_item SET ";
    		if (isset($_GET['detail']))	{ $update1 .= "detail   = '" . $detail ."',";}
	    	if (isset($_GET['size']))	{ $update1 .= "size 	= '" . $size ."',";}
	    	if (isset($_GET['price_1'])) { $update1 .= "price_1 	= '" . priceFilter($price_1) ."',";}
	    	if (isset($_GET['price_2'])) { $update1 .= "price_2 	= '" . priceFilter($price_2) ."',";}
	    	if (isset($_GET['qty']))	{ $update1 .= "qty		= '" . $qty ."',";}
	    	if (isset($_GET['unit'])) 	{ $update1 .= "unit   	= '" . $unit ."',";}
	    	if (isset($_GET['merk'])) 	{ $update1 .= "merk   	= '" . $merk ."',";}
	    	if (isset($_GET['type'])) 	{ $update1 .= "type   	= '" . $type ."',";}
	    	if (isset($_GET['core'])) 	{ $update1 .= "core   	= '" . $core ."',";}
	    	if (isset($_GET['gulungan'])) 	{ $update1 .= "gulungan   	= '" . $gulungan ."',";}
	    	if (isset($_GET['bahan'])) 	{ $update1 .= "bahan   	= '" . $bahan ."'";}
	    	$update1 .= " WHERE id = '".$id."'";
	      		
      		$sql1  = $connect->query($update1);
      		$logger = logger($connect,'Edit Purchase Order (item)', 'Detail: '.$detail.' - Size: '.$size.' - Qty: '.$qty.' - Unit: '.$unit.' - Price: '.priceFilter($price_1).' - Price (secondary): '.priceFilter($price_2).' - Unit: '.$unit.' - Merk: '.$merk.' - Type: '.$type.' - Core: '.$core.' - Gulungan: '.$gulungan.' - Bahan: '.$bahan);

	    	if(!$sql1 OR !$logger){
	    		$result  = $error;
	    		$message = $qerror;
	  		} else {
	    		$result  = $success;
	    		$message = $qsuccess;
	  		}
    	}

  	} elseif ($action == 'del_'.$slug){
  		// Delete data
    	if($id == ''){
      		$result  = $error;
      		$message = 'ID '.$missing;
    	} else {
    		$data1 = array();
    		$data2 = array();
    		$id = mysqli_real_escape_string($connect, $id);
    		
    		$select = "SELECT COUNT(a.id) AS jml, a.id_fk, a.detail, a.size, a.qty, a.unit, a.price_1, a.price_2, a.merk, a.type, a.core, a.gulungan, a.bahan FROM po_item AS a WHERE a.id = '".$id."'";
    		$query = $connect->query($select);
    		$fetch = $query->fetch_array();

    		if($fetch['jml'] > 1){
    			$del = "DELETE FROM po_item WHERE id = '".$id."'";
    			$sql = $connect->query($del);
    			$logger = logger($connect,'Delete Purchase Order (item)', 'Detail: '.$fetch['detail'].' - Size: '.$fetch['size'].' - Qty: '.$fetch['qty'].' - Unit: '.$fetch['unit'].' - Price: '.priceFilter($fetch['price_1']).' - Price (Secondary): '.priceFilter($fetch['price_2']).' - Merk: '.$fetch['merk'].' - Type: '.$fetch['type'].' - Core: '.$fetch['core'].' - Gulungan: '.$fetch['gulungan'].' - Bahan: '.$fetch['bahan']);

    			if(!$sql OR !$logger){
		    		$result  = $error;
		    		$message = $qerror;
		  		} else {
		    		$result  = $success;
		    		$message = $qsuccess;
		  		}

    		} else {
    			$del1 = "DELETE FROM po_item WHERE id = '".$id."'";
    			$del2 = "DELETE FROM po_customer WHERE id = '".$fetch['id_fk']."'";
    			$sql1 = $connect->query($del1);
    			$sql2 = $connect->query($del2);
    			$logger = logger($connect,'Delete Purchase Order (item)', 'Detail: '.$fetch['detail'].' - Size: '.$fetch['size'].' - Qty: '.$fetch['qty'].' - Unit: '.$fetch['unit'].' - Price: '.priceFilter($fetch['price_1']).' - Price (Secondary): '.priceFilter($fetch['price_2']).' - Merk: '.$fetch['merk'].' - Type: '.$fetch['type'].' - Core: '.$fetch['core'].' - Gulungan: '.$fetch['gulungan'].' - Bahan: '.$fetch['bahan']);

    			if(!$sql1 OR !$sql2 OR !$logger){
		    		$result  = $error;
		    		$message = $qerror;
		  		} else {
		    		$result  = $success;
		    		$message = $qsuccess;
		  		}
    		}
    	}

  	} else if($action == 'get_print_'.$slug){
  		if($id == '')
  		{
  			$result = $error;
  			$message = 'ID '. $missing;
  		
  		} else {
  			$result  = $success;
		    $message = $qsuccess;
  			$input = array();
  			$select = "SELECT a.vendor, a.address, b.po_date, b.nopo, b.note, b.ppn, c.detail, c.size, c.price_1, c.price_2, c.qty, c.unit, c.merk, c.type, c.core, c.gulungan, c.bahan, d.isi, d.value FROM vendor AS a LEFT JOIN po_customer AS b ON a.id = b.id_vendor LEFT JOIN po_item AS c ON b.id = c.id_fk LEFT JOIN setting AS d ON d.id = b.type WHERE b.id = '".$id."'";
  			$sql = $connect->query($select);
  			while ($row = $sql->fetch_array()) {
  				$mysqli_data['value'][] = array(
  					'vendor'	=> $row['vendor'],
  					'address'	=> $row['address'],
  					'po_date'	=> $row['po_date'],
  					'po_vendor'	=> $row['nopo'],
  					'po_type'	=> strtoupper($row['isi']),
  					'note'		=> $row['note'],
  					'ppn'		=> $row['ppn'],
  					"detail"    => $row['detail'],
		          	"size"     	=> $row['size'],
		          	"price_1"  	=> $row['price_1'],
		          	"price_2"  	=> $row['price_2'],
		          	"qty"  		=> $row['qty'],
		          	"unit"		=> $row['unit'],
		          	"merk"  	=> $row['merk'],
		          	"type"  	=> $row['type'],
		          	"core"  	=> $row['core'],
		          	"gulungan"  => $row['gulungan'],
		          	"bahan"		=> $row['bahan'],
  					'ttd'		=> 'Iskandar Zulkarnain'//$_SESSION['name'],
  				);

  				$obj = json_decode($row['value']);
  				$input = $obj->{'input'};
  			}

  			$mysqli_data['input'][] = array(
  				'attribute' => $input,
  			);
  		}

  	} else if($action == 'print'){
  		if($id == '')
  		{
  			$result = $error;
  			$message = 'ID '. $missing;

  		} else {
  			$array = array();
  			$select = "SELECT a.vendor, a.address, b.po_date, b.nopo, b.note, b.ppn, c.detail, c.size, c.price_1, c.price_2, c.qty, c.unit, c.item_to, d.company, d.address AS alamat, d.email, d.phone, d.logo, e.value FROM vendor AS a LEFT JOIN po_customer AS b ON a.id = b.id_vendor LEFT JOIN po_item AS c ON b.id = c.id_fk LEFT JOIN company AS d ON d.id = b.id_company LEFT JOIN setting AS e ON e.id = b.type WHERE b.id = '".$id."'";
  			$sql = $connect->query($select);
  			while($row = $sql->fetch_array()){

  				if($row['price_2'] > 0)
  				{
  					$subtotal = $row['price_2']*$row['qty'];
  				} else {
  					$subtotal = $row['price_1']*$row['qty'];
  				}

  				if($row['ppn'] > 0){ $tax = $subtotal/10; } else { $tax = 0; }
  				$total = $subtotal + $tax;
  				$obj = json_decode($row['value']);

  				$array['vendor']	= $row['vendor'];
  				$array['address']	= $row['address'];
  				$array['po_date']	= date("d F Y", strtotime($row['po_date']));
  				$array['nopo']		= $row['nopo'];
  				$array['note']		= $row['note'];
  				$array['ppn']		= $row['ppn'];
  				$array['detail'][]	= $row['detail'];
  				$array['size'][]	= $row['size'];
  				$array['price_1'][]	= $row['price_1'];
  				$array['price_2'][]	= $row['price_2'];
  				$array['qty'][]		= $row['qty'];
  				$array['unit'][]	= $row['unit'];
  				$array['ttd']		= mysqli_real_escape_string($connect, $_POST['tanda_tangan']);
  				$array['item_to'][]	= $row['item_to'];
  				$array['tgl']		= date('d F Y');
  				$array['ppn']		= $row['ppn'];
  				$array['subtotal'][]= $subtotal;
  				$array['tax'][]		= $tax;
  				$array['total'][]	= $total;
  				$array['company']	= strtoupper($row['company']);
  				$array['alamat']	= $row['alamat'];
  				$array['email']		= $row['email'];
  				$array['phone']		= $row['phone'];
  				$array['logo']		= $row['logo'];
  				$array['print']		= $obj->{'print'};
  			}

  			$mysqli_data['value'][] = array(
				'vendor'		=> $array['vendor'],
				'address'		=> $array['address'],
				'po_date'		=> $array['po_date'],
				'nopo'			=> $array['nopo'],
				'note'			=> $array['note'],
				'ppn'			=> $array['ppn'],
				'detail'		=> $array['detail'],
				'size'			=> $array['size'],
				'price_1'		=> $array['price_1'],
				'price_2'		=> $array['price_2'],
				'qty'			=> $array['qty'],
				'unit'			=> $array['unit'],
				'ttd'			=> $array['ttd'],
				'item_to'		=> $array['item_to'],
				'tgl'			=> $array['tgl'],
				'ppn'			=> $array['ppn'],
				'ttl_price_item'=> $array['subtotal'],
				'subtotal'		=> array_sum($array['subtotal']),
				'tax'			=> array_sum($array['tax']),
				'total'			=> array_sum($array['total']),
				'company'		=> $array['company'],
				'alamat'		=> $array['alamat'],
				'email'			=> $array['email'],
				'phone'			=> $array['phone'],
				'logo'			=> $array['logo'],
			);

			$mysqli_data['print'][] = array(
				'attribute' => $array['print']
			);

  			$result  = $success;
		    $message = $qsuccess;
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