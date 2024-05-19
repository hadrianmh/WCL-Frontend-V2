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
                <th>Tenggat Waktu</th>
                <th style="min-width:150px">Customer</th>
                <th>No PO</th>
                <th>No SO</th>
                <th style="min-width:150px">Nama Barang</th>
                <th style="min-width:150px">Ukuran</th>
                <th>Kor</th>
                <th>Line</th>
                <th>Roll</th>
                <th>Bahan</th>
                <th>Porporasi</th>
                <th>Qty</th>
                <th>Unit</th>
                <th>Isi ROLL/PCS</th>
                <th style="min-width:150px">Catatan</th>
                <th>Uk Bahan Baku</th>
                <th>Qty Bahan Baku</th>
                <th style="min-width:150px">Sources</th>
                <th style="min-width:150px">Order Status</th>
                <th style="min-width:150px">Diinput</th>
                <th style="min-width:150px">Option</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
      
          <!-- The Modal -->
          <div id="myModal" class="modal">

            <!-- Modal content -->
            <div class="modal-content">
              <span class="close lightbox_close">&times;</span>
              
              <h2 class="FormTitle" style="text-align: center">INPUT WO</h2>
              <form class="form add" id="form_inputWO" data-id="" novalidate>

                <div class="form-group po_date" style="display: none">
                  <label for="po_date">Tgl PO:</label>
                  <input type="text" class="form-control" name="po_date" id="po_date" required readonly>
                </div>

                <div class="form-group customer" style="display: none">
                  <label for="customer">Customer:</label>
                  <input type="text" class="form-control" name="customer" id="customer" required readonly>
                </div>

                <div class="form-group no_po" style="display: none">
                  <label for="po_customer">No PO:</label>
                  <input type="text" class="form-control" name="po_customer" id="po_customer" readonly>
                </div>

                <div class="form-group no_spk" style="display: none">
                  <label for="no_spk">No SPK/SO:</label>
                  <input type="text" class="form-control" name="no_spk" id="no_spk" required readonly>
                </div>

                <div class="form-group spk_date" style="display: none">
                  <label for="spk_date">Tgl SPK:</label>
             <input class="form-control" name="spk_date" id="spk_date" value="<?php echo date('d/m/Y'); ?>" data-date-format="dd/mm/yyyy" required>
                </div>

                <div class="form-group order_status" style="display: none">
                  <label for="Order status">Order Status: <span class="required">*</span></label>
                  <select class="form-control" name="order_status" id="order_status" required>
                    <option selected disabled>Pilih status</option>
                    <option value="16">Input PO</option>
                    <option value="15">Proses Sample</option>
                    <option value="14">Reture</option> 
                    <option value="13">Proses Sliting</option> 
                    <option value="12">Proses ACC</option> 
                    <option value="11">Proses Toyobo</option> 
                    <option value="10">Proses Film</option>
                    <option value="9">Proses Bahan Baku</option>
                    <option value="8">Proses Cetak</option>
                    <option value="7">Antri Cetak</option>
                    <option value="6">Antri Sliting</option>
                    <option value="5">Pembuatan Pisau</option>
                    <option value="4">Cetak SPK</option>
                    <option value="3">Packing</option>
                    <option value="2">Delivery</option>
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

          <!-- The Modal -->
          <div id="myModal2" class="modal">

            <!-- Modal content -->
            <div class="modal-content">
              <span class="close lightbox_close">&times;</span>
              
              <h2 class="FormTitle" style="text-align: center">PRATINJAU PRINT</h2>
              <form class="form add" id="form_print" novalidate>

                <hr>
                <p style="font-weight: bold">DITERBITKAN</p>
                
                <div class="form-group">
                  <label for="tgl">Tanggal: <span class="required">*</span></label>
                  <input class="form-control" name="tgl" id="tgl" value="<?php echo date('d/m/Y'); ?>" data-date-format="dd/mm/yyyy" required>
                </div>

                <hr>
                <p style="font-weight: bold">RINCIAN PRODUKSI</p>
                <div class="form-group">
                  <label for="pcus">No PO: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="pcus" id="pcus" readonly>
                </div>
                <div class="form-group">
                  <label for="customer">Customer: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="custom" id="custom" required readonly>
                </div>

                <div class="form-group">
                  <label for="no_spk">No SO: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="nospk" id="nospk" required readonly>
                </div>
        
                <div class="form-group">
                  <label for="keterangan">Keterangan:</label>
                  <input type="text" class="form-control" name="keterangan" id="keterangan">
                </div>
        
                <div class="form-group">
                  <label for="size_label">Ukuran:<span class="required">*</span></label>
                  <input type="text" class="form-control" name="size_label" id="size_label" readonly>
                </div>
        
                <div class="form-group">
                  <label for="size_baku">Uk. Bahan baku:</label>
                  <input type="text" class="form-control" name="size_baku" id="size_baku" readonly>
                </div>
        
                <div class="form-group">
                  <label for="bahan">Bahan: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="bahan" id="bahan" readonly>
                </div>

                <div class="form-group porporasi">
                  <label>Porporasi: <span class="required">*</span></label>
                  <input type="text" class="form-control" id="porporasi" name="porporasi" readonly>
                </div>
        
                <div class="form-group">
                  <label for="gulungan">Gulungan: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="gulungan" id="gulungan" readonly>
                </div>
        
                <div class="form-group">
                  <label for="kor">Kor: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="kor" id="kor" readonly>
                </div>
        
                <div class="form-group">
                  <label for="lins">Line: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="lins" id="lins" readonly>
                </div>
        
                <div class="form-group">
                  <label for="qty_baku">QTY Bahan baku:</label>
                  <input type="text" class="form-control" name="qty_baku" id="qty_baku" readonly>
                </div>
        
                <div class="form-group">
                  <label for="qty_produksi">QTY Produksi:</label>
                  <input type="text" class="form-control" name="qty_produksi" id="qty_produksi" readonly>
                </div>
        
                <div class="form-group">
                  <label for="isi">Isi 1 Roll: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="isi" id="isi" required readonly>
                </div>

                <hr>
                <p style="font-weight: bold">TANDA TANGAN</p>

                <div class="form-group">
                  <label for="ttd">Nama: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="ttd" id="ttd" value="<?php echo $this->session->userdata('name') ?>" readonly>
                </div>

                <div class="button_container" style="text-align: center">
                  <button type="submit" class="print">Cetak</button>
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
                  <table style="width:100%" border="0">
                    <caption class="text-center">SURAT PERINTAH KERJA CV WISNU CAHAYA LABEL</caption>
                    <tr>
                      <td colspan="3"></td>
                      <td>Tanggal : <span class="spkdate"></span></td>
                    </tr>
                    <tr>
                      <td>Nama</td>
                      <td>: <span class="cus"></span></td>
                      <td></td>
                      <td>No PO: <span class="pcus"></span></td>
                    </tr>
                    <tr>
                      <td colspan="4">Mohon diproduksi dengan spesifikasi sbb:</td>
                    </tr>
                    <tr>
                      <td>No SO</td>
                      <td colspan="2">: <span class="spk"><span></td>
                      <td></td>
                    </tr>
                    <tr>
                      <td>Keterangan</td>
                      <td colspan="2">: <span class="ann"><span></td>
                      <td></td>
                    </tr>
                    <tr>
                      <td>Ukuran Label</td>
                      <td colspan="2">: <span class="slabel"><span></td>
                      <td></td>
                    </tr>
                    <tr>
                      <td>Uk. Bahan baku</td>
                      <td colspan="2">: <span class="sbaku"><span></td>
                      <td></td>
                    </tr>
                    <tr>
                      <td>Bahan</td>
                      <td colspan="2">: <span class="bah"><span></td>
                      <td></td>
                    </tr>
                    <tr>
                      <td>Gulungan</td>
                      <td colspan="2">: <span class="gul"><span></td>
                      <td></td>
                    </tr>
                    <tr>
                      <td>Kor</td>
                      <td colspan="2">: <span class="kore"><span></td>
                      <td></td>
                    </tr>
                    <tr>
                      <td>Line</td>
                      <td colspan="2">: <span class="lie"><span></td>
                      <td></td>
                    </tr>
                    <tr>
                      <td>Porporasi</td>
                      <td colspan="2">: <span class="por"><span></td>
                      <td></td>
                    </tr>
                    <tr>
                      <td>QTY Bahan baku</td>
                      <td colspan="2">: <span class="qbaku"><span></td>
                      <td></td>
                    </tr>
                    <tr>
                      <td>QTY Produksi</td>
                      <td colspan="2">: <span class="qproduk"><span></td>
                      <td></td>
                    </tr>
                    <tr>
                      <td class='isi'>Isi 1 Roll</td>
                      <td colspan="2">: <span class="is"><span></td>
                      <td></td>
                    </tr>
                    <tr>
                      <td>Dibuat oleh</td>
                      <td colspan="2">: <span class="td"><span></td>
                      <td></td>
                    </tr>
                  </table>

                  <table style="width:97%" border="1">
                    <tr class="text-center">
                        <td colspan="5" class="text-center">Ket. Proses Produksi</td>
                      </tr>
                    <tr>
                    <tr>
                      <td class="text-center">Pembuat</td>
                      <td class="text-center">Penerima</td>
                      <td class="text-center">Bag. Produksi</td>
                      <td class="text-center">Slitting</td>
                      <td class="text-center">Total Produksi</td>
                    </tr>
                    <tr height="75px">
                      <td>&nbsp;</td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                    </tr>
                    <tr style="border:0px">
                      <td>&nbsp;<strong>Putih</strong> = ADM Produksi </td>
                      <td>&nbsp;<strong>Pink</strong> = Packing</td>
                      <td>&nbsp;<strong>Kuning</strong> = SPU</td>
                      <td>&nbsp;<strong>Hijau</strong> = Kor / Inventor</td>
                      <td>&nbsp;<strong>Biru</strong> = CS</td>
                    </tr>
                  </table>
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
  <script src="<?php echo base_url('assets/js/bootstrap/bootstrap-datepicker.min.js'); ?>"></script>
  <script src="<?php echo base_url('assets/js/jQuery.print.js'); ?>"></script>
  <script>
    $(document).ready(function()
    {
      $("#tgl").datepicker();
      $("#spk_date").datepicker();
      $('#form_inputWO').validate();
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

      function show_all_elemen(){
        $(".po_date").hide();
        $(".customer").hide();
        $(".no_po").hide();
        $(".no_spk").hide();
        $(".spk_date").hide();
        $(".size").show();
        $(".qore").show();
        $(".line").show();
        $(".roll").show();
        $(".material").show();
        $(".ingredient").show();
        $(".qty").show();
        $(".volume").show();
        $(".annotation").show();
      }

      // Show lightbox
      function show_lightbox(){
        $('#myModal').show();
      }
      // Lightbox close button
      $(document).on('click', '.lightbox_close', function(){
          hide_lightbox();
          show_all_elemen();
      });
      // Hide lightbox
      function hide_lightbox(){
        $('#myModal').hide();
        $('#myModal2').hide();
        $('#PrintModal').hide();
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

      /////////////////////////////////////////////////////////////////
      // Sort datatable from current month
      /////////////////////////////////////////////////////////////////

      var req = $.ajax({
        url: '<?php echo base_url('index.php/action/all/sort_month'); ?>',
        type: 'POST',
        data: {
          <?php echo $this->security->get_csrf_token_name(); ?>   : '<?php echo $this->security->get_csrf_hash(); ?>',
          table   : 'workorder_customer',
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

      var tabel = $('#tablenya').DataTable({
        processing: true,
        serverSide: true,
        scrollX   : true,
        ordering  : false,
        ajax: {
          url: "<?php echo base_url('index.php/action/all/workorder'); ?>",
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
          text: 'Export to Excel',
          filename : 'Workorder_'+getCookie("selectMonth"),
          title: 'Work Order '+getCookie("selectMonth"),
          exportOptions: {
            columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20 ]
          }
        }],
        "lengthMenu": [[10, -1], [10, 'All']],
        iDisplayLength: 10
      });

      $(document).on('click', '.UbahCustomer', function(e){
        e.preventDefault();
        show_loading_message();
        var idx   = $(this).data('id');
        var ex    = idx.split('-');
        var request = $.ajax({
          url:  '<?php echo base_url('index.php/action/all/get_wo'); ?>',
          type: 'POST',
          data: {
            <?php echo $this->security->get_csrf_token_name(); ?> : '<?php echo $this->security->get_csrf_hash(); ?>',
            id : ex[0],
            item_to: ex[1]
          }
        });
        request.done(function(output){
          var obj = JSON.parse(output);
          if(obj.result == 'success'){
            hide_loading_message();
            show_lightbox();
            $('h2.FormTitle').text('UBAH SALES ORDER');
            $('#form_inputWO').attr('class', 'form edit_customer');
            $('#form_inputWO').attr('data-id', idx);
            $('#form_inputWO .field_container label.error').hide();
            $(".po_date").show();
            $(".customer").show();
            $(".no_po").show();
            $(".no_spk").show();
            $(".spk_date").show();
            $(".order_status").show();
            $("#po_date").val(obj.data[0].po_date);
            $("#customer").val(obj.data[0].customer);
            $("#po_customer").val(obj.data[0].po_customer);
            $("#no_spk").val(obj.data[0].no_spk);
            $("#spk_date").val(obj.data[0].spk_date);
            $("#order_status").val(obj.data[0].order_status);

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

      $(document).on('submit', '#form_inputWO.edit_customer', function(e){
        e.preventDefault();
        if($('#form_inputWO').valid() == true){
          hide_lightbox();
          show_loading_message();
          var id        = $('#form_inputWO').attr('data-id').split("-");
          var form_data = $('#form_inputWO').serialize();
          var request   = $.ajax({
            url: '<?php echo base_url('index.php/action/all/edit_wo'); ?>',
            data: form_data + '&' + $.param({ 'id' : id[0], 'item_to' : id[1] }),
            cache: false,
            type: 'POST'
          });
          request.done(function(output){
            var obj = JSON.parse(output);
            if(obj.result == 'success'){
              tabel.ajax.reload(function(){
                hide_loading_message();
                show_message(obj.message, 'success');
                show_all_elemen();
              }, true);
            } else {
                hide_loading_message();
                show_message(obj.message, 'error');
                show_all_elemen();
            }
          });
          request.fail(function(jqXHR, textStatus){
            hide_loading_message();
            show_message('Gagal memasukan data: '+textStatus, 'error');
            show_all_elemen();
          });
        }
      });

      $(document).on('click', '.printWO', function(e){
        e.preventDefault();
        show_loading_message();
        var idx   = $(this).data('id');
        var ex    = idx.split('-');
        var request = $.ajax({
          url:  '<?php echo base_url('index.php/action/all/get_print_wo'); ?>',
          type: 'POST',
          data: {
            <?php echo $this->security->get_csrf_token_name(); ?> : '<?php echo $this->security->get_csrf_hash(); ?>',
            id : ex[0],
            item_to: ex[1]
          }
        });
        request.done(function(output){
          var obj = JSON.parse(output);
          if(obj.result == 'success'){
            hide_loading_message();
            if(obj.data[0].unit == 'PCS'){
              var total_unit = obj.data[0].total+" ROLL = "+obj.data[0].qty_produksi+" PCS";
              $('#isi').val(obj.data[0].isi+" PCS");

            } else if(obj.data[0].unit == 'ROLL'){
              var total_unit = obj.data[0].qty_produksi+" ROLL = "+obj.data[0].total+" PCS";
              $('#isi').val(obj.data[0].isi+" PCS");

            }
            $('h2.FormTitle').text('PRATINJAU PRINT');
            $('#form_print .field_container label.error').hide();
            $('#form_print').attr('data-id', idx);
            $('#form_print').attr('class', 'form printProses');
            $('#tgl').val(obj.data[0].spk_date);
            $('#pcus').val(obj.data[0].po_customer);
            $('#custom').val(obj.data[0].customer);
            $('#nospk').val(obj.data[0].no_spk);
            $('#keterangan').val(obj.data[0].annotation);
            $('#size_label').val(obj.data[0].size_label);
            $('#bahan').val(obj.data[0].bahan);
            $('#gulungan').val(obj.data[0].gulungan);
            $('#kor').val(obj.data[0].kor);
            $('#lins').val(obj.data[0].line);
            $('#size_baku').val(obj.data[0].size_baku);
            $('#qty_baku').val(obj.data[0].qty_baku);
            $('#porporasi').val(obj.data[0].porporasi);
            $('#qty_produksi').val(total_unit);
            $('#myModal2').show();
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

      $(document).on('submit', '#form_print', function(e){
        e.preventDefault();
        if($('#form_print').valid() == true){
          hide_lightbox();
          show_loading_message();
          var form_data = $('#form_print').serialize();
          var request   = $.ajax({
            url: '<?php echo base_url('index.php/action/all/print_wo'); ?>',
            data: form_data,
            cache: false,
            type: 'POST'
          });
          request.done(function(output){
            var obj = JSON.parse(output);
            if(obj.result == 'success'){
              hide_loading_message();
              $('#PrintModal').show();
              $(".spkdate").text(obj.data[0].spk_date);
              $(".pcus").text(obj.data[0].po_customer);
              $(".cus").text(obj.data[0].customer);
              $(".spk").text(obj.data[0].no_spk);
              $(".ann").text(obj.data[0].annotation);
              $(".slabel").text(obj.data[0].size_label);
              $(".sbaku").text(obj.data[0].size_baku);
              $(".bah").text(obj.data[0].bahan);
              $(".gul").text(obj.data[0].gulungan);
              $(".kore").text(obj.data[0].kor);
              $(".lie").text(obj.data[0].line);
              $(".por").text(obj.data[0].porporasi);
              $(".qbaku").text(obj.data[0].qty_baku);
              $(".qproduk").text(obj.data[0].qty_produksi);
              $(".is").text(obj.data[0].isi);
              $(".td").text(obj.data[0].ttd);

              $('.printnow').print({
                stylesheet : '<?php echo base_url('assets/css/bootstrap/bootstrap.min.css'); ?>',
                globalStyles : false,
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




    });
  </script>