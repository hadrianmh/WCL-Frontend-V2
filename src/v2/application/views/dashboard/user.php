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
                  <th>Nama</th>
                  <th>Email</th>
                  <th>Peran</th>
                  <th>Status Email</th>
                  <th>Status Akun</th>
                  <th>Option</th>
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
              
              <h2 class="FormTitle" style="text-align: center">INPUT USER</h2>
              <form class="form add" id="form_inputUSER" data-id="" novalidate>

                <div class="form-group name">
                  <label for="name">Nama: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="name" id="name" required>
                </div>

                <div class="form-group email">
                  <label for="email">Email: <span class="required">*</span></label>
                  <input type="email" class="form-control" name="email" id="email" required>
                </div>

                <div class="form-group password">
                  <label for="password">Password: <span class="required">*</span></label>
                  <input type="password" class="form-control" name="password" id="password" required>
                </div>

                <div class="form-group role">
                  <label>Peran sbg: <span class="required">*</span></label>
                  <select class="form-control" id="role" name="role" required>
                    <option value="" selected disabled>Pilih peran</option>
                    <option value="1">Root</option>
                    <option value="2">Administrator</option>
                    <option value="3">Sales Order</option>
                    <option value="4">Finance</option>
                    <option value="5">Guest</option>
                    <option value="6">Production</option>
                    </select>
                </div>

                <div class="form-group status">
                  <label>Status email: <span class="required">*</span></label>
                  <select class="form-control" id="status" name="status" required>
                    <option value="" disabled>Pilih status</option>
                    <option value="0">Not Verified</option>
                    <option value="1" selected>Verified</option>
                    </select>
                </div>

                <div class="form-group status">
                  <label>Status akun: <span class="required">*</span></label>
                  <select class="form-control" id="account" name="account" required>
                    <option value="" disabled>Pilih account</option>
                    <option value="0">Inactive</option>
                    <option value="1" selected>Active</option>
                    </select>
                </div>

                <div class="button_container" style="text-align: center">
                  <button type="submit" class="saving">Submit</button>
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
  <script>
  $(document).ready(function()
  {
    $('#form_inputUSER').validate();

    // Show lightbox
    function show_lightbox(){
      $('#myModal').show();
    }
    // Lightbox close button
    $(document).on('click', '.lightbox_close', function(){
      hide_lightbox();
      $('#name').val('');
      $('#email').val('');
      $('#password').val('');
      $('#role').val('');
      $('#status').val('');
      $('#account').val('');
    });

    // Hide lightbox
    function hide_lightbox(){
      $('#myModal').hide();
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

    var tabel = $('#tablenya').DataTable({
      processing: true,
      serverSide: true,
      scrollX   : true,
      ordering  : false,
      ajax: {
        url: "<?php echo base_url('index.php/action/all/user'); ?>",
        type: "POST",
        data: function ( data ) {
            data.<?php echo $this->security->get_csrf_token_name(); ?> = "<?php echo $this->security->get_csrf_hash(); ?>";
        }
      },
      dom: 'Bfrtip',
      buttons: [
      'pageLength',
      <?php if($this->session->userdata('role') != '5'){ ?>{
        text: 'Create User',
        action: function ( e ) {
          e.preventDefault();
          show_lightbox();
          $('H2.FormTitle').text('INPUT USER');
          $('#form_inputUSER button').text('Submit');
          $('#form_inputUSER').attr('class', 'form add');
          $('#form_inputUSER').attr('data-id', '');
          $('#form_inputUSER .field_container label.error').hide();
          $('#form_inputUSER .password').show();
        }
      } <?php } ?>
      ],
      "lengthMenu": [[10, -1], [10, 'All']],
      iDisplayLength: 10,
    });

    $(document).on('submit', '#form_inputUSER.add', function(e){
      e.preventDefault();
      if($('#form_inputUSER').valid() == true){
        hide_lightbox();
        show_loading_message();
        var form_data = $('#form_inputUSER').serialize();
        var request   = $.ajax({
          url: '<?php echo base_url('index.php/action/all/add_user'); ?>',
          data: form_data,
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
          show_message('Gagal memasukan data: '+textStatus, 'error');
        });
      }
    });

    $(document).on('click', '.edit_user', function(e){
      e.preventDefault();
      show_loading_message();
      var id      = $(this).data('id');
      var request = $.ajax({
        url:  '<?php echo base_url('index.php/action/all/get_user'); ?>',
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
          hide_loading_message();
          show_lightbox();
          $('#form_inputUSER button').text('Submit');
          $('#form_inputUSER').attr('class', 'form edit');
          $('#form_inputUSER').attr('data-id', id);
          $('#form_inputUSER .field_container label.error').hide();
          $('#name').val(obj.data[0].name);
          $('#email').val(obj.data[0].email);
          $('#role').val(obj.data[0].role);
          $('#status').val(obj.data[0].status);
          $('#account').val(obj.data[0].account);
          $('.password').hide();
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

    $(document).on('submit', '#form_inputUSER.edit', function(e){
      e.preventDefault();
      if($('#form_inputUSER').valid() == true){
        hide_lightbox();
        show_loading_message();
        var Infos = $('#product').val();
        var id        = $('#form_inputUSER').attr('data-id');
        var form_data = $('#form_inputUSER').serialize();
        var request   = $.ajax({
          url: '<?php echo base_url('index.php/action/all/edit_user/'); ?>'+id,
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
              }, true);
          } else {
              hide_loading_message();
              show_message(obj.message, 'error');
          }
        });
        request.fail(function(jqXHR, textStatus){
          hide_loading_message();
          show_message('Gagal menyunting: '+textStatus, 'error');
        });
      }
    });

    $(document).on('click', '.delete_user', function(e){
      e.preventDefault();
      var name = $(this).data('name');
      if(confirm("Anda yakin ingin menghapus '"+name+"'?")){
        show_loading_message();
        var id      = $(this).data('id');
        var request = $.ajax({
          url:          '<?php echo base_url('index.php/action/all/delete_user/'); ?>',
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
                show_message(obj.message, 'success');
              }, true);
          } else {
              hide_loading_message();
              show_message(obj.message, 'error');
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