  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <?php echo ucfirst($this->uri->segment($this->uri->total_segments())); ?>
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
                  <th>Company</th>
                  <th>Address</th>
                  <th>Email</th>
                  <th>Phone</th>
                  <th>Logo</th>
                  <th style="width:100px">Option</th>
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
              
              <h2 class="FormTitle" style="text-align: center">INPUT COMPANY</h2>
              <form class="form company_new" id="form_company" data-id="" novalidate>
                <hr>
                
                <div class="form-group company">
                  <label for="company">Nama Perusahaan: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="company" id="company" required>
                </div>

                <div class="form-group address">
                  <label for="address">Alamat: <span class="required">*</span></label>
                  <textarea class="form-control" name="address" id="address" required></textarea>
                </div>

                <div class="form-group email">
                  <label for="address">Email: <span class="required">*</span></label>
                  <input type="email" class="form-control" name="email" id="email" required>
                </div>

                <div class="form-group phone">
                  <label for="phone">Telp: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="phone" id="phone" required>
                </div>

                <div class="form-group logo margin-bottom-lg">
                  <label for="logo">Logo:</label>
                  <div class="row">
                    <div class="col-md-6 col-xs-12">
                      <input type="file" class="form-control" name="logo" id="logo" onchange="readImage(this);">
                      <input type="hidden" name="tmp_logo" id="tmp_logo" value="">
                      <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                    </div>
                    <div class="col-md-6 col-xs-12">
                      <p>Allowed .png .jpg (max 1MB)</p>
                    </div>
                  </div>
                  <div class="row margin-x">
                    <div class="col-md-6 col-xs-12">
                      <div id="ImageResult"></div>
                    </div>
                    <div class="col-md-6 col-xs-12">
                      <input type="button" class="btn btn-danger" id="RemoveLogo" value="Remove" style="display: none">
                    </div>
                  </div>
                </div>

                <div class="button_container" style="text-align: center">
                  <button type="submit" class="saving">Simpan</button>
                  <input type="button" class="lightbox_close" value="Batal">
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
    $('#form_company').validate();
    var tabel = $('#tablenya').DataTable({
      processing: true,
      serverSide: true,
      scrollX   : true,
      ordering  : false,
      ajax: {
        url: "<?php echo base_url('index.php/action/all/company'); ?>",
        type: "POST",
        data: function ( data ) {
            data.<?php echo $this->security->get_csrf_token_name(); ?> = "<?php echo $this->security->get_csrf_hash(); ?>";
        }
      },
      columnDefs: [
      {
        'targets': 4,
        render: function(data) {
          return '<img src="'+data+'" class="datatable_img">'
        }
      }],
      dom: 'Bfrtip',
      buttons: [
      'pageLength',
      {
        extend: 'excel',
        messageTop: false,
        footer: true,
        text: 'Export to Excel',
        filename : 'Company',
        title: 'Data Company',
        exportOptions: {
          columns: [ 0, 1, 2, 3, 4]
        }
      },
      <?php if($this->session->userdata('role') != '5'){ ?>{
        text: 'Create Company',
        action: function ( e ) {
          show_lightbox();
          $('H2.FormTitle').text('INPUT COMPANY');
          $('#form_company').attr('class', 'form company_new');
          $('#form_company').attr('data-id', '');
        }
      } <?php } ?>
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
      $('#company').val('');
      $('#address').val('');
      $('#email').val('');
      $('#phone').val('');
      $('#logo').val('');
      $('#ImageResult').empty();
      $('#RemoveLogo').hide();
    }

    $(document).on('submit', '#form_company.company_new', function(e){
      e.preventDefault();
      if($('#form_company').valid() == true){
        hide_lightbox();
        show_loading_message();
        var Infos = $('#company').val();
        var request   = $.ajax({
          url: '<?php echo base_url('index.php/action/all/add_company'); ?>',
          data: new FormData(this),
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
        url:  '<?php echo base_url('index.php/action/all/get_company'); ?>',
        cache:false,
        type: 'POST',
        data: {
          <?php echo $this->security->get_csrf_token_name(); ?> : '<?php echo $this->security->get_csrf_hash(); ?>',
          id : id
        }
      });
      request.done(function(output){
        var obj = JSON.parse(output);
        if (obj.result == 'success'){
          $('h2.FormTitle').text('EDIT COMPANY');
            $('#form_company').attr('class', 'form edit_company');
            $('#form_company').attr('data-id', id);
            $('.field_container label.error').hide();
            $('#company').val(obj.data[0].company);
            $('#address').val(obj.data[0].address);
            $('#email').val(obj.data[0].email);
            $('#phone').val(obj.data[0].phone);
            $('#tmp_logo').val(obj.data[0].logo);
            if(obj.data[0].logo){
              $('#ImageResult').append('<img src="'+obj.data[0].logo+'" class="img-thumbnail img_default">');
              $('#RemoveLogo').show();
            }
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
    
    $(document).on('submit', '#form_company.edit_company', function(e){
      e.preventDefault();
      if ($('#form_company').valid() == true){
        hide_lightbox();
        show_loading_message();
        var Infos = $('#company').val();
        var id        = $('#form_company').attr('data-id');
        var request   = $.ajax({
          url: '<?php echo base_url('index.php/action/all/edit_company/?id='); ?>'+id,
          data: new FormData(this),
          cache: false,
          contentType: false,
          processData: false,
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
          url:          '<?php echo base_url('index.php/action/all/delete_company/'); ?>',
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

    $(document).on('click', '#RemoveLogo', function(e){
      e.preventDefault();
      $('#logo').val('');
      $('#tmp_logo').val('');
      $('#ImageResult').empty();
      $('#RemoveLogo').hide();
      $('#UploadLogo').hide();
    });
  });

  function readImage(input) {
    $('#ImageResult').empty();
    if (input.files && input.files[0]) {
      var reader = new FileReader();
      reader.onload = function (e) {
        $('#ImageResult').append('<img src="'+e.target.result+'" class="img-thumbnail img_default">');
        $('#RemoveLogo').show();
      }
      reader.readAsDataURL(input.files[0]);
    } else {
      $('#RemoveLogo').show();
    }
  }

  </script>