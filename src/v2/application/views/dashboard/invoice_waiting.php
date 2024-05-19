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
          <form id="create_invoice">
          <table id="tablenya" class="table table-bordered" style="width:100%">
            <thead>
              <tr>
                  <th></th>
                  <th>Tgl Surat Jalan</th>
                  <th style="min-width:150px">Customer</th>
                  <th>No PO</th>
                  <th>No SO</th>
                  <th>Surat Jalan</th>
                  <th>Qty</th>
                  <th>Unit</th>
                  <th style="min-width:100px">Harga</th>
                  <th style="min-width:150px">Tagihan</th>
                  <th style="min-width:150px">Ppn</th>
                  <th style="min-width:150px">Total</th>
                  <th style="min-width:100px">Biaya Kirim</th>
                  <th style="min-width:100px">Option</th>
              </tr>
            </thead>
            <tbody></tbody>
            <tfoot>
              <tr>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th class="text-right" style="font-weight: bold">Total Amount:</th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
              </tr>
            </tfoot>
          </table>
          </form>

          <!-- Modal Invoice -->
          <div id="InvoiceModal" class="modal">

            <!-- Modal content -->
            <div class="modal-content">
              <span class="close lightbox_close">&times;</span>
              
              <h2 class="FormTitle" style="text-align: center">INPUT INVOICE</h2>
              <form class="form add" id="form_inputINV" data-id="" novalidate>

                <div class="form-group date">
                  <label for="date">Tanggal: <span class="required">*</span></label>
                  <input class="form-control" name="date" id="date" value="<?php echo date('d/m/Y'); ?>" data-date-format="dd/mm/yyyy" required>
                </div>
        
                <div class="button_container" style="text-align: center">
                  <button type="submit" class="saving">Simpan</button>
                  <input type="button" class="lightbox_close" value="Batal">
                  <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                </div>
              </form>
            </div>
          </div>

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
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <script src="<?php echo base_url('assets/js/datatables/dataTables.checkboxes.min.js'); ?>"></script>
  <script src="<?php echo base_url('assets/js/bootstrap/bootstrap-datepicker.min.js'); ?>"></script>
  <script>
    $(document).ready(function()
    {
      $("#date").datepicker();
      function convertToRupiah(angka){
        var checked = angka.toString().split('.').join(',');
        var filter = 'Rp. ' + checked.replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1.");
        return filter;
      }

      // Show message
      function show_message(message_text, message_type){
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

      // Show lightbox
      function show_lightbox(){
        $('#myModal').show();
      }

      // Hide lightbox
      function hide_lightbox(){
        $('#myModal').hide();
      }

      // Lightbox close button
      $(document).on('click', '.lightbox_close', function(){
        hide_lightbox(); hide_invoice();
      });

      var tabel = $('#tablenya').DataTable({
        processing: true,
        serverSide: true,
        scrollX   : true,
        ordering  : false,
        ajax: {
          url: "<?php echo base_url('index.php/action/all/invoice_waiting'); ?>",
          type: "POST",
          data: function ( data ) {
            data.<?php echo $this->security->get_csrf_token_name(); ?> = "<?php echo $this->security->get_csrf_hash(); ?>";
          }
        },
        'columnDefs': [
        {
          'targets': 0,
          'checkboxes': {
            'selectRow': true
          }
        }],
        'select': {
          'style': 'multi'
        },
        dom: 'Bfrtip',
        buttons: [
        'pageLength',
        {
          extend: 'excel',
          messageTop: false,
          footer: true,
          text: 'Export to Excel',
          filename : 'INVOICE-Waiting',
          title: 'Invoice Waiting',
          exportOptions: {
            columns: [ 1,2,3,4,5,6,7,8,9,10,11,12 ]
          }
        },
        { text: '<span class="multi_invoice">Create Invoice custom</span>' }
        ],
        "lengthMenu": [[10, -1], [10, 'All']],
        iDisplayLength: 10,
        "footerCallback": function ( row, data, start, end, display ) {
          var api = this.api(), data;
          var intVal = function ( i ) {
              return typeof i === 'string' ?
                  i.replace(/[\$,]/g, '')*1 :
                  typeof i === 'number' ?
                      i : 0;
          };
          
          Bills = api.column( 9, { page: 'current'} ).data().reduce( function (a, b) {
            return intVal(a) + intVal(b);
          }, 0 );

          Ppns = api.column( 10, { page: 'current'} ).data().reduce( function (a, b) {
            return intVal(a) + intVal(b);
          }, 0 );

          Totals = Bills + Ppns;
          
          Ship_cost = api.column( 12, { page: 'current'} ).data().reduce( function (a, b) {
            return intVal(a) + intVal(b);
          }, 0 );

          $( api.column( 9 ).footer() ).html(convertToRupiah(Bills));
          $( api.column( 10 ).footer() ).html(convertToRupiah(Ppns));
          $( api.column( 11 ).footer() ).html(convertToRupiah(Totals));
          $( api.column( 12 ).footer() ).html(convertToRupiah(Ship_cost));
        }
      });

      /////////////////////////////////////////
      // Modal Invoice
      ////////////////////////////////////////

      function show_invoice(){
        $('#InvoiceModal').show();
      }

      function hide_invoice(){
        $('#InvoiceModal').hide();
      }

      /////////////////////////////////////////
      // Single invoice
      ////////////////////////////////////////

      $(document).on('click', '.single_invoice', function(e)
      {
        e.preventDefault();
        var id = $(this).data('id');
        var name = $(this).data('name');
        if(confirm("Anda yakin ingin membuat faktur atas surat jalan "+name+" ?")){
          show_invoice();
          $('#form_inputINV').attr('data-id', id);
          $('#form_inputINV').attr('class', 'form single');
        }
      });

      $(document).on('submit', '#form_inputINV.single', function(e){
        e.preventDefault();
        if($('#form_inputINV').valid() == true){
          hide_invoice();
          show_loading_message();
          var form_data = $('#form_inputINV').serialize();
          var id        = $('#form_inputINV').attr('data-id').split('-');
          var request   = $.ajax({
            url: '<?php echo base_url('index.php/action/all/create_single_invoice'); ?>',
            data: form_data + '&' + $.param({ 'id_fk' : id[0], 'id_sj' : id[1] }),
            cache: false,
            type: 'POST'
          });
          request.done(function(output){
            var obj = JSON.parse(output);
            if(obj.result == 'success'){
              tabel.ajax.reload(function(){
                hide_loading_message();
                show_message(obj.message, 'success');
              }, true);
            } else {
                hide_loading_message();
                show_message(obj.message, 'error');
            }
          });
          request.fail(function(jqXHR, textStatus){
            hide_loading_message();
            show_message('Gagal memproses: '+textStatus, 'error');
          });
        }
      });

      /////////////////////////////////////////
      // Multiple invoice
      ////////////////////////////////////////

      $(document).on('click', '.multi_invoice', function(e){
        e.preventDefault();
        var form = $('#create_invoice');
        var rows_selected = tabel.column(0).checkboxes.selected();
        if(typeof rows_selected[0] === 'undefined')
        {
          alert("Silahkan pilih dan centang terlebih dahulu.");

        } else {

          $.each(rows_selected, function(index, rowId){
            $(form).append($('<input>').attr('type', 'hidden').attr('name', 'id[]').val(rowId));
          });

          if(confirm("Anda yakin ingin menggabungkan faktur yang dicentang?"))
          {
            var id = rows_selected.join(",");
            show_invoice();
            $('#form_inputINV').attr('data-id', id);
            $('#form_inputINV').attr('class', 'form multiple');
          }

          $('input[name="id\[\]"]', form).remove();
        }
      });

      $(document).on('submit', '#form_inputINV.multiple', function(e){
        e.preventDefault();
        if($('#form_inputINV').valid() == true){
          hide_invoice();
          show_loading_message();
          var form_data = $('#form_inputINV').serialize();
          var id        = $('#form_inputINV').attr('data-id');
          var request   = $.ajax({
            url: '<?php echo base_url('index.php/action/all/create_multi_invoice'); ?>',
            data: form_data + '&' + $.param({ 'id' : id }),
            cache: false,
            type: 'POST'
          });
          request.done(function(output){
            var obj = JSON.parse(output);
            if(obj.result == 'success'){
              hide_loading_message();
              tabel.ajax.reload(function(){
                show_message(obj.message, 'success');
              }, true);
            } else {
                hide_loading_message();
                show_message(obj.message, 'error');
            }
          });
          request.fail(function(jqXHR, textStatus){
            hide_loading_message();
            show_message('Gagal memproses: '+textStatus, 'error');
          });
        }
      });

    });
  </script>