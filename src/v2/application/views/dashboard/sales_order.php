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
                  <th>Diterbitkan</th>
                  <th style='min-width:150px'>Customer</th>
                  <th>Estimasi</th>
                  <th style='min-width:150px'>Company</th>
                  <th>Order Grade</th>
                  <th style='min-width:150px'>No PO</th>
                  <th>No SO</th>
                  <th style='min-width:150px'>Nama Barang</th>
                  <th>Jenis Item</th>
                  <th>Ukuran</th>
                  <th>Merk</th>
                  <th>Type</th>
                  <th>Uk. Bahan Baku</th>
                  <th>Qty</th>
                  <th>Satuan</th>
                  <th>Kor</th>
                  <th>Line</th>
                  <th>Qty Bahan Baku</th>
                  <th>Gulungan</th>
                  <th>Bahan</th>
                  <th>Porporasi</th>
                  <th>Isi ROLL/PCS</th>
                  <th style='min-width:150px'>Harga</th>
                  <th style='min-width:150px'>Jumlah</th>
                  <th style='min-width:150px'>PPN</th>
                  <th style='min-width:150px'>TOTAL</th>
                  <th style='min-width:150px'>Catatan</th>
                  <th>Sources</th>
                  <th style='min-width:150px'>Total Ongkir</th>
                  <th style='min-width:150px'>Diinput</th>
                  <th style='min-width:150px'>Option</th>
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
              
              <h2 class="FormTitle" style="text-align: center">INPUT SALES ORDER</h2>
              <form class="form add" id="form_inputSO" data-id="" novalidate>

                <div class="form-group company">
                  <label for="company">Company: <span class="required">*</span></label>
                  <select class="form-control" name="company" id="company" data-live-search="true" required></select>
                </div>

                <div class="form-group customer">
                  <label for="customer">Customer: <span class="required">*</span></label>
                  <select class="form-control" name="customer" id="customer" data-live-search="true" required></select>
                  <input type="hidden" name="id_customer" id="id_customer" value="">
                  <input type="hidden" name="customers" id="customers" value="">
                </div>

                <div class="form-group po_date">
                  <label for="PO Date">PO date: <span class="required">*</span></label>
                  <input class="form-control" name="po_date" id="po_date" value="<?php echo date('d/m/Y'); ?>" data-date-format="dd/mm/yyyy" required>
                </div>

                <div class="form-group po_customer">
                  <label for="PO Customer">No PO: </label>
                  <input type="text" class="form-control" name="po_customer" id="po_customer" value="">
                </div>

                <div class="form-group order_grade">
                  <label for="Order Grade">Order Grade: <span class="required">*</span></label>
                  <select class="form-control" name="order_grade" id="order_grade" required>
                    <option value="0" selected>Reguler</option>
                    <option value="1">Spesial</option>
                  </select>
                </div>

                <hr class='header-item'>
                <p><button type="button" class="btn btn-primary tambah_barang">Tambah barang</button></p>

                <div class="form-group source">
                  <label for="source">Sources: <span class="required">*</span></label>
                  <select class="form-control" name="data[sources][]" id="source" id_source ="1" required>
                    <option selected disabled>Pilih sources</option>
                    <option value="1">Internal</option>
                    <option value="2">Subcont</option>
                    <option value="3">In Stock</option>
                  </select>
                </div>
                
                <div class="form-group etc1-class-1" style="display:none">
                  <label for="Subcont-1">Subcont kepada: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="data[etc1][]" id="etc1-input-1" required>
                </div>

                <div class="form-group etc2-class-1" style="display:none">
                  <label for="Estimasi">Estimasi: <span class="required">*</span></label>
                  <input class="form-control" name="data[etc2][]" id="etc2-input" value="<?php echo date('d/m/Y'); ?>" data-date-format="dd/mm/yyyy" required>
                </div>

                <div class="form-group item">
                  <label for="Item">Nama Barang: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="data[item][]" id="item" value="" required>
                </div>

                <div class="form-group detail">
                  <label for="detail">Jenis Barang: <span class="required">*</span></label>
                  <select class="form-control" name="data[detail][]" id="detail" required></select>
                </div>

                <div class="form-group merk" style="display:none">
                  <label for="merk">Merk: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="data[merk][]" id="merk" value="" required>
                </div>

                <div class="form-group type" style="display:none">
                  <label for="type">Type: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="data[type][]" id="type" value="" required>
                </div>

                <div class="form-group size" style="display:none">
                  <label for="Size">Size: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="data[size][]" id="size" value="" required>
                </div>

                <div class="form-group uk_bahan_baku" style="display:none">
                  <label for="sbaku">Uk. Bahan baku: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="data[uk_bahan_baku][]" id="uk_bahan_baku" required>
                </div>

                <div class="form-group qore" style="display:none">
                  <label for="Qore">Qore: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="data[qore][]" id="qore" required>
                </div>

                <div class="form-group lin" style="display:none">
                  <label for="lin">Line: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="data[lin][]" id="lin" required>
                </div>

                <div class="form-group qty_bahan_baku" style="display:none">
                  <label for="qty_bahan_baku">QTY Bahan baku: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="data[qty_bahan_baku][]" id="qty_bahan_baku" required>
                </div>

                <div class="form-group roll" style="display:none">
                  <label for="roll">Gulungan:</label>
                    <select class="form-control" name="data[roll][]" id="roll">
                      <option selected>Select roll</option>
                      <option value="FI">FI</option>
                      <option value="FO">FO</option>
                      <option value="LIPAT">LIPAT</option>
                      <option value="SHEET">SHEET</option>
                    </select>
                </div>

                <div class="form-group ingredient" style="display:none">
                  <label for="ingredient">Bahan: </label>
                  <input type="text" class="form-control" name="data[ingredient][]" id="ingredient" value="">
                </div>

                <div class="form-group porporasi" style="display:none">
                  <label>Porporasi:</label>
                  <select class="form-control" id="porporasi" name="data[porporasi][]">
                    <option selected>Pilih Porporasi</option>
                    <option value="1">YA</option>
                    <option value="0">TIDAK</option>
                    </select>
                </div>

                <div class="form-group unit" style="display:none">
                  <label for="Unit">Unit: <span class="required">*</span></label>
                    <select class="form-control" name="data[unit][]" id="unit">
                      <option value="" selected>Pilih satuan</option>
                      <option value="PCS">PCS</option>
                      <option value="ROLL">ROLL</option>
                      <option value="PAK">PAK</option>
                      <option value="METER">METER</option>
                    </select>
                </div>
        
                <div class="form-group qty">
                  <label for="Qty">Qty: <span class="required">*</span></label>
                  <input type="number" min="1" class="form-control" name="data[qty][]" id="qty" value="0" placeholder="0" required>
                </div>

                <div class="form-group volume" style="display:none">
                  <label for="volume">Isi Roll/Pcs: <span class="required">*</span></label>
                  <input type="number" min="0" class="form-control" name="data[volume][]" id="volume" value="" placeholder="1" required>
                </div>

                <div class="form-group price">
                  <label for="Price">Harga: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="data[price][]" id="price" value="0" placeholder="0" required>
                </div>

                <div class="form-group annotation">
                  <label for="Annotation">Catatan:</label>
                  <input type="text" class="form-control" name="data[annotation][]" id="annotation" value="">
                </div>

                <div id="looping_barang"></div>
                
                <hr class='footer-item'>

                <div class="form-group ppns">
                  <label for="sel1">PPN: <span class="required">*</span></label></label>
                  <select class="form-control" name="ppns" id="ppns" required>
                    <option selected disabled>Menggunakan PPN 10% ?</option>
                    <option value="0">Tidak</option>
                    <option value="1">Ya</option>
                  </select>
                </div>

                <div class="button_container" style="text-align: center">
                  <button type="submit" class="saving">Simpan</button>
                  <input type="button" class="lightbox_close" value="Batal">
                </div>
                <input type="hidden" name="id_wo" id="id_wo" value="">
                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
              </form>
            </div>
          </div>

          <!-- Modal Ongkir -->
          <div id="OngkirModal" class="modal">

            <!-- Modal content -->
            <div class="modal-content">
              <span class="close ongkirbox_close">&times;</span>
              
              <h2 class="FormTitle" style="text-align: center">INPUT ONGKIR</h2>
              <form class="form add" id="form_inputOngkir" data-id="" novalidate>

                <div class="form-group surat_jalan">
                  <label for="surat_jalan">No Surat Jalan: <span class="required">*</span></label>
                  <select class="form-control" name="surat_jalan" id="surat_jalan" required></select>
                </div>

                <div class="form-group ongkos_kirim">
                  <label for="ongkos_kirim">Biaya Kirim: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="ongkos_kirim" id="ongkos_kirim" required>
                </div>

                <div class="form-group ekspedisi">
                  <label for="ekspedisi">Ekspedisi: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="ekspedisi" id="ekspedisi" required>
                </div>

                <div class="form-group ppns">
                  <label for="sel1">Unit: <span class="required">*</span></label></label>
                  <select class="form-control" name="uom" id="uom" required>
                    <option value="" selected>Pilih satuan</option>
                    <option value="KG">KG</option>
                    <option value="DUS">DUS</option>
                  </select>
                </div>

                <div class="form-group jml">
                  <label for="jml">Jumlah: <span class="required">*</span></label>
                  <input type="number" min="0" class="form-control" name="jml" id="jml" required>
                </div>
        
                <div class="button_container" style="text-align: center">
                  <button type="submit" class="saving">Simpan</button>
                  <input type="button" class="ongkirbox_close" value="Batal">
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
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <script src="<?php echo base_url('assets/js/bootstrap/bootstrap-select.min.js'); ?>"></script>
  <script src="<?php echo base_url('assets/js/bootstrap/ajax-bootstrap-select.min.js'); ?>"></script>
  <script src="<?php echo base_url('assets/js/bootstrap/bootstrap-datepicker.min.js'); ?>"></script>
  <script>
    $(document).ready(function()
    {
      var detailJSON = [];
      var barisN = 1;
      $('#form_inputOngkir').validate();
      $('#form_inputSO').validate();
      $("#po_date").datepicker();
      $("#etc2-input").datepicker();
      $('#price').mask('0.000.000.000.000,00', {reverse: true});
      $('#ongkos_kirim').mask('0.000.000.000.000', {reverse: true});
      $('#qty').keyup(function(){
        $('#ppns').val('0');
      });

      $('#price').keyup(function(){
        $('#ppns').val('0');
      });

      $('.tambah_barang').click(function(){
        $('#ppns').val('0');
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
          table   : 'preorder_customer',
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

      /////////////////////////////////////////////////////////////////
      // Get item list
      /////////////////////////////////////////////////////////////////

      var options_detail_item = $.ajax({
        url: '<?php echo base_url('index.php/action/json/so_detail'); ?>',
        type: 'POST',
        data: {
          <?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>'
        }
      });

      options_detail_item.done(function(resp) {
        detailJSON = resp.data;
      });

      //////////////////////////////////////////////////////
      // Pemeriksaan tipe item subcont, internal, in stock
      //////////////////////////////////////////////////////

      $(document).on("change", "#source", function(){
        var idsumber = $(this).attr("id_source");  
        var valSource = $(this).val();
        if(valSource == "2"){
          $("label[for='Subcont-"+idsumber+"']").html("Subcont kepada: <span class='required'>*</span>");
            $("#etc1-input-"+idsumber+"").attr("type", "text");
            $(".etc1-class-"+idsumber+"").show();
            $(".etc2-class-"+idsumber+"").show();
          } else if(valSource == "3"){
          $("label[for='Subcont-"+idsumber+"']").html("Stok tersedia: <span class='required'>*</span>");
            $("#etc1-input-"+idsumber+"").attr("type", "number");
            $(".etc1-class-"+idsumber+"").show();
            $(".etc2-class-"+idsumber+"").hide();
          } else {
            $(".etc1-class-"+idsumber+"").hide();
            $(".etc2-class-"+idsumber+"").hide();
          }
      });

      //////////////////////////////////////////////////////
      ////////// Pemeriksaan Jenis Item
      //////////////////////////////////////////////////////

      $(document).on('change', '#detail', function(e){
        e.preventDefault(); hidden_input();
        var id = $(this).val();
        var attributeGET = $.ajax({
          url: '<?php echo base_url('index.php/action/json/so_attribute'); ?>',
          type: 'POST',
          data: {
            id : id,
            <?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>'
          }
        });

        attributeGET.done(function(output){
          var split = output.data[0].field.split(',');
          for(var loop = 0; loop < split.length; loop++) $('.'+split[loop]).show();
        });

        attributeGET.fail(function(jqXHR, textStatus){
          hide_loading_message();
          show_message('Gagal mengambil data: '+textStatus, 'error');
        });
      });

      function convertToRupiah(angka){
        var checked = angka.toString().split('.').join(',');
        var filter = 'Rp. ' + checked.replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1.");
        return filter;
      }

      function show_message(message_text, message_type)
      {
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

      function show_input(){
        $('.merk').show();
        $('.type').show();
        $('.size').show();
        $('.uk_bahan_baku').show();
        $('.qore').show();
        $('.lin').show();
        $('.qty_bahan_baku').show();
        $('.roll').show();
        $('.ingredient').show();
        $('.porporasi').show();
        $('.unit').show();
        $('.volume').show();
      }

      function hidden_input(){
        $('.merk').hide();
        $('.type').hide();
        $('.size').hide();
        $('.uk_bahan_baku').hide();
        $('.qore').hide();
        $('.lin').hide();
        $('.qty_bahan_baku').hide();
        $('.roll').hide();
        $('.ingredient').hide();
        $('.porporasi').hide();
        $('.unit').hide();
        $('.volume').hide();
      }

      function reset(){
        $('#customer').val('');
        $('#po_customer').val('');
        $('#detail').val('');
        $('#item').val('');
        $('#size').val('');
        $('#merk').val('');
        $('#type').val('');
        $('#qty').val('');
        $('#unit').val('');
        $('#price').val('');
        $('#ppn').val('');
        $('#qore').val('');
        $('#lin').val('');
        $('#roll').val('');
        $('#ingredient').val('');
        $('#volume').val('');
        $('#annotation').val('');
        $('#etc1-input-1').val('');
        $('#etc2-input').val('');
        $('#ppns').val('0');
        $('.company').show();
        $('.customer').show();
        $('.po_date').show();
        $('.po_customer').show();
        $('.header-item').show();
        $('.tambah_barang').show();
        $('.order_grade').show();
        $('.source').show();
        $('.item').show();
        $('.detail').show();
        $('.size').show();
        $('.qore').show();
        $('.lin').show();
        $('.uk_bahan_baku').show();
        $('.qty_bahan_baku').show();
        $('.roll').show();
        $('.ingredient').show();
        $('.porporasi').show();
        $('.volume').show();
        $('.annotation').show();
        $('.qty').show();
        $('.unit').show();
        $('.price').show();
        $('.footer-item').show();
        $('#source').attr('name','data[sources][]');
        $('#etc1-input-1').attr('name','data[etc1][]');
        $('#etc2-input').attr('name','data[etc2][]');
        $('#qore').attr('name','data[qore][]');
        $('#lin').attr('name','data[lin][]');
        $('#roll').attr('name','data[roll][]');
        $('#ingredient').attr('name','data[ingredient][]');
        $('#porporasi').attr('name','data[porporasi][]');
        $('#volume').attr('name','data[volume][]');
        $('#annotation').attr('name','data[annotation][]');
        $('#uk_bahan_baku').attr('name','data[uk_bahan_baku][]');
        $('#qty_bahan_baku').attr('name','data[qty_bahan_baku][]');
        $('#detail').attr('name','data[detail][]');
        $('#merk').attr('name','data[merk][]');
        $('#type').attr('name','data[type][]');
        $('#item').attr('name','data[item][]');
        $('#unit').attr('name','data[unit][]');
        $('#qty').attr('name','data[qty][]');
        $('#price').attr('name','data[price][]');
        $('.looping_barang').remove();
        $('#company').empty();
        $('#detail').empty();
      }

      function reset_form_add_so(){
        $("#id_customer").val('0');
        $("#customers").val('0');
        $('#item').val('');
        $('#size').val('');
        $('#merk').val('');
        $('#type').val('');
        $('#detail').val('');
        $('#uk_bahan_baku').val('');
        $('#qore').val('');
        $('#lin').val('');
        $('#qty_bahan_baku').val('');
        $('#roll').val('');
        $('#ingredient').val('');
        $('#porporasi').val('');
        $('#unit').val('');
        $('#qty').val('');
        $('#volume').val('');
        $('#price').val('');
        $('#annotation').val('');
        $('.looping_barang').remove();
        hidden_input();
      }

      // Lightbox close button
      $(document).on('click', '.lightbox_close', function(){
          hide_lightbox();
          reset();
      });

      var tabel = $('#tablenya').DataTable({
        processing: true,
        serverSide: true,
        scrollX   : true,
        ordering  : false,
        ajax: {
          url: "<?php echo base_url('index.php/action/all/sales_order'); ?>",
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
            columns: [ 0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,17,19,20,21,22,23,24,25,26,27,28,29 ]
          }
        },
        <?php if($this->session->userdata('role') != '5'){ ?> {
          text: 'Create Sales Order',
            action: function ( e ) {
              e.preventDefault();
              hidden_input(); show_lightbox();
              $('H2.FormTitle').text('INPUT SALES ORDER');
              $('#form_inputSO').attr('class', 'form add');
              $('#form_inputSO').attr('data-id', '');
              $('.company').show();
              $('.customer').show();
              $('.po_date').show();
              $('.po_customer').show();
              $('.order_grade').show();
              $('.header-item').show();
              $('.tambah_barang').show();
              $('.footer-item').show();
              $('.ppns').show();
              $('#detail').append('<option value="" selected>Pilih Item</option>');
              for(var z = 0; z < detailJSON.length; z++) $('#detail').append('<option value="'+detailJSON[z].id+'">' +detailJSON[z].item+ '</option>');
            }
        } <?php } ?>],
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
          uangETD = api.column( 23, { page: 'current'} ).data().reduce( function (a, b) {
            return intVal(a) + intVal(b);
          }, 0 );

          uangPPN = api.column( 24, { page: 'current'} ).data().reduce( function (a, b) {
            return intVal(a) + intVal(b);
          }, 0 );

          Totals = uangETD + uangPPN;

          $( api.column( 23 ).footer() ).html(convertToRupiah(uangETD));
          $( api.column( 24 ).footer() ).html(convertToRupiah(uangPPN));
          $( api.column( 25 ).footer() ).html(convertToRupiah(Totals));
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
          emptyTitle: 'Pilih Company',
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

      var options_customer = {
        ajax: {
          url: '<?php echo base_url('index.php/action/json/so_customer'); ?>',
          type: 'POST',
          dataType: 'json',
          data: {
            q: '{{{q}}}',
            <?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>'
          }
        },
        minLength: 3,
        locale: {
          emptyTitle: 'Pilih Customer',
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
                  customer: data[i].id_customer,
                  subtext: data[i].subtext,
                  name: data[i].name
                },
              }));
            }
          }

          return array;
        }
      };
      $('#customer').selectpicker().ajaxSelectPicker(options_customer);

      $('#customer').on('changed.bs.select', function ()
      {
        reset_form_add_so();
        var dataid = $('#form_inputSO').attr('data-id');
        var id_customer = $('option:selected', this).attr("data-customer");
        var id_po = $('option:selected', this).attr("data-po");
        $('#id_customer').val(id_customer);
        $('#customers').val($('option:selected', this).attr("data-name"));
        if(isNaN(parseInt(dataid)))
        {
          var get_po_item = $.ajax({
            url: '<?php echo base_url('index.php/action/json/so_item'); ?>',
            type: 'POST',
            data: {
              customer : id_customer,
              po : id_po,
              <?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>'
            },
          });

          get_po_item.done(function(output)
          {
            show_input();
            var options = '';
            $('#id_customer').val(output[0].id_customer);
            for(var z = 0; z < detailJSON.length; z++) options += '<option value="'+detailJSON[z].id+'">' +detailJSON[z].item+ '</option>';

            if(parseInt(output[0].id_po) > 0)
            {
              for(var x = 0; x < output.length; x++)
              {
                if(parseInt(x) == '0')
                { 
                  $('#item').val(output[x].item);
                  $('#size').val(output[x].size);
                  $('#uk_bahan_baku').val(output[x].uk_bahan_baku);
                  $('#qore').val(output[x].qore);
                  $('#lin').val(output[x].lin);
                  $('#qty_bahan_baku').val(output[x].qty_bahan_baku);
                  $('#roll').val(output[x].roll);
                  $('#ingredient').val(output[x].ingredient);
                  $('#porporasi').val(output[x].porporasi);
                  $('#unit').val(output[x].unit);
                  $('#qty').val(output[x].qty);
                  $('#volume').val(output[x].volume);
                  $('#price').val(output[x].price);
                  $('#annotation').val(output[x].annotation);
                  $('#detail').val(output[x].detail);
                  $('#merk').val(output[x].merk);
                  $('#type').val(output[x].type);

                } else {
                  $('#looping_barang').append(
                    '<div class="looping_barang" id="looping-'+barisN+'"><hr class="looping-item"><p><button type="button" name="remove" idx="'+barisN+'" class="btn btn-danger btn_remove">Hapus</button></p><div class="form-group source-'+barisN+'"><label for="source">Sources: <span class="required">*</span></label><select class="form-control" name="data[sources][]" id="source" id_source="'+barisN+'" required><option selected disabled>Pilih sources</option><option value="1">Internal</option><option value="2">Subcont</option><option value="3">In Stock</option></select></div><div class="form-group etc1-class-'+barisN+'" style="display:none"><label for="Subcont-'+barisN+'">Subcont kepada: <span class="required">*</span></label><input type="text" class="form-control" name="data[etc1][]" id="etc1-input-'+barisN+'" required></div><div class="form-group etc2-class-'+barisN+'" style="display:none"><label for="Estimasi">Estimasi: <span class="required">*</span></label><input class="form-control" name="data[etc2][]" id="etc2-input-'+barisN+'" value="<?php echo date('d/m/Y'); ?>" data-date-format="dd/mm/yyyy" required></div><div class="form-group item-'+barisN+'"><label for="Item">Nama Barang: <span class="required">*</span></label><input type="text" class="form-control" name="data[item][]" id="item" value="'+output[x].item+'" required></div><div class="form-group detail-'+barisN+'"><label for="detail">Jenis Item: <span class="required">*</span></label><select class="form-control" name="data[detail][]" id="detail-'+barisN+'" required><option value="" selected>Pilih Item</option>'+options+'</select></div><div class="form-group merk-'+barisN+'"><label for="merk">Merk: <span class="required">*</span></label><input type="text" class="form-control" name="data[merk][]" id="merk" value="'+output[x].merk+'" required></div><div class="form-group type-'+barisN+'"><label for="type">Type: <span class="required">*</span></label><input type="text" class="form-control" name="data[type][]" id="type" value="'+output[x].type+'" required></div><div class="form-group size-'+barisN+'"><label for="Size">Ukuran: <span class="required">*</span></label><input type="text" class="form-control" name="data[size][]" id="size" value="'+output[x].size+'" required></div><div class="form-group uk_bahan_baku-'+barisN+'"><label for="uk_bahan_baku">Uk. Bahan baku: <span class="required">*</span></label><input type="text" class="form-control" name="data[uk_bahan_baku][]" id="uk_bahan_baku" value="'+output[x].uk_bahan_baku+'" required></div><div class="form-group qore-'+barisN+'"><label for="Qore">Kor: <span class="required">*</span></label><input type="text" class="form-control" name="data[qore][]" id="qore" value="'+output[x].qore+'" required></div><div class="form-group lin-'+barisN+'"><label for="lin">Line: <span class="required">*</span></label><input type="text" class="form-control" name="data[lin][]" id="lin" value="'+output[x].lin+'" required></div><div class="form-group qty_bahan_baku-'+barisN+'"><label for="qty_bahan_baku">QTY Bahan baku: <span class="required">*</span></label><input type="text" class="form-control" name="data[qty_bahan_baku][]" id="qty_bahan_baku" value="'+output[x].qty_bahan_baku+'" required></div><div class="form-group roll-'+barisN+'"><label for="roll">Gulungan: <span class="required">*</span></label><select class="form-control" name="data[roll][]" id="roll" required><option disabled>Select roll</option><option value="FI" '+(output[x].roll == "FI" ? "selected": "")+'>FI</option><option value="FO" '+(output[x].roll == "FO" ? "selected": "")+'>FO</option><option value="LIPAT" '+(output[x].roll == "LIPAT" ? "selected": "")+'>LIPAT</option><option value="SHEET" '+(output[x].roll == "SHEET" ? "selected": "")+'>SHEET</option></select></div><div class="form-group ingredient-'+barisN+'"><label for="ingredient">Bahan: </label><input type="text" class="form-control" name="data[ingredient][]" id="ingredient" value="'+output[x].ingredient+'"></div><div class="form-group porporasi-'+barisN+'"><label for="porporasi">Porporasi: <span class="required">*</span></label><select class="form-control" id="porporasi" name="data[porporasi][]" required><option disabled>Pilih Porporasi</option><option value="YA" '+(output[x].porporasi == "1" ? "selected": "")+'>YA</option><option value="TIDAK" '+(output[x].porporasi == "0" ? "selected": "")+'>TIDAK</option></select></div><div class="form-group unit-'+barisN+'"><label for="Unit">Satuan: <span class="required">*</span></label><select class="form-control" name="data[unit][]" id="unit" value="'+output[x].unit+'" required><option disabled>Select satuan</option><option value="PCS" '+(output[x].unit == "PCS" ? "selected": "")+'>PCS</option><option value="ROLL" '+(output[x].unit == "ROLL" ? "selected": "")+'>ROLL</option><option value="PAK" '+(output[x].unit == "PAK" ? "selected": "")+'>PAK</option><option value="METER" '+(output[x].unit == "METER" ? "selected": "")+'>METER</option></select></div><div class="form-group qty-'+barisN+'"><label for="Qty">Qty: <span class="required">*</span></label><input type="number" min="1" class="form-control" name="data[qty][]" id="qty-"'+barisN+' placeholder="0" value="'+output[x].qty+'" required></div><div class="form-group volume-'+barisN+'"><label for="volume">Isi Roll/Pcs: <span class="required">*</span></label><input type="number" min="0" class="form-control" name="data[volume][]" id="volume" placeholder="1" value="'+output[x].volume+'" required></div><div class="form-group price-'+barisN+'"><label for="Price">Harga: <span class="required">*</span></label><input type="text" value="" class="form-control" name="data[price][]" id="price-'+barisN+'" placeholder="0" value="'+output[x].price+'" required></div><div class="form-group annotation-'+barisN+'"><label for="Annotation">Catatan:</label><input type="text" class="form-control" name="data[annotation][]" id="annotation" value="'+output[x].annotation+'"></div><script>$(document).ready(function(){ $("#etc2-input-'+barisN+'").datepicker();$("#price-'+barisN+'").mask("0.000.000.000.000,00", {reverse: true});$("#qty-'+barisN+'").keyup(function(){ $("#ppns").val("0"); });$("#price-'+barisN+'").keyup(function(){ $("#ppns").val("0"); });$(document).on("change", "#detail-'+barisN+'", function(e){e.preventDefault();$(".merk-'+barisN+'").hide();$(".type-'+barisN+'").hide();$(".size-'+barisN+'").hide();$(".uk_bahan_baku-'+barisN+'").hide();$(".qore-'+barisN+'").hide();$(".lin-'+barisN+'").hide();$(".qty_bahan_baku-'+barisN+'").hide();$(".roll-'+barisN+'").hide();$(".ingredient-'+barisN+'").hide();$(".porporasi-'+barisN+'").hide();$(".unit-'+barisN+'").hide();$(".volume-'+barisN+'").hide();var id_'+barisN+' = $(this).val();if(id_'+barisN+'){$.ajax({url:"<?php echo base_url('index.php/action/json/so_attribute'); ?>",type:"POST",data:{id : id_'+barisN+',<?php echo $this->security->get_csrf_token_name(); ?>: "<?php echo $this->security->get_csrf_hash(); ?>"},success: function(output){var split_'+barisN+' = output.data[0].field.split(",");for(var loop_'+barisN+' = 0; loop_'+barisN+' < split_'+barisN+'.length; loop_'+barisN+'++)$("."+split_'+barisN+'[loop_'+barisN+']+"-'+barisN+'").show();}});}});});</' + 'script></div>'
                  );
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
      // Add PO (item)
      //////////////////////////

      $(document).on('click', '.tambah_barang', function(e)
      {
        e.preventDefault();
        var options = ''; barisN++;
        for(var z = 0; z < detailJSON.length; z++)
        {
          options += '<option value="'+detailJSON[z].id+'">' +detailJSON[z].item+ '</option>';
        }

        $('#looping_barang').append(
          '<div class="looping_barang" id="looping-'+barisN+'"><hr class="looping-item"><p><button type="button" name="remove" idx="'+barisN+'" class="btn btn-danger btn_remove">Hapus</button></p><div class="form-group source-'+barisN+'"><label for="source">Sources: <span class="required">*</span></label><select class="form-control" name="data[sources][]" id="source" id_source="'+barisN+'" required><option selected disabled>Pilih sources</option><option value="1">Internal</option><option value="2">Subcont</option><option value="3">In Stock</option></select></div><div class="form-group etc1-class-'+barisN+'" style="display:none"><label for="Subcont-'+barisN+'">Subcont kepada: <span class="required">*</span></label><input type="text" class="form-control" name="data[etc1][]" id="etc1-input-'+barisN+'" required></div><div class="form-group etc2-class-'+barisN+'" style="display:none"><label for="Estimasi">Estimasi: <span class="required">*</span></label><input class="form-control" name="data[etc2][]" id="etc2-input-'+barisN+'" value="<?php echo date('d/m/Y'); ?>" data-date-format="dd/mm/yyyy" required></div><div class="form-group item-'+barisN+'"><label for="Item">Nama Barang: <span class="required">*</span></label><input type="text" class="form-control" name="data[item][]" id="item" required></div><div class="form-group detail-'+barisN+'"><label for="detail">Jenis Item: <span class="required">*</span></label><select class="form-control" name="data[detail][]" id="detail-'+barisN+'" required><option value="" selected>Pilih Item</option>'+options+'</select></div><div class="form-group merk-'+barisN+'" style="display:none"><label for="merk">Merk: <span class="required">*</span></label><input type="text" class="form-control" name="data[merk][]" id="merk" value="" required></div><div class="form-group type-'+barisN+'" style="display:none"><label for="type">Type: <span class="required">*</span></label><input type="text" class="form-control" name="data[type][]" id="type" value="" required></div><div class="form-group size-'+barisN+'" style="display:none"><label for="Size">Ukuran: <span class="required">*</span></label><input type="text" class="form-control" name="data[size][]" id="size" required></div><div class="form-group uk_bahan_baku-'+barisN+'" style="display:none"><label for="uk_bahan_baku">Uk. Bahan baku: <span class="required">*</span></label><input type="text" class="form-control" name="data[uk_bahan_baku][]" id="uk_bahan_baku" required></div><div class="form-group qore-'+barisN+'" style="display:none"><label for="Qore">Kor: <span class="required">*</span></label><input type="text" class="form-control" name="data[qore][]" id="qore" required></div><div class="form-group lin-'+barisN+'" style="display:none"><label for="lin">Line: <span class="required">*</span></label><input type="text" class="form-control" name="data[lin][]" id="lin" required></div><div class="form-group qty_bahan_baku-'+barisN+'" style="display:none"><label for="qty_bahan_baku">QTY Bahan baku: <span class="required">*</span></label><input type="text" class="form-control" name="data[qty_bahan_baku][]" id="qty_bahan_baku" required></div><div class="form-group roll-'+barisN+'" style="display:none"><label for="roll">Gulungan: <span class="required">*</span></label><select class="form-control" name="data[roll][]" id="roll" required><option disabled selected>Select roll</option><option value="FI">FI</option><option value="FO">FO</option><option value="LIPAT">LIPAT</option><option value="SHEET">SHEET</option></select></div><div class="form-group ingredient-'+barisN+'" style="display:none"><label for="ingredient">Bahan: </label><input type="text" class="form-control" name="data[ingredient][]" id="ingredient"></div><div class="form-group porporasi-'+barisN+'" style="display:none"><label for="porporasi">Porporasi: <span class="required">*</span></label><select class="form-control" id="porporasi" name="data[porporasi][]" required><option selected>Pilih Porporasi</option><option value="YA">YA</option><option value="TIDAK">TIDAK</option></select></div><div class="form-group unit-'+barisN+'" style="display:none"><label for="Unit">Satuan: <span class="required">*</span></label><select class="form-control" name="data[unit][]" id="unit" required><option disabled selected>Select satuan</option><option value="PCS">PCS</option><option value="ROLL">ROLL</option><option value="PAK">PAK</option><option value="METER">METER</option></select></div><div class="form-group qty-'+barisN+'"><label for="Qty">Qty: <span class="required">*</span></label><input type="number" value="" min="1" class="form-control" name="data[qty][]" id="qty-"'+barisN+' placeholder="0" required></div><div class="form-group volume-'+barisN+'" style="display:none"><label for="volume">Isi Roll/Pcs: <span class="required">*</span></label><input type="number" min="0" class="form-control" name="data[volume][]" id="volume" placeholder="1" required></div><div class="form-group price-'+barisN+'"><label for="Price">Harga: <span class="required">*</span></label><input type="text" value="" class="form-control" name="data[price][]" id="price-'+barisN+'" placeholder="0" required></div><div class="form-group annotation-'+barisN+'"><label for="Annotation">Catatan:</label><input type="text" class="form-control" name="data[annotation][]" id="annotation"></div><script>$(document).ready(function(){ $("#etc2-input-'+barisN+'").datepicker();$("#price-'+barisN+'").mask("0.000.000.000.000,00", {reverse: true});$("#qty-'+barisN+'").keyup(function(){ $("#ppns").val("0"); });$("#price-'+barisN+'").keyup(function(){ $("#ppns").val("0"); });$(document).on("change", "#detail-'+barisN+'", function(e){e.preventDefault();$(".merk-'+barisN+'").hide();$(".type-'+barisN+'").hide();$(".size-'+barisN+'").hide();$(".uk_bahan_baku-'+barisN+'").hide();$(".qore-'+barisN+'").hide();$(".lin-'+barisN+'").hide();$(".qty_bahan_baku-'+barisN+'").hide();$(".roll-'+barisN+'").hide();$(".ingredient-'+barisN+'").hide();$(".porporasi-'+barisN+'").hide();$(".unit-'+barisN+'").hide();$(".volume-'+barisN+'").hide();var id_'+barisN+' = $(this).val();if(id_'+barisN+'){$.ajax({url:"<?php echo base_url('index.php/action/json/so_attribute'); ?>",type:"POST",data:{id : id_'+barisN+',<?php echo $this->security->get_csrf_token_name(); ?>: "<?php echo $this->security->get_csrf_hash(); ?>"},success: function(output){var split_'+barisN+' = output.data[0].field.split(",");for(var loop_'+barisN+' = 0; loop_'+barisN+' < split_'+barisN+'.length; loop_'+barisN+'++)$("."+split_'+barisN+'[loop_'+barisN+']+"-'+barisN+'").show();}});}});});</' + 'script></div>'
        );
      });
      
      $(document).on('click', '.btn_remove', function(){  
        var button_id = $(this).attr('idx');
        $('#looping-'+button_id+'').remove();
        $('#ppns').val('0');
      });

      $(document).on('submit', '#form_inputSO.add', function(e){
        e.preventDefault();
        if($('#form_inputSO').valid() == true){
          hide_lightbox();
          show_loading_message();
          var form_data = $('#form_inputSO').serialize();
          var request   = $.ajax({
            url: '<?php echo base_url('index.php/action/all/add_so'); ?>',
            data: form_data,
            cache: false,
            type: 'POST'
          });
          request.done(function(output){
            if(output.result == 'success'){
              tabel.ajax.reload(function(){
                hide_loading_message();
                show_message(output.message, 'success');
                $('.looping_barang').remove(); reset();
              }, true);
            } else {
                hide_loading_message();
                show_message(output.message, 'error');
                $('#detail').empty();
            }
          });
          request.fail(function(jqXHR, textStatus){
            hide_loading_message();
            show_message('Gagal memasukan data: '+textStatus, 'error');
            $('#detail').empty();
          });
        }
      });

      $(document).on('click', '.UbahCustomer', function(e){
        e.preventDefault();
        show_loading_message();
        hidden_input();
        var id      = $(this).data('id').split('-');
        var request = $.ajax({
          url:  '<?php echo base_url('index.php/action/all/get_so'); ?>',
          type: 'POST',
          data: {
            <?php echo $this->security->get_csrf_token_name(); ?> : '<?php echo $this->security->get_csrf_hash(); ?>',
            id : id[0]
          }
        });
        request.done(function(output){
          var obj = JSON.parse(output);
          if(obj.result == 'success'){
            hide_loading_message();
            show_lightbox();
            $('h2.FormTitle').text('UBAH SALES ORDER');
            $('#form_inputSO').attr('class', 'form edit_customer');
            $('#form_inputSO').attr('data-id', id[0]+'-'+id[1]);
            $('#form_inputSO .field_container label.error').hide();

            $('#company').append(
              '<option value="'+obj.data[0].id_company+'" data-subtext="'+obj.data[0].address+'" selected="selected">'+obj.data[0].company+'</option>'
              ).selectpicker('refresh').trigger('change');

            $('#customer').append(
              '<option value="0" data-subtext="" selected="selected">'+obj.data[0].customer+'</option>'
              ).selectpicker('refresh').trigger('change');
            
            $('#id_customer').val(obj.data[0].id_customer);
            $('#customers').val(obj.data[0].customer);
            $('#order_grade').val(obj.data[0].order_grade);
            $('#po_date').val(obj.data[0].po_date);
            $('#po_customer').val(obj.data[0].po_customer);
            $('#ppns').val(obj.data[0].ppn);
            $('.ppns').show();
            $('.source').hide();
            $('.item').hide();
            $('.detail').hide();
            $('.price').hide();
            $('.qty').hide();
            $('.annotation').hide();
            $('.header-item').hide();
            $('.tambah_barang').hide();
            $('.footer-item').hide();

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

      $(document).on('submit', '#form_inputSO.edit_customer', function(e){
        e.preventDefault();
        if($('#form_inputSO').valid() == true){
          hide_lightbox();
          show_loading_message();
          var id        = $('#form_inputSO').attr('data-id').split("-");
          var form_data = $('#form_inputSO').serialize();
          var request   = $.ajax({
            url: '<?php echo base_url('index.php/action/all/edit_so'); ?>',
            data: form_data + '&' + $.param({ 'id' : id[0], 'fk' : id[1] }),
            cache: false,
            type: 'POST'
          });
          request.done(function(output){
            var obj = JSON.parse(output);
            if(obj.result == 'success'){
              tabel.ajax.reload(function(){
                hide_loading_message();
                show_message(obj.message, 'success');
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

      $(document).on('click', '.UbahItem', function(e){
        e.preventDefault();
        show_loading_message();
        hidden_input();
        var id      = $(this).data('id');
        var request = $.ajax({
          url:  '<?php echo base_url('index.php/action/all/get_so_item'); ?>',
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
            var exSources = obj.data[0].sources.split('|');
            $('h2.FormTitle').text('UBAH BARANG SALES ORDER');
            $('#form_inputSO').attr('class', 'form edit_item');
            $('#form_inputSO').attr('data-id', id);
            $('#form_inputSO .field_container label.error').hide();
            if(exSources[0] == 2){
              $("label[for='Subcont-1']").html("Subcont kepada: <span class='required'>*</span>");
              $("#etc1-input-1").attr("type", "text");
              $("#source").val(exSources[0]);
              $("#etc1-input-1").val(exSources[1]);
              $("#etc2-input").val(exSources[2]);
              $(".etc1-class-1").show();
              $(".etc2-class-1").show();
            } else if(exSources[0] == 3) {
              $("label[for='Subcont-1']").html("Stok tersedia: <span class='required'>*</span>");
              $("#etc1-input-1").attr("type", "number");
              $("#source").val(exSources[0]);
              $("#etc1-input-1").val(exSources[1]);
              $(".etc1-class-1").show();
              $(".etc2-class-1").hide();
            } else {
              $("#source").val(exSources[0]);
              $(".etc1-class-1").hide();
              $(".etc2-class-1").hide();
            }
            
            $('#item').val(obj.data[0].item);
            $('#detail').append('<option value="" selected>Pilih Item</option>');
            for(var z = 0; z < detailJSON.length; z++) $('#detail').append('<option value="'+detailJSON[z].id+'" '+(detailJSON[z].id == obj.data[0].detail ? 'selected': '')+'>' +detailJSON[z].item+ '</option>');
            $('#size').val(obj.data[0].size);
            $('#merk').val(obj.data[0].merk);
            $('#type').val(obj.data[0].type);
            $('#uk_bahan_baku').val(obj.data[0].uk_bahan_baku);
            $('#qore').val(obj.data[0].qore);
            $('#lin').val(obj.data[0].lin);
            $('#qty_bahan_baku').val(obj.data[0].qty_bahan_baku);
            $('#roll').val(obj.data[0].roll);
            $('#ingredient').val(obj.data[0].ingredient);
            $('#porporasi').val(obj.data[0].porporasi);
            $('#unit').val(obj.data[0].unit);
            $('#volume').val(obj.data[0].volume);
            $('#annotation').val(obj.data[0].annotation);
            $('#qty').val(obj.data[0].qty);
            $('#unit').val(obj.data[0].unit);
            $('#price').val(obj.data[0].price);
            $('#id_wo').val(obj.data[0].id_wo);
            $('#source').attr('name','sources');
            $('#etc1-input-1').attr('name','etc1');
            $('#etc2-input').attr('name','etc2');
            $('#item').attr('name','item');
            $('#detail').attr('name','detail');
            $('#merk').attr('name','merk');
            $('#type').attr('name','type');
            $('#size').attr('name','size');
            $('#uk_bahan_baku').attr('name','uk_bahan_baku');
            $('#qty').attr('name','qty');
            $('#unit').attr('name','unit');
            $('#price').attr('name','price');
            $('#qore').attr('name','qore');
            $('#lin').attr('name','lin');
            $('#qty_bahan_baku').attr('name','qty_bahan_baku');
            $('#roll').attr('name','roll');
            $('#ingredient').attr('name','ingredient');
            $('#porporasi').attr('name','porporasi');
            $('#volume').attr('name','volume');
            $('#annotation').attr('name','annotation');
            $('.company').hide();
            $('.customer').hide();
            $('.po_date').hide();
            $('.po_customer').hide();
            $('.order_grade').hide();
            $('.header-item').hide();
            $('.tambah_barang').hide();
            $('.footer-item').hide();
            $('.ppns').hide();

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
      
      $(document).on('submit', '#form_inputSO.edit_item', function(e){
        e.preventDefault();
        if($('#form_inputSO').valid() == true){
          hide_lightbox();
          show_loading_message();
          var id        = $('#form_inputSO').attr('data-id');
          var form_data = $('#form_inputSO').serialize();
          var request   = $.ajax({
            url: '<?php echo base_url('index.php/action/all/edit_so_item'); ?>',
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

      /////////////////////////////////////////////////////////
      ////////////// ONGKIR FROM FUNCTION
      /////////////////////////////////////////////////////////

      function ongkirbox_show(){
        $('#OngkirModal').show();
      }

      function ongkirbox_close(){
        $('#OngkirModal').hide();
      }

      function ongkirbox_reset(){
        $('#ongkos_kirim').val('');
        $('#ekspedisi').val('');
        $('#jml').val('');
        $('#jml').val('');
        $('#uom').val('');
        $('#surat_jalan').empty();
      }

      $(document).on('click', '.ongkirbox_close', function(e){
        ongkirbox_close(); ongkirbox_reset();
      });

      $(document).on('click', '.ongkirs', function(e){
        e.preventDefault();
        show_loading_message();
        var id = $(this).data('id');
        var request = $.ajax({
          url:  '<?php echo base_url('index.php/action/all/get_ongkir'); ?>',
          type: 'POST',
          data: {
            <?php echo $this->security->get_csrf_token_name(); ?> : '<?php echo $this->security->get_csrf_hash(); ?>',
            id : id
          }
        });
        request.done(function(output){
          var obj = JSON.parse(output);
          if(obj.result == 'success'){
            hide_loading_message(); ongkirbox_show();
            $('#surat_jalan').append( '<option selected disabled>Pilih</option>');
            for(var j = 0; j < obj.data.length; j++) $('#surat_jalan').append(
              '<option value="'+obj.data[j].id+'" data-cost="'+obj.data[j].cost+'" data-ekspedisi="'+obj.data[j].ekspedisi+'" data-uom="'+obj.data[j].uom+'" data-jml="'+obj.data[j].jml+'">' +obj.data[j].detail+ '</option>');
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

      $(document).on('change', '#surat_jalan', function(e){
        e.preventDefault();
        $('#ongkos_kirim').val($(this).find(':selected').data('cost'));
        $('#ekspedisi').val($(this).find(':selected').data('ekspedisi'));
        $('#uom').val($(this).find(':selected').data('uom'));
        $('#jml').val($(this).find(':selected').data('jml'));
      });

      $(document).on('submit', '#form_inputOngkir.add', function(e){
        e.preventDefault();
        if($('#form_inputOngkir').valid() == true){
          ongkirbox_close(); show_loading_message();
          var form_data = $('#form_inputOngkir').serialize();
          var request   = $.ajax({
            url: '<?php echo base_url('index.php/action/all/edit_ongkir'); ?>',
            data: form_data,
            cache: false,
            type: 'POST'
          });
          request.done(function(output){
            var obj = JSON.parse(output);
            if(obj.result == 'success'){
                tabel.ajax.reload(function(){
                  ongkirbox_reset(); hide_loading_message();
                  show_message(obj.message, 'success');
                  reset();
                }, true);
            } else {
                hide_loading_message();
                show_message(obj.message, 'error');
            }
          });
          request.fail(function(jqXHR, textStatus){
            hide_loading_message();
            show_message(obj.message+ ': '+textStatus, 'error');
          });
        }
      });

      $(document).on('click', '.HapusItem', function(e)
      {
        e.preventDefault();
        var info = $(this).data('name');
        var id = $(this).data('id').split('-');
        if(confirm("Anda yakin ingin menghapus '"+info+"'?")){
          show_loading_message();
          var request = $.ajax({
            url:  '<?php echo base_url('index.php/action/all/delete_so'); ?>',
            type: 'POST',
            data: {
              <?php echo $this->security->get_csrf_token_name(); ?> : '<?php echo $this->security->get_csrf_hash(); ?>',
              id : id[0],
              item: id[1]
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