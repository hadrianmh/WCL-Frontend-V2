<?php

/////////////////////////////
// Personal config DataTables 
////////////////////////////
require '../dashboard/session.php';
require 'connect.php';
include 'history.php';
$tabel1 = 'workorder_customer';
$tabel2 = 'workorder_item';
$tabel4 = 'delivery_orders_customer';
$tabel5 = 'delivery_orders_item';
$tabel6 = 'invoice';
$tabel7 = 'status';
$Query = 'action';
$slug = 'workorder';
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
  if($action == 'result_'.$slug ||
    $action == 'edit_'.$slug ||
    $action == 'get_'.$slug ||
    $action == 'get_print_'.$slug |
    $action == 'print' ||
    $action == 'sortdata_'.$slug ||
    $action == 'periode_'.$slug
  ){
    if(isset($_GET['id'])){
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
  $output = number_format($angka, 0, '', '.');
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

    ///////////////////////////
    // Get pre order data
    ///////////////////////////
    
    $curMonth = str_replace('/', '-', $_GET['curMonth']);

    $query = "SELECT a.*, b.*, c.order_status, c.item_to, a.id AS id_customer FROM $tabel1 AS a LEFT JOIN $tabel2 AS b ON a.id_fk = b.id_fk LEFT JOIN $tabel7 AS c ON a.id_fk = c.id_fk AND b.item_to = c.item_to WHERE a.po_date LIKE '$curMonth%' ORDER BY b.id DESC";
    $sql = $connect->query($query);
    
    if(!$sql){
      $result  = $error;
      $message = $qerror;
    } else {
      $result  = $success;
        $message = $qsuccess;
        $no = 1;

      while($row = $sql->fetch_array()){
        $ex_no_spk = explode("/", $row['no_so']);
        $ex_sources = explode("|", $row['sources']);
        if($ex_sources[0] == 1){
          $sources = 'Internal';
        } elseif($ex_sources[0] == 2){
          $sources = 'SUBCONT ('.$ex_sources[1].', '.date("d/m/Y", strtotime($ex_sources[2])).')';
        } elseif($ex_sources[0] == 3){
          $sources = 'IN STOCK ('.$ex_sources[1].' '.$row['unit'].')';
        }
        
        if($row['porporasi'] > 0){ $porporasi = 'YA'; } else { $porporasi = 'YA'; }
        
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

        $original_spkDate = $row['spk_date'];
        $filter_spk_date = date("d/m/Y", strtotime($original_spkDate));
        if($filter_spk_date == '01/01/1970'){$spk_date = '';}
          else {$spk_date = $filter_spk_date;}
        $original_duration = $row['duration'];
        $filter_duration = date("d/m/Y", strtotime($original_duration));
        if($filter_duration == '01/01/1970'){$duration = '';}
          else {$duration = $filter_duration;}
        
        $select = "SELECT name FROM user WHERE id = '".$row['input_by']."'";
        $hasil = $connect->query($select);
        $fetch = $hasil->fetch_array();

        $functions  = '<div class="function_buttons"><ul>';
        if($_SESSION['role'] == '5'){
          $functions  = '<li>Not Allowed</li>';
        } else {
          $functions .= '<li class="function_process UbahCustomer"><a data-id="'.$row['id_customer'].'-'.$row['item_to'].'" title="Proses"><span>Proses</span></a></li>';
          $functions .= '<li class="function_print"><a data-id="'.$row['id_customer'].'-'.$row['item_to'].'" title="Print"><span>Print</span></a></li>';
        }
        $functions .= '</ul></div>';
        

        $mysqli_data[] = array(
            "no"        => $no++,
            "customer"  => $row['customer'],
            "po_customer" => $row['po_customer'],
            "no_spk"      => $ex_no_spk[0]."/".$ex_no_spk[1].$ex_no_spk[2],
            "spk_date"  => $spk_date,
            "duration"  => $duration,
            "order_status" => $order_status,
            "item"      => $row['item'],
            "size"      => $row['size'],
            "qore"      => $row['qore'],
            "line"      => $row['lin'],
            "roll"      => $row['roll'],
            "ingredient"  => $row['ingredient'],
            "porporasi" => $porporasi,
            "qty"       => rupiah($row['qty']),
            "unit"      => $row['unit'],
            "volume"    => rupiah($row['volume']),
            "annotation"=> $row['annotation'],
            "uk_bahan"  => $row['uk_bahan_baku'],
            "qty_bahan" => $row['qty_bahan_baku'],
            "annotation"=> $row['annotation'],
            "sources"   => $sources,
            "input"     => $fetch['name'],
            "functions" => $functions
        );
      }
    }

  } elseif ($action == 'periode_'.$slug){

    ///////////////////////////
    // Get pre order data
    ///////////////////////////
    
    $dari = mysqli_real_escape_string($connect, $_GET['dari']);
    $sampai = mysqli_real_escape_string($connect, $_GET['sampai']);

    $query = "SELECT a.*, b.*, c.order_status, c.item_to, a.id AS id_customer FROM $tabel1 AS a LEFT JOIN $tabel2 AS b ON a.id_fk = b.id_fk LEFT JOIN $tabel7 AS c ON a.id_fk = c.id_fk AND b.item_to = c.item_to WHERE a.po_date BETWEEN '$dari' AND '$sampai' ORDER BY b.id ASC";
    $sql = $connect->query($query);
    
    if(!$sql){
      $result  = $error;
      $message = $qerror;
    } else {
      $result  = $success;
      $message = $qsuccess;
      $no = 1;

      while($row = $sql->fetch_array()){
        $ex_no_spk = explode("/", $row['no_so']);
        $ex_sources = explode("|", $row['sources']);
        if($ex_sources[0] == 1){
          $sources = 'Internal';
        } elseif($ex_sources[0] == 2){
          $sources = 'SUBCONT ('.$ex_sources[1].', '.date("d/m/Y", strtotime($ex_sources[2])).')';
        } elseif($ex_sources[0] == 3){
          $sources = 'IN STOCK ('.$ex_sources[1].' '.$row['unit'].')';
        }
        
        if($row['porporasi'] > 0){ $porporasi = 'YA'; } else { $porporasi = 'YA'; }
        
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

        $original_spkDate = $row['spk_date'];
        $filter_spk_date = date("d/m/Y", strtotime($original_spkDate));
        if($filter_spk_date == '01/01/1970'){$spk_date = '';}
          else {$spk_date = $filter_spk_date;}
        $original_duration = $row['duration'];
        $filter_duration = date("d/m/Y", strtotime($original_duration));
        if($filter_duration == '01/01/1970'){$duration = '';}
          else {$duration = $filter_duration;}
        
        $select = "SELECT name FROM user WHERE id = '".$row['input_by']."'";
        $hasil = $connect->query($select);
        $fetch = $hasil->fetch_array();

        $functions  = '<div class="function_buttons"><ul>';
        if($_SESSION['role'] == '5'){
          $functions  = '<li>Not Allowed</li>';
        } else {
          $functions .= '<li class="function_process UbahCustomer"><a data-id="'.$row['id_customer'].'-'.$row['item_to'].'" title="Proses"><span>Proses</span></a></li>';
          $functions .= '<li class="function_print"><a data-id="'.$row['id_customer'].'-'.$row['item_to'].'" title="Print"><span>Print</span></a></li>';
        }
        $functions .= '</ul></div>';
        

        $mysqli_data[] = array(
            "no"        => $no++,
            "customer"  => $row['customer'],
            "po_customer" => $row['po_customer'],
            "no_spk"      => $ex_no_spk[0]."/".$ex_no_spk[1].$ex_no_spk[2],
            "spk_date"  => $spk_date,
            "duration"  => $duration,
            "order_status" => $order_status,
            "item"      => $row['item'],
            "size"      => $row['size'],
            "qore"      => $row['qore'],
            "line"      => $row['lin'],
            "roll"      => $row['roll'],
            "ingredient"  => $row['ingredient'],
            "porporasi" => $porporasi,
            "qty"       => rupiah($row['qty']),
            "unit"      => $row['unit'],
            "volume"    => rupiah($row['volume']),
            "annotation"=> $row['annotation'],
            "uk_bahan"  => $row['uk_bahan_baku'],
            "qty_bahan" => $row['qty_bahan_baku'],
            "annotation"=> $row['annotation'],
            "sources"   => $sources,
            "input"     => $fetch['name'],
            "functions" => $functions
        );
      }
    }

  } elseif ($action == 'get_'.$slug){
    // Get data by id
    if($id == ''){
        $result  = $error;
        $message = 'ID '.$missing;
    } else {
      $id = mysqli_real_escape_string($connect, $id);
      $item_to = mysqli_real_escape_string($connect, $_GET['item_to']);
      $query = "SELECT a.*, b.order_status, b.item_to, c.no_so FROM $tabel1 AS a LEFT JOIN $tabel7 AS b ON a.id_fk = b.id_fk LEFT JOIN $tabel2 AS c ON c.id_fk = a.id_fk WHERE a.id = '".$id."' AND b.item_to = '".$item_to."' AND c.item_to = '".$item_to."'";
      $sql = $connect->query($query);
      if (!$sql){
        $result  = $error;
        $message = $qerror;
      } else {
        $result  = $success;
        $message = $qsuccess;
        while($row = $sql->fetch_array()){
          $ex_no_spk = explode("/", $row['no_so']);
          $mysqli_data[] = array(
            "po_date"       => $row['po_date'],
            "spk_date"      => $row['spk_date'],
            "no_spk"        => $ex_no_spk[0]."/".$ex_no_spk[1].$ex_no_spk[2],
            "po_customer"   => $row['po_customer'],
            "customer"      => $row['customer'],
            "order_status"  => $row['order_status']
          );
        }
      }
    }
  
  } elseif($action == 'edit_'.$slug){
    // Edit
    if($id == ''){
      $result  = $error;
      $message = 'ID '.$missing;
    } else {
      $id = mysqli_real_escape_string($connect, $id);
      $spk_date = mysqli_real_escape_string($connect, $_GET['spk_date']);
      $customer = mysqli_real_escape_string($connect, $_GET['customer']);
      $no_spk = mysqli_real_escape_string($connect, $_GET['no_spk']);
      $nopo = mysqli_real_escape_string($connect, $_GET['po_customer']);
      $order_status = mysqli_real_escape_string($connect, $_GET['order_status']);
      $duration = date('Y-m-d', strtotime($spk_date. '+16 day'));
      $item_to = mysqli_real_escape_string($connect, $_GET['item_to']);

      //mengambil id_fk pada tabel WO_customer
      $select = "SELECT id_fk FROM $tabel1 WHERE id = '".$id."'";
      $query = $connect->query($select);
      $fetch = $query->fetch_array();

      //Query update spk date dan durasi pd tabel WO_customer
      $update1 = "UPDATE $tabel1 SET ";
      if(isset($_GET['spk_date'])){ $update1 .= "spk_date = '".$spk_date."', duration = '".$duration."', input_by = '".$_SESSION['id']."' ";}
      $update1 .= "WHERE id = '".$id."'";

      //Query update spk date dan durasi pd tabel WO_customer
      $update2 = "UPDATE $tabel7 SET ";
      if (isset($_GET['order_status'])){ $update2 .= "order_status = '".$order_status."' ";}
      $update2 .= "WHERE id_fk = '".$fetch['id_fk']."' AND item_to = '".$item_to."'";
      
      $sql1  = $connect->query($update1);
      $sql2  = $connect->query($update2);
      $logger = logger($connect,'Edit WO (Procces)', 'Customer: '.$customer.' - No so: '.$no_spk.' - No po: '.$nopo.' - So date: '.$spk_date.' - Order status: '.$order_status);

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
    if($id == ''){
        $result  = $error;
        $message = 'ID '.$missing;
    } else {
      $data = array();
      $id = mysqli_real_escape_string($connect, $id);
      $item_to = mysqli_real_escape_string($connect, $_GET['item_to']);

      //mencari id_fk pada tabel WO customer
      $select1 = "SELECT id_fk FROM $tabel1 WHERE id = '".$id."'";
      $query1 = $connect->query($select1);
      $fetch = $query1->fetch_array();

      //memeriksa banyaknya order status yg lebih dari 0
      $select2 = "SELECT no FROM $tabel7 WHERE id_fk = '".$fetch['id_fk']."' AND order_status > 0";
      $query2 = $connect->query($select2);

      //menentukan data DO customer, DO item dan invoice
      $select3 = "SELECT a.id AS id_wo_item, a.item, b.id AS id_do_item, b.id_fk, b.item_to, b.no_delivery, c.jml_item_dlm_tiap_sj, d.id AS id_do_customer, d.id_sj, e.id AS id_invoice, e.invoice_date FROM $tabel2 AS a LEFT JOIN $tabel5 AS b ON b.id_fk LEFT JOIN (SELECT id_fk, no_delivery, COUNT(id) AS jml_item_dlm_tiap_sj FROM $tabel5 WHERE id_fk = '".$fetch['id_fk']."' GROUP BY no_delivery) AS c ON c.id_fk = a.id_fk AND c.no_delivery = b.no_delivery LEFT JOIN $tabel4 AS d ON d.id_fk = a.id_fk AND d.id_sj = b.id_sj LEFT JOIN $tabel6 AS e ON e.id_fk = a.id_fk AND e.id_sj = d.id_sj WHERE a.id_fk = '".$fetch['id_fk']."' AND a.item_to = '".$item_to."' GROUP BY b.id";
      $query3 = $connect->query($select3);
      while($row = $query3->fetch_array()){
        $data[] = $row;
      }

      //menghapus tanggal spk date dan durasi jika item tsb hanya 1 berstatus 1-4
      if($query2->num_rows < 2 ){
        $update1 = "UPDATE $tabel1 SET spk_date = '0000-00-00', duration = '0000-00-00', input_by = '0' WHERE id = '".$id."'";
        $run1 = $connect->query($update1);
      }

      //mengembalikan ke waiting list
      $update2 = "UPDATE $tabel7 SET order_status = '0' WHERE id_fk = '".$fetch['id_fk']."' AND item_to = '".$item_to."'";
      $run2 = $connect->query($update2);

      //menghapus DO customer, DO item dan invoice berdasarkan kondisi
      foreach($data as $key => $value){
        $trigger = $data[$key]['jml_item_dlm_tiap_sj'];

        if($trigger < 2){
          if($fetch['id_fk'] == $data[$key]['id_fk'] AND $item_to == $data[$key]['item_to']){
            $del1 = "DELETE FROM $tabel4 WHERE id = '".$data[$key]['id_do_customer']."'";
            $del2 = "DELETE FROM $tabel5 WHERE id = '".$data[$key]['id_do_item']."'";
            $del3 = "DELETE FROM $tabel6 WHERE id = '".$data[$key]['id_invoice']."'";
            $sql1 = $connect->query($del1);
            $sql2 = $connect->query($del2);
            $sql3 = $connect->query($del3);

          }
        } else {
          if($fetch['id_fk'] == $data[$key]['id_fk'] AND $item_to == $data[$key]['item_to']){
            $del1 = "DELETE FROM $tabel5 WHERE id = '".$data[$key]['id_do_item']."'";
            $sql1 = $connect->query($del1);       
          }
        }
      }

      $logger = logger($connect,'Delete WO (Procces)', 'Customer: '.$customer.' - No po: '.$nopo.' - No so: '.$no_spk.' - So date: '.$spk_date.' - Order status: 0');

      if(!$run2 OR !$logger){
        $result  = $error;
        $message = $qerror;
      } else {
        $result  = $success;
        $message = $qsuccess;
      }
    }

  } elseif ($action == 'get_print_'.$slug){
    if ($id == ''){
      $result  = $error;
      $message = 'ID'.$missing;
    } else {
      $id = mysqli_real_escape_string($connect, $id);
      $item_to = mysqli_real_escape_string($connect, $_GET['item_to']);

      $query = "SELECT a.*, b.*, c.item_to FROM $tabel1 AS a LEFT JOIN $tabel2 AS b ON a.id_fk = b.id_fk LEFT JOIN $tabel7 AS c ON a.id_fk = c.id_fk AND b.item_to = c.item_to WHERE a.id = '".$id."' AND b.item_to = '".$item_to."'";
      $sql = $connect->query($query);
      if(!$sql){
        $result  = $error;
        $message = $qerror;
      } else {
        $result  = $success;
        $message = $qsuccess;
        while($row = $sql->fetch_array()){
          $ex_sources = explode("|", $row['sources']);
          $ex_no_spk = explode("/", $row['no_so']);
          if($ex_sources[0] == 3){
            if($row['unit'] == 'PCS'){
              if($ex_sources[1] >= $row['qty']){
                $total = 0;
                $qty = 0;
                $isi = 0;
              } else {
                $qty = $row['qty'] - $ex_sources[1];
                $isi = $row['volume'];
                $total = round($qty/$isi, 1);
              }

            } else if($row['unit'] == 'ROLL'){
              if($ex_sources[1] >= $row['qty']){
                $qty = 0;
                $total = 0;
                $isi = 0;
              } else {
                $qty = $row['qty'] - $ex_sources[1];
                $isi = $row['volume'];
                $total = $qty * $isi;
              }
            } else {
              $qty = $row['qty'];
              $isi = $row['volume'];
              $total = $row['total'];
            }
            
          } else {
            $qty = $row['qty'];
            $total = $row['total'];
            $isi = $row['volume'];
          }
          if($row['porporasi'] > 0){ $porporasi = 'Ya'; } else { $porporasi = 'Tidak'; }
          $mysqli_data[] = array(
            "spk_date"    => $row['spk_date'],
            "customer"    => $row['customer'],
            "no_spk"      => $ex_no_spk[0]."/".$ex_no_spk[1].$ex_no_spk[2],
            "size_label"  => $row['size'],
            "unit"        => $row['unit'],
            "total"       => $total,
            "kor"         => $row['qore'],
            "line"        => $row['lin'],
            "gulungan"    => $row['roll'],
            "bahan"       => $row['ingredient'],
            "qty_produksi"=> $qty,
            "isi"         => $isi,
            "annotation"  => $row['annotation'],
            "po_customer" => $row['po_customer'],
            "size_baku"   => $row['uk_bahan_baku'],
            "qty_baku"    => $row['qty_bahan_baku'],
            "porporasi"   => $porporasi,
          );
        }
      }
    }

  } elseif ($action == 'print'){

      $original = mysqli_real_escape_string($connect, $_POST['tgl']);
      $spk_date = date("d F Y", strtotime($original));
      $customer = mysqli_real_escape_string($connect, $_POST['custom']);
      $no_spk = mysqli_real_escape_string($connect, $_POST['nospk']);
      $annotation = mysqli_real_escape_string($connect, $_POST['keterangan']);
      $size_label = mysqli_real_escape_string($connect, $_POST['size_label']);
      $size_baku = mysqli_real_escape_string($connect, $_POST['size_baku']);
      $bahan = mysqli_real_escape_string($connect, $_POST['bahan']);
      $gulungan = mysqli_real_escape_string($connect, $_POST['gulungan']);
      $kor = mysqli_real_escape_string($connect, $_POST['kor']);
      $line = mysqli_real_escape_string($connect, $_POST['lins']);
      $porporasi = mysqli_real_escape_string($connect, $_POST['porporasi']);
      $qty_baku = mysqli_real_escape_string($connect, $_POST['qty_baku']);
      $qty_produksi = mysqli_real_escape_string($connect, $_POST['qty_produksi']);
      $isi = mysqli_real_escape_string($connect, $_POST['isi']);
      $ttd = mysqli_real_escape_string($connect, $_POST['ttd']);
      $pcus = mysqli_real_escape_string($connect, $_POST['pcus']);
      $ptelp = mysqli_real_escape_string($connect, $_POST['ptelp']);

      $mysqli_data[] = array(
        "spk_date"        => $spk_date,
        "customer"        => $customer,
        "no_spk"          => $no_spk,
        "annotation"      => $annotation,
        "size_label"      => $size_label,
        "size_baku"       => $size_baku,
        "bahan"           => $bahan,
        "gulungan"        => $gulungan,
        "kor"             => $kor,
        "line"            => $line,
        "porporasi"       => $porporasi,
        "qty_baku"        => $qty_baku,
        "qty_produksi"    => $qty_produksi,
        "isi"             => $isi,
        "ttd"             => $ttd,
        "po_customer"     => $pcus,
        "p_telp"          => $ptelp,
      );

    $sql = logger($connect,'Print WO (Procces)', 'Customer: '.$customer.' - No po: '.$pcus.' - No so: '.$no_spk.' - So date: '.$original);
    if(!$sql){
      $result  = $error;
      $message = $qerror; 
    } else {
      $result  = $success;
      $message = $qsuccess;
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