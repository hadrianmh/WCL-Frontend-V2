<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <title>Dashboard</title>
  <link rel="shortcut icon" href="<?php echo base_url('assets/img/favicon.ico'); ?>" type="image/x-icon">
  <link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap/bootstrap-select.min.css'); ?>">
  <link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap/bootstrap.min.css'); ?>">
  <link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap/bootstrap-datepicker3.min.css'); ?>">
  <link rel="stylesheet" href="<?php echo base_url('assets/css/font-awesome/css/font-awesome.min.css'); ?>">
  <link rel="stylesheet" href="<?php echo base_url('assets/css/AdminLTE.min.css'); ?>">
  <link rel="stylesheet" href="<?php echo base_url('assets/css/_all-skins.min.css'); ?>">
  <link rel="stylesheet" href="<?php echo base_url('assets/css/style.css'); ?>">
  <link rel="stylesheet" href="<?php echo base_url('assets/css/styles.css'); ?>">
  <link rel="stylesheet" href="<?php echo base_url('assets/css/font.css'); ?>">
  <script src="<?php echo base_url('assets/js/jquery-3.3.1.min.js'); ?>"></script>
  <script src="<?php echo base_url('assets/js/jquery.mask.min.js'); ?>"></script>
  <script src="<?php echo base_url('assets/js/theme/adminlte.min.js'); ?>"></script>
  <script src="<?php echo base_url('assets/js/theme/demo.js'); ?>"></script>
  <script src="<?php echo base_url('assets/js/bootstrap/bootstrap.min.js'); ?>"></script>
  <!-- DataTables properties -->
  <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/datatables/datatables.bootstrap.min.css'); ?>"/>
  <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/datatables/buttons.dataTables.min.css'); ?>"/>
  <script type="text/javascript" src="<?php echo base_url('assets/js/datatables/jquery.datatables.min.js'); ?>"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/js/datatables/datatables.bootstrap.min.js'); ?>"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/js/datatables/dataTables.buttons.min.js'); ?>"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/js/datatables/jszip.min.js'); ?>"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/js/datatables/buttons.html5.min.js'); ?>"></script>
  <!-- jQuery validation  -->
  <script type="text/javascript" src="<?php echo base_url('assets/js/jquery.validate.min.js'); ?>"></script>

  <script>
    $(document).ready(function()
    {
      function load_unseen_notification()
      {
        $('.looping-notif').remove();
        $.ajax({
          url     : "<?php echo base_url('index.php/action/all/notification'); ?>",
          data    : { "<?php echo $this->security->get_csrf_token_name(); ?>" : "<?php echo $this->security->get_csrf_hash(); ?>" },
          method  : "POST",
          dataType: "json",
          success :function(output)
          {
            $('.jmlNotif').text(output.data[0].count);
            $('.headerjmlNotif').text('Anda memiliki '+output.data[0].count+' notifikasi');
            for(var i = 0; i<output.data[0].item.length; i++)
            {
              if(output.data[0].item.length > 0){
                $('.kontenjmlNtotif').append(output.data[0].item[i]);  
              } else {
                $('.kontenjmlNtotif').append('<li class="looping-notif"></li>');  
              }
            }
          }
        })
      }

      load_unseen_notification();
      $('.notifications-menu a').click(function() { load_unseen_notification(); });
    });
  </script>
</head>