<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <?php echo 'Bahan Baku';//ucfirst($this->uri->segment($this->uri->total_segments())); ?>
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
                <th>Ukuran</th>
                <th>Bahan</th>
                <th>Warna</th>
                <th>Keterangan</th>
                <th>Satuan</th>
                <th>Stok</th>
                <th>Diinput</th>
                <th>Option</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>

          <table id="tablenya2" class="datatable" style="display:none;width:100%">
            <thead>
              <tr>
                <th>Tanggal</th>
                <th>Size</th>
                <th>Bahan</th>
                <th>Warna</th>
                <th>Customer</th>
                <th>No PO</th>
                <th>Status</th>
                <th>Ukuran</th>
                <th>Keterangan</th>
                <th>Satuan</th>
                <th>Stok Awal</th>
                <th>Stok Masuk</th>
                <th>Stok Keluar</th>
                <th>Stok Akhir</th>
                <th>Diinput</th>
                <th>Option</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>

          <!-- The Modal -->
          <div id="myModal" class="modal">

            <!-- Modal content -->
            <div class="modal-content">
              <span class="close lightbox_close">&times;</span>
              
              <h2 class="FormTitle" style="text-align: center">INPUT BAHAN BAKU</h2>
              <form class="form add" id="form_inputMATERIAL" data-id="" novalidate>

                <div class="form-group date" style="display: none">
                  <label for="date">Tanggal: <span class="required">*</span></label>
                  <input type="date" class="form-control" name="date" id="date" required>
                </div>

                <div class="form-group customer" style="display: none">
                  <label for="customer">Customer: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="customer" id="customer" required>
                </div>

                <div class="form-group nopo" style="display: none">
                  <label for="nopo">No PO: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="nopo" id="nopo" required>
                </div>

                <div class="form-group ukuran" style="display: none">
                  <label for="ukuran">Ukuran: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="ukuran" id="ukuran" required>
                </div>

                <div class="form-group size">
                  <label for="size">Ukuran: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="size" id="size" value="" required>
                </div>

                <div class="form-group ingredient">
                  <label for="ingredient">Bahan: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="ingredient" id="ingredient" value="" required>
                </div>

                <div class="form-group color">
                  <label for="color">Warna: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="color" id="color" value="" required>
                </div>
                
                <div class="form-group note">
                  <label for="note">Keterangan:</label>
                  <textarea type="text" class="form-control" name="note" id="note"></textarea>
                </div>

                <div class="form-group stock">
                  <label for="stock">Stok: <span class="required">*</span></label>
                  <input type="number" min="0" class="form-control" name="stock" id="stock" required>
                </div>

                <div class="form-group s_masuk" style="display: none">
                  <label for="s_masuk">Stok Masuk: <span class="required">*</span></label>
                  <input type="number" min="0" class="form-control" name="s_masuk" id="s_masuk" required>
                </div>

                <div class="form-group s_keluar" style="display: none">
                  <label for="s_keluar">Stok Keluar: <span class="required">*</span></label>
                  <input type="number" min="0" class="form-control" name="s_keluar" id="s_keluar" required>
                </div>

                <div class="form-group unit">
                  <label for="unit">Satuan: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="unit" id="unit" required>
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
      $('#form_inputMATERIAL').validate();
      var tabel = $('#tablenya').DataTable({
        processing: true,
        serverSide: true,
        scrollX   : true,
        ordering  : false,
        ajax: {
          url: "<?php echo base_url('index.php/action/all/material'); ?>",
          type: "POST",
          data: function ( data ) {
              data.<?php echo $this->security->get_csrf_token_name(); ?> = "<?php echo $this->security->get_csrf_hash(); ?>";
          }
        },
        dom: 'Bfrtip',
        buttons: [
        'pageLength',
        <?php if($this->session->userdata('role') != '5'){ ?> {
          text: 'Input Bahan Baku',
          action: function ( e ) {
            show_lightbox();
            $('H2.FormTitle').text('INPUT BAHAN BAKU');
            $('#form_inputMATERIAL').attr('class', 'form add');
            $('#form_inputMATERIAL').attr('data-id', '');
            reset();
          }
        }, <?php } ?>
        {
          text: 'Stock History',
          action: function ( e ) {
            $('#tablenya').parents('div.dataTables_wrapper').first().hide();
            $('#tablenya2').show();
            var tabel2 = $('#tablenya2').DataTable({
              processing: true,
              serverSide: true,
              scrollX   : true,
              ordering  : false,
              ajax: {
                url: "<?php echo base_url('index.php/action/all/histori_material'); ?>",
                type: "POST",
                data: function ( data ) {
                  data.<?php echo $this->security->get_csrf_token_name(); ?> = "<?php echo $this->security->get_csrf_hash(); ?>";
                }
              },

              dom: 'Bfrtip',
              buttons: [
              'pageLength',
              {
                text: 'Back',
                action: function ( e ) {
                  e.preventDefault();
                  location.reload();
                }
              },
              {
                extend: 'excel',
                messageTop: false,
                footer: true,
                text: 'Export to Excel',
                filename : 'Material History',
                title: 'Material History',
                exportOptions: {
                  columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14 ]
                }
              }],
              "lengthMenu": [[10, -1], [10, 'All']],
              iDisplayLength: 10,
            });

            $(document).on('click', '.delhis', function(e){
              e.preventDefault();
              var Infos = $(this).data('name');
              if(confirm("Anda yakin ingin menghapus '"+Infos+"'?")){
                show_loading_message();
                var id      = $(this).data('id');
                var request = $.ajax({
                  url:          '<?php echo base_url('index.php/action/all/delhis_material/'); ?>',
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
                      tabel2.ajax.reload(function(){
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
          }
        } 
        ],
        "lengthMenu": [[10, -1], [10, 'All']],
        iDisplayLength: 10,
      });

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
        $('.date').hide();
        $('.customer').hide();
        $('.nopo').hide();
        $('.ukuran').hide();
        $('.size').show();
        $('.ingredient').show();
        $('.color').show();
        $('.stock').show();
        $('.s_masuk').hide();
        $('.s_keluar').hide();
        $('#date').val('');
        $('#customer').val('');
        $('#nopo').val('');
        $('#ukuran').val('');
        $('#size').val('');
        $('#ingredient').val('');
        $('#color').val('');
        $('#note').val('');
        $('#unit').val('');
        $('#stock').val('');
        $('#s_masuk').val('');
        $('#s_keluar').val('');
        $('#stock').attr('readonly', false);
      }

      $(document).on('submit', '#form_inputMATERIAL.add', function(e){
        e.preventDefault();
        if($('#form_inputMATERIAL').valid() == true){
          hide_lightbox();
          show_loading_message();
          var Infos = $('#size').val();
          var form_data = $('#form_inputMATERIAL').serialize();
          var request   = $.ajax({
            url: '<?php echo base_url('index.php/action/all/add_material'); ?>',
            data: form_data,
            cache: false,
            type: 'POST'
          });
          request.done(function(output){
            var obj = JSON.parse(output);
            if(obj.result == 'success'){
              tabel.ajax.reload(function(){
                hide_loading_message();
                show_message("'"+Infos+"' berhasil dimasukan", 'success');
                reset();
              }, true);
            } else {
                hide_loading_message();
                show_message(obj.message, 'error');
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
          url:  '<?php echo base_url('index.php/action/all/get_material'); ?>',
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
            $('h2.FormTitle').text('UBAH BAHAN BAKU');
            $('#form_inputMATERIAL').attr('class', 'form edit');
            $('#form_inputMATERIAL').attr('data-id', id);
            $('.field_container label.error').hide();
            hide_loading_message();
            show_lightbox();
            reset();
            $('#size').val(obj.data[0].size);
            $('#ingredient').val(obj.data[0].ingredient);
            $('#color').val(obj.data[0].color);
            $('#note').val(obj.data[0].note);
            $('#unit').val(obj.data[0].unit);
            $('#stock').val(obj.data[0].stock);
            $('#stock').attr('readonly', true);
          } else {
            hide_loading_message();
            reset();
            show_message('Gagal mengambil data', 'error');
          }
        });
        request.fail(function(jqXHR, textStatus){
          hide_loading_message();
          reset();
            show_message('Gagal mengambil data: '+textStatus, 'error');
        });
      });

      $(document).on('submit', '#form_inputMATERIAL.edit', function(e){
        e.preventDefault();
        if($('#form_inputMATERIAL').valid() == true){
          hide_lightbox();
          show_loading_message();
          var Infos = $('#size').val();
          var id        = $('#form_inputMATERIAL').attr('data-id');
          var form_data = $('#form_inputMATERIAL').serialize();
          var request   = $.ajax({
            url: '<?php echo base_url('index.php/action/all/edit_material/?id='); ?>'+id,
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
                reset();
            }
          });
          request.fail(function(jqXHR, textStatus){
            hide_loading_message();
            show_message('Gagal menyunting: '+textStatus, 'error');
            reset();
          });
        }
      });

      $(document).on('click', '.function_delete', function(e){
        e.preventDefault();
        var Infos = $(this).data('name');
        if(confirm("Anda yakin ingin menghapus '"+Infos+"'?")){
          show_loading_message();
          var id      = $(this).data('id');
          var request = $.ajax({
            url:          '<?php echo base_url('index.php/action/all/delete_material/'); ?>',
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

      $(document).on('click', '.function_ins', function(e){
        e.preventDefault();
        var id = $(this).data('id');
        show_lightbox();
        $('h2.FormTitle').text('STOK MASUK BAHAN BAKU');
        $('#form_inputMATERIAL').attr('class', 'form stokMasuk');
        $('#form_inputMATERIAL').attr('data-id', id);
        $('#form_inputMATERIAL .field_container label.error').hide();
        $('.date').show();
        $('.customer').show();
        $('.nopo').show();
        $('.ukuran').show();
        $('.size').hide();
        $('.ingredient').hide();
        $('.color').hide();
        $('.stock').hide();
        $('.s_masuk').show();
        $('.s_keluar').hide();
      });

      $(document).on('submit', '#form_inputMATERIAL.stokMasuk', function(e){
        e.preventDefault();
        if($('#form_inputMATERIAL').valid() == true){
            hide_lightbox();
            show_loading_message();
            var id        = $('#form_inputMATERIAL').attr('data-id');
            var form_data = $('#form_inputMATERIAL').serialize();
            var request   = $.ajax({
              url: '<?php echo base_url('index.php/action/all/mashis_material'); ?>',
              data: form_data + '&' + $.param({ 'id' : id }),
              cache: false,
              type: 'POST'
          });
          request.done(function(output){
            var obj = JSON.parse(output);
            if(obj.result == 'success'){
              tabel.ajax.reload(function(){
                hide_loading_message();
                show_message("Berhasil menambahkan stok", 'success');
                reset();
              }, true);
            } else {
              hide_loading_message();
              show_message("Gagal menambahkan stok", 'error');
              reset();
            }
          });
          request.fail(function(jqXHR, textStatus){
            hide_loading_message();
            show_message('Gagal menambahkan stok: '+textStatus, 'error');
            reset();
          });
        }
      });

      $(document).on('click', '.function_outs', function(e){
        e.preventDefault();
        show_lightbox();
        var id = $(this).data('id');
        $('h2.FormTitle').text('STOK KELUAR BAHAN BAKU');
        $('#form_inputMATERIAL').attr('class', 'form stokKeluar');
        $('#form_inputMATERIAL').attr('data-id', id);
        $('#form_inputMATERIAL .field_container label.error').hide();
        $('.date').show();
        $('.customer').show();
        $('.nopo').show();
        $('.ukuran').show();
        $('.size').hide();
        $('.ingredient').hide();
        $('.color').hide();
        $('.stock').hide();
        $('.s_masuk').hide();
        $('.s_keluar').show();
      });

      $(document).on('submit', '#form_inputMATERIAL.stokKeluar', function(e){
        e.preventDefault();
        if($('#form_inputMATERIAL').valid() == true){
            hide_lightbox();
            show_loading_message();
            var id        = $('#form_inputMATERIAL').attr('data-id');
            var form_data = $('#form_inputMATERIAL').serialize();
            var request   = $.ajax({
              url: '<?php echo base_url('index.php/action/all/kelhis_material'); ?>',
              data: form_data + '&' + $.param({ 'id' : id }),
              cache: false,
              type: 'POST'
          });
          request.done(function(output){
            var obj = JSON.parse(output);
            if(obj.result == 'success'){
              tabel.ajax.reload(function(){
                hide_loading_message();
                show_message("Berhasil mengurangkan stok", 'success');
                reset_field(); reset_value();
              }, true);
            } else {
              hide_loading_message();
              show_message("Gagal mengurangkan stok", 'error');
              reset_field();
            }
          });
          request.fail(function(jqXHR, textStatus){
            hide_loading_message();
            show_message('Gagal mengurangkan stok: '+textStatus, 'error');
            reset_field();
          });
        }
      });

    });
  </script>