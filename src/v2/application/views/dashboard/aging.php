<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <?php echo ucfirst($this->uri->segment($this->uri->total_segments())); ?>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"><?php echo ucfirst($this->uri->segment($this->uri->total_segments())); ?></li>
      </ol>
    </section>

    <hr>

    <!-- Main content -->
    <section class="content">
      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class ="container-fluid">
          <table id="tablenya" class="table table-bordered" style="width:100%">
            <caption>
              <div id="widthSortby" class="input-group">
                <span class="input-group-addon">Sort by </span>
                <select id="sortby" class="form-control"></select>
                <span class="input-group-btn">
                  <button type="button" id="LoadData" class="btn btn-default">View</button>
                </span>
              </div>
            </caption>
            <thead>
              <tr>
                  <th>Customer</th>
                  <th>Company</th>
                  <th>Invoice No</th>
                  <th>No Surat</th>
                  <th>No SO</th>
                  <th>No PO</th>
                  <th>Invoice Date</th>
                  <th>Due Date</th>
                  <th>Amount</th>
                  <th>Tgl Lunas</th>
                  <th>Keterangan</th>
                  <th>Ongkir</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>

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
    $(document).ready(function(){
      function convertToRupiah(angka){
        var checked = angka.toString().split('.').join(',');
        var filter = 'Rp. ' + checked.replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1.");
        return filter;
      }

      /////////////////////////////////////////////////////////////////
      // Sort datatable from current month
      /////////////////////////////////////////////////////////////////

      var req = $.ajax({
        url: '<?php echo base_url('index.php/action/all/sort_month'); ?>',
        type: 'POST',
        data: {
          <?php echo $this->security->get_csrf_token_name(); ?>   : '<?php echo $this->security->get_csrf_hash(); ?>',
          table   : 'invoice',
          column  : 'invoice_date',
          where   : '',
          order   : 'invoice_date'
        }
      });

      req.done(function(output){
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

      $(document).on('click', '#LoadData', function(){
        var valMonth = $('#sortby').find(":selected").val();
        setCookie("selectMonth", valMonth, 1);
        location.reload();
      });

      var tabel = $('#tablenya').DataTable({
        processing: true,
        serverSide: true,
        scrollX   : true,
        ordering  : false,
        ajax: {
          url: "<?php echo base_url('index.php/action/all/aging'); ?>",
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
          messageTop: false,
          footer: true,
          text: 'Export to Excel',
          filename : 'Aging-'+getCookie("selectMonth"),
          title: 'AGING '+getCookie("selectMonth"),
          exportOptions: {
            columns: [ 0,1,2,3,4,5,6,7,8,9,10,11 ]
          }
        }],
        iDisplayLength: -1,
        "footerCallback": function ( row, data, start, end, display ) {
          var api = this.api(), data;
          var intVal = function ( i ) {
              return typeof i === 'string' ?
                  i.replace(/[\$,]/g, '')*1 :
                  typeof i === 'number' ?
                      i : 0;
          };

          Amounts = api.column( 8, { page: 'current'} ).data().reduce( function (a, b) {
            return intVal(a) + intVal(b);
          }, 0 );

          Costs = api.column( 11, { page: 'current'} ).data().reduce( function (a, b) {
            return intVal(a) + intVal(b);
          }, 0 );

          $( api.column( 8 ).footer() ).html(convertToRupiah(Amounts));
          $( api.column( 11 ).footer() ).html(convertToRupiah(Costs));
        }
      });

    });
  </script>