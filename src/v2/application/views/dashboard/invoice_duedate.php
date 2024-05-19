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
                  <th>Diterbitkan</th>
                  <th>Jatuh Tempo</th>
                  <th style="min-width:150px">Customer</th>
                  <th style="min-width:150px">No PO</th>
                  <th>No SO</th>
                  <th>Surat Jalan</th>
                  <th>No Faktur</th>
                  <th>Qty</th>
                  <th>Unit</th>
                  <th style="min-width:150px">Harga</th>
                  <th style="min-width:150px">Tagihan</th>
                  <th style="min-width:150px">Ppn</th>
                  <th style="min-width:150px">Total</th>
                  <th style="min-width:150px">Biaya Kirim</th>
                  <th>Ekspedisi</th>
                  <th>Uom</th>
                  <th>Jumlah</th>
                  <th>Dicetak</th>
                  <th style="min-width:150px">Diinput</th>
                  <th style="min-width:150px">Option</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
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
                  <th class="text-right" style="font-weight: bold">Total Amount:</th>
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
              </tr>
            </tfoot>
          </table>

          <!-- The Modal -->
          <div id="myModal" class="modal">

            <!-- Modal content -->
            <div class="modal-content">
              <span class="close lightbox_close">&times;</span>
              
              <h2 class="FormTitle" style="text-align: center">PRATINJAU PRINT</h2>
              <form class="form add" id="form_print" data-id="" novalidate>

                <hr class="baris">
                <p style="font-weight: bold" class="judul">DITERBITKAN</p>
                
                <div class="form-group tanggal">
                  <label for="tgl">Tanggal: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="tgl" id="tgl" required readonly>
                </div>

                <div class="form-group company">
                  <label for="company">Company: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="company" id="company" required readonly>
                </div>

                <div class="form-group address">
                  <label for="address">Address: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="address" id="address" required readonly>
                </div>

                <div class="form-group phone">
                  <label for="phone">Phone: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="phone" id="phone" required readonly>
                </div>

                <hr class="baris">
                <p style="font-weight: bold" class="judul">RINCIAN TAGIHAN</p>
                <div class="form-group no_faktur">
                  <label for="no_faktur">No Faktur: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="no_faktur" id="no_faktur" required readonly>
                </div>

                <div class="form-group customers">
                  <label for="customer">Customer: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="customer" id="customer" required readonly>
                </div>

                <div class="form-group billto">
                  <label for="billto">Bill Address: <span class="required">*</span></label>
                  <textarea class="form-control" name="billto" id="billto" required readonly></textarea>
                </div>

                <div class="form-group tagihan">
                  <label for="tagihan">Tagihan: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="tagihan" id="tagihan" required readonly>
                  <input type="hidden" name="bill" id="bill">
                </div>

                <div class="form-group status_ppn" style="display: none">
                  <label for="status_ppn">PPN: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="status_ppn" id="status_ppn" required readonly>
                </div>

                <div class="form-group s_cost">
                  <label for="s_cost">Biaya kirim: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="s_cost" id="s_cost" required readonly>
                  <input type="hidden" name="biaya_kirim" id="biaya_kirim">
                </div>

                <div class="form-group customers">
                  <label for="no_po">No PO: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="no_po" id="no_po" required readonly>
                </div>

                <div class="form-group ship_name">
                  <label for="ship_name">Ship Name: <span class="required">*</span></label>
                  <textarea class="form-control" name="ship_name" id="ship_name" required readonly></textarea>
                </div>

                <div class="form-group shipto">
                  <label for="shipto">Ship Address: <span class="required">*</span></label>
                  <textarea class="form-control" name="shipto" id="shipto" required readonly></textarea>
                </div>

                <div class="form-group telp">
                  <label for="telp">Telp: <span class="required">*</span></label>
                  <input type="number" min="0" class="form-control" name="telp" id="telp">
                </div>
                
                <div class="datanyanih"></div>

                <hr class="baris">
                <p style="font-weight: bold" class="judul">RINCIAN PEMBAYARAN</p>

                <div class="form-group pilihBANK">
                  <label>Bank: <span class="required">*</span></label>
                  <select class="form-control" id="pilihBANK" name="pilihBANK" required></select>
                </div>
                
                <hr class="baris">
                <p style="font-weight: bold" class="judul">TANDA TANGAN</p>

                <div class="form-group ttds">
                  <label for="ttd">Nama: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="ttd" id="ttd" value="<?php echo $_SESSION['name'];?>" required readonly>
                </div>

                <div class="button_container" style="text-align: center">
                  <button type="submit" class="print">Cetak</button>
                  <input type="button" class="lightbox_close" value="Batal">
                  <input type="hidden" id="id_fk" value="">
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
                <div class="row margin-bottom-lg">
                  <div class="col-xs-12">
                    <div class="row delivery-orders-header"></div>
                    <br>
                    <div class="row delivery-orders-title" style="font-size: 12px">
                      <div class="col-md-9 col-xs-9">
                        <p><strong>Bill to : </strong><span class="bill_nama"></span></p>
                        <p class="bill_alamat"></p>
                        <p><strong>Ship to : </strong><span class="ship_nama"></span></p>
                        <p class="ship_alamat"></p>
                      </div>
                      <div class="col-md-3 col-xs-3">
                        <p>Dated : <span class="invoice_date"></span></p>
                        <p>PO No : <span class="po_customer"></span></p>
                        <p>Payment Term: <span class="payment_term">30 DAYS</span></p>
                        <p>Payment Due : <span class="payment_due"></span></p>
                      </div>
                    </div>
                  </div>
                </div>
              
                <div class="row" style="font-size: 12px;">
                  <div class="col-md-12">
                  <table class="table table-bordered">
                    <thead class="thead">
                      <tr>
                        <td class="text-center">No</td>
                        <td class="text-center" style="width:250px">Item</td>
                        <td class="text-center">No SO</td>
                        <td class="text-center">Qty</td>
                        <td class="text-center">Unit</td>
                        <td class="text-center">Unit Price</td>
                        <td class="text-center">Amount</td>
                      </tr>
                    </thead>
                    <tbody class="tbody"></tbody>
                  </table>
                  </div>
                </div>

                <div class="row" style="font-size: 12px;">
                  <div class="col-md-8 col-xs-8">
                    <p><strong>Delivery Order No :</strong></p>
                    <p class="suratjalan"></p>
                    <p><strong>Bank Account No :</strong></p>
                    <p>A/C : <span class="rekening"></span></p>
                    <p>A/N : <span class="atasnama"></span></p>
                    <p>Bank : <span class="namabank"></span></p>
                  </div>
                  <div class="col-md-2 col-xs-2">
                    <p><strong>Sub Total : </strong></p>
                    <p><strong>VAT 10% : </strong></p>
                    <p><strong>Total : </strong></p>
                    <hr class="line_cost" style="border: 1px solid #666; display: none">
                    <p class="label_cost" style="display: none;margin-bottom: 0px"><strong>Shipping Costs : </strong></p>
                  </div>
                  <div class="col-md-2 col-xs-2 text-right">
                    <p class="subtotal"></p>
                    <p class="vat"></p>
                    <p class="jumlah"></p>
                    <hr class="line_cost" style="border: 1px solid #666; display: none">
                    <p class="cost" style="display: none;"></p>
                  </div>
                </div>

                <div class="row" style="font-size: 12px;">
                  <div class="col-xs-12 col-md-12">
                    <div class="text-right">
                      <p>Issued by,</p>
                      <p class="ttd_person"></p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Modal Invoice -->
          <div id="InvoiceModal" class="modal">

            <!-- Modal content -->
            <div class="modal-content">
              <span class="close lightbox_close">&times;</span>
              
              <h2 class="FormTitle" style="text-align: center">INPUT INVOICE</h2>
              <form class="form lunas" id="form_inputINV" data-id="" novalidate>

                <div class="form-group date">
                  <label for="date">Tanggal Lunas: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="date" id="date" value="<?php echo date('d/m/Y'); ?>" data-date-format="dd/mm/yyyy" required>
                </div>

                <div class="form-group ket">
                  <label for="keterangan">Keterangan: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="ket" id="ket" placeholder="LUNAS" required>
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
  <script src="<?php echo base_url('assets/js/bootstrap/bootstrap-datepicker.min.js'); ?>"></script>
  <script src="<?php echo base_url('assets/js/jQuery.print.js'); ?>"></script>
  <script>
    $(document).ready(function()
    {
      $("#date").datepicker();
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
        $('#PrintModal').hide();
      }
      // Lightbox close button
      $(document).on('click', '.lightbox_close', function(){
        hide_lightbox(); hide_invoice();
        $('.looping_barang').empty();
        $('.suratjalan').empty();
        $('.tbody').empty();
        $('.delivery-orders-header').empty();
        $('#date').val('');
        $('#ket').val('');
      });

      /////////////////////////////////////////////////////////////////
      // Get bank list
      /////////////////////////////////////////////////////////////////

      var reqs = $.ajax({
        url: '<?php echo base_url('index.php/action/all/banklist'); ?>',
        type: 'POST',
        data: {
          <?php echo $this->security->get_csrf_token_name(); ?>   : '<?php echo $this->security->get_csrf_hash(); ?>',
        }
      });

      reqs.done(function(output){
        if(output.result == 'success')
        {
          $('#pilihBANK').append('<option value="" disabled selected>Pilih Bank :</option>');

          for(var i = 0; i<output.data.length; i++) $('#pilihBANK').append("<option value='"+output.data[i].value+"'>Tahun: "+output.data[i].text+"</option>");

        } else {
          show_message('Gagal memuat daftar bank', 'error');
        }
      });

      var tabel = $('#tablenya').DataTable({
        processing: true,
        serverSide: true,
        scrollX   : true,
        ordering  : false,
        ajax: {
          url: "<?php echo base_url('index.php/action/all/invoice_duedate'); ?>",
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
          filename : 'INVOICE-Duedate',
          title: 'Invoice Duedate',
          exportOptions: {
            columns: [ 0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18 ]
          }
        }],
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
          
          Bills = api.column( 10, { page: 'current'} ).data().reduce( function (a, b) {
            return intVal(a) + intVal(b);
          }, 0 );

          Ppns = api.column( 11, { page: 'current'} ).data().reduce( function (a, b) {
            return intVal(a) + intVal(b);
          }, 0 );

          Totals = Bills + Ppns;
          
          Ship_cost = api.column( 14, { page: 'current'} ).data().reduce( function (a, b) {
            return intVal(a) + intVal(b);
          }, 0 );

          $( api.column( 10 ).footer() ).html(convertToRupiah(Bills));
          $( api.column( 11 ).footer() ).html(convertToRupiah(Ppns));
          $( api.column( 12 ).footer() ).html(convertToRupiah(Totals));
          $( api.column( 13 ).footer() ).html(convertToRupiah(Ship_cost));
        }
      });

      $(document).on('click', '.PrintView', function(e){
        e.preventDefault();
        show_loading_message();
        var id      = $(this).data('id');
        var request = $.ajax({
          url:  '<?php echo base_url('index.php/action/all/get_print_invoice_duedate'); ?>',
          type: 'POST',
          data: {
            <?php echo $this->security->get_csrf_token_name(); ?> : '<?php echo $this->security->get_csrf_hash(); ?>',
            id : id,
          }
        });
        request.done(function(output){
          var obj = JSON.parse(output);
          if(obj.result == 'success'){
            hide_loading_message();
            show_lightbox();
            $('H2.FormTitle').text('PRATINJAU PRINT');
            $('#form_print .field_container label.error').hide();
            $('#form_print').attr('data-id', id);
            $('#form_print').attr('class', 'form printProses');
            $('#no_faktur').val(obj.data[0].no_invoice);
            $('#company').val(obj.data[0].company);
            $('#address').val(obj.data[0].address);
            $('#phone').val(obj.data[0].phone);
            $('#tagihan').val(convertToRupiah(obj.data[0].tagihan));
            $('#s_cost').val(convertToRupiah(obj.data[0].biaya_kirim));
            $('#bill').val(obj.data[0].tagihan);
            $('#biaya_kirim').val(obj.data[0].biaya_kirim);
            $('#customer').val(obj.data[0].customer);
            $('#no_po').val(obj.data[0].no_po);
            $('#billto').val(obj.data[0].billto);
            $('#shipto').val(obj.data[0].shipto);
            $('#ship_name').val(obj.data[0].ship_name);
            $('#telp').val(obj.data[0].telp);
            $('#status_ppn').val(obj.data[0].ppn);
            $('#id_fk').val(obj.data[0].id_fk);
            $('#tgl').val(obj.data[0].invoice_date);
            for(var i = 0; i<obj.data[0].item.length; i++){
              $('.datanyanih').append(
                '<div class="looping_barang"><hr><div class="form-group item"><label for="no_sj">Surat Jalan: <span class="required">*</span></label><input type="text" class="form-control" name="data[no_sj][]" id="no_sj" value="'+obj.data[0].no_sj[i]+'" required readonly></div><div class="form-group item"><label for="no_so">No SO: <span class="required">*</span></label><input type="text" class="form-control" name="data[no_so][]" id="no_so" value="'+obj.data[0].no_so[i]+'" required readonly></div><div class="form-group item"><label for="item">Nama Barang: <span class="required">*</span></label><input type="text" class="form-control" name="data[item][]" id="item" value="'+obj.data[0].item[i]+'" required readonly></div><div class="form-group qty"><label for="qty">Qty: <span class="required">*</span></label><input type="text" class="form-control" name="data[qty][]" id="qty" value="'+obj.data[0].send_qty[i]+'" required readonly></div><div class="form-group unit"><label for="unit">Satuan: <span class="required">*</span></label><input type="text" class="form-control" name="data[unit][]" id="unit" value="'+obj.data[0].unit[i]+'" required readonly></div><div class="form-group price" style="display:none"><label for="price">Price: <span class="required">*</span></label><input type="text" class="form-control" name="data[price][]" id="price" value="'+obj.data[0].price[i]+'" required readonly></div></div>'
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

      $(document).on('submit', '#form_print', function(e){
        e.preventDefault();
        if($('#form_print').valid() == true){
          hide_lightbox();
          show_loading_message();
          var id = $('#form_print').attr('data-id');
          var form_data = $('#form_print').serialize();
          var request   = $.ajax({
            url: '<?php echo base_url('index.php/action/all/print_invoice_duedate'); ?>',
            data: form_data + '&' + $.param({ 'id' : id }) ,
            cache: false,
            type: 'POST'
          });
          request.done(function(output){
            var obj = JSON.parse(output);
            if(obj.result == 'success'){
              tabel.ajax.reload(function()
              {
                hide_loading_message();
                var dataBANK = obj.data[0].bank.split("-");
                var loop = obj.data.length;
                $('#PrintModal').show();
                if(!!obj.data[0].logo){
                  $('.delivery-orders-header').append(
                    '<div class="col-md-2 col-xs-2"><img src="'+obj.data[0].logo+'" width="100px" height="50px" style="margin-top: 20px"></div><div class="col-md-7 col-xs-7"><h4 class="perusahaan" style="letter-spacing:2px;margin-bottom: 0px"><strong></strong></h4><p class="alamat" style="font-size:12px;letter-spacing:2px;margin-bottom:0px"></p><p style="font-size:12px;letter-spacing:2px;margin-bottom:0px"><span class="telpon"></span><span class="email"></span></p></div><div class="col-md-2 col-xs-2"><h4 class="text-right" style="letter-spacing:2px;margin-bottom:0px"><strong>INVOICE</strong></h4><h5 class="no-invoice text-right"></h5></div>'
                  );

                } else {
                  $('.delivery-orders-header').append(
                    '<div class="col-md-6 col-xs-6"><h4 class="perusahaan" style="letter-spacing:2px;margin-bottom: 0px"><strong></strong></h4><p class="alamat" style="font-size:12px;letter-spacing:2px;margin-bottom: 0px"></p><p style="font-size:12px;letter-spacing:2px;margin-bottom: 0px"><span class="telpon"></span><span class="email"></span></p></div><div class="col-md-6 col-xs-6"><h4 class="text-right" style="letter-spacing:2px;margin-bottom: 0px">INVOICE</h4><h5 class="no-invoice text-right"></h5></div>'
                  );
                }
                $('.perusahaan strong').text(obj.data[0].company);
                $('.alamat').text(obj.data[0].address);
                $('.telpon').text('Telp : '+obj.data[0].phone);
                $('.no-invoice').text('No. '+obj.data[0].no_invoice);
                $('.email').text('. '+obj.data[0].email);
                $('.bill_nama').text(obj.data[0].customer);
                $('.bill_alamat').text(obj.data[0].billto);
                $('.ship_nama').text(obj.data[0].ship_name);
                $('.ship_alamat').text(obj.data[0].shipto);
                $('.invoice_date').text(obj.data[0].tgl);
                $('.po_customer').text(obj.data[0].no_po);
                $('.payment_due').text(obj.data[0].tenggat);
                $('.rekening').text(obj.data[0].rek);
                $('.atasnama').text(obj.data[0].an);
                $('.namabank').text(obj.data[0].bank);
                $('.subtotal').text(convertToRupiah(obj.data[loop- 1].subtotal));
                $('.vat').text(convertToRupiah(obj.data[loop- 1].ppn));
                $('.jumlah').text(convertToRupiah(obj.data[0].total));
                $('.ttd_person').text(obj.data[0].ttd);

                if(parseInt(obj.data[0].ongkoskir) > 0 ){
                  $('.line_cost').show();
                  $('.label_cost').show();
                  $('.cost').show();
                  $('.cost').text(convertToRupiah(obj.data[0].ongkoskir));
                } else {
                  $('.line_cost').hide();
                  $('.label_cost').hide();
                  $('.cost').hide();
                }

                for(var x = 0; x < loop; x++)
                {
                  if(x === (loop - 1)){ break; }
                  if(x === (loop - 2)){
                    $('.suratjalan').append(obj.data[x].no_sj);
                  } else {
                    if(!!obj.data[x].no_sj.length)
                    {
                      $('.suratjalan').append(' - '+obj.data[x].no_sj);
                    }
                  }
                  $('.tbody').append(
                    '<tr><td class="text-center">'+obj.data[x].no+'</td><td>'+obj.data[x].item+'</td><td class="text-center">'+obj.data[x].no_so+'</td><td class="text-center">'+obj.data[x].qty+'</td><td class="text-center">'+obj.data[x].unit+'</td><td class="text-center">'+convertToRupiah(parseFloat(obj.data[x].price))+'</td><td class="text-right">'+convertToRupiah(parseInt(obj.data[x].qty) * parseFloat(obj.data[x].price))+'</td></tr>'
                  );
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

              }, true);

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
      
      /////////////////////////////////////////
      // Complete invoice
      ////////////////////////////////////////

      function show_invoice(){
        $('#InvoiceModal').show();
      }

      function hide_invoice(){
        $('#InvoiceModal').hide();
      }

      $(document).on('click', '.complete', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        var name = $(this).data('name');
        if(confirm("Anda yakin ingin memproses nomor faktur "+name+" ?")){
          $('#form_inputINV').attr('data-id', id);
          show_invoice();
        }
      });

      $(document).on('submit', '#form_inputINV', function(e){
        e.preventDefault();
        if($('#form_inputINV').valid() == true){
          hide_invoice();
          show_loading_message();
          var id = $('#form_inputINV').attr('data-id');
          var form_data = $('#form_inputINV').serialize();
          var request   = $.ajax({
            url: '<?php echo base_url('index.php/action/all/invoice_duedate_complete'); ?>',
            data: form_data + '&' + $.param({ 'id' : id }) ,
            cache: false,
            type: 'POST'
          });
          request.done(function(output){
            var obj = JSON.parse(output);
            if(obj.result == 'success'){
              tabel.ajax.reload(function(){
                $('#date').val(''); $('#ket').val('');
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
            show_message('Gagal memproses: '+textStatus, 'error');
          });
        }
      });

      $(document).on('click', '.HapusItem', function(e){
        e.preventDefault();
        var name = $(this).data('name');
        var id = $(this).data('id');
        if(confirm("Anda yakin ingin menghapus faktur "+name+" ?")){
          show_loading_message();
          var request = $.ajax({
            url:  '<?php echo base_url('index.php/action/all/delete_invoice_duedate'); ?>',
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
    });
  </script>