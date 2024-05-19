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
                  <th style="min-width:100px">Date</th>
                  <th style="min-width:100px">Company</th>
                  <th style="min-width:100px">Vendor</th>
                  <th style="min-width:100px">PO No</th>
                  <th>Purchase Order</th>
                  <th>Detail</th>
                  <th>Size</th>
                  <th>Price</th>
                  <th>Price/Roll</th>
                  <th>Qty</th>
                  <th>Unit</th>
                  <th>Merk</th>
                  <th>Type</th>
                  <th>Core</th>
                  <th>Gulungan</th>
                  <th>bahan</th>
                  <th style="min-width:100px">Note</th>
                  <th style="min-width:100px">Subtotal</th>
                  <th style="min-width:100px">Tax</th>
                  <th style="min-width:100px">Total</th>
                  <th style="min-width:100px">Diinput</th>
                  <th style="min-width:150px">Option</th>
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
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th class="text-right" style="font-weight: bold">Total Amount :</th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
              </tr>
            </tfoot>
          </table>
          
          <!-- The Modal -->
          <div id="myModal" class="modal">

            <!-- Modal content -->
            <div class="modal-content">
              <span class="close lightbox_close">&times;</span>
              
              <h2 class="FormTitle" style="text-align: center">INPUT PURCHASE ORDER</h2>
              <form class="form add" id="form_inputPO" data-id="" novalidate>

                <div class="form-group company">
                  <label for="company">Company: <span class="required">*</span></label>
                  <select class="form-control" name="company" id="company" data-live-search="true" required></select>
                </div>

                <div class="form-group vendor">
                  <label for="vendor">Vendor Name: <span class="required">*</span></label>
                  <select class="form-control" name="vendor" id="vendor" data-live-search="true" required></select>
                  <input type="hidden" id="id_vendor" name="id_vendor" value="">
                </div>

                <div class="form-group po_date">
                  <label for="PO Date">Date: <span class="required">*</span></label>
                  <input class="form-control" name="po_date" id="po_date" value="<?php echo date('d/m/Y'); ?>" data-date-format="dd/mm/yyyy" required>
                </div>

                <div class="form-group po_vendor" style="display: none">
                  <label for="PO vendor">No PO: </label>
                  <input type="text" class="form-control" name="po_vendor" id="po_vendor" value="">
                </div>

                <div class="form-group po_type">
                  <label for="purchase">Purchase Order Type: <span class="required">*</span></label>
                  <select class="form-control" name="po_type" id="po_type" required></select>
                </div>

                <hr class='header-item'>
                
                <p>
                  <button type="button" class="btn btn-primary tambah_barang" style="display: none">Tambah barang</button>
                </p>

                <div class="form-group detail" style="display: none">
                  <label for="detail">Detail: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="data[detail][]" id="detail" value="" required>
                </div>

                <div class="form-group size" style="display: none">
                  <label for="Size">Size: <span class="required">*</span></label>
                  <input type="number" class="form-control sizeval_1" name="data[size][]" id="size" value="" required>
                </div>

                <div class="form-group merk" style="display: none">
                  <label for="merk">Merk: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="data[merk][]" id="merk" required>
                </div>

                <div class="form-group type" style="display: none">
                  <label for="type">Type: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="data[type][]" id="type" required>
                </div>

                <div class="form-group core" style="display: none">
                  <label for="core">Core: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="data[core][]" id="core" required>
                </div>

                <div class="form-group gulungan" style="display: none">
                  <label for="gulungan">Gulungan: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="data[gulungan][]" id="gulungan" required>
                </div>

                <div class="form-group bahan" style="display: none">
                  <label for="bahan">Bahan: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="data[bahan][]" id="bahan" required>
                </div>

                <div class="form-group price_1">
                  <label for="price_1">Price: <span class="required">*</span></label>
                  <input type="text" class="form-control price1val_1" name="data[price_1][]" id="price_1" required>
                </div>

                <div class="form-group price_2" style="display: none">
                  <label for="price_2">Price (Secondary): <span class="required">*</span> <em class="label label-success">Size x Price = Price (secondary)</em></label>
                  <div class="row">
                    <div class="col-md-10">
                      <input type="text" class="form-control price2val_1" name="data[price_2][]" id="price_2" required>
                    </div>
                    <div class="col-md-2">
                      <button type="button" class="btn btn-primary hitung_1">Hitung</button>
                    </div>
                  </div>
                </div>

                <div class="form-group qty">
                  <label for="qty">Qty: <span class="required">*</span></label>
                  <input type="number" min="0" class="form-control" name="data[qty][]" id="qty" required>
                </div>  

                <div class="form-group unit" style="display: none">
                  <label for="unit">Unit: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="data[unit][]" id="unit" required>
                </div>

                <div id="looping_barang"></div>
                
                <hr class='footer-item'>

                <div class="form-group note">
                  <label for="Note">Note:</label>
                  <textarea class="form-control" name="note" id="note"></textarea>
                </div>

                <div class="form-group ppns">
                  <label for="sel1">Tax: <span class="required">*</span></label></label>
                  <select class="form-control" name="ppns" id="ppns" required>
                    <option value="" selected disabled>Menggunakan PPN 10% ?</option>
                    <option value="0">Tidak</option>
                    <option value="1">Ya</option>
                  </select>
                </div>

                <div class="form-group address" style="display: none">
                  <label for="address">Address:</label>
                  <textarea class="form-control" name="address" id="address"></textarea>
                </div>

                <div class="form-group tanda_tangan" style="display: none">
                  <label for="tanda_tangan">Tanda Tangan:</label>
                  <input type="text" class="form-control" name="tanda_tangan" id="tanda_tangan" required>
                </div>

                <div class="button_container" style="text-align: center">
                  <button type="submit" class="saving">Simpan</button>
                  <input type="button" class="lightbox_close" value="Batal">
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
                <div class= "page-header">
                  <div class="row">
                    <div class="col-xs-4 col-md-4">
                      <div class="logo_surat"></div>
                    </div>
                    <div class=" col-xs-8 col-md-8 text-center" style="letter-spacing: 2px;">
                      <h4 class="company_surat" style="margin-bottom: 0px"><strong></strong></h4>
                      <p class="alamat_surat" style="font-size: 12px;margin-bottom: 0px"></p>
                      <p class="telp_surat" style="font-size: 12px;margin-bottom: 0px"></p>
                      <h5 class="kepala_surat" style="margin-bottom: 0px"><strong>PURCHASE ORDER</strong></h5>
                    </div>
                  </div>
                </div>

                <div class="row" style="font-size: 12px;">
                  <div class="col-xs-6 col-md-6">
                    <p class="tgl_po" style="margin-bottom: 0px"></p>
                  </div>
                  <div class="col-xs-6 col-md-6">
                    <p class="penjual" style="margin-bottom: 0px"></p>
                  </div>
                  <div class="col-xs-6 col-md-6">
                    <p class="nomor"></p>
                  </div>
                  <div class="col-xs-6 col-md-6">
                    <p class="alamat"></p>
                  </div>
                </div>

                <table class="table table-bordered" style="font-size: 12px;">
                  <thead><tr class="thead"></tr></thead>
                  <tbody class="tbody"></tbody>
                  <tfoot>
                    <tr class='tfoot-heading'></tr>
                    <tr class='tfoot-value1'></tr>
                    <tr class='tfoot-value2'></tr>
                    <tr class='tfoot-value3'></tr>
                  </tfoot>
                </table>

                <div class="row" style="font-size: 12px;">
                  <div class="col-md-8"></div>
                  <div class="col-md-4 text-right">
                    <p class="ttd_tgl"></p>
                    <p class="ttd_person"></p>
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
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <script src="<?php echo base_url('assets/js/bootstrap/bootstrap-select.min.js'); ?>"></script>
  <script src="<?php echo base_url('assets/js/bootstrap/ajax-bootstrap-select.min.js'); ?>"></script>
  <script src="<?php echo base_url('assets/js/bootstrap/bootstrap-datepicker.min.js'); ?>"></script>
  <script src="<?php echo base_url('assets/js/jQuery.print.js'); ?>"></script>
  <style>.ttd_person{padding-top:75px}.notes{word-wrap:break-word;max-width:100px;}</style>
  <script>
    $(document).ready(function()
    {
      var barisN = 1;
      var loopN = 1;
      var po_typeJSON = [];
      var po_type_attribute = [];
      $('#po_date').datepicker();
      $('#price_1').mask('0.000.000.000.000,00', {reverse: true});
      $('#price_2').mask('0.000.000.000.000,00', {reverse: true});
      $('#qty').keyup(function(){ $('#ppns').val('') });
      $('#price2').keyup(function(){ $('#ppns').val('') });
      $('.tambah_barang').click(function(){ $('#ppns').val('') });
      $(document).on('click', '.hitung_1', function(e){
        var ukuran_1 = $('.sizeval_1').val();
        var harga_1 = $('.price1val_1').val().replace(/\./g,'').replace(/\,/g,'.');
        console.log(harga_1);
        $('.price2val_1').val(parseFloat(parseInt(ukuran_1) * parseFloat(harga_1)).toFixed(2));
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

      /////////////////////////////////////////////////////////////////
      // Sort datatable from current month
      /////////////////////////////////////////////////////////////////

      var req = $.ajax({
        url: '<?php echo base_url('index.php/action/all/sort_month'); ?>',
        type: 'POST',
        data: {
          <?php echo $this->security->get_csrf_token_name(); ?>   : '<?php echo $this->security->get_csrf_hash(); ?>',
          table   : 'po_customer',
          column  : 'po_date',
          where   : '',
          order   : 'po_date'
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

      $(document).on('click', '#LoadData', function(){
        var valMonth = $('#sortby').find(":selected").val();
        setCookie("selectMonth", valMonth, 1);
        location.reload();
      });

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

      function clean(){
        $("#company").empty().val('').selectpicker("refresh");
        $('#vendor').empty().val('').selectpicker("refresh");
        $('#po_date').val('');
        $('#detail').val('');
        $('#size').val('');
        $('#merk').val('');
        $('#type').val('');
        $('#core').val('');
        $('#gulungan').val('');
        $('#bahan').val('');
        $('#price_1').val('');
        $('#price_2').val('');
        $('#qty').val('');
        $('#unit').val('');
        $('#note').val('');
        $('#ppns').val('0');
        $('#looping_barang').empty();
        $('.logo_surat').empty();
        $('.tbody').empty();
        $('.thead').empty();
        $('.thead').empty();
        $('.tfoot-heading').empty();
        $('.tfoot-value1').empty();
        $('.tfoot-value2').empty();
        $('.tfoot-value3').empty();
      }

      function reset(){
        $('.tambah_barang').hide();
        $('.tanda_tangan').hide();
        $('.address').hide();
        $('#PrintModal').hide();
        $('.company').show();
        $('.vendor').show();
        $('.po_date').show();
        $('.po_type').show();
        $('.price_1').show();
        $('.hitung_1').show();
        $('.qty').show();
        $('.ppns').show();
        $('.note').show();
        $('.header-item').show();
        $('.footer-item').show();
        $('.detail').hide();
        $('.size').hide();
        $('.merk').hide();
        $('.type').hide();
        $('.core').hide();
        $('.gulungan').hide();
        $('.bahan').hide();
        $('.price_2').hide();
        $('.unit').hide();
        $('#detail').attr('name','data[detail][]');
        $('#size').attr('name','data[size][]');
        $('#merk').attr('name','data[merk][]');
        $('#type').attr('name','data[type][]');
        $('#core').attr('name','data[core][]');
        $('#gulungan').attr('name','data[gulungan][]');
        $('#bahan').attr('name','data[bahan][]');
        $('#price_1').attr('name','data[price_1][]');
        $('#price_2').attr('name','data[price_2][]');
        $('#qty').attr('name','data[qty][]');
        $('#unit').attr('name','data[unit][]');
        $('#vendor').removeAttr('disabled');
        $('#po_date').removeAttr('disabled');
        document.getElementById("po_type").disabled = false;
        $('#detail').attr('readonly', false);
        $('#size').attr('readonly', false);
        $('#merk').attr('readonly', false);
        $('#type').attr('readonly', false);
        $('#core').attr('readonly', false);
        $('#gulungan').attr('readonly', false);
        $('#bahan').attr('readonly', false);
        $('#price_1').attr('readonly', false);
        $('#price_2').attr('readonly', false);
        $('#qty').attr('readonly', false);
        $('#unit').attr('readonly', false);
        $('#note').attr('readonly', false);
        $('#ppns').attr('readonly', false);
      }

      // Lightbox close button
      $(document).on('click', '.lightbox_close', function(){
          hide_lightbox(); reset(); clean();
      });

      $('#form_inputPO').validate();
      var tabel = $('#tablenya').DataTable({
        processing: true,
        serverSide: true,
        scrollX   : true,
        ordering  : false,
        ajax: {
          url: "<?php echo base_url('index.php/action/all/purchase_order'); ?>",
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
          filename : 'PurchaseOrder_'+getCookie("selectMonth"),
          title: 'PURCHASE ORDER '+getCookie("selectMonth"),
          exportOptions: {
            columns: [ 0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20 ]
          }
        },
        <?php if($this->session->userdata('role') != '5'){ ?> {
          text: 'Create Purchase Order',
          action: function ( e ) {
            e.preventDefault();
            show_lightbox();
            $('H2.FormTitle').text('INPUT PURCHASE ORDER');
            $('#form_inputPO').attr('class', 'form add');
            $('#form_inputPO').attr('data-id', '');
            $('.ppns').show();
            $('.tambah_barang').hide();
            $('#company').empty();
            $('#po_type').empty();
            $('#id_vendor').val('');
            $('#po_type').val('');
            $('#detail').val('');
            $('#size').val('');
            $('#merk').val('');
            $('#type').val('');
            $('#core').val('');
            $('#gulungan').val('');
            $('#bahan').val('');
            $('#price_1').val('');
            $('#price_2').val('');
            $('#qty').val('');
            $('#unit').val('');
            $('#note').val('');
            $('#ppns').val('0');
            $('#looping_barang').empty();
            reset();

            $('#po_type').append('<option value="" selected disabled>Pilih Tipe</option>');
            for(var z = 0; z < po_typeJSON.length; z++) 
            {
              $('#po_type').append('<option value="'+po_typeJSON[z].id+'">' +po_typeJSON[z].item+ '</option>');
            }
          }
        }<?php } ?>],
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
          SubTotal = api.column( 17, { page: 'current'} ).data().reduce( function (a, b) {
            return intVal(a) + intVal(b);
          }, 0 );

          Tax = api.column( 18, { page: 'current'} ).data().reduce( function (a, b) {
            return intVal(a) + intVal(b);
          }, 0 );

          Totals = SubTotal + Tax;

          $( api.column( 17 ).footer() ).html(convertToRupiah(SubTotal));
          $( api.column( 18 ).footer() ).html(convertToRupiah(Tax));
          $( api.column( 19 ).footer() ).html(convertToRupiah(Totals));
        }
      });

      var options_company = {
        ajax: {
          url: '<?php echo base_url('index.php/action/json/po_company'); ?>',
          type: 'POST',
          data: {
            q: '{{{q}}}',
            <?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>'
          }
        },
        minLength: 3,
        locale: {
          emptyTitle: 'Pilih Entitas',
          searchPlaceholder: 'Kata kunci pencarian',
          statusInitialized: 'Minimal kata kunci 3 karakter',
          statusTooShort: 'Silakan masukkan lagi kata kunci minimal 3 karakter',
          statusSearching: 'Memuat pencarian...',
          currentlySelected: 'Dipilih saat ini',
          statusNoResults: 'Tidak terdaftar, silakan daftar sebagai company baru',
        },
        preprocessData: function (data) {
          var i, l = data.length, array = [];
          if (l) {
            for (i = 0; i < l; i++) {
              array.push($.extend(true, data[i], {
                text : data[i].company,
                value: data[i].id,
                data : {
                  subtext: data[i].address,
                }
              }));
            }
          }

          return array;
        }
      };
      $('#company').selectpicker().ajaxSelectPicker(options_company);

      var options_vendor = {
        ajax: {
          url: '<?php echo base_url('index.php/action/json/po_vendor'); ?>',
          type: 'POST',
          dataType: 'json',
          data: {
            q: '{{{q}}}',
            <?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>'
          }
        },
        minLength: 3,
        locale: {
          emptyTitle: 'Pilih vendor',
          searchPlaceholder: 'Kata kunci pencarian',
          statusInitialized: 'Minimal kata kunci 3 karakter',
          statusTooShort: 'Silakan masukkan lagi kata kunci minimal 3 karakter',
          statusSearching: 'Memuat pencarian...',
          currentlySelected: 'Dipilih saat ini',
          statusNoResults: 'Tidak terdaftar, silakan daftar sebagai vendor baru',
        },
        preprocessData: function (data) {
          var i, l = data.length, array = [];
          if (l) {
            for (i = 0; i < l; i++) {
              array.push($.extend(true, data[i], {
                text : data[i].name,
                value: i,
                data : {
                  po: data[i].id_po,
                  vendor: data[i].id_vendor,
                  subtext: data[i].subtext
                },
              }));
            }
          }

          return array;
        }
      };
      $('#vendor').selectpicker().ajaxSelectPicker(options_vendor);

      $('#vendor').on('changed.bs.select', function ()
      {
        var dataid = $('#form_inputPO').attr('data-id');
        var id_vendor = $('option:selected', this).attr("data-vendor");
        var id_po = $('option:selected', this).attr("data-po");
        $('#id_vendor').val(id_vendor);
        if(isNaN(parseInt(dataid)))
        {
          var get_po_item = $.ajax({
            url: '<?php echo base_url('index.php/action/json/po_item'); ?>',
            type: 'POST',
            data: {
              vendor : id_vendor,
              item : id_po,
              <?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>'
            },
          });

          get_po_item.done(function(output)
          {
            $('#id_vendor').val(id_vendor); reset();
            if(parseInt(output.value[0].id_po) > 0 && !!id_vendor)
            {
              po_type_attribute = output.input[0].attribute; 
              var split_input = output.input[0].attribute.split(',');
              $('#po_type').val(output.input[0].type);
              $('.tambah_barang').show();

              for(var x = 0; x < output.value.length; x++)
              {
                if(parseInt(x) === 0)
                {
                  $('#detail').val(output.value[x].detail);
                  $('#size').val(output.value[x].size);
                  $('#merk').val(output.value[x].merk);
                  $('#type').val(output.value[x].type);
                  $('#core').val(output.value[x].core);
                  $('#gulungan').val(output.value[x].gulungan);
                  $('#bahan').val(output.value[x].bahan);
                  $('#price_1').val(output.value[x].price_1);
                  $('#price_2').val(output.value[x].price_2);
                  $('#qty').val(output.value[x].qty);
                  $('#unit').val(output.value[x].unit);
                  for(var i = 0; i < split_input.length; i++)
                    {
                      $('.'+split_input[i]).show();
                    }

                } else {
                  loopN++;
                  $('#looping_barang').append(
                    '<div class="looping_barang" id="looping-'+loopN+'"><hr class="looping-item"><p><button type="button" name="remove" data-id="'+loopN+'" class="btn btn-danger btn_remove">Hapus</button></p><div class="form-group detail-'+loopN+'" style="display: none"><label for="detail">Detail: <span class="required">*</span></label><input type="text" class="form-control" name="data[detail][]" id="detail" value="'+output.value[x].detail+'" required></div><div class="form-group size-'+loopN+'" style="display: none"><label for="Size">Size: <span class="required">*</span></label><input type="number" class="form-control sizeval_'+loopN+'" name="data[size][]" id="size" value="'+output.value[x].size+'" required></div><div class="form-group merk-'+loopN+'" style="display: none"><label for="merk">Merk: <span class="required">*</span></label><input type="text" class="form-control" name="data[merk][]" id="merk" value="'+output.value[x].merk+'" required></div><div class="form-group type-'+loopN+'" style="display: none"><label for="type">Type: <span class="required">*</span></label><input type="text" class="form-control" name="data[type][]" id="type" value="'+output.value[x].type+'" required></div><div class="form-group core-'+loopN+'" style="display: none"><label for="core">Core: <span class="required">*</span></label><input type="text" class="form-control" name="data[core][]" id="core" value="'+output.value[x].core+'" required></div><div class="form-group gulungan-'+loopN+'" style="display: none"><label for="gulungan">Gulungan: <span class="required">*</span></label><input type="text" class="form-control" name="data[gulungan][]" id="gulungan" value="'+output.value[x].gulungan+'" required></div><div class="form-group bahan-'+loopN+'" style="display: none"><label for="bahan">Bahan: <span class="required">*</span></label><input type="text" class="form-control" name="data[bahan][]" id="bahan" value="'+output.value[x].bahan+'" required></div><div class="form-group price_1"><label for="price_1">Price: <span class="required">*</span></label><input type="text" class="form-control price_1_looping-'+loopN+'" name="data[price_1][]" id="price_1" value="'+output.value[x].price_1+'" required></div><div class="form-group price_2-'+loopN+'" style="display: none"><label for="price_2">Price (Secondary): <span class="required">*</span> <em class="label label-success">Size x Price = Price (secondary)</em></label><div class="row"><div class="col-md-10"><input type="text" class="form-control price_2_looping-'+loopN+'" name="data[price_2][]" id="price_2" value="'+output.value[x].price_2+'" required></div><div class="col-md-2"><button type="button" class="btn btn-primary hitung_'+loopN+'">Hitung</button></div></div><div class="form-group qty"><label for="qty">Qty: <span class="required">*</span></label><input type="number" min="0" class="form-control" name="data[qty][]" id="qty" value="'+output.value[x].qty+'" required></div><div class="form-group unit-'+loopN+'" style="display: none"><label for="unit">Unit: <span class="required">*</span></label><input type="text" class="form-control" name="data[unit][]" id="unit" value="'+output.value[x].unit+'" required></div><script>$(document).ready(function(){ $(".price_1_looping-'+loopN+'").mask("0.000.000.000.000,00", {reverse: true});$(".price_2_looping-'+loopN+'").mask("0.000.000.000.000,00", {reverse: true});$(document).on("click", ".hitung_'+loopN+'", function(e){ var ukuran_'+loopN+' = $(".sizeval_'+loopN+'").val();var harga_'+loopN+' = $(".price_1_looping-'+loopN+'").val().replace(/\\./g,"");$(".price_2_looping-'+loopN+'").val(parseInt(ukuran_'+loopN+' * harga_'+loopN+'.replace(/\\,/g,".")));});});</' + 'script></div>'
                  );

                  for(var i = 0; i < split_input.length; i++) {
                    $('.'+split_input[i]+'-'+loopN).show();
                  }
                }
              }
            }
          });

          get_po_item.fail(function(jqXHR, textStatus) {
            hide_loading_message();
            show_message('Gagal mengambil data: '+textStatus, 'error');
          });
        }
      });

      ///////////////////////////
      // Load po type item
      //////////////////////////
      
      var options_potype = $.ajax({
        url: '<?php echo base_url('index.php/action/json/po_type'); ?>',
        type: 'POST',
        data: {
          <?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>'
        }
      });

      options_potype.done(function(resp) {
        po_typeJSON = resp.data;
      });

      //////////////////////////////////////////////////////
      ////////// Pemeriksaan tipe purchase order
      //////////////////////////////////////////////////////

      $(document).on('change', '#po_type', function(e){
        e.preventDefault(); reset();
        $('#looping_barang').empty();
        $('.tambah_barang').show();
        $.ajax({
          url : '<?php echo base_url('index.php/action/json/po_attribute'); ?>',
          type : "POST",
          data: {
            <?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>',
            id: $(this).val()
          },
          success: function(output){
            po_type_attribute = output.data[0].field;
            var split_po_type_attribute = output.data[0].field.split(',');
            for(var x = 0; x < split_po_type_attribute.length; x++)
            {
              $('.'+split_po_type_attribute[x]).show();
            }
          }
          });
      });

      $(document).on('click', '.tambah_barang', function(e){
        e.preventDefault(); barisN++;
        var split_po_type_attribute = po_type_attribute.split(',');

        $('#looping_barang').append('<div class="looping_barang" id="looping-'+barisN+'"><hr class="looping-item"><p><button type="button" name="remove" data-id="'+barisN+'" class="btn btn-danger btn_remove">Hapus</button></p><div class="form-group detail-'+barisN+'" style="display: none"><label for="detail">Detail: <span class="required">*</span></label><input type="text" class="form-control" name="data[detail][]" id="detail" value="" required></div><div class="form-group size-'+barisN+'" style="display: none"><label for="Size">Size: <span class="required">*</span></label><input type="number" class="form-control sizeval_'+barisN+'" name="data[size][]" id="size" value="" required></div><div class="form-group merk-'+barisN+'" style="display: none"><label for="merk">Merk: <span class="required">*</span></label><input type="text" class="form-control" name="data[merk][]" id="merk" required></div><div class="form-group type-'+barisN+'" style="display: none"><label for="type">Type: <span class="required">*</span></label><input type="text" class="form-control" name="data[type][]" id="type" required></div><div class="form-group core-'+barisN+'" style="display: none"><label for="core">Core: <span class="required">*</span></label><input type="text" class="form-control" name="data[core][]" id="core" required></div><div class="form-group gulungan-'+barisN+'" style="display: none"><label for="gulungan">Gulungan: <span class="required">*</span></label><input type="text" class="form-control" name="data[gulungan][]" id="gulungan" required></div><div class="form-group bahan-'+barisN+'" style="display: none"><label for="bahan">Bahan: <span class="required">*</span></label><input type="text" class="form-control" name="data[bahan][]" id="bahan" required></div><div class="form-group price_1"><label for="price_1">Price: <span class="required">*</span></label><input type="text" class="form-control price_1_looping-'+barisN+'" name="data[price_1][]" id="price_1" required></div><div class="form-group price_2-'+barisN+'" style="display: none"><label for="price_2">Price (Secondary): <span class="required">*</span> <em class="label label-success">Size x Price = Price (secondary)</em></label><div class="row"><div class="col-md-10"><input type="text" class="form-control price_2_looping-'+barisN+'" name="data[price_2][]" id="price_2" required></div><div class="col-md-2"><button type="button" class="btn btn-primary hitung_'+barisN+'">Hitung</button></div></div></div><div class="form-group qty"><label for="qty">Qty: <span class="required">*</span></label><input type="number" min="0" class="form-control" name="data[qty][]" id="qty" required></div><div class="form-group unit-'+barisN+'" style="display: none"><label for="unit">Unit: <span class="required">*</span></label><input type="text" class="form-control" name="data[unit][]" id="unit" required></div><script>$(document).ready(function(){ $(".price_1_looping-'+barisN+'").mask("0.000.000.000.000,00", {reverse: true}); $(".price_2_looping-'+barisN+'").mask("0.000.000.000.000,00", {reverse: true});$(document).on("click", ".hitung_'+barisN+'", function(e){ var ukuran_'+barisN+' = $(".sizeval_'+barisN+'").val();var harga_'+barisN+' = $(".price_1_looping-'+barisN+'").val().replace(/\\./g,"");$(".price_2_looping-'+barisN+'").val(parseInt(ukuran_'+barisN+' * harga_'+barisN+'.replace(/\\,/g,".")));});});</' + 'script></div>');

        for(var x = 0; x < split_po_type_attribute.length; x++)
        {
          $('.'+split_po_type_attribute[x]+'-'+barisN).show();
        }
      });

      $(document).on('click', '.btn_remove', function(){  
        var button_id = $(this).data('id');
        $('#looping-'+button_id+'').remove();
      });

      $(document).on('submit', '#form_inputPO.add', function(e){
        e.preventDefault();
        if($('#form_inputPO').valid() == true){
          hide_lightbox();
          show_loading_message();
          var form_data = $('#form_inputPO').serialize();
          var request   = $.ajax({
            url: '<?php echo base_url('index.php/action/all/add_po'); ?>',
            data: form_data,
            cache: false,
            type: 'POST'
          });
          request.done(function(output){
            if(output.result == 'success'){
              tabel.ajax.reload(function(){
                hide_loading_message();
                show_message(output.message, 'success');
                reset(); clean();
              }, true);
            } else {
                hide_loading_message();
                show_message(output.message, 'error');
                reset(); clean();
            }
          });
          request.fail(function(jqXHR, textStatus){
            hide_loading_message();
            show_message('Gagal memasukan data: '+textStatus, 'error');
            reset(); clean();
          });
        }
      });

      $(document).on('click', '.UbahVendor', function(e){
        e.preventDefault();
        show_loading_message();
        $('#company').empty().val('').selectpicker("refresh");
        $('#vendor').empty().val('').selectpicker("refresh");
        $('#po_type').empty();
        $('.ppns').show();
        document.getElementById("po_type").disabled = true;
        var id      = $(this).data('id');
        var request = $.ajax({
          url:  '<?php echo base_url('index.php/action/all/get_po'); ?>',
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
            $('h2.FormTitle').text('UBAH PURCHASE ORDER');
            $('#form_inputPO').attr('class', 'form edit_vendor');
            $('#form_inputPO').attr('data-id', id);
            $('#form_inputPO .field_container label.error').hide();
            $('#company').append(
              '<option value="'+obj.data[0].id_company+'" data-subtext="'+obj.data[0].address+'" selected="selected">'+obj.data[0].company+'</option>'
              ).selectpicker('refresh').trigger('change');

            $('#vendor').append(
              '<option value="0" data-subtext="'+obj.data[0].isi+' '+obj.data[0].detail+'" selected="selected">'+obj.data[0].vendor+'</option>'
              ).selectpicker('refresh').trigger('change');

            for(var z = 0; z < po_typeJSON.length; z++) $('#po_type').append('<option value="'+po_typeJSON[z].id+'">' +po_typeJSON[z].item+ '</option>');

            $('#id_vendor').val(obj.data[0].id_vendor);
            $('#po_date').val(obj.data[0].po_date);
            $('#po_type').val(obj.data[0].po_type);
            $('#ppns').val(obj.data[0].ppn);
            $('#note').val(obj.data[0].note);
            $('.header-item').hide();
            $('.footer-item').hide();
            $('.tambah_barang').hide();
            $('.detail').hide();
            $('.size').hide();
            $('.merk').hide();
            $('.type').hide();
            $('.core').hide();
            $('.gulungan').hide();
            $('.bahan').hide();
            $('.price_1').hide();
            $('.price_2').hide();
            $('.qty').hide();
            $('.unit').hide();

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

      $(document).on('submit', '#form_inputPO.edit_vendor', function(e){
        e.preventDefault();
        if($('#form_inputPO').valid() == true){
          hide_lightbox();
          show_loading_message();
          var id        = $('#form_inputPO').attr('data-id');
          var form_data = $('#form_inputPO').serialize();
          var request   = $.ajax({
            url: '<?php echo base_url('index.php/action/all/edit_po'); ?>',
            data: form_data + '&' + $.param({ 'id' : id, 'type' : $('#po_type').val() }),
            cache: false,
            type: 'POST'
          });
          request.done(function(output){
            var obj = JSON.parse(output);
            if(obj.result == 'success'){
              tabel.ajax.reload(function(){
                hide_loading_message();
                show_message(obj.message, 'success');
                reset(); clean();
              }, true);
            } else {
                hide_loading_message();
                show_message(obj.message, 'error');
                reset(); clean();
            }
          });
          request.fail(function(jqXHR, textStatus){
            hide_loading_message();
            show_message('Gagal memasukan data: '+textStatus, 'error');
            reset(); clean();
          });
        }
      });

      $(document).on('click', '.UbahItem', function(e){
        e.preventDefault();
        show_loading_message();
        var id      = $(this).data('id');
        var request = $.ajax({
          url:  '<?php echo base_url('index.php/action/all/get_item'); ?>',
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
            $('h2.FormTitle').text('UBAH ITEM PURCHASE ORDER');
            $('#form_inputPO').attr('class', 'form edit_item');
            $('#form_inputPO').attr('data-id', id);
            $('#form_inputPO .field_container label.error').hide();
            $('#detail').val(obj.data.value[0].detail);
            $('#size').val(obj.data.value[0].size);
            $('#merk').val(obj.data.value[0].merk);
            $('#type').val(obj.data.value[0].type);
            $('#core').val(obj.data.value[0].core);
            $('#gulungan').val(obj.data.value[0].gulungan);
            $('#bahan').val(obj.data.value[0].bahan);
            $('#price_1').val(obj.data.value[0].price_1);
            $('#price_2').val(obj.data.value[0].price_2);
            $('#qty').val(obj.data.value[0].qty);
            $('#unit').val(obj.data.value[0].unit);
            $('#detail').attr('name','detail');
            $('#size').attr('name','size');
            $('#merk').attr('name','merk');
            $('#type').attr('name','type');
            $('#core').attr('name','core');
            $('#gulungan').attr('name','gulungan');
            $('#bahan').attr('name','bahan');
            $('#price_1').attr('name','price_1');
            $('#price_2').attr('name','price_2');
            $('#qty').attr('name','qty');
            $('#unit').attr('name','unit');
            $('.company').hide();
            $('.vendor').hide();
            $('.po_date').hide();
            $('.po_type').hide();
            $('.header-item').hide();
            $('.tambah_barang').hide();
            $('.footer-item').hide();
            $('.ppns').hide();
            $('.note').hide();

            var split_input = obj.data.input[0].attribute.split(',');
            for(var i = 0; i < split_input.length; i++)
            {
              $('.'+split_input[i]).show();
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

      $(document).on('submit', '#form_inputPO.edit_item', function(e){
        e.preventDefault();
        if($('#form_inputPO').valid() == true){
          hide_lightbox();
          show_loading_message();
          var id        = $('#form_inputPO').attr('data-id');
          var form_data = $('#form_inputPO').serialize();
          var request   = $.ajax({
            url: '<?php echo base_url('index.php/action/all/edit_item'); ?>',
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
                reset(); clean();
              }, true);
            } else {
                hide_loading_message();
                show_message(obj.message, 'error');
                reset(); clean();
            }
          });
          request.fail(function(jqXHR, textStatus){
            hide_loading_message();
            show_message('Gagal memasukan data: '+textStatus, 'error');
            reset(); clean();
          });
        }
      });

      $(document).on('click', '.PrintView', function(e)
      {
        e.preventDefault();
        show_loading_message();
        reset();
        var id      = $(this).data('id');
        var request = $.ajax({
          url:  '<?php echo base_url('index.php/action/all/get_print_po'); ?>',
          type: 'POST',
          data: {
            <?php echo $this->security->get_csrf_token_name(); ?> : '<?php echo $this->security->get_csrf_hash(); ?>',
            id : id
          }
        });
        request.done(function(output){
          var obj = JSON.parse(output);
          if (obj.result == 'success'){
            show_lightbox();
            hide_loading_message();
            $('h2.FormTitle').text('PRATINJAU PRINT '+obj.data.value[0].po_type);
            $('#form_inputPO .field_container label.error').hide();
            $('#form_inputPO').attr('data-id', id);
            $('#form_inputPO').attr('class', 'form printProses');
            $('.company').hide();
            $('.po_type').hide();
            $('.tambah_barang').hide();
            $('.address').show();
            $('.tanda_tangan').show();
            $('#po_date').val(obj.data.value[0].po_date);
            $('#ppns').val(obj.data.value[0].ppn);
            $('#note').val(obj.data.value[0].note);
            $('#address').val(obj.data.value[0].address);
            $('#tanda_tangan').val(obj.data.value[0].ttd);
            $('#po_date').attr("disabled", "disabled");
            $('#ppns').attr('readonly', true);
            $('#note').attr('readonly', true);
            $('#address').attr('readonly', true);
            $('#vendor').attr("disabled", "disabled");
            $('#vendor').append(
              '<option value="'+obj.data.value[0].vendor+'" selected="selected">'+obj.data.value[0].vendor+'</option>'
              ).selectpicker('refresh').trigger('change');

            var split_input = obj.data.input[0].attribute.split(',');
            for(var x = 0; x < obj.data.value.length; x++)
            {
              if(parseInt(x) === 0)
              {
                $('#detail').val(obj.data.value[x].detail);
                $('#size').val(obj.data.value[x].size);
                $('#merk').val(obj.data.value[x].merk);
                $('#type').val(obj.data.value[x].type);
                $('#core').val(obj.data.value[x].core);
                $('#gulungan').val(obj.data.value[x].gulungan);
                $('#bahan').val(obj.data.value[x].bahan);
                $('#price_1').val(obj.data.value[x].price_1);
                $('#price_2').val(obj.data.value[x].price_2);
                $('#qty').val(obj.data.value[x].qty);
                $('#unit').val(obj.data.value[x].unit);
                for(var i = 0; i < split_input.length; i++) $('.'+split_input[i]).show();
                $('#detail').attr('readonly', true);
                $('#size').attr('readonly', true);
                $('#merk').attr('readonly', true);
                $('#type').attr('readonly', true);
                $('#core').attr('readonly', true);
                $('#gulungan').attr('readonly', true);
                $('#bahan').attr('readonly', true);
                $('#price_1').attr('readonly', true);
                $('#price_2').attr('readonly', true);
                $('#qty').attr('readonly', true);
                $('#unit').attr('readonly', true);
                $('.hitung_1').hide();

              } else {
                loopN++;
                $('#looping_barang').append(
                  '<div class="looping_barang" id="looping-'+loopN+'"><hr><div class="form-group detail-'+loopN+'" style="display: none"><label for="detail">Detail: <span class="required">*</span></label><input type="text" class="form-control" name="data[detail][]" id="detail-'+loopN+'" value="'+obj.data.value[x].detail+'" required></div><div class="form-group size-'+loopN+'" style="display: none"><label for="Size">Size: <span class="required">*</span></label><input type="number" class="form-control sizeval_'+loopN+'" name="data[size][]" id="size-'+loopN+'" value="'+obj.data.value[x].size+'" required></div><div class="form-group merk-'+loopN+'" style="display: none"><label for="merk">Merk: <span class="required">*</span></label><input type="text" class="form-control" name="data[merk][]" id="merk-'+loopN+'" value="'+obj.data.value[x].merk+'" required></div><div class="form-group type-'+loopN+'" style="display: none"><label for="type">Type: <span class="required">*</span></label><input type="text" class="form-control" name="data[type][]" id="type-'+loopN+'" value="'+obj.data.value[x].type+'" required></div><div class="form-group core-'+loopN+'" style="display: none"><label for="core">Core: <span class="required">*</span></label><input type="text" class="form-control" name="data[core][]" id="core-'+loopN+'" value="'+obj.data.value[x].core+'" required></div><div class="form-group gulungan-'+loopN+'" style="display: none"><label for="gulungan">Gulungan: <span class="required">*</span></label><input type="text" class="form-control" name="data[gulungan][]" id="gulungan-'+loopN+'" value="'+obj.data.value[x].gulungan+'" required></div><div class="form-group bahan-'+loopN+'" style="display: none"><label for="bahan">Bahan: <span class="required">*</span></label><input type="text" class="form-control" name="data[bahan][]" id="bahan-'+loopN+'" value="'+obj.data.value[x].bahan+'" required></div><div class="form-group price_1"><label for="price_1">Price: <span class="required">*</span></label><input type="text" class="form-control price_1_looping-'+loopN+'" name="data[price_1][]" id="price_1-'+loopN+'" value="'+obj.data.value[x].price_1+'" required></div><div class="form-group price_2-'+loopN+'" style="display: none"><label for="price_2">Price (Secondary): <span class="required">*</span> <em class="label label-success">Size x Price = Price (secondary)</em></label><div class="row"><div class="col-md-10"><input type="text" class="form-control price_2_looping-'+loopN+'" name="data[price_2][]" id="price_2-'+loopN+'" value="'+obj.data.value[x].price_2+'" required></div><div class="col-md-2"><button type="button" class="btn btn-primary hitung_'+loopN+'">Hitung</button></div></div></div><div class="form-group qty"><label for="qty">Qty: <span class="required">*</span></label><input type="number" min="0" class="form-control" name="data[qty][]" id="qty-'+loopN+'" value="'+obj.data.value[x].qty+'" required></div><div class="form-group unit-'+loopN+'" style="display: none"><label for="unit">Unit: <span class="required">*</span></label><input type="text" class="form-control" name="data[unit][]" id="unit-'+loopN+'" value="'+obj.data.value[x].unit+'" required></div><script>$(document).ready(function(){ $(".price_1_looping-'+loopN+'").mask("0.000.000.000.000,00", {reverse: true});$(".price_2_looping-'+loopN+'").mask("0.000.000.000.000,00", {reverse: true});$(document).on("click", ".hitung_'+loopN+'", function(e){ var ukuran_'+loopN+' = $(".sizeval_'+loopN+'").val();var harga_'+loopN+' = $(".price_1_looping-'+loopN+'").val().replace(/\\./g,"");$(".price_2_looping-'+loopN+'").val(parseInt(ukuran_'+loopN+' * harga_'+loopN+'.replace(/\\,/g,".")));});});</' + 'script></div>'
                );

                for(var i = 0; i < split_input.length; i++) $('.'+split_input[i]+'-'+loopN).show();
                $('#unit-'+loopN).attr('readonly', true);
                $('#detail-'+loopN).attr('readonly', true);
                $('#size-'+loopN).attr('readonly', true);
                $('#merk-'+loopN).attr('readonly', true);
                $('#type-'+loopN).attr('readonly', true);
                $('#core-'+loopN).attr('readonly', true);
                $('#gulungan-'+loopN).attr('readonly', true);
                $('#bahan-'+loopN).attr('readonly', true);
                $('#price_1-'+loopN).attr('readonly', true);
                $('#price_2-'+loopN).attr('readonly', true);
                $('#qty-'+loopN).attr('readonly', true);
                $('#unit-'+loopN).attr('readonly', true);
                $(".hitung_"+loopN).hide();

              }
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

      $(document).on('submit', '.printProses', function(e)
      {
        e.preventDefault();
        if($('.printProses').valid() == true) {
          hide_lightbox();
          show_loading_message();
          var id      = $('.printProses').attr('data-id');
          var form_data   = $('.printProses').serialize();
          $.ajax({
            url:  '<?php echo base_url('index.php/action/all/print_po'); ?>',
            type: 'POST',
            data: form_data + '&' + $.param({ 
              'id' : id,
              '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
            }),
            success: function(respon)
            {
              var obj = JSON.parse(respon);
              if(obj.result == 'success')
              {
                hide_loading_message();
                var split_print = obj.data.print[0].attribute.split(',');
                var thead = '';
                $('.thead').append('<th class="text-center">NO</th>');
                for(var i = 0; i < split_print.length; i++)
                {
                  if(split_print[i] === 'price_1'){
                    thead = 'PRICE';
                  } else if(split_print[i] === 'price_2') {
                    thead = 'PRICE/ROLL';
                  } else {
                    thead = split_print[i].toUpperCase();
                  }

                  $('.thead').append(
                    '<th class="text-center">'+thead+'</th>'
                  );
                }
                
                $('.thead').append('<th class="text-center">TOTAL</th>');
                var itemnya = obj.data.value[0].item_to;

                for(var x = 0; x < itemnya.length; x++)
                {
                  $('.tbody').append('<tr class="tbody-value-'+x+'"></tr>');
                  $('.tbody-value-'+x).append('<td class="text-center">'+parseInt( x + 1)+'</td>');

                  for(var z = 0; z < split_print.length; z++)
                  {
                    if(split_print[z] === 'detail' || split_print[z] === 'merk')
                    {
                      $('.tbody-value-'+x).append('<td class="text-left">'+obj.data.value[0][split_print[z]][x] +'</td>');
                    } else if(split_print[z] === 'price_1'){
                      $('.tbody-value-'+x).append('<td class="text-center">'+convertToRupiah(obj.data.value[0][split_print[z]][x])+'</td>');
                    } else if(split_print[z] === 'price_2'){
                      $('.tbody-value-'+x).append('<td class="text-center">'+convertToRupiah(obj.data.value[0][split_print[z]][x])+'</td>');
                    } else {
                      $('.tbody-value-'+x).append('<td class="text-center">'+obj.data.value[0][split_print[z]][x] +'</td>');
                    }
                  }

                  $('.tbody-value-'+x).append('<td class="text-right">'+convertToRupiah(obj.data.value[0].ttl_price_item[x])+'</td>');                  
                }

                $('.tfoot-heading').append('<th colspan="'+parseInt(split_print.length + 2)+'">ADDITIONAL NOTES</th>');
                $('.tfoot-value1').append('<th class="notes" colspan="'+parseInt(split_print.length)+'" rowspan="3"></th><th>SUBTOTAL</th><th class="subtotal text-right"></th>');
                $('.tfoot-value2').append('<th>TAX</th><th class="pajak text-right"></th>');
                $('.tfoot-value3').append('<th>TOTAL</th><th class="jumlah text-right"></th>');
                $('#PrintModal').show();
                $('.tgl_po').text('DATE : '+obj.data.value[0].po_date);
                $('.penjual').text('VENDOR NAME : '+obj.data.value[0].vendor);
                $('.nomor').text('NO PO : '+obj.data.value[0].nopo);
                $('.alamat').text('ADDRESS : '+obj.data.value[0].address);
                $('.notes').text(obj.data.value[0].note);
                $('.ttd_tgl').text('Depok, '+obj.data.value[0].tgl);
                $('.ttd_person').text('( '+obj.data.value[0].ttd+' )');
                $('.subtotal').text(convertToRupiah(obj.data.value[0].subtotal));
                $('.pajak').text(convertToRupiah(obj.data.value[0].tax));
                $('.jumlah').text(convertToRupiah(obj.data.value[0].total));
                $('.company_surat strong').text(obj.data.value[0].company);
                $('.alamat_surat').text(obj.data.value[0].alamat);
                $('.telp_surat').text('Telp : '+obj.data.value[0].phone+', Email '+obj.data.value[0].email);
                if(!!obj.data.value[0].logo.length){
                  $('.logo_surat').append('<img src="'+obj.data.value[0].logo+'" height="75px" width="150px" class="center-block">');
                }

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
                show_message('Print gagal.', 'error');
              }
            },
            error: function(jqXHR, textStatus, errorThrown){
              show_message('Print gagal: '+textStatus, 'error');
            }
          });
        }
      });

      $(document).on('click', '.HapusItem', function(e)
      {
        e.preventDefault();
        var info = $(this).data('name');
        var id = $(this).data('id');
        if(confirm("Anda yakin ingin menghapus '"+info+"'?")){
          show_loading_message();
          var request = $.ajax({
            url:  '<?php echo base_url('index.php/action/all/delete_po'); ?>',
            type: 'POST',
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
                reset(); clean();
              }, true);
            } else {
                hide_loading_message();
                show_message(obj.message, 'error');
                reset(); clean();
            }
          });
          request.fail(function(jqXHR, textStatus){
            hide_loading_message();
            show_message('Gagal memasukan data: '+textStatus, 'error');
            reset(); clean();
          });
        }
      });
    });
  </script>