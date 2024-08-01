<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>
  <?php
  if($_SESSION['role'] == '1'){
    echo "Root";
  } else if($_SESSION['role'] == '2'){
    echo "Admin";
  } elseif ($_SESSION['role'] == '3') {
    echo "Sales Order";
  } elseif ($_SESSION['role'] == '4') {
    echo "Finance";
  } elseif ($_SESSION['role'] == '5') {
    echo "Guest";
  } elseif ($_SESSION['role'] == '6') {
    echo "Production";
  }
  ?>
  Dashboard</title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <script src="../lib/js/jquery/jquery.min.js"></script>
  <script src="../lib/js/jquery/jquery.mask.min.js"></script>
  <link rel="stylesheet" href="../lib/css/bootstrap/bootstrap.min.css">
  <link rel="stylesheet" href="../lib/css/font-awesome/css/font-awesome.min.css">
  <link rel="stylesheet" href="../lib/css/theme/AdminLTE.min.css">
  <link rel="stylesheet" href="../lib/css/theme/_all-skins.min.css">
  <link rel="stylesheet" href="../lib/css/theme/style.css">
  <link rel="stylesheet" href="../lib/fonts/font.css">
  <link rel="shortcut icon" href="../files/img/favicon.ico" type="image/x-icon">
  <link rel="icon" href="../files/img/favicon.ico" type="image/x-icon">
  <?php if(!empty($_GET["page"])){?>
  <!-- DataTables properties -->
  <link rel="stylesheet" type="text/css" href="../plugins/DataTables/DataTables-1.10.16/css/jquery.dataTables.min.css"/>
  <link rel="stylesheet" type="text/css" href="../plugins/DataTables/Responsive-2.2.1/css/responsive.dataTables.min.css"/>
  <link rel="stylesheet" type="text/css" href="../plugins/DataTables/styles.css"/>
  <script type="text/javascript" src="../plugins/DataTables/datatables.min.js"></script>
  <script type="text/javascript" src="../plugins/jquery-validation/jquery.validate.min.js"></script>
  <!-- DataTables properties -->
  <?php } ?>
  <script type="text/javascript" src="../plugins/notif/core.js"></script>
  <script>
    $(document).ready(function(){
      load_unseen_notification();
      $('.notifications-menu a').click(function(){
        load_unseen_notification();
      });
    });
  </script>
</head>