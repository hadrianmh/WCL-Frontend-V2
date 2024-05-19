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
          <div id="widthSortby" class="input-group">
            <span class="input-group-addon">Sort by </span>
            <select id="sortby" class="form-control"></select>
            <span class="input-group-btn">
              <button type="button" id="LoadData" class="btn btn-default"><i class="fa fa-search" aria-hidden="true"></i> View</button>
            </span>
          </div>
          <table id="tablenya" class="table table-bordered" style="width:100%">
            <thead>
              <tr>
                  <th>Tgl Surat Jalan</th>
                  <th>Surat Jalan</th>
                  <th style="min-width:150px">Customer</th>
                  <th style="min-width:100px">No PO</th>
                  <th style="min-width:100px">No SO</th>
                  <th style="min-width:250px">Ship To</th>
                  <th style="min-width:150px">Nama Barang</th>
                  <th>Qty</th>
                  <th>Satuan</th>
                  <th style="min-width:150px">Kurir</th>
                  <th style="min-width:150px">No Resi</th>
                  <th>Ongkir</th>
                  <th style="min-width:150px">Diinput</th>
                  <th style="min-width:150px">Option</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>

          <!-- The Modal -->
          <div id="myModal2" class="modal">
            <!-- Modal content -->
            <div class="modal-content">
              <span class="close lightbox_close">&times;</span>
              <h2 class="FormTitle" style="text-align: center">PRATINJAU PRINT</h2>
              <form class="form add" id="form_print" data-id="" novalidate>

                <hr>
                <p style="font-weight: bold">DITERBITKAN</p>
                
                <div class="form-group">
                  <label for="sj_date">Tanggal: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="sj_date" id="sj_date" required readonly>
                </div>
        
                <div class="form-group">
                  <label for="no_po_pratinjau">No Po: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="no_po_pratinjau" id="no_po_pratinjau" readonly>
                </div>
        
                <div class="form-group">
                  <label for="no_delivery">No Surat Jalan: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="no_delivery" id="no_delivery" required readonly>
                </div>
        
                <hr>
                <p style="font-weight: bold">RINCIAN TAGIHAN</p>
                <div class="form-group">
                  <label for="customer">Customer: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="custom" id="custom" required readonly>
                </div>

                <div class="form-group shipto">
                  <label for="shipto">Ship to:</label>
                  <textarea class="form-control" name="shipto" id="shipto" minlength="15"></textarea>
                </div>

                <hr>
                <p style="font-weight: bold">RINCIAN PENGIRIMAN</p>
                <div class="itemnya"></div>
        
                <hr>
                <p style="font-weight: bold">PENANGGUNG JAWAB</p>

                <div class="form-group">
                  <label for="ttd">Nama: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="ttd" id="ttd" value="<?php echo $_SESSION['name'];?>" required readonly>
                </div>

                <div class="button_container" style="text-align: center">
                  <button type="submit" class="print">Cetak</button>
                  <input type="button" class="lightbox_close" value="Batal">
                  <input type="hidden" class="data-id" value="">
                  <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                </div>
              </form>
            </div>
          </div>

          <div id="PrintModal" class="modal">
            <div class="modal-content">
              <span class="close lightbox_close">&times;</span>
              <br><hr>
              <div class="printnow">
                <div class="row">
                  <div class="col-xs-12">
                    <div class="row delivery-orders-title"></div>
                    <div class="row">
                      <div class="col-xs-12">
                        <p class="text-center"><strong>DELIVERY NOTE</strong></p>
                      </div>
                    </div>
                    <div class="row" style="font-size: 12px">
                      <div class="col-md-9 col-xs-9">
                        <p><strong>Bill To : </strong><span class="bill"></span></p>
                        <p><strong>Ship to : </strong><span class="ship"></span></p>
                      </div>
                      <div class="col-md-3 col-xs-3">
                          <p><strong>Date : </strong><span class="tgl"></span></p>
                          <p><strong>Delivery Order No : </strong><span class="nosj"></span></p>
                      </div>
                    </div>
                  </div>
                </div>
              
                <div class="row" style="font-size: 12px">
                  <div class="col-md-12">
                    <table class="table table-bordered">
                      <thead>
                        <tr>
                          <th class="text-center">NO</th>
                          <th class="text-center">NO SO</th>
                          <th class="text-center">NO PO</th>
                          <th class="text-center" style="width:250px">ITEM</th>
                          <th class="text-center">QTY</th>
                          <th class="text-center">UNIT</th>
                        </tr>
                      </thead>
                      <tbody class="tbody"></tbody>
                    </table>
                  </div>
                </div>

                <div class="row" style="font-size: 12px">
                  <div class="col-md-9 col-xs-9">
                    <p>
                      <strong>Prepared by</strong>
                    </p>
                    <p class="ttd"></p>
                    <p class="ttd_date"></p>
                  </div>
                  <div class="col-md-3 col-xs-3">
                    <p>
                      <strong>Received by</strong>
                    </p>
                    <p>
                      <strong>Name :</strong>
                    </p>
                    <p>
                      <strong>Date :</strong>
                    </p>
                  </div>
                </div>
              </div>
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
  <script src="<?php echo base_url('assets/js/jQuery.print.js'); ?>"></script>
  <script>
    $(document).ready(function()
    {
      $('#form_print').validate();
      var tabel = $('#tablenya').DataTable({
        processing: true,
        serverSide: true,
        scrollX   : true,
        ordering  : false,
        ajax: {
          url: "<?php echo base_url('index.php/action/all/do_delivery'); ?>",
          type: "POST",
          data: function ( data ) {
            data.<?php echo $this->security->get_csrf_token_name(); ?> = "<?php echo $this->security->get_csrf_hash(); ?>";
            data.curMonth = getCookie("selectMonth");
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
          filename : 'DO-Delivery_'+getCookie("selectMonth"),
          title: 'Delivery Order Delivery '+getCookie("selectMonth"),
          exportOptions: {
            columns: [ 0,1,2,3,4,5,7,8,9,10,11,12,13 ]
          }
        }],
        "lengthMenu": [[10, -1], [10, 'All']],
        iDisplayLength: 10
      });

      /////////////////////////////////////////////////////////////////
      // Sort datatable from current month
      /////////////////////////////////////////////////////////////////

      var req = $.ajax({
        url: '<?php echo base_url('index.php/action/all/sort_month'); ?>',
        type: 'POST',
        data: {
          <?php echo $this->security->get_csrf_token_name(); ?>   : '<?php echo $this->security->get_csrf_hash(); ?>',
          table   : 'delivery_orders_customer',
          column  : 'sj_date',
          where   : '',
          order   : 'sj_date'
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
          $('#customer').attr('readonly', false);
        $('#myModal').hide();
        $('#myModal2').hide();
        $('#PrintModal').hide();
          $('label.error').hide();
      }

      // Lightbox close button
      $(document).on('click', '.lightbox_close', function(){
          hide_lightbox();
          $('.company').empty();
          $('.address').empty();
          $('.telp').empty();
          $('.bill').val('');
          $('.tgl').empty();
          $('.ship').val('');
          $('.nosj').empty();
          $('.ttd').empty();
          $('.ttd_date').empty();
          $('.tbody').empty();
          $('.looping-item').remove();
          $('.delivery-orders-title').empty();
      });

      $(document).on('click', '.PrintView', function(e){
        e.preventDefault();
        show_loading_message();
        var id   = $(this).data('id');
        var ex    = id.split('-');
        var request = $.ajax({
          url:  '<?php echo base_url('index.php/action/all/get_print_do'); ?>',
          type: 'POST',
          data: {
            <?php echo $this->security->get_csrf_token_name(); ?> : '<?php echo $this->security->get_csrf_hash(); ?>',
            id: ex[0],
            id_fk: ex[1],
            id_sj: ex[2],
          }
        });
        request.done(function(output){
          var obj = JSON.parse(output);
          if(obj.result == 'success'){
            var no=0; hide_loading_message();
            $('#myModal2').show();
            $('h2.FormTitle').text('PRATINJAU PRINT');
            $('#form_print .field_container label.error').hide();
            $('#form_print').attr('data-id', id);
            $('#form_print').attr('class', 'form printProses');
            $('#sj_date').val(obj.data[0].sj_date);
            $('#no_po_pratinjau').val(obj.data[0].po_customer);
            $('#no_delivery').val(obj.data[0].no_delivery);
            $('#custom').val(obj.data[0].customer);
            $('#shipto').val(obj.data[0].shipto);
            for(var i = 0; i<obj.data.length; i++){
              no++;
              $('.itemnya').append(
                '<div class="looping-item"><div class="form-group"><label for="item">Nama Barang '+no+' : <span class="required">*</span></label><input type="text" class="form-control" name="data[item][]" id="item" value="'+obj.data[i].item+'" required readonly></div><div class="form-group"><label for="qty">Qty: <span class="required">*</span></label><input type="text" class="form-control" name="data[qty][]" id="qty" value="'+obj.data[i].qty+' '+obj.data[i].unit+'" required readonly></div>'
                );
            }
            
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

      $(document).on('submit', '#form_print.printProses', function(e){
        e.preventDefault();
        if($('#form_print').valid() == true)
        {
          hide_lightbox();
          show_loading_message();
          var id = $('#form_print').attr('data-id');
          var ex = id.split('-');
          var form_data = $('#form_print').serialize();
          var request   = $.ajax({
            url: '<?php echo base_url('index.php/action/all/print_do'); ?>',
            data: form_data + '&' + $.param({ 'id' : ex[0], 'id_fk' : ex[1], 'id_sj' : ex[2] }),
            cache: false,
            type: 'POST'
          });
          request.done(function(output)
          {
            var obj = JSON.parse(output);
            if(obj.result == 'success')
            {
              hide_loading_message();
              $('#PrintModal').show();
              if(!!obj.data[0].logo)
              {
                $('.delivery-orders-title').append(
                  '<div class="col-md-2 col-xs-2"><img src="'+obj.data[0].logo+'" width="100px" height="50px" style="margin-top: 20px"></div><div class="col-md-10 col-xs-10"><h4 class="company" style="letter-spacing:2px;margin-bottom: 0px"><strong></strong></h4><p class="address" style="letter-spacing:2px;margin-bottom: 0px"></p><p class="telp" style="letter-spacing:2px;margin-bottom: 0px"></p></div>'
                );

              } else {
                $('.delivery-orders-title').append(
                  '<div class="col-md-12 col-xs-12"><h4 class="company" style="letter-spacing:2px;margin-bottom: 0px"><strong></strong></h4><p class="address" style="letter-spacing:2px;margin-bottom: 0px"></p><p class="telp" style="letter-spacing:2px;margin-bottom: 0px"></p></div>'
                );
              }
              $('.company strong').text(obj.data[0].company);
              $('.address').text(obj.data[0].address);
              $('.telp').text('Telp : '+obj.data[0].phone);
              $('.bill').text(obj.data[0].customer);
              $('.tgl').text(obj.data[0].sj_date);
              $('.ship').text(obj.data[0].shipto);
              $('.nosj').text(obj.data[0].no_delivery);
              for(var i = 0; i<obj.data.length; i++){
                $('.tbody').parent().append(
                  '<tr><td class="text-center">'+obj.data[i].no+'</td><td class="text-center">'+obj.data[i].no_so+'</td><td class="text-center">'+obj.data[i].po_customer+'</td><td>'+obj.data[i].item+'</td><td class="text-center">'+obj.data[i].qty+'</td><td class="text-center">'+obj.data[i].unit+'</td></tr>'
                );
              }
              
              $('.ttd').append('<strong>Name : </strong>'+obj.data[0].ttd);
              $('.ttd_date').append('<strong>Date : </strong>'+obj.data[0].sj_date);

              $('.printnow').print({
                stylesheet : '<?php echo base_url('assets/css/bootstrap/bootstrap.min.css'); ?>',
                globalStyles : true,
                mediaPrint : false,
                iframe : true,
                append: null,
                prepend: null,
                deferred: $.Deferred().done(function() { console.log('Print berhasil.', arguments); })
              });

            } else {
              hide_loading_message();
              show_message(obj.message, 'error');
            }
          });
          request.fail(function(jqXHR, textStatus){
            hide_loading_message();
            show_message('Print gagal: '+textStatus, 'error');
          });
        }
      });

      $(document).on('click', '.HapusItem', function(e)
      {
        e.preventDefault();
        var info = $(this).data('name');
        var id = $(this).data('id');
          var ex = id.split('-');
        if(confirm("Anda yakin ingin menghapus '"+info+"'?"))
        {
          show_loading_message();
          var request = $.ajax({
            url:  '<?php echo base_url('index.php/action/all/delete_do'); ?>',
            type: 'POST',
            data: {
              <?php echo $this->security->get_csrf_token_name(); ?> : '<?php echo $this->security->get_csrf_hash(); ?>',
              id: ex[0],
              id_fk: ex[1],
              item_to: ex[2],
              id_sj: ex[3]
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
            show_message('Gagal menghapus data: '+textStatus, 'error');
          });
        }
      });

    });
  </script>