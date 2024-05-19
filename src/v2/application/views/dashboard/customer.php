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
                  <th>Bill Name</th>
                  <th style="max-width:300px">Bill Address</th>
                  <th>Ship Name</th>
                  <th style="max-width:300px">Ship Address</th>
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
              
              <h2 class="FormTitle" style="text-align: center">INPUT CUSTOMER</h2>
              <form class="form cs_new" id="form_cs" data-id="" novalidate>
                <hr>

                <div class="form-group">
                  <h3>Bill Information</h3>
                </div>
                
                <div class="form-group b_nama">
                  <label for="b_nama">Nama : <span class="required">*</span></label>
                  <input type="text" class="form-control" name="b_nama" id="b_nama" required>
                </div>

                <div class="form-group b_alamat">
                  <label for="b_alamat">Address: <span class="required">*</span></label>
                  <textarea class="form-control" name="b_alamat" id="b_alamat" required></textarea>
                </div>

                <div class="form-group b_kota">
                  <label for="b_kota">Kota: <span class="required">*</span></label>
                  <input class="form-control" name="b_kota" id="b_kota" required>
                </div>

                <div class="form-group b_negara">
                  <label for="b_negara">Negara: <span class="required">*</span></label>
                  <select name="b_negara" id="b_negara" class="form-control">
                    <option value="" selected disabled>Pilih negara</option>
                    <option value="Indonesia">Indonesia</option>
                  </select>
                </div>

                <div class="form-group b_provinsi">
                  <label for="b_provinsi">Provinsi: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="b_provinsi" id="b_provinsi" required>
                </div>

                <div class="form-group b_kodepos">
                  <label for="b_kodepos">Kode Pos: <span class="required">*</span></label>
                  <input type="number" min="0" class="form-control" name="b_kodepos" id="b_kodepos" required>
                </div>

                <div class="form-group b_telp">
                  <label for="b_telp">No Telepon: <span class="required">*</span></label>
                  <input type="number" min="0" maxlength="14" class="form-control" name="b_telp" id="b_telp" required>
                </div>

                <hr>

                <div class="form-group">
                  <h3>Ship Information</h3>
                </div>
                
                <div class="form-group s_nama">
                  <label for="s_nama">Nama :</label>
                  <input type="text" class="form-control" name="s_nama" id="s_nama">
                </div>

                <div class="form-group s_alamat">
                  <label for="s_alamat">Address:</label>
                  <textarea class="form-control" name="s_alamat" id="s_alamat"></textarea>
                </div>

                <div class="form-group s_kota">
                  <label for="s_kota">Kota:</label>
                  <input class="form-control" name="s_kota" id="s_kota">
                </div>

                <div class="form-group s_negara">
                  <label for="s_negara">Negara:</label>
                  <select name="s_negara" id="s_negara" class="form-control">
                    <option value="" selected disabled>Pilih negara</option>
                    <option value="Indonesia">Indonesia</option>
                  </select>
                </div>

                <div class="form-group s_provinsi">
                  <label for="s_provinsi">Provinsi:</label>
                  <input type="text" class="form-control" name="s_provinsi" id="s_provinsi">
                </div>

                <div class="form-group s_kodepos">
                  <label for="s_kodepos">Kode Pos:</label>
                  <input type="number" min="0" class="form-control" name="s_kodepos" id="s_kodepos">
                </div>

                <div class="form-group s_telp">
                  <label for="s_telp">No Telepon:</label>
                  <input type="number" min="0" maxlength="14" class="form-control" name="s_telp" id="s_telp">
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
      $('#form_cs').validate();
      var tabel = $('#tablenya').DataTable({
        processing: true,
        serverSide: true,
        scrollX   : true,
        ordering  : false,
        ajax: {
          url: "<?php echo base_url('index.php/action/all/customer'); ?>",
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
          filename : 'Customer',
          title: 'Data Customer',
          exportOptions: {
            columns: [ 0, 1, 2, 3 ]
          }
        },
        <?php if($this->session->userdata('role') != '5'){ ?>{
          text: 'Create Customer',
          action: function ( e ) {
            show_lightbox();
            $('H2.FormTitle').text('INPUT CUSTOMER');
            $('#form_cs').attr('class', 'form cs_new');
            $('#form_cs').attr('data-id', '');
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
        $('#b_nama').val('');
        $('#b_alamat').val('');
        $('#b_kota').val('');
        $('#b_negara').val('');
        $('#b_provinsi').val('');
        $('#b_kodepos').val('');
        $('#b_telp').val('');
        $('#s_nama').val('');
        $('#s_alamat').val('');
        $('#s_kota').val('');
        $('#s_negara').val('');
        $('#s_provinsi').val('');
        $('#s_kodepos').val('');
        $('#s_telp').val('');
      }

      $(document).on('submit', '#form_cs.cs_new', function(e){
        e.preventDefault();
        if ($('#form_cs').valid() == true){
          hide_lightbox();
          show_loading_message();
          var Infos = $('#b_nama').val();
          var form_data = $('#form_cs').serialize();
          var request   = $.ajax({
            url: '<?php echo base_url('index.php/action/all/add_customer'); ?>',
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
            url:  '<?php echo base_url('index.php/action/all/get_customer'); ?>',
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
                $('h2.FormTitle').text('UBAH CUSTOMER');
                $('#form_cs').attr('class', 'form edit_cs');
                $('#form_cs').attr('data-id', id);
                $('.field_container label.error').hide();
                $('#b_nama').val(obj.data[0].b_nama);
                $('#b_alamat').val(obj.data[0].b_alamat);
                $('#b_kota').val(obj.data[0].b_kota);
                $('#b_negara').val(obj.data[0].b_negara);
                $('#b_provinsi').val(obj.data[0].b_provinsi);
                $('#b_kodepos').val(obj.data[0].b_kodepos);
                $('#b_telp').val(obj.data[0].b_telp);
                $('#s_nama').val(obj.data[0].s_nama);
                $('#s_alamat').val(obj.data[0].s_alamat);
                $('#s_kota').val(obj.data[0].s_kota);
                $('#s_negara').val(obj.data[0].s_negara);
                $('#s_provinsi').val(obj.data[0].s_provinsi);
                $('#s_kodepos').val(obj.data[0].s_kodepos);
                $('#s_telp').val(obj.data[0].s_telp);
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

      $(document).on('submit', '#form_cs.edit_cs', function(e){
        e.preventDefault();
        if($('#form_cs').valid() == true){
          hide_lightbox();
          show_loading_message();
          var Infos = $('#b_nama').val();
          var id        = $('#form_cs').attr('data-id');
          var form_data = $('#form_cs').serialize();
          var request   = $.ajax({
            url: '<?php echo base_url('index.php/action/all/edit_customer/?id='); ?>'+id,
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
            url:          '<?php echo base_url('index.php/action/all/delete_customer/'); ?>',
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