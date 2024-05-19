  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <?php echo ucfirst($this->uri->segment($this->uri->total_segments()));?>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"><?php echo ucfirst($this->uri->segment($this->uri->total_segments()));?></li>
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
                  <th>Vendor Name</th>
                  <th style="max-width:600px">Address</th>
                  <th>Phone</th>
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
              
              <h2 class="FormTitle" style="text-align: center">INPUT VENDOR</h2>
              <form class="form vendor_new" id="form_vendor" data-id="" novalidate>
                <hr>
                
                <div class="form-group vendor">
                  <label for="vendor">Nama Vendor: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="vendor" id="vendor" required>
                </div>

                <div class="form-group address">
                  <label for="address">Alamat: <span class="required">*</span></label>
                  <textarea class="form-control" name="address" id="address" required></textarea>
                </div>

                <div class="form-group phone">
                  <label for="phone">Telp: <span class="required">*</span></label>
                  <input type="number" min="0" class="form-control" name="phone" id="phone" required>
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
  <script>
    $(document).ready(function()
    {
      $('#form_vendor').validate();
      var tabel = $('#tablenya').DataTable({
        processing: true,
        serverSide: true,
        scrollX   : true,
        ordering  : false,
        ajax: {
          url: "<?php echo base_url('index.php/action/all/vendor'); ?>",
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
          footer: true,
          text: 'Export to Excel',
          filename : 'Vendor',
          title: 'Data Vendor',
          exportOptions: {
            columns: [ 0, 1, 2 ]
          }
        },
        <?php if($_SESSION['role'] != '5'){ ?>{
          text: 'Create Vendor',
          action: function ( e ) {
            show_lightbox();
            $('H2.FormTitle').text('INPUT VENDOR');
            $('#form_vendor').attr('class', 'form vendor_new');
            $('#form_vendor').attr('data-id', '');
          }
        } <?php } ?>
        ],
        "lengthMenu": [[10, -1], [10, 'All']],
        iDisplayLength: 10,
      });

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
          hide_lightbox();
          reset();
      });

      function reset(){
        $('#vendor').val('');
        $('#address').val('');
        $('#phone').val('');
      }

      $(document).on('submit', '#form_vendor.vendor_new', function(e){
        e.preventDefault();
        if ($('#form_vendor').valid() == true){
          hide_lightbox();
          show_loading_message();
          var Infos = $('#vendor').val();
          var form_data = $('#form_vendor').serialize();
          var request   = $.ajax({
            url: '<?php echo base_url('index.php/action/all/add_vendor'); ?>',
            cache: false,
            data: form_data,
            type: 'POST'
          });

          request.done(function(output){
            var obj = JSON.parse(output);
            if (obj.result == 'success'){
              tabel.ajax.reload(function(){
                hide_loading_message();
                show_message("'"+Infos+"' berhasil dimasukan", 'success');
                reset();
              }, true);
            } else {
                hide_loading_message();
                show_message('Gagal memasukan data', 'error');
            }
          });

          request.fail(function(jqXHR, textStatus){
            hide_loading_message();
            show_message('Gagal memasukan data: '+textStatus, 'error');
          });
        }
      });

      $(document).on('click', '.function_edit', function(e){
        e.preventDefault();
          show_loading_message();
          var id      = $(this).data('id');
          var request = $.ajax({
            url:  '<?php echo base_url('index.php/action/all/get_vendor'); ?>',
            cache:false,
            type: 'POST',
            data: {
              <?php echo $this->security->get_csrf_token_name(); ?> : '<?php echo $this->security->get_csrf_hash(); ?>',
              id : id
            }
          });
          request.done(function(output){
            var obj = JSON.parse(output);
            if(obj.result == 'success'){
                $('h2.FormTitle').text('UBAH VENDOR');
                $('#form_vendor').attr('class', 'form edit_vendor');
                $('#form_vendor').attr('data-id', id);
                $('.field_container label.error').hide();
                $('#vendor').val(obj.data[0].vendor);
                $('#address').val(obj.data[0].address);
                $('#phone').val(obj.data[0].phone);
                hide_loading_message();
                show_lightbox();
              } else {
                hide_loading_message();
                show_message('Gagal mengambil data', 'error');
              }
          });
          request.fail(function(jqXHR, textStatus){
            hide_loading_message();
              show_message('Gagal mengambil data: '+textStatus, 'error');
          });
      });

      $(document).on('submit', '#form_vendor.edit_vendor', function(e){
        e.preventDefault();
        if($('#form_vendor').valid() == true){
          hide_lightbox();
          show_loading_message();
          var Infos = $('#vendor').val();
          var id        = $('#form_vendor').attr('data-id');
          var form_data = $('#form_vendor').serialize();
          var request   = $.ajax({
            url: '<?php echo base_url('index.php/action/all/edit_vendor/?id='); ?>'+id,
            data: form_data,
            cache: false,
            type: 'POST'
          });
          request.done(function(output){
            var obj = JSON.parse(output);
            if(obj.result == 'success'){
                tabel.ajax.reload(function(){
                  hide_loading_message();
                  show_message("'"+Infos+"' berhasil disunting", 'success');
                  reset();
                }, true);
            } else {
                hide_loading_message();
                show_message('Gagal menyunting', 'error');
            }
          });
          request.fail(function(jqXHR, textStatus){
            hide_loading_message();
            show_message('Gagal menyunting: '+textStatus, 'error');
          });
        }
      });

      $(document).on('click', '.function_delete', function(e){
        e.preventDefault();
        var Infos = $(this).data('name');
        if (confirm("Anda yakin ingin menghapus '"+Infos+"'?")){
          show_loading_message();
          var id      = $(this).data('id');
          var request = $.ajax({
            url:          '<?php echo base_url('index.php/action/all/delete_vendor/'); ?>',
            cache:        false,
            type:         'POST',
            data: {
              <?php echo $this->security->get_csrf_token_name(); ?> : '<?php echo $this->security->get_csrf_hash(); ?>',
              id : id
            }
          });
          
          request.done(function(output){
            var obj = JSON.parse(output);
            if(obj.result == 'success'){
                tabel.ajax.reload(function(){
                  hide_loading_message();
                  show_message("'"+Infos+"' berhasil dihapus", 'success');
                }, true);
            } else {
                hide_loading_message();
                show_message('Gagal menghapus', 'error');
            }
          });
          
          request.fail(function(jqXHR, textStatus){
            hide_loading_message();
            show_message('Gagal menghapus: '+textStatus, 'error');
          });
        }
      });

    });
  </script>