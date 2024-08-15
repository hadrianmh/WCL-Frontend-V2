<?php
session_start();

/////////////////////////////
// Personal config DataTables 
////////////////////////////

require_once '../dashboard/session.php';
require_once 'connect.php';
$tabel1 = 'preorder_customer';
$tabel2 = 'workorder_customer';
$tabel3 = 'delivery_orders_customer';
$tabel4 = 'invoice';
$tabel5 = 'status';
$tabel6 = "preorder_item";
$tabel7 = "preorder_price";
$tabel8 = "workorder_item";
$tabel9 = "delivery_orders_item";
$Query = 'action';
$slug = 'dashboard';

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
if(isset($_GET[$Query])){
  $action = $_GET[$Query];
    if($action == 'sortdata_'.$slug || $action == 'statistik_'.$slug || $action == 'result_'.$slug || $action == 'periode_'.$slug || $action == 'statistik_periode_'.$slug){
      
    } else {
      $action = '';
    }
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
      $year_list = '';
      $tahun = array();
      $array = array();
      while($row = $sql->fetch_array()){
        $montly = date("Y/m", strtotime($row['po_date']));
        $year = date("Y", strtotime($row['po_date']));
        if(!isset($montly_list[$montly])){
          $montly_list[$montly] = 1;
          $array['montly'][] = $montly;
        }
        if(!isset($year_list[$year])){
          $year_list[$year] = 1;
          $array['year'][] = $year;
        }
      }

      $mysqli_data[] = array(
        'montly'  => $array['montly'],
        'year'    => $array['year'],
      );
    }

  } elseif($action == 'statistik_'.$slug){
    $curMonth = str_replace('/', '-', $_GET['curMonth']);

    $select1 = "SELECT count(id) AS jml_po FROM $tabel1 WHERE po_date LIKE '$curMonth%'";
    //$select2 = "SELECT count(a.id) as jml_wo FROM $tabel2 AS a LEFT JOIN $tabel5 AS b ON a.id_fk = b.id_fk WHERE a.spk_date LIKE '$curMonth%' AND b.order_status >= 5 GROUP BY a.id_fk";
    $select3 = "SELECT count(a.id) as jml_do FROM $tabel3 AS a LEFT JOIN $tabel5 AS b ON a.id_fk = b.id_fk WHERE a.sj_date LIKE '$curMonth%' AND b.order_status >= 6 GROUP BY a.id_fk";
    $select4 = "SELECT count(DISTINCT no_invoice) as jml_in FROM $tabel4 WHERE invoice_date LIKE '$curMonth%' AND status = 1";

    $sql1 = $connect->query($select1);
    //$sql2 = $connect->query($select2);
    $sql3 = $connect->query($select3);
    $sql4 = $connect->query($select4);

    if (!$sql1 OR !$sql3 OR !$sql4){
      $result  = $error;
      $message = $qerror;
    } else {
      $result  = $success;
      $message = $qsuccess;

      $fetch1 = $sql1->fetch_array();
      $fetch4 = $sql4->fetch_array();

      $mysqli_data[] = array(
        "jml_po"  => $fetch1['jml_po'],
        //"jml_wo"  => $sql2->num_rows,
        "jml_do"  => $sql3->num_rows,
        "jml_in"  => $fetch4['jml_in']
      );
    }

  } elseif($action == 'result_'.$slug) {
    $curMonth = str_replace('/', '-', $_GET['curMonth']);
    $select = "SELECT a.item, a.price, a.qty AS req_qty, a.unit, b.id_fk, b.order_grade, b.customer, b.po_customer, b.po_date, c.ppn, d.no_so, d.item, d.size, d.unit, d.qore, d.lin, d.roll, d.ingredient, d.volume, d.porporasi, d.annotation, d.uk_bahan_baku, d.qty_bahan_baku, d.sources, d.merk, d.type, e.spk_date, f.no_delivery, f.send_qty, g.id_fk, g.id_sj, g.sj_date, g.courier, g.no_tracking, g.cost, h.order_status, i.company, j.isi FROM $tabel6 AS a LEFT JOIN $tabel1 AS b ON b.id_fk = a.id_fk LEFT JOIN $tabel7 AS c ON c.id_fk = b.id_fk LEFT JOIN $tabel8 AS d ON d.id_fk = a.id_fk AND d.item_to = a.item_to LEFT JOIN $tabel2 AS e ON e.id_fk = b.id_fk LEFT JOIN $tabel9 AS f ON f.id_fk = a.id_fk AND f.item_to = a.item_to LEFT JOIN $tabel3 AS g ON g.id_fk = b.id_fk AND g.id_sj = f.id_sj LEFT JOIN $tabel5 AS h ON h.id_fk = a.id_fk AND h.item_to = a.item_to LEFT JOIN company AS i ON i.id = b.id_company LEFT JOIN setting AS j ON j.id = d.detail WHERE b.po_date LIKE '$curMonth%' ORDER BY b.id, f.no_delivery ASC";
    $sql = $connect->query($select);

    print_r($select);

    if(!$sql){
      $result  = $error;
      $message = $qerror;
    } else {
      $result  = $success;
      $message = $qsuccess;
      $no = 1;
      $data_cost = array();
      while($row = $sql->fetch_array()){
        //Harga sementara
        $price_before = $row['send_qty']*$row['price'];
        //PPN
        if($row['ppn'] > 0){ $ppn = $price_before/10; } else { $ppn = 0; }
        //Harga total
        $total = $price_before + $ppn;
        //Menentukan order status
        if($row['order_grade'] == '0'){$order_grade = 'Reguler';} else {$order_grade = 'Spesial';}
        //konversikan tanggal
        $newDate1 = date("d/m/Y", strtotime($row['po_date']));
        $etd = date('d/m/Y', strtotime($row['po_date']. '+16 day'));
        if($row['spk_date'] == '0000-00-00'){$newDate2 = '';} else {$newDate2 = date("d/m/Y", strtotime($row['spk_date']));}
        if(empty($row['sj_date'])){$newDate3 = '';} else {$newDate3 = date("d/m/Y", strtotime($row['sj_date']));}
        //Filter No SO
        $ex_no_so = explode("/", $row['no_so']);

        if($row['porporasi'] > 0){ $porporasi = 'YA'; } else { $porporasi = 'TIDAK'; }

        $ex_sources = explode("|", $row['sources']);
        if($ex_sources[0] == 1){
          $sources = 'Internal';
        } elseif($ex_sources[0] == 2){
          $sources = 'SUBCONT ('.$ex_sources[1].', '.date("d-M-Y", strtotime($ex_sources[2])).')';
        } elseif($ex_sources[0] == 3){
          $sources = 'IN STOCK ('.$ex_sources[1].' '.$row['unit'].')';
        }

        if(!in_array($row['id_fk'].'-'.$row['id_sj'].'-'.$row['cost'], $data_cost)){
          $biaya_kirim = $row['cost'];
        } else {
          $biaya_kirim = '';
        }

        if(empty($row['send_qty'])){ $remarks = ''; } else { $remarks = $row['send_qty']." ".$row['unit']; }

        $data_cost[] = $row['id_fk'].'-'.$row['id_sj'].'-'.$row['cost'];

        if($row['send_qty'] !== '0')
        {
        	//Menentukan order status setiap item PO
        	if($row['send_qty'] > 0)
        	{
        		$order_status = 'Delivery';

        	} else {

		        if($row['order_status'] == '0'){
		          $order_status = 'PO baru dibuat';
		        } elseif($row['order_status'] == '1' || $row['order_status'] == '2'){
		          $order_status = 'Delivery';
		        } elseif($row['order_status'] == '3'){
		          $order_status = 'Packing';
		        } elseif($row['order_status'] == '4'){
		          $order_status = 'Cetak SPK';
		        } elseif($row['order_status'] == '5'){
		          $order_status = 'Pembuatan Pisau';
		         } elseif($row['order_status'] == '6'){
          $order_status = 'Antri Sliting';
        } elseif($row['order_status'] == '7'){
          $order_status = 'Antri Cetak';
		} elseif($row['order_status'] == '8'){
          $order_status = 'Proses Cetak';
	    } elseif($row['order_status'] == '9'){
          $order_status ='Proses Bahan Baku';
		  } elseif($row['order_status'] == '10'){
          $order_status ='Proses Film';
		  } elseif($row['order_status'] == '11'){
          $order_status ='Proses Toyobo';
		  } elseif($row['order_status'] == '12'){
          $order_status ='Proses ACC';
		  } elseif($row['order_status'] == '13'){
          $order_status ='Proses Sliting';
		  } elseif($row['order_status'] == '14'){
          $order_status ='Reture';
		  } elseif($row['order_status'] == '15'){
          $order_status ='Proses Sample';
		  } elseif($row['order_status'] == '16'){
          $order_status ='Input PO';
		        }
        	}
        	
        	$mysqli_data[] = array(
	            'no'                 => $no++,
	            'company'            => $row['company'],
	            'order_grade'        => $order_grade,
	            'no_spk'             => $ex_no_so[0]."/".$ex_no_so[1].$ex_no_so[2],
	            'so_date'            => $newDate1,
	            'etd'                => $etd,
	            'customer'           => $row['customer'],
	            'po_customer'        => $row['po_customer'],
	            'po_date'            => $newDate1,
	            'item'               => $row['item'],
	            'detail'             => $row['isi'],
	            'merk'               => $row['merk'],
	            'type'               => $row['type'],
	            "size"               => $row['size'],
	            "qore"               => $row['qore'],
	            "line"               => $row['lin'],
	            "roll"               => $row['roll'],
	            "ingredient"         => $row['ingredient'],
	            "porporasi"          => $porporasi,
	            "qty"                => $row['req_qty'],
	            'unit'               => $row['unit'],
	            "volume"             => $row['volume'],
	            "uk_bahan"           => $row['uk_bahan_baku'],
	            "qty_bahan"          => $row['qty_bahan_baku'],
	            "annotation"         => $row['annotation'],
	            "sources"            => $sources,
	            'price'              => $row['price'],
	            'price_before'       => $price_before,
	            'tax'                => $ppn,
	            'total'              => $total,
	            'spk_date'           => $newDate2,
	            'order_status'       => $order_status,
	            'no_delivery'        => $row['no_delivery'],
	            'sj_date'            => $newDate3,
	            'courier'            => $row['courier'],
	            'no_tracking'        => $row['no_tracking'],
	            'send_qty'           => $remarks,
	            'cost'               => $biaya_kirim,
	        );
        }
      }
    }

  } elseif($action == 'periode_'.$slug) {
    $dari = mysqli_real_escape_string($connect, $_GET['dari']);
    $sampai = mysqli_real_escape_string($connect, $_GET['sampai']);

    $select = "SELECT a.item, a.price, a.qty AS req_qty, a.unit, b.order_grade, b.customer, b.po_customer, b.po_date, c.ppn, d.no_so, d.item, d.size, d.unit, d.qore, d.lin, d.roll, d.ingredient, d.volume, d.porporasi, d.annotation, d.uk_bahan_baku, d.qty_bahan_baku, d.sources, d.merk, d.type, e.spk_date, f.no_delivery, f.send_qty, g.id_fk, g.id_sj, g.sj_date, g.courier, g.no_tracking, g.cost, h.order_status, i.company, j.isi FROM $tabel6 AS a LEFT JOIN $tabel1 AS b ON b.id_fk = a.id_fk LEFT JOIN $tabel7 AS c ON c.id_fk = b.id_fk LEFT JOIN $tabel8 AS d ON d.id_fk = a.id_fk AND d.item_to = a.item_to LEFT JOIN $tabel2 AS e ON e.id_fk = b.id_fk LEFT JOIN $tabel9 AS f ON f.id_fk = a.id_fk AND f.item_to = a.item_to LEFT JOIN $tabel3 AS g ON g.id_fk = b.id_fk AND g.id_sj = f.id_sj LEFT JOIN $tabel5 AS h ON h.id_fk = a.id_fk AND h.item_to = a.item_to LEFT JOIN company AS i ON i.id = b.id_company LEFT JOIN setting AS j ON j.id = d.detail WHERE b.po_date BETWEEN '$dari' AND '$sampai' ORDER BY b.id, f.no_delivery ASC";
    $sql = $connect->query($select);

    if(!$sql){
      $result  = $error;
      $message = $qerror;
    } else {
    	$result  = $success;
    	$message = $qsuccess;
    	$no = 1;
    	$data_cost = array();
    	while($row = $sql->fetch_array())
    	{
    		//Harga sementara
	        $price_before = $row['send_qty']*$row['price'];
	        //PPN
	        if($row['ppn'] > 0){ $ppn = $price_before/10; } else { $ppn = 0; }
	        //Harga total
	        $total = $price_before + $ppn;
	        //Menentukan order status
	        if($row['order_grade'] == '0'){$order_grade = 'Reguler';} else {$order_grade = 'Spesial';}
	        
	        //konversikan tanggal
	        $newDate1 = date("d/m/Y", strtotime($row['po_date']));
	        $etd = date('d/m/Y', strtotime($row['po_date']. '+16 day'));
	        if($row['spk_date'] == '0000-00-00'){$newDate2 = '';} else {$newDate2 = date("d/m/Y", strtotime($row['spk_date']));}
	        if(empty($row['sj_date'])){$newDate3 = '';} else {$newDate3 = date("d/m/Y", strtotime($row['sj_date']));}
	        //Filter No SO
	        $ex_no_so = explode("/", $row['no_so']);

	        if($row['porporasi'] > 0){ $porporasi = 'YA'; } else { $porporasi = 'TIDAK'; }

	        $ex_sources = explode("|", $row['sources']);
	        if($ex_sources[0] == 1){
	          $sources = 'Internal';
	        } elseif($ex_sources[0] == 2){
	          $sources = 'SUBCONT ('.$ex_sources[1].', '.date("d-M-Y", strtotime($ex_sources[2])).')';
	        } elseif($ex_sources[0] == 3){
	          $sources = 'IN STOCK ('.$ex_sources[1].' '.$row['unit'].')';
	        }

	        if(!in_array($row['id_fk'].'-'.$row['id_sj'].'-'.$row['cost'], $data_cost)){
	          $biaya_kirim = $row['cost'];
	        } else {
	          $biaya_kirim = '';
	        }

	        if(empty($row['send_qty'])){ $remarks = ''; } else { $remarks = $row['send_qty']." ".$row['unit']; }

	        $data_cost[] = $row['id_fk'].'-'.$row['id_sj'].'-'.$row['cost'];

	        if($row['send_qty'] !== '0')
	        {
	        	//Menentukan order status setiap item PO
	        	if($row['send_qty'] > 0)
	        	{
	        		$order_status = 'Delivery';

	        	} else {

			        if($row['order_status'] == '0'){
			          $order_status = 'PO baru dibuat';
			        } elseif($row['order_status'] == '1' || $row['order_status'] == '2'){
			          $order_status = 'Delivery';
			        } elseif($row['order_status'] == '3'){
			          $order_status = 'Packing';
			        } elseif($row['order_status'] == '4'){
			          $order_status = 'Cetak SPK';
			        } elseif($row['order_status'] == '5'){
			          $order_status = 'Pembuatan Pisau';
			         } elseif($row['order_status'] == '6'){
          $order_status = 'Antri Sliting';
        } elseif($row['order_status'] == '7'){
          $order_status = 'Antri Cetak';
		} elseif($row['order_status'] == '8'){
          $order_status = 'Proses Cetak';
	    } elseif($row['order_status'] == '9'){
          $order_status ='Proses Bahan Baku';
		  } elseif($row['order_status'] == '10'){
          $order_status ='Proses Film';
		  } elseif($row['order_status'] == '11'){
          $order_status ='Proses Toyobo';
		  } elseif($row['order_status'] == '12'){
          $order_status ='Proses ACC';
		  } elseif($row['order_status'] == '13'){
          $order_status ='Proses Sliting';
		  } elseif($row['order_status'] == '14'){
          $order_status ='Reture';
		  } elseif($row['order_status'] == '15'){
          $order_status ='Proses Sample';
		  } elseif($row['order_status'] == '16'){
          $order_status ='Input PO';
			        }
	        	}
	        	
	        	$mysqli_data[] = array(
		            'no'                 => $no++,
		            'company'            => $row['company'],
		            'order_grade'        => $order_grade,
		            'no_spk'             => $ex_no_so[0]."/".$ex_no_so[1].$ex_no_so[2],
		            'so_date'            => $newDate1,
		            'etd'                => $etd,
		            'customer'           => $row['customer'],
		            'po_customer'        => $row['po_customer'],
		            'po_date'            => $newDate1,
		            'item'               => $row['item'],
		            'detail'             => $row['isi'],
		            'merk'               => $row['merk'],
		            'type'               => $row['type'],
		            "size"               => $row['size'],
		            "qore"               => $row['qore'],
		            "line"               => $row['lin'],
		            "roll"               => $row['roll'],
		            "ingredient"         => $row['ingredient'],
		            "porporasi"          => $porporasi,
		            "qty"                => $row['req_qty'],
		            'unit'               => $row['unit'],
		            "volume"             => $row['volume'],
		            "uk_bahan"           => $row['uk_bahan_baku'],
		            "qty_bahan"          => $row['qty_bahan_baku'],
		            "annotation"         => $row['annotation'],
		            "sources"            => $sources,
		            'price'              => $row['price'],
		            'price_before'       => $price_before,
		            'tax'                => $ppn,
		            'total'              => $total,
		            'spk_date'           => $newDate2,
		            'order_status'       => $order_status,
		            'no_delivery'        => $row['no_delivery'],
		            'sj_date'            => $newDate3,
		            'courier'            => $row['courier'],
		            'no_tracking'        => $row['no_tracking'],
		            'send_qty'           => $remarks,
		            'cost'               => $biaya_kirim,
		        );
	        }
	    }
    }

  } elseif($action == 'statistik_periode_'.$slug){
    $dari = mysqli_real_escape_string($connect, $_GET['dari']);
    $sampai = mysqli_real_escape_string($connect, $_GET['sampai']);

    $select1 = "SELECT count(id) AS jml_po FROM $tabel1 WHERE po_date BETWEEN '$dari' AND '$sampai'";
    //$select2 = "SELECT count(a.id) as jml_wo FROM $tabel2 AS a LEFT JOIN $tabel5 AS b ON a.id_fk = b.id_fk WHERE a.spk_date LIKE '$curMonth%' AND b.order_status >= 5 GROUP BY a.id_fk";
    $select3 = "SELECT count(a.id) as jml_do FROM $tabel3 AS a LEFT JOIN $tabel5 AS b ON a.id_fk = b.id_fk WHERE b.order_status >= 6 AND a.sj_date BETWEEN '$dari' AND '$sampai' GROUP BY a.id_fk";
    $select4 = "SELECT count(DISTINCT no_invoice) as jml_in FROM $tabel4 WHERE status = 1 AND invoice_date BETWEEN '$dari' AND '$sampai'";

    $sql1 = $connect->query($select1);
    //$sql2 = $connect->query($select2);
    $sql3 = $connect->query($select3);
    $sql4 = $connect->query($select4);

    if (!$sql1 OR !$sql3 OR !$sql4){
      $result  = $error;
      $message = $qerror;
    } else {
      $result  = $success;
      $message = $qsuccess;

      $fetch1 = $sql1->fetch_array();
      $fetch4 = $sql4->fetch_array();

      $mysqli_data[] = array(
        "jml_po"  => $fetch1['jml_po'],
        //"jml_wo"  => $sql2->num_rows,
        "jml_do"  => $sql3->num_rows,
        "jml_in"  => $fetch4['jml_in']
      );
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