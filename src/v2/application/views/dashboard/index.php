<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        SO Tracking
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
      </ol>
    </section>

    <hr>

    <!-- Main content -->
    <section class="content">
      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="col-lg-3 col-6">
          <div class="form-group">
            <div class="input-group">
              <span class="input-group-addon">Sort by </span>
              <select id="sortby" class="form-control"></select>
              <span class="input-group-btn">
                <button type="button" id="LoadData" class="btn btn-default">View</button>
              </span>
            </div>
          </div>
        </div>
      </div>

      <div class="row diagramDASHBOARD">

        <?php if($data['role'] == '1' OR $data['role'] == '2' OR $data['role'] == '3' OR $data['role'] == '5'){ ?>

        <div class="col-lg-3 col-6">
          <!-- small box -->
          <div class="small-box" style="background-color:#28a745!important">
            <div class="inner">
              <h3 class="statistiPO">0</h3>
              <p>Preorder</p>
            </div>
            <div class="icon">
              <i class="ion ion-bag"></i>
            </div>
            <a href="<?php echo base_url('index.php/dashboard/sales_order') ?>" class="small-box-footer">Selengkapnya <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>

        <?php } if($data['role'] == '1' OR $data['role'] == '2' OR $data['role'] == '5'){ ?>

        <div class="col-lg-3 col-6">
          <!-- small box -->
          <div class="small-box" style="background-color:#dc3545!important">
            <div class="inner">
              <h3 class="statistiSJ">0</h3>
              <p>Pengiriman selesai</p>
            </div>
            <div class="icon">
              <i class="ion ion-person-add"></i>
            </div>
            <a href="<?php echo base_url('index.php/dashboard/do_delivery') ?>" class="small-box-footer">Selengkapnya <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>

        <?php } if($data['role'] == '1' OR $data['role'] == '4' OR $data['role'] == '5'){ ?>

        <div class="col-lg-3 col-6">
          <!-- small box -->
          <div class="small-box" style="background-color:#ffc107!important">
            <div class="inner">
              <h3 class="statistiFAKTUR">0</h3>
              <p>Faktur selesai</p>
            </div>
            <div class="icon">
              <i class="ion ion-pie-graph"></i>
            </div>
            <a href="<?php echo base_url('index.php/dashboard/invoice_done') ?>" class="small-box-footer">Selengkapnya <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>

        <?php } ?>

        <?php if($this->session->userdata('role') == '1' OR $this->session->userdata('email') == 'iskandarwisnu7@gmail.com' OR $this->session->userdata('email') == 'riawidiastuti83@gmail.com'){ ?>
        <div class="col-lg-12">
          <table id="tablenya" class="table table-striped table-bordered" style="display: none">
            <thead>
              <tr>
                <th>Company</th>
                <th>Order Grade</th>
                <th>No SO</th>
                <th>SO Date</th>
                <th>ETD</th>
                <th>Customer</th>
                <th>No PO</th>
                <th>PO Date</th>
                <th>Product</th>
                <th>Detail</th>
                <th>Merk</th>
                <th>Type</th>
                <th>Ukuran</th>
                <th>Kor</th>
                <th>Line</th>
                <th>Roll</th>
                <th>Bahan</th>
                <th>Porporasi</th>
                <th>Qty</th>
                <th>Unit</th>
                <th>Isi ROLL/PCS</th>
                <th>Uk Bahan Baku</th>
                <th>Qty Bahan Baku</th>
                <th>Catatan</th>
                <th>Sources</th>
                <th>Price</th>
                <th>Price Before</th>
                <th>Tax</th>
                <th>Total Amount</th>
                <th>Request Date</th>
                <th>Order Status</th>
                <th>No DN</th>
                <th>Delivery Date</th>
                <th>Courier Name</th>
                <th>Tracking No</th>
                <th>Remarks</th>
                <th>Cost</th>
              </tr>
            </thead>
          </table>
        </div>
        <?php } ?>

          <noscript id="noscript_container">
            <div id="noscript" class="error">
              <p>JavaScript support is needed to use this page.</p>
            </div>
          </noscript>

          <div id="message_container">
            <div id="message" class="success">
              <p>This is a success message.</p>
            </div>
          </div>

          <div id="loading_container">
            <div id="loading_container2">
              <div id="loading_container3">
                <div id="loading_container4">
                  Loading, please wait...
                </div>
              </div>
            </div>
          </div>

      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <script>
    $(document).ready(function()
    {
      var tabel = $('#tablenya').DataTable({
        processing: true,
        serverSide: true,
        searching : false,
        paging    : false,
        ordering  : false,
        info      : false,
        ajax: {
          url: "<?php echo base_url('index.php/action/all/dashboard'); ?>",
          type: "POST",
          data: function ( data ) {
              data.<?php echo $this->security->get_csrf_token_name(); ?> = "<?php echo $this->security->get_csrf_hash(); ?>";
              data.curMonth = getCookie("selectMonth");
          }
        },
        dom: 'Bfrtip',
        buttons: [
        {
          extend: 'excel',
          text: 'Export to Excel',
          filename : 'SOTracking_'+getCookie("selectMonth"),
          messageTop: false,
          footer: true,
          title: 'SO Tracking '+getCookie("selectMonth"),
        }
        ],
        iDisplayLength: -1,
      });

      /////////////////////////////////////////////////////////////////
      // Sort data from current month
      /////////////////////////////////////////////////////////////////

      var Sortdata = $.ajax({
        url: '<?php echo base_url('index.php/action/all/sort_month'); ?>',
        type: 'POST',
        data: {
          <?php echo $this->security->get_csrf_token_name(); ?>   : '<?php echo $this->security->get_csrf_hash(); ?>',
          table   : 'preorder_customer',
          column  : 'po_date',
          where   : '',
          order   : 'po_date'
        }
      });

      Sortdata.done(function(output){
        if(output.result == 'success'){
          for(var i = 0; i<output.data[0].year.length; i++){
            $("#sortby").append("<option value='"+output.data[0].year[i]+"'>Tahun: "+output.data[0].year[i]+"</option>");
          }
          
          for(var x = 0; x<output.data[0].montly.length; x++){
            $("#sortby").append("<option value='"+output.data[0].montly[x]+"' "+(getCookie("selectMonth") == output.data[0].montly[x] ? 'selected' : '')+" >"+output.data[0].montly[x]+"</option>");
          }

        } else {
              show_message('Gagal memuat data', 'error');
        }
      });

      Sortdata.fail(function(jqXHR, textStatus){
        hide_loading_message();
        show_message('Gagal memuat data: '+textStatus, 'error');
      });

      $(document).on('click', '#LoadData', function(){
        var valMonth = $('#sortby').find(":selected").val();
        setCookie("selectMonth", valMonth, 1);
        location.reload();
      });

      /////////////////////////////////////////////////////////////////
      // Get data from current month
      /////////////////////////////////////////////////////////////////

      var Getdata = $.ajax({
        url: '<?php echo base_url('index.php/action/all/statistics'); ?>',
        type: 'POST',
        data: {
          token   : '<?php echo $this->security->get_csrf_hash(); ?>',
          action  : 'now',
          curMonth: getCookie("selectMonth")
        }
      });

      Getdata.done(function(output){
        if(output.result == 'success'){
          $('.statistiPO').html(output.data[0].jml_po);
          $('.statistiSPK').html(output.data[0].jml_wo);
          $('.statistiSJ').html(output.data[0].jml_do);
          $('.statistiFAKTUR').html(output.data[0].jml_in);
          show_message("Berhasil memuat data.", 'success');
        } else {
              show_message('Gagal memuat data', 'error');
        }
      });

      Getdata.fail(function(jqXHR, textStatus){
        hide_loading_message();
        show_message('Gagal memuat data: '+textStatus, 'error');
      });

      /////////////////////////////////////////////////////////////////
      // Set cookie as 'SelectMonth'
      /////////////////////////////////////////////////////////////////

      function setCookie(cname, cvalue, exdays) {
          var d = new Date();
          d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
          var expires = "expires="+d.toUTCString();
          document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
      }

      function getCookie(cname) {
          var name = cname + "=";
          var ca = document.cookie.split(';');
          for(var i = 0; i < ca.length; i++) {
              var c = ca[i];
              while (c.charAt(0) == ' ') {
                  c = c.substring(1);
              }
              if (c.indexOf(name) == 0) {
                  return c.substring(name.length, c.length);
              }
          }
          return "";
      }

      // Show message
      function show_message(message_text, message_type) {
        $('#message').html('<p>' + message_text + '</p>').attr('class', message_type);
        $('#message_container').show();
        if (typeof timeout_message !== 'undefined'){
          window.clearTimeout(timeout_message);
        }
        timeout_message = setTimeout(function(){
          hide_message();
        }, 3000);
      }
      
      // Hide message
      function hide_message(){
        $('#message').html('').attr('class', '');
          $('#message_container').hide();
      }

      // Show loading message
      function show_loading_message(){
          $('#loading_container').show();
      }

      // Hide loading message
      function hide_loading_message(){
          $('#loading_container').hide();
      }

    });
  </script>