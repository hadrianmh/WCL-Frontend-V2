<?php

function rupiah($angka){
  $output = "Rp. ".number_format($angka, 2, ',', '.');
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
$tabel9 = 'user';
$tabel10 = 'workorder_item';
$tabel11 = 'customer';
$Query = 'action';
$slug = 'invoice_procces';

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
    $action == 'get_'.$slug ||
    $action == 'print' ||
    $action == 'complete' ||
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

$mysqli_data = array();

if($action != ''){
	///////////////////////
	// Execute all action
	//////////////////////

	if ($action == 'result_'.$slug){

    ///////////////////////////
    // Get pre order data
    ///////////////////////////
    
    $query = "SELECT a.id_fk, a.id_sj, a.no_delivery, a.send_qty, b.id, b.print, b.no_invoice, b.invoice_date, b.duration, b.input_by, c.price, c.unit, d.no_so, e.customer, e.po_customer, f.sj_date, f.cost, f.ekspedisi, f.uom, f.jml, g.ppn, i.name FROM delivery_orders_item AS a LEFT JOIN invoice AS b ON b.id_fk = a.id_fk AND b.id_sj = a.id_sj LEFT JOIN preorder_item AS c ON c.id_fk = a.id_fk AND c.item_to = a.item_to LEFT JOIN workorder_item AS d ON d.id_fk = a.id_fk AND d.item_to = a.item_to LEFT JOIN preorder_customer AS e ON e.id_fk = a.id_fk LEFT JOIN delivery_orders_customer AS f ON f.id_fk = a.id_fk AND f.id_sj = a.id_sj LEFT JOIN preorder_price AS g ON g.id_fk = a.id_fk LEFT JOIN status AS h ON h.id_fk = a.id_fk AND h.item_to = a.item_to LEFT JOIN user AS i ON i.id = b.input_by WHERE h.order_status BETWEEN 1 AND 2 AND b.status = 0 AND b.duration >= '".date('Y-m-d')."' AND b.id IS NOT NULL ORDER BY a.id DESC";
    $sql = $connect->query($query);
    if(!$sql){
      $result  = $error;
      $message = $qerror;
    } else {
      $result  = $success;
    	$message = $qsuccess;
    	$no = 1;

      $data_cost = array();
      $data_print = array();
      $data_name = array();
      $data_invoice = array();
      while($row = $sql->fetch_array()){
        $invoice_date = date("d/m/Y", strtotime($row['invoice_date']));
        $ex_no_so = explode("/", $row['no_so']);
        $duration = date("d/m/Y", strtotime($row['duration']));
        $tagihan = $row['send_qty'] * $row['price'];

        $functions  = '<div class="function_buttons"><ul>';
        if($_SESSION['role'] != '5'){
          if(!in_array($row['no_invoice'], $data_invoice)){
            $functions .= '<li class="function_print"><a data-id="'.$row['id'].'" title="Print"><span>Print</span></a></li>';
            $functions .= '<li class="function_complete"><a data-id="'.$row['id'].'" data-name="'.$row['no_invoice'].'" title="Selesai"><span>Selesai</span></a></li>';
            $functions .= "<li class='function_delete HapusInvoice'><a data-id='".$row['id']."' data-name='".$row['no_invoice']."' title='Hapus Invoice'><span>Hapus</span></a></li>";
          }
        } else {
          $functions .= '<li>Not allowed</li>';
        }
        $functions .= '</ul></div>';

        if($row['ppn'] > 0){
          $ppn = $tagihan/11;
          $total = $tagihan + $ppn;
        } else {
          $ppn = '0';
          $total = $tagihan;
        }

        if($row['send_qty'] > 0)
        {
          if(!in_array($row['id_fk'].'-'.$row['id_sj'].'-'.$row['cost'], $data_cost)){
            $biaya_kirim = $row['cost'];
          } else {
            $biaya_kirim = '';
          }

          if(!in_array($row['no_invoice'], $data_invoice)){
            if($row['print'] == '1'){ $print = "SUDAH"; } else { $print = "BELUM"; }
            $name = $row['name'];
          } else {
            $print = '';
            $name = '';
          }
          $mysqli_data[] = array(
            "no"            => $no++,
            "invoice_date"  => $invoice_date,
            "duration"      => $duration,
            "customer"      => $row['customer'],
            "no_po"         => $row['po_customer'],
            "no_so"         => $ex_no_so[0]."/".$ex_no_so[1].$ex_no_so[2],
            "no_sj"         => $row['no_delivery'],
            "no_invoice"    => $row['no_invoice'],
            "send_qty"      => $row['send_qty'],
            "unit"          => $row['unit'],
            "price"         => $row['price'],
            "ekspedisi"     => $row['ekspedisi'],
            "uom"           => $row['uom'],
            "jml"           => $row['jml'],
            "bill"          => $tagihan,
            "ppn"           => $ppn,
            "total"         => $total,
            "shipping_costs"=> $biaya_kirim,
            "dicetak"       => $print,
            "diinput"       => $name,
            "functions"     => $functions
          );

          $data_invoice[] = $row['no_invoice'];
          $data_cost[] = $row['id_fk'].'-'.$row['id_sj'].'-'.$row['cost'];
        }
	    }
	  }

	} elseif($action == 'resultAll_'.$slug){

    ///////////////////////
    // Load data print
    //////////////////////

    $query = "SELECT a.id_fk, a.id_sj, a.no_delivery, a.send_qty, b.id, b.print, b.no_invoice, b.invoice_date, b.duration, b.input_by, c.price, c.unit, d.no_so, e.customer, e.po_customer, f.sj_date, f.cost, f.ekspedisi, f.uom, f.jml, g.ppn, i.name FROM delivery_orders_item AS a LEFT JOIN invoice AS b ON b.id_fk = a.id_fk AND b.id_sj = a.id_sj LEFT JOIN preorder_item AS c ON c.id_fk = a.id_fk AND c.item_to = a.item_to LEFT JOIN workorder_item AS d ON d.id_fk = a.id_fk AND d.item_to = a.item_to LEFT JOIN preorder_customer AS e ON e.id_fk = a.id_fk LEFT JOIN delivery_orders_customer AS f ON f.id_fk = a.id_fk AND f.id_sj = a.id_sj LEFT JOIN preorder_price AS g ON g.id_fk = a.id_fk LEFT JOIN status AS h ON h.id_fk = a.id_fk AND h.item_to = a.item_to LEFT JOIN user AS i ON i.id = b.input_by WHERE h.order_status BETWEEN 1 AND 2 AND b.status = 0 AND b.duration > '".date('Y-m-d')."' AND b.id IS NOT NULL ORDER BY a.id ASC";
    $sql = $connect->query($query);
      
    if(!$sql){
      $result  = $error;
      $message = $qerror;
    } else {
      $result  = $success;
      $message = $qsuccess;
      $no = 1;

      $data_cost = array();
      $data_print = array();
      $data_name = array();
      $data_invoice = array();
      while($row = $sql->fetch_array()){
        $invoice_date = date("d/m/Y", strtotime($row['invoice_date']));
        $ex_no_so = explode("/", $row['no_so']);
        $duration = date("d/m/Y", strtotime($row['duration']));
        $tagihan = $row['send_qty'] * $row['price'];
        
        if($row['ppn'] > 0){
          $ppn = $tagihan/11;
          $total = $tagihan + $ppn;
        } else {
          $ppn = '0';
          $total = $tagihan;
        }
        
        if($row['send_qty'] > 0)
        {
          if(!in_array($row['id_fk'].'-'.$row['id_sj'].'-'.$row['cost'], $data_cost)){
            $biaya_kirim = $row['cost'];
          } else {
            $biaya_kirim = '';
          }

          if(!in_array($row['no_invoice'], $data_invoice)){
            if($row['print'] == '1'){ $print = "SUDAH"; } else { $print = "BELUM"; }
            $name = $row['name'];
          } else {
            $print = '';
            $name = '';
          }
          $mysqli_data[] = array(
            "no"            => $no++,
            "invoice_date"  => $invoice_date,
            "duration"      => $duration,
            "customer"      => $row['customer'],
            "no_po"         => $row['po_customer'],
            "no_so"         => $ex_no_so[0]."/".$ex_no_so[1].$ex_no_so[2],
            "no_sj"         => $row['no_delivery'],
            "no_invoice"    => $row['no_invoice'],
            "send_qty"      => $row['send_qty'],
            "unit"          => $row['unit'],
            "price"         => $row['price'],
            "ekspedisi"     => $row['ekspedisi'],
            "uom"           => $row['uom'],
            "jml"           => $row['jml'],
            "bill"          => $tagihan,
            "ppn"           => $ppn,
            "total"         => $total,
            "shipping_costs"=> $biaya_kirim,
            "dicetak"       => $print,
            "diinput"       => $name,
          );
          
          $data_invoice[] = $row['no_invoice'];
          $data_cost[] = $row['id_fk'].'-'.$row['id_sj'].'-'.$row['cost'];
        }
      }
    }

  } elseif ($action == 'periode_'.$slug){

    ///////////////////////////
    // Get pre order data
    ///////////////////////////

    $dari = mysqli_real_escape_string($connect, $_GET['dari']);
    $sampai = mysqli_real_escape_string($connect, $_GET['sampai']);
    
    $query = "SELECT a.id_fk, a.id_sj, a.no_delivery, a.send_qty, b.id, b.print, b.no_invoice, b.invoice_date, b.duration, b.input_by, c.price, c.unit, d.no_so, e.customer, e.po_customer, f.sj_date, f.cost, f.ekspedisi, f.uom, f.jml, g.ppn, i.name FROM delivery_orders_item AS a LEFT JOIN invoice AS b ON b.id_fk = a.id_fk AND b.id_sj = a.id_sj LEFT JOIN preorder_item AS c ON c.id_fk = a.id_fk AND c.item_to = a.item_to LEFT JOIN workorder_item AS d ON d.id_fk = a.id_fk AND d.item_to = a.item_to LEFT JOIN preorder_customer AS e ON e.id_fk = a.id_fk LEFT JOIN delivery_orders_customer AS f ON f.id_fk = a.id_fk AND f.id_sj = a.id_sj LEFT JOIN preorder_price AS g ON g.id_fk = a.id_fk LEFT JOIN status AS h ON h.id_fk = a.id_fk AND h.item_to = a.item_to LEFT JOIN user AS i ON i.id = b.input_by WHERE h.order_status BETWEEN 1 AND 2 AND b.status = 0 AND b.duration BETWEEN '$dari' AND '$sampai' AND b.id IS NOT NULL ORDER BY a.id DESC";
    $sql = $connect->query($query);
    if(!$sql){
      $result  = $error;
      $message = $qerror;
    } else {
      $result  = $success;
      $message = $qsuccess;
      $no = 1;

      $data_cost = array();
      $data_print = array();
      $data_name = array();
      $data_invoice = array();
      while($row = $sql->fetch_array()){
        $invoice_date = date("d/m/Y", strtotime($row['invoice_date']));
        $ex_no_so = explode("/", $row['no_so']);
        $duration = date("d/m/Y", strtotime($row['duration']));
        $tagihan = $row['send_qty'] * $row['price'];

        $functions  = '<div class="function_buttons"><ul>';
        if($_SESSION['role'] != '5'){
          if(!in_array($row['no_invoice'], $data_invoice)){
            $functions .= '<li class="function_print"><a data-id="'.$row['id'].'" title="Print"><span>Print</span></a></li>';
            $functions .= '<li class="function_complete"><a data-id="'.$row['id'].'" data-name="'.$row['no_invoice'].'" title="Selesai"><span>Selesai</span></a></li>';
            $functions .= "<li class='function_delete HapusInvoice'><a data-id='".$row['id']."' data-name='".$row['no_invoice']."' title='Hapus Invoice'><span>Hapus</span></a></li>";
          }
        } else {
          $functions .= '<li>Not allowed</li>';
        }
        $functions .= '</ul></div>';

        if($row['ppn'] > 0){
          $ppn = $tagihan/11;
          $total = $tagihan + $ppn;
        } else {
          $ppn = '0';
          $total = $tagihan;
        }

        if($row['send_qty'] > 0)
        {
          if(!in_array($row['id_fk'].'-'.$row['id_sj'].'-'.$row['cost'], $data_cost)){
            $biaya_kirim = $row['cost'];
          } else {
            $biaya_kirim = '';
          }

          if(!in_array($row['no_invoice'], $data_invoice)){
            if($row['print'] == '1'){ $print = "SUDAH"; } else { $print = "BELUM"; }
            $name = $row['name'];
          } else {
            $print = '';
            $name = '';
          }
          $mysqli_data[] = array(
            "no"            => $no++,
            "invoice_date"  => $invoice_date,
            "duration"      => $duration,
            "customer"      => $row['customer'],
            "no_po"         => $row['po_customer'],
            "no_so"         => $ex_no_so[0]."/".$ex_no_so[1].$ex_no_so[2],
            "no_sj"         => $row['no_delivery'],
            "no_invoice"    => $row['no_invoice'],
            "send_qty"      => $row['send_qty'],
            "unit"          => $row['unit'],
            "price"         => $row['price'],
            "ekspedisi"     => $row['ekspedisi'],
            "uom"           => $row['uom'],
            "jml"           => $row['jml'],
            "bill"          => $tagihan,
            "ppn"           => $ppn,
            "total"         => $total,
            "shipping_costs"=> $biaya_kirim,
            "dicetak"       => $print,
            "diinput"       => $name,
            "functions"     => $functions
          );

          $data_invoice[] = $row['no_invoice'];
          $data_cost[] = $row['id_fk'].'-'.$row['id_sj'].'-'.$row['cost'];
        }
      }
    }

  } elseif ($action == 'get_'.$slug){
    if($id == ''){
      $result  = $error;
      $message = 'ID '.$missing;
    } else {
      $id = mysqli_real_escape_string($connect, $id);

      $select1 = "SELECT no_invoice FROM $tabel8 WHERE id = '$id'";
      $sql1 = $connect->query($select1);
      $fetch = $sql1->fetch_array();

      $select2 = "SELECT a.id_fk, a.no_invoice, a.input_by, a.invoice_date, b.id_sj, b.item_to, b.no_delivery, b.send_qty, c.item, c.unit, c.price, c.price, d.customer, d.po_customer, e.ppn, f.shipto, f.cost, g.no_so, g.ingredient, g.size, g.volume, h.alamat, h.kota, h.negara, h.provinsi, h.kodepos, h.telp, h.s_nama, h.s_alamat, h.s_kota, h.s_negara, h.s_provinsi, h.s_kodepos, i.company, i.address, i.phone FROM invoice AS a LEFT JOIN delivery_orders_item AS b ON b.id_fk = a.id_fk AND b.id_sj = a.id_sj LEFT JOIN preorder_item AS c ON c.id_fk = a.id_fk AND c.item_to = b.item_to LEFT JOIN preorder_customer AS d ON d.id_fk = a.id_fk LEFT JOIN preorder_price AS e ON e.id_fk = a.id_fk LEFT JOIN delivery_orders_customer AS f ON f.id_fk = b.id_fk AND f.id_sj = b.id_sj LEFT JOIN workorder_item AS g ON g.id_fk = a.id_fk AND g.item_to = b.item_to LEFT JOIN customer AS h ON h.id = d.id_customer LEFT JOIN company AS i ON i.id = d.id_company WHERE a.no_invoice = '".$fetch['no_invoice']."' ORDER BY b.no_delivery ASC";
      $sql2 = $connect->query($select2);

      if(!$sql1 OR !$sql2){
        $result  = $error;
        $message = $qerror;
      } else {
        $result  = $success;
        $message = $qsuccess;
        $data_cost = array();
        $suratjalan = array();
        while($row = $sql2->fetch_array()){
          $tagihan = $row['send_qty'] * $row['price'];
          $no_so = explode("/", $row['no_so']);
          if($row['ppn'] > 0){ $ppn = $tagihan/11; $total = $tagihan + $ppn; } else { $ppn = 0; $total = $tagihan; }
          if(!in_array($row['id_fk'].'-'.$row['id_sj'].'-'.$row['cost'], $data_cost)){
            $biaya_kirim = $row['cost'];
          } else {
            $biaya_kirim = '';
          }

          $data_cost[] = $row['id_fk'].'-'.$row['id_sj'].'-'.$row['cost'];

          if(empty($row['alamat'])) { $alamat = ''; } else { $alamat = $row['alamat'].". ";}
          if(empty($row['kota']))   { $kota = ''; } else { $kota = $row['kota']." - ";}
          if(empty($row['negara'])) { $negara = ''; } else { $negara = $row['negara'].". ";}
          if(empty($row['provinsi'])){ $provinsi = ''; } else { $provinsi = $row['provinsi'].", ";}
          if(empty($row['kodepos'])){ $kodepos = ''; } else { $kodepos = $row['kodepos'].". ";}

          if(empty($row['s_alamat'])) { $s_alamat = ''; } else { $s_alamat = $row['s_alamat'].". ";}
          if(empty($row['s_kota']))   { $s_kota = ''; } else { $s_kota = $row['s_kota']." - ";}
          if(empty($row['s_negara'])) { $s_negara = ''; } else { $s_negara = $row['s_negara'].". ";}
          if(empty($row['s_provinsi'])){ $s_provinsi = ''; } else { $s_provinsi = $row['s_provinsi'].", ";}
          if(empty($row['s_kodepos'])){ $s_kodepos = ''; } else { $s_kodepos = $row['s_kodepos'].". ";}
          if(empty($row['s_nama']))   { $s_nama = $row['customer']; } else { $s_nama = $row['s_nama'].". ";}

          if($row['send_qty'] > 0)
          {
            $array['id_fk']        = $row['id_fk'];
            $array['company']      = $row['company'];
            $array['address']      = $row['address'];
            $array['phone']        = $row['phone'];
            $array['customer']     = $row['customer'];
            $array['billto']       = $alamat.$kota.$provinsi.$negara.$kodepos;
            $array['shipto']       = $row['shipto'].$s_telp;
            $array['ship_name']    = $s_nama;
            $array['no_po']        = $row['po_customer'];
            $array['no_sj'][]      = $row['no_delivery'];
            $array['no_invoice']   = $row['no_invoice'];
            $array['item'][]       = strtoupper($row['item']);
            $array['send_qty'][]   = $row['send_qty'];
            $array['unit'][]       = $row['unit'];
            $array['tagihan'][]    = $total;
            $array['biaya_kirim'][]= $biaya_kirim;
            $array['telp']         = $row['telp'];
            $array['price'][]      = $row['price'];
            $array['ppn'][]        = $ppn;
            $array['no_so'][]      = $no_so[0]."/".$no_so[1].$no_so[2];
            $array['invoice_date'] = $row['invoice_date'];
          }

          $suratjalan[] = $row['no_delivery'];
        }

        $mysqli_data[] = array(
          "id_fk"         => $array['id_fk'],
          "company"       => $array['company'],
          "address"       => $array['address'],
          "phone"         => $array['phone'],
          "customer"      => $array['customer'],
          "billto"        => $array['billto'],
          "shipto"        => $array['shipto'],
          "ship_name"     => $array['ship_name'],
          "no_po"         => $array['no_po'],
          "no_sj"         => $array['no_sj'],
          "no_invoice"    => $array['no_invoice'],
          "item"          => $array['item'],
          "send_qty"      => $array['send_qty'],
          "unit"          => $array['unit'],
          "tagihan"       => array_sum($array['tagihan']),
          "biaya_kirim"   => array_sum($array['biaya_kirim']),
          "telp"          => $array['telp'],
          "price"         => $array['price'],
          "ppn"           => array_sum($array['ppn']),
          "no_so"         => $array['no_so'],
          "invoice_date"  => $array['invoice_date'],
        );
      }
    }

  } elseif ($action == 'print'){
    if($id == ''){
      $result  = $error;
      $message = 'ID '.$missing;
    } else {
      $no_delivery = '';
      $id = mysqli_real_escape_string($connect, $id);
      $tgl = mysqli_real_escape_string($connect, $_POST['tgl']);
      $tgls = date("d M Y", strtotime($tgl));
      $no_invoice = mysqli_real_escape_string($connect, $_POST['no_faktur']);
      $company = mysqli_real_escape_string($connect, $_POST['company']);
      $address = mysqli_real_escape_string($connect, $_POST['address']);
      $phone = mysqli_real_escape_string($connect, $_POST['phone']);
      $bill = mysqli_real_escape_string($connect, $_POST['bill']);
      $biaya_kirim = mysqli_real_escape_string($connect, $_POST['biaya_kirim']);
      $status_ppn = mysqli_real_escape_string($connect, $_POST['status_ppn']);
      $customer = mysqli_real_escape_string($connect, $_POST['customer']);
      $ship_name = mysqli_real_escape_string($connect, $_POST['ship_name']);
      $no_po = mysqli_real_escape_string($connect, $_POST['no_po']);
      $billto = str_replace("\\r\\n"," ", mysqli_real_escape_string($connect, $_POST['billto']));
      $shipto = str_replace("\\r\\n"," ", mysqli_real_escape_string($connect, $_POST['shipto']));
      $telp = mysqli_real_escape_string($connect, $_POST['telp']);
      $bank = explode('-', mysqli_real_escape_string($connect, $_POST['pilihBANK']));
      $ttd = mysqli_real_escape_string($connect, $_POST['ttd']);

      $data = $_POST['data'];
      $no = 1;

      $select = "SELECT a.duration, c.logo, c.email FROM $tabel8 AS a LEFT JOIN preorder_customer AS b ON b.id_fk = a.id_fk LEFT JOIN company AS c ON c.id = b.id_company WHERE a.id = '".$id."'";
      $sql = $connect->query($select);
      $fetch = $sql->fetch_array();

      if(empty($fetch['email'])){ $email = ''; } else { $email = 'Email: '.$fetch['email']; }
      
      foreach($data['price'] as $key => $value){
        $no_sj    = $data['no_sj'][$key];
        $no_so    = $data['no_so'][$key];
        $item     = $data['item'][$key];
        $send_qty = $data['qty'][$key];
        $unit     = $data['unit'][$key];
        $price    = $data['price'][$key];

        if($status_ppn > 0){ $ppn = $price / 11; } else { $ppn = 0; }
        if(strtolower($no_sj) === $no_delivery){ $suratjalan = ''; } else { $suratjalan = $no_sj; }

        $mysqli_data[] = array(
          "no"        => $no++,
          "company"   => strtoupper($company),
          "address"   => $address,
          "phone"     => $phone,
          "customer"  => $customer,
          "no_invoice"=> $no_invoice,
          "no_po"     => $no_po,
          "no_sj"     => $suratjalan,
          "total"     => $bill,
          "ongkoskir" => $biaya_kirim,
          "item"      => $item,
          "unit"      => $unit,
          "qty"       => $send_qty,
          "price"     => $price,
          "tgl"       => $tgls,
          "alamat"    => $alamat,
          "telp"      => $telp,
          "an"        => $bank[2],
          "rek"       => $bank[1],
          "bank"      => $bank[0],
          "ttd"       => $ttd,
          "no_so"     => $no_so,
          "billto"    => $billto,
          "shipto"    => $shipto,
          "ship_name" => $ship_name,
          "tenggat"   => date('d M Y', strtotime($fetch['duration'])),
          "logo"      => $fetch['logo'],
          "email"     => $email,
        );

        $array['ppn'][] = $ppn * $send_qty;
        $array['subtotal'][] = $price * $send_qty;
        $no_delivery = strtolower($no_sj);
      }

      $mysqli_data[] = array(
        'ppn'       => array_sum($array['ppn']),
        'subtotal'  => array_sum($array['subtotal'])
      );

      $update = "UPDATE $tabel8 SET print = '1', print_date = '".$tgl."' WHERE id = '$id'";
      $sql1 = $connect->query($update);
      $logger = logger($connect,'Print invoice (procces)', 'Customer: '.$customer.' - Invoice: '.$no_invoice.' - Total: Rp '.$bill.' - BANK: '.$bank);

      if(!$sql1 OR !$logger){
        $result  = $error;
        $message = $qerror;
      } else {
        $result  = $success;
        $message = $qsuccess;
      }
    }

  } elseif ($action == 'complete'){
    if($id == ''){
      $result  = $error;
      $message = 'ID '.$missing;
    } else {
      $id = mysqli_real_escape_string($connect, $id);
      $date = mysqli_real_escape_string($connect, $_POST['date']);
      $ket = mysqli_real_escape_string($connect, $_POST['ket']);

      $select = "SELECT a.invoice_date, a.no_invoice, b.customer, GROUP_CONCAT(DISTINCT c.no_delivery SEPARATOR ', ') AS no_delivery FROM invoice AS a LEFT JOIN preorder_customer AS b ON b.id_fk = a.id_fk LEFT JOIN delivery_orders_item AS c ON c.id_fk = a.id_fk AND c.id_sj = a.id_sj WHERE a.id = '".$id."' GROUP BY a.no_invoice";
      $sql1 = $connect->query($select);
      $fetch = $sql1->fetch_array();

      $update = "UPDATE $tabel8 SET status = '1', complete_date = '".$date."', note = '".$ket."' WHERE id = '".$id."'";
      $sql2 = $connect->query($update);

      $logger = logger($connect,'Completed invoice (procces)', ' No invoice: '.$fetch['no_invoice'].' - Invoice date: '.$fetch['invoice_date'].' - Customer: '.$fetch['customer'].' - No delivery: '.$fetch['no_delivery']);

      if(!$sql1 OR !$sql2 OR !$logger){
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
      $id = mysqli_real_escape_string($connect, $id);
      $select = "SELECT no_invoice FROM $tabel8 WHERE id = '$id'";
      $sql1 = $connect->query($select);
      $fetch = $sql1->fetch_array();

      $del = "DELETE FROM $tabel8 WHERE no_invoice = '".$fetch['no_invoice']."'";
      $sql2 = $connect->query($del);

      $logger = logger($connect,'Delete invoice (procces)', ' No invoice: '.$fetch['no_invoice']);

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