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
                  <th>Rak</th>
                  <th>Customer</th>
                  <th>Nama Barang</th>
                  <th>Ukuran</th>
                  <th>Bahan</th>
                  <th>Core</th>
                  <th>Line</th>
                  <th>Isi Roll</th>
                  <th>Stock</th>
                  <th>Diinput</th>
                  <th style="width:200px">Option</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>

          <table id="tablenya2" class="datatable" style="display:none;width:100%">
            <thead>
              <tr>
                  <th>Tanggal</th>
                  <th>Rak</th>
                  <th>Nama</th>
                  <th>No S.Jalan</th>
                  <th>No PO</th>
                  <th>Status</th>
                  <th>Nama Barang</th>
                  <th>Ukuran</th>
                  <th>Bahan</th>
                  <th>Core</th>
                  <th>Line</th>
                  <th>Gulungan</th>
                  <th>Content (PCS)</th>
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
              
              <h2 class="FormTitle" style="text-align: center">INPUT LABEL</h2>
              <form class="form add" id="form_inputLABEL" data-id="" novalidate>

                <div class="form-group rak">
                  <label for="rak">Rak:</label>
                  <input type="text" class="form-control" name="rak" id="rak">
                </div>

                <div class="form-group customer">
                  <label for="name">Customer:</label>
                  <input type="text" class="form-control" name="customer" id="customer">
                </div>

                <div class="form-group product">
                  <label for="product">Nama Barang: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="product" id="product" required>
                </div>

                <div class="form-group size">
                  <label for="Ukuran">Ukuran: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="size" id="size" required>
                </div>

                <div class="form-group material">
                  <label for="Bahan">Bahan: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="material" id="material" required>
                </div>

                <div class="form-group core">
                  <label for="qore">Qore: <span class="required">*</span></label>
                  <input type="number" min="1" class="form-control" name="core" id="core" required>
                </div>

                <div class="form-group line">
                  <label for="line">Line: <span class="required">*</span></label>
                  <input type="number" min="1" class="form-control" name="line" id="line" required>
                </div>

                <div class="form-group per_roll">
                  <label for="per_roll">Per Roll: <span class="required">*</span></label>
                  <input type="number" class="form-control" name="per_roll" id="per_roll" required>
                </div>

                <div class="form-group stock" style="display: none">
                  <label for="stok">Stok: <span class="required">*</span></label>
                  <input type="number" min="1" class="form-control" name="stock" id="stock" required>
                </div>

                <div class="form-group nama_" style="display: none">
                  <label for="nama_">Customer: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="nama_" id="nama_" required>
                </div>

                <div class="form-group tgl_" style="display: none">
                  <label for="tanggal">Tanggal: <span class="required">*</span></label>
                  <input type="date" class="form-control" name="tgl_" id="tgl_" required>
                </div>

                <div class="form-group nosj_" style="display: none">
                  <label for="nosj">No Surat Jalan: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="nosj_" id="nosj_" required>
                </div>

                <div class="form-group nopo_" style="display: none">
                  <label for="nopo">No PO: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="nopo_" id="nopo_" required>
                </div>

                <div class="form-group roll_" style="display: none">
                  <label for="roll">Gulungan: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="roll_" id="roll_" required>
                </div>

                <div class="form-group content_" style="display: none">
                  <label for="content">Content (PCS): <span class="required">*</span></label>
                  <input type="text" class="form-control" name="content_" id="content_" required>
                </div>

                <div class="form-group unit_" style="display: none">
                  <label for="unit">Satuan: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="unit_" id="unit_" required>
                </div>

                <div class="form-group smasuk_" style="display: none">
                  <label for="smasuk">Stok Masuk: <span class="required">*</span></label>
                  <input type="number" class="form-control" name="smasuk_" id="smasuk_" required>
                </div>

                <div class="form-group skeluar_" style="display: none">
                  <label for="skeluar">Stok Keluar: <span class="required">*</span></label>
                  <input type="number" class="form-control" name="skeluar_" id="skeluar_" required>
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
      $('#form_inputLABEL').validate();
      var tabel = $('#tablenya').DataTable({
        processing: true,
        serverSide: true,
        scrollX   : true,
        ordering  : false,
        ajax: {
          url: "<?php echo base_url('index.php/action/all/label'); ?>",
          type: "POST",
          data: function ( data ) {
              data.<?php echo $this->security->get_csrf_token_name(); ?> = "<?php echo $this->security->get_csrf_hash(); ?>";
          }
        },
        dom: 'Bfrtip',
        buttons: [
        'pageLength',
        <?php if($this->session->userdata('role') != '5'){ ?> {
          text: 'Input Label',
          action: function ( e ) {
            show_lightbox();
            $('H2.FormTitle').text('INPUT LABEL');
            $('#form_inputLABEL').attr('class', 'form add');
            $('#form_inputLABEL').attr('data-id', '');
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
                url: "<?php echo base_url('index.php/action/all/histori_label'); ?>",
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
                filename : 'Label History',
                title: 'Label History',
                exportOptions: {
                  columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18 ]
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
                  url:          '<?php echo base_url('index.php/action/all/delhis_label/'); ?>',
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

      //Reset value
      function reset(){
        $('#rak').val('');
        $('#customer').val('');
        $('#product').val('');
        $('#size').val('');
        $('#material').val('');
        $('#core').val('');
        $('#line').val('');
        $('#per_roll').val('');
        $('#stock').val('');
        $('#nama_').val('');
        $('#tgl_').val('');
        $('#rak_').val('');
        $('#nosj_').val('');
        $('#nopo_').val('');
        $('#product_').val('');
        $('#size_').val('');
        $('#material_').val('');
        $('#core_').val('');
        $('#line_').val('');
        $('#roll_').val('');
        $('#content_').val('');
        $('#unit_').val('');
        $('#smasuk_').val('');
        $('#skeluar_').val('');
        $('.rak').show();
        $('.customer').show();
        $('.product').show();
        $('.size').show();
        $('#size').attr('readonly', false);
        $('.material').show();
        $('.core').show();
        $('.line').show();
        $('.per_roll').show();
        $('.stock').show();
        $('.nama_').hide();
        $('.tgl_').hide();
        $('.nosj_').hide();
        $('.nopo_').hide();
        $('.roll_').hide();
        $('.content_').hide();
        $('.unit_').hide();
        $('.smasuk_').hide();
        $('.skeluar_').hide();
      }

      $(document).on('submit', '#form_inputLABEL.add', function(e){
        e.preventDefault();
        if($('#form_inputLABEL').valid() == true){
          hide_lightbox();
          show_loading_message();
          var Infos = $('#product').val();
          var form_data = $('#form_inputLABEL').serialize();
          var request   = $.ajax({
            url: '<?php echo base_url('index.php/action/all/add_label'); ?>',
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
          url:  '<?php echo base_url('index.php/action/all/get_label'); ?>',
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
            $('h2.FormTitle').text('UBAH LABEL');
            $('#form_inputLABEL').attr('class', 'form edit');
            $('#form_inputLABEL').attr('data-id', id);
            $('.field_container label.error').hide();
            reset();
            hide_loading_message();
            show_lightbox();
            $('#rak').val(obj.data[0].rak);
            $('#customer').val(obj.data[0].customer);
            $('#product').val(obj.data[0].product);
            $('#size').val(obj.data[0].size);
            $('#material').val(obj.data[0].material);
            $('#core').val(obj.data[0].core);
            $('#line').val(obj.data[0].line);
            $('#per_roll').val(obj.data[0].per_roll);
            $('#stock').val(obj.data[0].stock).attr('readonly', true);
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

      $(document).on('submit', '#form_inputLABEL.edit', function(e){
        e.preventDefault();
        if($('#form_inputLABEL').valid() == true){
          hide_lightbox();
          show_loading_message();
          var Infos = $('#product').val();
          var id        = $('#form_inputLABEL').attr('data-id');
          var form_data = $('#form_inputLABEL').serialize();
          var request   = $.ajax({
            url: '<?php echo base_url('index.php/action/all/edit_label/?id='); ?>'+id,
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
        if(confirm("Anda yakin ingin menghapus '"+Infos+"'?")){
          show_loading_message();
          var id      = $(this).data('id');
          var request = $.ajax({
            url:          '<?php echo base_url('index.php/action/all/delete_label/'); ?>',
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
        $('h2.FormTitle').text('STOK MASUK LABEL');
        $('#form_inputLABEL').attr('class', 'form stokMasuk');
        $('#form_inputLABEL').attr('data-id', id);
        $('#form_inputLABEL .field_container label.error').hide();
        $('.rak').hide();
        $('.customer').hide();
        $('.product').hide();
        $('.size').hide();
        $('.material').hide();
        $('.core').hide();
        $('.line').hide();
        $('.per_roll').hide();
        $('.stock').hide();
        $('.nama_').show();
        $('.tgl_').show();
        $('.nosj_').show();
        $('.nopo_').show();
        $('.roll_').show();
        $('.content_').show();
        $('.unit_').show();
        $('.smasuk_').show();
      });

      $(document).on('submit', '#form_inputLABEL.stokMasuk', function(e){
        e.preventDefault();
        if($('#form_inputLABEL').valid() == true){
            hide_lightbox();
            show_loading_message();
            var id        = $('#form_inputLABEL').attr('data-id');
            var form_data = $('#form_inputLABEL').serialize();
            var request   = $.ajax({
              url: '<?php echo base_url('index.php/action/all/mashis_label'); ?>',
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
                reset_all();
              }, true);
            } else {
              hide_loading_message();
              show_message("Gagal menambahkan stok", 'error');
            }
          });
          request.fail(function(jqXHR, textStatus){
            hide_loading_message();
            show_message('Gagal menambahkan stok: '+textStatus, 'error');
          });
        }
      });

      $(document).on('click', '.function_outs', function(e){
        e.preventDefault();
        show_lightbox();
        var id = $(this).data('id');
        $('h2.FormTitle').text('STOK KELUAR LABEL');
        $('#form_inputLABEL').attr('class', 'form stokKeluar');
        $('#form_inputLABEL').attr('data-id', id);
        $('#form_inputLABEL .field_container label.error').hide();
        $('.rak').hide();
        $('.customer').hide();
        $('.product').hide();
        $('.size').hide();
        $('.material').hide();
        $('.core').hide();
        $('.line').hide();
        $('.per_roll').hide();
        $('.stock').hide();
        $('.nama_').show();
        $('.tgl_').show();
        $('.nosj_').show();
        $('.nopo_').show();
        $('.roll_').show();
        $('.content_').show();
        $('.unit_').show();
        $('.skeluar_').show();
      });

      $(document).on('submit', '#form_inputLABEL.stokKeluar', function(e){
        e.preventDefault();
        if($('#form_inputLABEL').valid() == true){
            hide_lightbox();
            show_loading_message();
            var id        = $('#form_inputLABEL').attr('data-id');
            var form_data = $('#form_inputLABEL').serialize();
            var request   = $.ajax({
              url: '<?php echo base_url('index.php/action/all/kelhis_label'); ?>',
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
                reset_all();
              }, true);
            } else {
              hide_loading_message();
              show_message("Gagal mengurangkan stok", 'error');
            }
          });
          request.fail(function(jqXHR, textStatus){
            hide_loading_message();
            show_message('Gagal mengurangkan stok: '+textStatus, 'error');
          });
        }
      });

    });
  </script>