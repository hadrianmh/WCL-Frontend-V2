<?php
require '../auth/connect.php';
require 'session.php';

$session_name = $_SESSION['name'];
$session_email = $_SESSION['email'];
$session_role = $_SESSION['role'];
$session_status = $_SESSION['status'];
$session_account = $_SESSION['account'];

$query = "SELECT * FROM user WHERE email='$session_email' AND name='$session_name' AND status='$session_status' AND role='$session_role' AND account='$session_account'";
$sql = $connect->query($query);
if($sql->num_rows > 0){
  $data = $sql->fetch_array();
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>
  <?php
  if($data['role'] == '1'){
    echo "Root";
  } else if($data['role'] == '2'){
    echo "Admin";
  } elseif ($data['role'] == '3') {
    echo "Sales Order";
  } elseif ($data['role'] == '4') {
    echo "Finance";
  } elseif ($data['role'] == '5') {
    echo "Guest";
  } elseif ($data['role'] == '6') {
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
<?php } else {
  session_destroy();
  header("Location:../signin.php");
  exit;
}
?>