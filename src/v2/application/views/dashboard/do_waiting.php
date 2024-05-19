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
            <thead>
              <tr>
                  <th>Tgl SPK</th>
                  <th>Customer</th>
                  <th>No PO</th>
                  <th>No SO</th>
                  <th>Tenggat Waktu</th>
                  <th style="min-width:100px">Option</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>

          <!-- The Modal -->
          <div id="myModal" class="modal">
            <!-- Modal content -->
            <div class="modal-content">
              <span class="close lightbox_close">&times;</span>
              
              <h2 class="FormTitle" style="text-align: center">INPUT Surat jalan</h2>
              <form class="form add" id="form_inputSJ" data-id="" novalidate>

                <div class="form-group spk_date">
                  <label for="spk_date">Tgl SPK: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="spk_date" id="spk_date" readonly>
                </div>

                <div class="form-group customer">
                  <label for="customer">Customer: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="customer" id="customer" readonly>
                  <input type="hidden" name="validasi" id="validasi" value="0">
                </div>

                <div class="form-group po_customer">
                  <label for="po_customer">No PO: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="po_customer" id="po_customer" readonly>
                </div>

                <div class="form-group no_sj">
                  <label for="no_sj">No Surat Jalan: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="no_sj" id="no_sj" value="" readonly>
                </div>
                
                <div class="form-group tanggal">
                  <label for="tanggal">Tgl Surat Jalan: <span class="required">*</span></label>
                  <input class="form-control" name="tanggal" id="tanggal" value="<?php echo date('d/m/Y'); ?>" data-date-format="dd/mm/yyyy" required>
                </div>

                <div class="form-group shipto">
                  <label for="shipto">Ship to: <span class="required">*</span></label>
                  <textarea class="form-control" name="shipto" id="shipto" minlength="15" required></textarea>
                </div>

                <div class="datanyanih"></div><hr>

                <div class="form-group nama_kurir">
                  <label for="nama_kurir">Courier Name: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="nama_kurir" id="nama_kurir" required>
                </div>

                <div class="form-group no_resi">
                  <label for="no_resi">No Tracking:</label>
                  <input type="text" class="form-control" name="no_resi" id="no_resi">
                </div>

                <div class="button_container" style="text-align: center">
                  <button type="submit" class="saving">Simpan</button>
                  <input type="button" class="lightbox_close" value="Cancel">
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
  <script src="<?php echo base_url('assets/js/bootstrap/bootstrap-datepicker.min.js'); ?>"></script>
  <script>
    $(document).ready(function()
    {
      $("#tanggal").datepicker();
      
      // Show lightbox
      function show_lightbox(){
        $('#myModal').show();
      }

      // Hide lightbox
      function hide_lightbox(){
        $('#myModal').hide();
      }

      // Show loading message
      function show_loading_message(){
          $('#loading_container').show();
      }

      // Hide loading message
      function hide_loading_message(){
          $('#loading_container').hide();
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

      // Lightbox close button
      $(document).on('click', '.lightbox_close', function(){
          hide_lightbox();
          clean_value_elemen();
      });

      //clean javascript effect
      function clean_value_elemen(){
        $(".looping-item").remove();
        $("#nama_kurir").val('');
        $("#no_resi").val('');
        $("#cost").val('0');
      }

      $('#form_inputSJ').validate();
      var tabel = $('#tablenya').DataTable({
        processing: true,
        serverSide: true,
        scrollX   : true,
        ordering  : false,
        ajax: {
          url: "<?php echo base_url('index.php/action/all/do_waiting'); ?>",
          type: "POST",
          data: function ( data ) {
            data.<?php echo $this->security->get_csrf_token_name(); ?> = "<?php echo $this->security->get_csrf_hash(); ?>";
          }
        },
        dom: 'Bfrtip',
        buttons: [
        'pageLength',
        {
          extend: 'excel',
          messageTop: false,
          text: 'Export to Excel',
          filename : 'DO-Waiting_list',
          title: 'Delivery Order Waiting',
          exportOptions: {
            columns: [ 0, 1, 2, 3, 4 ]
          }
        }],
        "lengthMenu": [[10, -1], [10, 'All']],
        iDisplayLength: 10
      });

      $(document).on('click', '.prosesDO', function(e){
        e.preventDefault();
        show_loading_message();
        var id    = $(this).data('id');
        var split = id.split('-');
        var request = $.ajax({
          url:  '<?php echo base_url('index.php/action/all/get_do_waiting'); ?>',
          type: 'POST',
          data: {
            <?php echo $this->security->get_csrf_token_name(); ?> : '<?php echo $this->security->get_csrf_hash(); ?>',
            id : split[0],
          }
        });
        request.done(function(output){
          var obj = JSON.parse(output);
          if(obj.result == 'success')
          {
            $('h2.FormTitle').text('PRATINJAU PRINT');
            $('#form_inputSJ').attr('data-id', id);
            $('#form_inputSJ').attr('class', 'form ProsesSJ');
            $('#form_inputSJ .field_container label.error').hide();
            $('#spk_date').val(obj.data[0].spk_date);
            $('#customer').val(obj.data[0].customer);
            $('#po_customer').val(obj.data[0].po_customer);
            $('#shipto').val(obj.data[0].shipto);
            for(var i = 0; i<obj.data.length; i++)
            {
              if(obj.data[i].req_qty == 0){
                var inputQTY = '<input type="number" class="form-control" name="data[qty][]" id="qty" value="0" readonly>';
              } else {
                var inputQTY = '<input type="number" min="0" class="form-control" name="data[qty][]" id="qty" placeholder="0" required>';
              }

              $('.datanyanih').append(
                '<div class="looping-item"><hr><div class="form-group no_spk"><label for="no_spk">No SO: <span class="required">*</span></label><input type="text" class="form-control" name="no_so" id="no_so" value="'+obj.data[i].no_so+'" readonly></div><div class="form-group"><label for="item">Nama Barang:</label><input type="text" class="form-control" name="data[item][]" id="item" value="'+obj.data[i].item+'" readonly></div><div class="form-group"><label for="req_qty">Request qty:</label><input type="text" class="form-control" name="data[req_qty][]" id="req_qty" value="'+obj.data[i].req_qty+'" readonly></div><div class="form-group"><label for="qty">Send qty: <span class="required">*</span></label>'+inputQTY+'</div><div class="form-group"><label for="Unit">Satuan:</label><input type="text" class="form-control" name="data[unit][]" id="unit" value="'+obj.data[i].unit+'" readonly></div><input type="hidden" class="form-control" name="data[item_to][]" id="item_to" value="'+obj.data[i].item_to+'"></div>'
                );
            }

            $.ajax({
              url:  '<?php echo base_url('index.php/action/all/no_sj'); ?>',
              type: 'POST',
              data: {
                <?php echo $this->security->get_csrf_token_name(); ?> : '<?php echo $this->security->get_csrf_hash(); ?>'
              },
              success: function(result)
              {
                var obj = JSON.parse(result);
                $('#no_sj').val(obj.data[0].no_sj);
                show_lightbox();
                hide_loading_message();
              }
            });
            
          } else {
            hide_loading_message();
            show_message(obj.message, 'error');
          }
        });
        request.fail(function(jqXHR, textStatus){
          hide_loading_message();
          show_message('Gagal mengambil data: '+textStatus, 'error');
        });
      });

      $(document).on('submit', '#form_inputSJ.ProsesSJ', function(e){
        e.preventDefault();
        if($('#form_inputSJ').valid() == true){
          hide_lightbox();
          show_loading_message();
          var id        = $('#form_inputSJ').attr('data-id');
          var form_data = $('#form_inputSJ').serialize();
          var request   = $.ajax({
            url: '<?php echo base_url('index.php/action/all/add_do_waiting'); ?>',
            data: form_data + '&' + $.param({ 'id' : id }),
            cache: false,
            type: 'POST'
          });
          request.done(function(output){
            var obj = JSON.parse(output);
            if(obj.result == 'success'){
              tabel.ajax.reload(function(){
                hide_loading_message();
                show_message(obj.message, 'success');
                clean_value_elemen();
              }, true);
            } else {
                hide_loading_message();
                show_message(obj.message, 'error');
                clean_value_elemen();
            }
          });
          request.fail(function(jqXHR, textStatus){
            hide_loading_message();
            show_message('Gagal memasukan data: '+textStatus, 'error');
            clean_value_elemen();
          });
        }
      });

    });
  </script>