	<?php

/////////////////////////////
// Personal config DataTables 
////////////////////////////
require '../dashboard/session.php';
require 'connect.php';
require 'history.php';
$Query = 'action';
$slug = 'aging';

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
		$query = "SELECT DISTINCT invoice_date FROM invoice ORDER BY invoice_date DESC";
		$sql = $connect->query($query);

		if(!$sql){
			$result  = $error;
			$message = $qerror;
		} else {
			$result  = $success;
			$message = $qsuccess;

			$montly_list = '';

			while($row = $sql->fetch_array()){
				$montly = date("Y/m", strtotime($row['invoice_date']));
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
	    
	    $query = "SELECT b.customer, g.company, a.no_invoice, GROUP_CONCAT(DISTINCT(c.no_delivery) SEPARATOR ' - ') AS no_delivery, GROUP_CONCAT(DISTINCT(CONCAT(SUBSTRING_INDEX(d.no_so,'/',2),SUBSTRING_INDEX(d.no_so,'/',-1))) SEPARATOR ' - ') AS no_so, b.po_customer, a.invoice_date, a.duration AS duedate, SUM(c.send_qty * e.price) AS subtotal, f.ppn, a.complete_date, a.status, SUM(DISTINCT(h.cost)) AS cost, a.note FROM invoice AS a LEFT JOIN preorder_customer AS b ON b.id_fk = a.id_fk LEFT JOIN delivery_orders_item AS c ON c.id_fk = a.id_fk AND c.id_sj = a.id_sj LEFT JOIN workorder_item AS d ON d.id_fk = c.id_fk AND d.item_to = c.item_to LEFT JOIN preorder_item AS e ON e.id_fk = c.id_fk AND e.item_to = c.item_to LEFT JOIN preorder_price AS f ON f.id_fk = a.id_fk LEFT JOIN company AS g ON g.id = b.id_company LEFT JOIN delivery_orders_customer AS h ON h.id_fk = a.id_fk AND h.id_sj = a.id_sj WHERE a.invoice_date LIKE '$curMonth%' GROUP BY a.no_invoice ORDER BY a.id DESC";
	    $sql = $connect->query($query);
	    
	    if(!$sql){
	      $result  = $error;
	      $message = $qerror;
	    } else {
	    	$no = 1;
		    while($row = $sql->fetch_array()){
		    	$date = date("d/m/Y", strtotime($row['invoice_date']));
		    	$duedate = date("d/m/Y", strtotime($row['duedate']));
		    	if(empty($row['complete_date'])){
		    		$complete_date = '';
		    	} else { $complete_date = date("d/m/Y", strtotime($row['complete_date']));}

		    	if($row['ppn'] > 0 )
		    	{
		    		$ppn = $row['subtotal'] / 10;
		    		$amount = $row['subtotal'] + $ppn;

		    	} else { $amount = $row['subtotal']; }

		        $mysqli_data[] = array(
		          	"no"    		=> $no++,
		          	"customer"     	=> $row['customer'],
		          	"company"    	=> $row['company'],
		          	"invoice"    	=> $row['no_invoice'],
		          	"nosj" 			=> $row['no_delivery'],
		          	"noso" 			=> $row['no_so'],
		          	"nopo"  		=> $row['po_customer'],
		          	"date"  		=> $date,
		          	"duedate"  		=> $duedate,
		          	"amount"  		=> rupiah($amount),
		          	"complete_date" => $complete_date,
		          	"annotation"  	=> $row['note'],
		          	"ongkir"  		=> rupiah($row['cost']),
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
	    
	    $query = "SELECT b.customer, g.company, a.no_invoice, GROUP_CONCAT(DISTINCT(c.no_delivery) SEPARATOR ' - ') AS no_delivery, GROUP_CONCAT(DISTINCT(CONCAT(SUBSTRING_INDEX(d.no_so,'/',2),SUBSTRING_INDEX(d.no_so,'/',-1))) SEPARATOR ' - ') AS no_so, b.po_customer, a.invoice_date, a.duration AS duedate, SUM(c.send_qty * e.price) AS subtotal, f.ppn, a.complete_date, a.status, SUM(DISTINCT(h.cost)) AS cost, a.note FROM invoice AS a LEFT JOIN preorder_customer AS b ON b.id_fk = a.id_fk LEFT JOIN delivery_orders_item AS c ON c.id_fk = a.id_fk AND c.id_sj = a.id_sj LEFT JOIN workorder_item AS d ON d.id_fk = c.id_fk AND d.item_to = c.item_to LEFT JOIN preorder_item AS e ON e.id_fk = c.id_fk AND e.item_to = c.item_to LEFT JOIN preorder_price AS f ON f.id_fk = a.id_fk LEFT JOIN company AS g ON g.id = b.id_company LEFT JOIN delivery_orders_customer AS h ON h.id_fk = a.id_fk AND h.id_sj = a.id_sj WHERE a.invoice_date LIKE '$curMonth%' GROUP BY a.no_invoice ORDER BY a.id ASC";
	    $sql = $connect->query($query);
	    
	    if(!$sql){
	      $result  = $error;
	      $message = $qerror;
	    } else {
	      	$no = 1;
		    while($row = $sql->fetch_array()){
		    	$date = date("d/m/Y", strtotime($row['invoice_date']));
		    	$duedate = date("d/m/Y", strtotime($row['duedate']));

		    	if(empty($row['complete_date'])){
		    		$complete_date = '';
		    	} else { $complete_date = date("d/m/Y", strtotime($row['complete_date']));}

		    	if($row['ppn'] > 0 )
		    	{
		    		$ppn = $row['subtotal'] / 10;
		    		$amount = $row['subtotal'] + $ppn;

		    	} else { $amount = $row['subtotal']; }

		        $mysqli_data[] = array(
		          	"no"    		=> $no++,
		          	"customer"     	=> $row['customer'],
		          	"company"    	=> $row['company'],
		          	"invoice"    	=> $row['no_invoice'],
		          	"nosj" 			=> $row['no_delivery'],
		          	"noso" 			=> $row['no_so'],
		          	"nopo"  		=> $row['po_customer'],
		          	"date"  		=> $date,
		          	"duedate"  		=> $duedate,
		          	"amount"  		=> $amount,
		          	"complete_date" => $complete_date,
		          	"annotation"  	=> $row['note'],
		          	"ongkir"  		=> $row['cost'],
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
	    
	    $query = "SELECT b.customer, g.company, a.no_invoice, GROUP_CONCAT(DISTINCT(c.no_delivery) SEPARATOR ' - ') AS no_delivery, GROUP_CONCAT(DISTINCT(CONCAT(SUBSTRING_INDEX(d.no_so,'/',2),SUBSTRING_INDEX(d.no_so,'/',-1))) SEPARATOR ' - ') AS no_so, b.po_customer, a.invoice_date, a.duration AS duedate, SUM(c.send_qty * e.price) AS subtotal, f.ppn, a.complete_date, a.status, SUM(DISTINCT(h.cost)) AS cost, a.note FROM invoice AS a LEFT JOIN preorder_customer AS b ON b.id_fk = a.id_fk LEFT JOIN delivery_orders_item AS c ON c.id_fk = a.id_fk AND c.id_sj = a.id_sj LEFT JOIN workorder_item AS d ON d.id_fk = c.id_fk AND d.item_to = c.item_to LEFT JOIN preorder_item AS e ON e.id_fk = c.id_fk AND e.item_to = c.item_to LEFT JOIN preorder_price AS f ON f.id_fk = a.id_fk LEFT JOIN company AS g ON g.id = b.id_company LEFT JOIN delivery_orders_customer AS h ON h.id_fk = a.id_fk AND h.id_sj = a.id_sj WHERE a.invoice_date BETWEEN '$dari' AND '$sampai' GROUP BY a.no_invoice ORDER BY a.id ASC";
	    $sql = $connect->query($query);
	    
	    if(!$sql){
	      $result  = $error;
	      $message = $qerror;
	    } else {
	    	$no = 1;
		    while($row = $sql->fetch_array()){
		    	$date = date("d/m/Y", strtotime($row['invoice_date']));
		    	$duedate = date("d/m/Y", strtotime($row['duedate']));
		    	if(empty($row['complete_date'])){
		    		$complete_date = '';
		    	} else { $complete_date = date("d/m/Y", strtotime($row['complete_date']));}

		    	if($row['ppn'] > 0 )
		    	{
		    		$ppn = $row['subtotal'] / 10;
		    		$amount = $row['subtotal'] + $ppn;

		    	} else { $amount = $row['subtotal']; }

		        $mysqli_data[] = array(
		          	"no"    		=> $no++,
		          	"customer"     	=> $row['customer'],
		          	"company"    	=> $row['company'],
		          	"invoice"    	=> $row['no_invoice'],
		          	"nosj" 			=> $row['no_delivery'],
		          	"noso" 			=> $row['no_so'],
		          	"nopo"  		=> $row['po_customer'],
		          	"date"  		=> $date,
		          	"duedate"  		=> $duedate,
		          	"amount"  		=> $amount,
		          	"complete_date" => $complete_date,
		          	"annotation"  	=> $row['note'],
		          	"ongkir"  		=> $row['cost'],
		        );

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