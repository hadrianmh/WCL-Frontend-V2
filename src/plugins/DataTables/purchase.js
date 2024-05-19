$(document).ready(function(){

	//////////////////////////
	//Default config
	/////////////////////////

	var idTablenya = $('#tablenya');
	var pathFile = '../auth/purchase.php';
	var Act = 'action';
	var sLug = 'purchase';
	var FormsLug = 'PURCHASE ORDER';
	var IDForm = "#form_inputPO";
	var sukses = 'success'; //Message alert
	var barisN = 1;
	var loopN = 1;
	var companyJSON = [];
	var po_typeJSON = [];
	var po_type_attribute = [];

	/////////////////////////////////////////////////////////////////
	// Set cookie as 'SelectMonth'
	/////////////////////////////////////////////////////////////////

	var mm = ("0" + (new Date().getMonth() + 1)).slice(-2);
	var yyyy = new Date().getFullYear();
	var arsip = yyyy+"/"+mm;

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
		url: pathFile+"?"+Act+"=sortdata_"+sLug,
		cache: false,
		dataType: 'json',
		contentType: 'application/json; charset=utf-8',
		type: 'get'
	});

	req.done(function(output){
		if(output.result == sukses){
			for(var i = 0; i<output.data.length; i++){
				$("#sortby").append("<option value='"+output.data[i].montly+"' "+(getCookie("selectMonth") == output.data[i].montly ? 'selected' : '')+" >"+output.data[i].montly+"</option>");
			}
			setCookie("selectMonth", arsip, 1);

		} else {
	        show_message('Gagal memuat data', 'error');
		}
	});

	$(document).on('change', '#sortby', function(){
		var valMonth = $(this).find(":selected").val();
		setCookie("selectMonth", valMonth, 1);
	});

	$(document).on('click', '#LoadData', function(){
		var valMonth = $('#sortby').find(":selected").val();
		setCookie("selectMonth", valMonth, 1);
		location.reload();
	});

	/////////////////////////////////////////////////////////////////
	// Get company list
	/////////////////////////////////////////////////////////////////

	var reqCompany = $.ajax({
		dataType: 'json',
		type: 'GET',
		cache: false,
		url: '../auth/json.php',
		data: {
			req: 'company'
		}
	});

	reqCompany.done(function(output){
		companyJSON = output;
	});

	reqCompany.fail(function(jqXHR, textStatus)
	{
		hide_loading_message();
		show_message('Gagal mengambil daftar company: '+textStatus, 'error');
	});

	/////////////////////////////////////////////////////////////////
	// Get po type list
	/////////////////////////////////////////////////////////////////

	var reqDetail = $.ajax({
		dataType: 'json',
		type: 'GET',
		cache: false,
		url: '../auth/purchase.php?action=po_type'
	});

	reqDetail.done(function(output){
		po_typeJSON = output.data;
	});

	reqDetail.fail(function(jqXHR, textStatus)
	{
		hide_loading_message();
		show_message('Gagal mengambil daftar item: '+textStatus, 'error');
	});

	//////////////////////////////////////////////////////////////
	// On page load: datatable
	//////////////////////////////////////////////////////////////

	var tablenya = idTablenya.DataTable({
		"scrollX": true,
	    "ajax": pathFile+"?"+Act+"=result_"+sLug+"&curMonth="+getCookie("selectMonth"),
	    'columnDefs': [
	    	{
	    		'targets': [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,18,19,20,21,22],
	            'className': 'dt-nowrap'
	        }
	    ],
	    "columns": [
	      { "data": "no" },
	      { "data": "po_date" },
	      { "data": "company" },
	      { "data": "vendor" },
	      { "data": "nopo" },
	      { "data": "po_type" },
	      { "data": "detail"},
	      { "data": "size"},
	      { "data": "price_1"},
	      { "data": "price_2"},
	      { "data": "qty"},
	      { "data": "unit"},
	      { "data": "merk"},
	      { "data": "type"},
	      { "data": "core"},
	      { "data": "gulungan"},
	      { "data": "bahan"},
	      { "data": "note"},
	      { "data": "subtotal"},
	      { "data": "tax"},
	      { "data": "total"},
	      { "data": "input"},
	      { "data": "functions","sClass": "functions" }
	    ],
	    "lengthMenu": [[10, -1], [10, "All"]],
	    iDisplayLength: 10,
	    dom: 'Bfrtip',
        buttons: [ 
        	'pageLength',
        	{
                text: 'Periode View',
                action: function ( e ) {
                	e.preventDefault();
                	$('H2.FormTitle').text('INPUT PERIODE');
                	$('#Form_periode').attr('class', 'form add');
                	$('#Form_periode').attr('data-id', '');
                    periode_show();
                }
            }
        ],
	    "oLanguage": {
	      "oPaginate": {
	        "sFirst":       "<<",
	        "sPrevious":    "Prev",
	        "sNext":        "Next",
	        "sLast":        ">>",
	      },
	      "sLengthMenu":    "Records per page: _MENU_",
	      "sInfo":          "Total of _TOTAL_ records (showing _START_ to _END_)",
	      "sInfoFiltered":  "(filtered from _MAX_ total records)"
	    }
	});

	//////////////////////////////////////////////////////////////
	// Create function print all with hidden table
	/////////////////////////////////////////////////////////////

	var tablePrint = $("#tablePrint").DataTable({
		"scrollX": false,
		"bPaginate": false,
		"searching": false,
		"info": false,
	    "ajax": pathFile+"?"+Act+"=resultAll_"+sLug+"&curMonth="+getCookie("selectMonth"),
	    "columns": [
	      { "data": "no" },
	      { "data": "po_date" },
	      { "data": "company" },
	      { "data": "vendor" },
	      { "data": "nopo" },
	      { "data": "po_type" },
	      { "data": "detail"},
	      { "data": "size"},
	      { "data": "price_1"},
	      { "data": "price_2"},
	      { "data": "qty"},
	      { "data": "unit"},
	      { "data": "merk"},
	      { "data": "type"},
	      { "data": "core"},
	      { "data": "gulungan"},
	      { "data": "bahan"},
	      { "data": "note"},
	      { "data": "subtotal"}, //18
	      { "data": "tax"},
	      { "data": "total"},
	      { "data": "input"},
	    ],
	    iDisplayLength: -1,
	    "footerCallback": function ( row, data, start, end, display ) {
        	var api = this.api(), data;
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };
            SubTotal = api.column( 18, { page: 'current'} ).data().reduce( function (a, b) {
            	return intVal(a) + intVal(b);
            }, 0 );

            Tax = api.column( 19, { page: 'current'} ).data().reduce( function (a, b) {
            	return intVal(a) + intVal(b);
            }, 0 );

            Totals = SubTotal + Tax;

            $( api.column( 18 ).footer() ).html(convertToRupiah(SubTotal));
            $( api.column( 19 ).footer() ).html(convertToRupiah(Tax));
            $( api.column( 20 ).footer() ).html(convertToRupiah(Totals));
        }
	    
	});

	var buttons = new $.fn.dataTable.Buttons(tablePrint, {
		buttons:[
        {
        	extend: 'excelHtml5',
        	messageTop: false,
        	footer: true,
        	text: 'Export to Excel',
        	filename : 'PurchaseOrder-'+getCookie("selectMonth"),
        	title: 'PURCHASE ORDER '+getCookie("selectMonth"),
        }
		]
	}).container().appendTo($('.dt-buttons'));

	//////////////////////////////////////////////////////////////
	// On page load: form validation
	/////////////////////////////////////////////////////////////

	var FormNYA = $(IDForm);
  	FormNYA.validate();

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
		$('#vendor').val('');
		$('#id_vendor').val('');
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
        $('#vendor').attr('readonly', false);
		$('#id_vendor').attr('readonly', false);
		$('#po_date').attr('readonly', false);
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
	    hide_lightbox();
	    reset();
	    clean();
	});
	// Escape keyboard key
	$(document).keyup(function(e){
	    if (e.keyCode == 27){
	    	hide_lightbox();
	    }
	});
	// Hide iPad keyboard
	function hide_ipad_keyboard(){
	    document.activeElement.blur();
	    $('input').blur();
	}

	function convertToRupiah(angka){
		var checked = angka.toString().split('.').join(',');
		var filter = 'Rp. ' + checked.replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1.");
		return filter;
	}

	/////////////////////////////////////////////////////////
	////////////// PERIODE FUNCTION
	/////////////////////////////////////////////////////////

	function periode_show(){
		$('#PeriodeResult').show();
	}

	function periode_hide(){
		$('#PeriodeResult').hide();
	}

	function periode_reset(){
		$('#dari').val('');
		$('#sampai').val('');
	}

	$(document).on('click', '.periode_close', function(){
		periode_hide();
		periode_reset();
	});

	var FormPeriode = $('#form_periode');
  	FormPeriode.validate();

	$(document).on('submit', '#form_periode.add', function(e){
    	e.preventDefault();
    	hide_ipad_keyboard();
      	periode_hide();
      	show_loading_message();
      	var form_data = $(FormPeriode).serialize();
      	var request   = $.ajax({
        	url:          pathFile+"?"+Act+"=periode_"+sLug,
        	cache:        false,
        	data:         form_data,
        	method: 	  'GET',
        	dataType: 'json'
      	});

      	request.done(function(output){
	    	if (output.result == sukses){
	    		tablenya.ajax.url(pathFile+"?"+Act+"=periode_"+sLug+"&"+form_data).load();
      			tablePrint.ajax.url(pathFile+"?"+Act+"=periode_"+sLug+"&"+form_data).load();
      			tablenya.draw();
      			tablePrint.draw();
        		hide_loading_message();
        		show_message("Berhasil memuat dimasukan.", 'success');
        		periode_reset();

	    	} else {
	      		hide_loading_message();
	      		show_message(output.message, 'error');
	    	}
	  	});

	  	request.fail(function(jqXHR, textStatus){
	    	hide_loading_message();
	    	show_message('Gagal memuat data: '+textStatus, 'error');
	  	});
  	});

	///////////////////
	// Add PO button
  	//////////////////

  	$(document).on('click', '#add_inputPO', function(e){
  		e.preventDefault();
		$('H2.FormTitle').text('INPUT '+FormsLug);
		$(IDForm).attr('class', 'form add');
		$(IDForm).attr('data-id', '');
		$('.ppns').show();
		$('.tambah_barang').hide();
		$('#company').empty();
	    $('#po_type').empty();
		show_lightbox();

		$('#company').append('<option selected disabled>Pilih Entitas</option>');
		for(var x = 0; x<companyJSON.length; x++)
		{
			$('#company').append('<option value="'+companyJSON[x].id+'">'+companyJSON[x].company+'</option>');
		}

		$('#po_type').append('<option value="" selected disabled>Pilih Tipe</option>');
		for(var z = 0; z < po_typeJSON.length; z++)	
		{
			$('#po_type').append('<option value="'+po_typeJSON[z].id+'">' +po_typeJSON[z].item+ '</option>');
		}

		////////////////////////////////////////
	  	// auto complete customer
	  	//////////////////////////////////////

	  	$.widget( "custom.catcomplete", $.ui.autocomplete, {
	  		_create: function()
	  		{
	  			this._super();
	  			this.widget().menu( "option", "items", "> :not(.ui-autocomplete-category)" );
	  		},

	  		_renderMenu: function( ul, items )
	  		{
	  			var that = this,
	  			currentCategory = "";
	  			$.each( items, function( index, item )
	  			{
	  				var li;
	  				if ( item.category != currentCategory )
	  				{
	  					ul.append( "<li class='ui-autocomplete-category'>" + item.category + "</li>" );
	  					currentCategory = item.category;
	  				}
	  				li = that._renderItemData( ul, item );
	  				if ( item.category )
	  				{
	  					li.attr( "aria-label", item.category + " : " + item.label );
	  				}
	  			});
	  		}
	  	});

	  	$('#vendor').catcomplete({
	  		minLength: 2,
	  		source: function(request, respone)
	  		{
	  			$.ajax({
	  				url: '../auth/json.php',
	  				dataType: 'json',
	  				type: 'GET',
	  				data: {
	  					req: 'vendor',
	  					keyword: request.term
	  				},
	  				success: function(data)
	  				{
	  					respone(data);
	  				}
	  			});
	  		},
	  		select: function(event, ui)
	  		{
	  			var vendor = ui.item.id_vendor;
	  			var id_po = ui.item.id_po;
	  			var meminta = $.ajax({
	  				url: '../auth/json.php',
	  				type: 'GET',
	  				dataType: 'json',
	  				cache: false,
	  				data: {
	  					req: 'po_item',
	  					vendor: vendor,
	  					id: id_po
	  				},

	  				contentType: 'application/json; charset=utf-8'
	  			});

	  			meminta.done(function(output)
	  			{
	  				reset();
	  				$('#id_vendor').val(output.value[0].id_vendor);
	  				if(parseInt(output.value[0].id_po) > 0)
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
	  								'<div class="looping_barang" id="looping-'+loopN+'"><hr class="looping-item"><p><button type="button" name="remove" data-id="'+loopN+'" class="btn btn-danger btn_remove">Hapus</button></p><div class="form-group detail-'+loopN+'" style="display: none"><label for="detail">Detail: <span class="required">*</span></label><input type="text" class="form-control" name="data[detail][]" id="detail" value="'+output.value[x].detail+'" required></div><div class="form-group size-'+loopN+'" style="display: none"><label for="Size">Size: <span class="required">*</span></label><input type="number" class="form-control sizeval_'+loopN+'" name="data[size][]" id="size" value="'+output.value[x].size+'" required></div><div class="form-group merk-'+loopN+'" style="display: none"><label for="merk">Merk: <span class="required">*</span></label><input type="text" class="form-control" name="data[merk][]" id="merk" value="'+output.value[x].merk+'" required></div><div class="form-group type-'+loopN+'" style="display: none"><label for="type">Type: <span class="required">*</span></label><input type="text" class="form-control" name="data[type][]" id="type" value="'+output.value[x].type+'" required></div><div class="form-group core-'+loopN+'" style="display: none"><label for="core">Core: <span class="required">*</span></label><input type="text" class="form-control" name="data[core][]" id="core" value="'+output.value[x].core+'" required></div><div class="form-group gulungan-'+loopN+'" style="display: none"><label for="gulungan">Gulungan: <span class="required">*</span></label><input type="text" class="form-control" name="data[gulungan][]" id="gulungan" value="'+output.value[x].gulungan+'" required></div><div class="form-group bahan-'+loopN+'" style="display: none"><label for="bahan">Bahan: <span class="required">*</span></label><input type="text" class="form-control" name="data[bahan][]" id="bahan" value="'+output.value[x].bahan+'" required></div><div class="form-group price_1"><label for="price_1">Price: <span class="required">*</span></label><input type="text" class="form-control price_1_looping-'+loopN+'" name="data[price_1][]" id="price_1" value="'+output.value[x].price_1+'" required></div><div class="form-group price_2-'+loopN+'" style="display: none"><label for="price_2">Price (Secondary): <span class="required">*</span> <em class="label label-success">Size x Price = Price (secondary)</em></label><div class="row"><div class="col-md-10"><input type="text" class="form-control price_2_looping-'+loopN+'" name="data[price_2][]" id="price_2" value="'+output.value[x].price_2+'" required></div><div class="col-md-2"><button type="button" class="btn btn-primary hitung_'+loopN+'">Hitung</button></div></div><div class="form-group qty"><label for="qty">Qty: <span class="required">*</span></label><input type="number" min="0" class="form-control" name="data[qty][]" id="qty" value="'+output.value[x].qty+'" required></div><div class="form-group unit-'+loopN+'" style="display: none"><label for="unit">Unit: <span class="required">*</span></label><input type="text" class="form-control" name="data[unit][]" id="unit" value="'+output.value[x].unit+'" required></div><script>$(document).ready(function(){ $(".price_1_looping-'+loopN+'").mask("0.000.000.000.000,00", {reverse: true});$(".price_2_looping-'+loopN+'").mask("0.000.000.000.000,00", {reverse: true});$(document).on("click", ".hitung_'+loopN+'", function(e){ var ukuran_'+loopN+' = $(".sizeval_'+loopN+'").val();var harga_'+loopN+' = $(".price_1_looping-'+loopN+'").val().replace(/\\./g,"");$(".price_2_looping-'+loopN+'").val(parseInt(ukuran_'+loopN+' * harga_'+loopN+'.replace(/\\,/g,".")));});});</script></div>'
	  							);

	  							for(var i = 0; i < split_input.length; i++)
			  					{
			  						$('.'+split_input[i]+'-'+loopN).show();
			  					}
	  						}
	  					}
	  				}
	  			});

	  			meminta.fail(function(jqXHR, textStatus)
	  			{
	  				hide_loading_message();
	  				show_message('Gagal mengambil data: '+textStatus, 'error');
	  			});

	  		}
	  	});

	  	$('#vendor').keyup(function(){
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
	  	});
  	});

	///////////////////////////
  	// Add PO (item)
  	//////////////////////////

  	$(document).on('click', '.tambah_barang', function(e){
  		e.preventDefault();
  		barisN++;
  		var split_po_type_attribute = po_type_attribute.split(',');
  		$('#looping_barang').append(
  			'<div class="looping_barang" id="looping-'+barisN+'"><hr class="looping-item"><p><button type="button" name="remove" data-id="'+barisN+'" class="btn btn-danger btn_remove">Hapus</button></p><div class="form-group detail-'+barisN+'" style="display: none"><label for="detail">Detail: <span class="required">*</span></label><input type="text" class="form-control" name="data[detail][]" id="detail" value="" required></div><div class="form-group size-'+barisN+'" style="display: none"><label for="Size">Size: <span class="required">*</span></label><input type="number" class="form-control sizeval_'+barisN+'" name="data[size][]" id="size" value="" required></div><div class="form-group merk-'+barisN+'" style="display: none"><label for="merk">Merk: <span class="required">*</span></label><input type="text" class="form-control" name="data[merk][]" id="merk" required></div><div class="form-group type-'+barisN+'" style="display: none"><label for="type">Type: <span class="required">*</span></label><input type="text" class="form-control" name="data[type][]" id="type" required></div><div class="form-group core-'+barisN+'" style="display: none"><label for="core">Core: <span class="required">*</span></label><input type="text" class="form-control" name="data[core][]" id="core" required></div><div class="form-group gulungan-'+barisN+'" style="display: none"><label for="gulungan">Gulungan: <span class="required">*</span></label><input type="text" class="form-control" name="data[gulungan][]" id="gulungan" required></div><div class="form-group bahan-'+barisN+'" style="display: none"><label for="bahan">Bahan: <span class="required">*</span></label><input type="text" class="form-control" name="data[bahan][]" id="bahan" required></div><div class="form-group price_1"><label for="price_1">Price: <span class="required">*</span></label><input type="text" class="form-control price_1_looping-'+barisN+'" name="data[price_1][]" id="price_1" required></div><div class="form-group price_2-'+barisN+'" style="display: none"><label for="price_2">Price (Secondary): <span class="required">*</span> <em class="label label-success">Size x Price = Price (secondary)</em></label><div class="row"><div class="col-md-10"><input type="text" class="form-control price_2_looping-'+barisN+'" name="data[price_2][]" id="price_2" required></div><div class="col-md-2"><button type="button" class="btn btn-primary hitung_'+barisN+'">Hitung</button></div></div></div><div class="form-group qty"><label for="qty">Qty: <span class="required">*</span></label><input type="number" min="0" class="form-control" name="data[qty][]" id="qty" required></div><div class="form-group unit-'+barisN+'" style="display: none"><label for="unit">Unit: <span class="required">*</span></label><input type="text" class="form-control" name="data[unit][]" id="unit" required></div><script>$(document).ready(function(){	$(".price_1_looping-'+barisN+'").mask("0.000.000.000.000,00", {reverse: true});	$(".price_2_looping-'+barisN+'").mask("0.000.000.000.000,00", {reverse: true});$(document).on("click", ".hitung_'+barisN+'", function(e){ var ukuran_'+barisN+' = $(".sizeval_'+barisN+'").val();var harga_'+barisN+' = $(".price_1_looping-'+barisN+'").val().replace(/\\./g,"");$(".price_2_looping-'+barisN+'").val(parseInt(ukuran_'+barisN+' * harga_'+barisN+'.replace(/\\,/g,".")));});});</script></div>'
  		);

		for(var x = 0; x < split_po_type_attribute.length; x++)
		{
			$('.'+split_po_type_attribute[x]+'-'+barisN).show();
		}
  	});

  	$(document).on('click', '.btn_remove', function(){  
           var button_id = $(this).data('id');
           $('#looping-'+button_id+'').remove();
    });

  	///////////////////////////
  	// Add PO form
  	//////////////////////////

  	$(document).on('submit', IDForm+'.add', function(e){
    	e.preventDefault();
    	hide_ipad_keyboard();
      	hide_lightbox();
      	show_loading_message();
      	var Infos = $('#vendor').val();
      	var form_data = $(IDForm).serialize();
      	var request   = $.ajax({
        	url:          pathFile+"?"+Act+"=add_"+sLug,
        	cache:        false,
        	data:         form_data,
        	method: 	  'POST',
      	});

      	request.done(function(output){
      		var obj = JSON.parse(output);
	    	if (obj.result == sukses){
	    		$("#sortby").empty();
	      		tablenya.ajax.reload(function(){
	      			tablePrint.ajax.reload();
	        		hide_loading_message();
	        		show_message("'"+Infos+"' berhasil dimasukan.", 'success');
	        		$('#company').empty();
	        		$('#po_type').empty();
	        		reset();
	        		clean();
	      		}, true);

	      		$.ajax({
          			url: pathFile+"?"+Act+"=sortdata_"+sLug,
          			cache: false,
          			dataType: 'json',
          			contentType: 'application/json; charset=utf-8',
          			type: 'get',
          			success: function(output){
          				if(output.result == sukses){
          					for(var i = 0; i<output.data.length; i++){
          						$("#sortby").append("<option value='"+output.data[i].montly+"' "+(getCookie("selectMonth") == output.data[i].montly ? 'selected' : '')+" >"+output.data[i].montly+"</option>");
          					}
          					setCookie("selectMonth", arsip, 1);
          				} else {
          					show_message('Gagal memuat data', 'error');
          				}
          			}
          		});

	    	} else if(obj.result == 'invalid'){
	    		$('#company').empty();
	    		$('#po_type').empty();
	      		hide_loading_message();
	      		show_message(obj.message+', silakan periksa dan coba kembali.', 'error');
	    	} else {
	    		$('#company').empty();
	    		$('#po_type').empty();
	      		hide_loading_message();
	      		show_message('Gagal memasukan data', 'error');
	    	}
	  	});
	  	request.fail(function(jqXHR, textStatus){
	  		$('#company').empty();
	  		$('#po_type').empty();
	    	hide_loading_message();
	    	show_message('Gagal memasukan data: '+textStatus, 'error');
	  	});
  	});

	/////////////////////
  	// Edit Vendor
	////////////////////

	$(document).on('click', '.UbahVendor a', function(e){
		e.preventDefault();
	    show_loading_message();
	    $('.ppns').show();
	    $('#company').empty();
	    $('#po_type').empty();
	    document.getElementById("po_type").disabled = true;
	    var id      = $(this).data('id');
	    var request = $.ajax({
	    	url:          pathFile+"?"+Act+"=get_vendor_"+sLug,
	      	cache:        false,
	      	data:         'id='+id,
	      	dataType:     'json',
	      	contentType:  'application/json; charset=utf-8',
	      	type:         'get'
	    });
	    request.done(function(output){
	    	if (output.result == sukses){
	        	hide_loading_message();
	        	show_lightbox();
	    		$('h2.FormTitle').text('UBAH '+FormsLug);
	        	$(IDForm).attr('class', 'form edit_vendor');
	        	$(IDForm).attr('data-id', id);
	        	$(IDForm +' .field_container label.error').hide();

	        	$('#company').append('<option selected disabled>Pilih Tipe</option>');
	        	for(var x = 0; x<companyJSON.length; x++)
	        	{
	        		$('#company').append('<option value="'+companyJSON[x].id+'" '+(companyJSON[x].id == output.data[0].id_company ? 'selected':'')+'>'+companyJSON[x].company+'</option>');
	        	}

	        	$('#po_type').append('<option value="" selected disabled>Pilih Tipe</option>');
				for(var z = 0; z < po_typeJSON.length; z++)	
				{
					$('#po_type').append('<option value="'+po_typeJSON[z].id+'">' +po_typeJSON[z].item+ '</option>');
				}

	        	$('#vendor').val(output.data[0].vendor);
	        	$('#id_vendor').val(output.data[0].id_vendor);
		        $('#po_date').val(output.data[0].po_date);
	        	$('#po_type').val(output.data[0].po_type);
		        $('#ppns').val(output.data[0].ppn);
		        $('#note').val(output.data[0].note);
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

	        	////////////////////////////////////////
			  	// auto complete customer
			  	//////////////////////////////////////

			  	$.widget( "custom.catcomplete", $.ui.autocomplete, {
			  		_create: function()
			  		{
			  			this._super();
			  			this.widget().menu( "option", "items", "> :not(.ui-autocomplete-category)" );
			  		},

			  		_renderMenu: function( ul, items )
			  		{
			  			var that = this,
			  			currentCategory = "";
			  			$.each( items, function( index, item )
			  			{
			  				var li;
			  				if ( item.category != currentCategory )
			  				{
			  					ul.append( "<li class='ui-autocomplete-category'>" + item.category + "</li>" );
			  					currentCategory = item.category;
			  				}
			  				li = that._renderItemData( ul, item );
			  				if ( item.category )
			  				{
			  					li.attr( "aria-label", item.category + " : " + item.label );
			  				}
			  			});
			  		}
			  	});

			  	$('#vendor').catcomplete({
			  		minLength: 2,
			  		source: function(request, respone)
			  		{
			  			$.ajax({
			  				url: '../auth/json.php',
			  				dataType: 'json',
			  				type: 'GET',
			  				data: {
			  					req: 'vendor',
			  					keyword: request.term
			  				},
			  				success: function(data)
			  				{
			  					respone(data);
			  				}
			  			});
			  		},
			  		select: function(event, ui)
			  		{
			  			$('#vendor').val(ui.item.category);
			  			$('#id_vendor').val(ui.item.id_vendor);
			  		}
			  	});

			  	$('#vendor').keyup(function(){
			  		$('#id_vendor').val('');
			  	});

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

	//////////////////////////////////////////////////////
	// Edit Vendor form
  	////////////////////////////////////////////////////

  	$(document).on('submit', IDForm+'.edit_vendor', function(e){
    	e.preventDefault();
    	if (FormNYA.valid() == true){
      		hide_ipad_keyboard();
      		hide_lightbox();
      		show_loading_message();
      		var Infos = $('#vendor').val();
      		var id        = $(IDForm).attr('data-id');
      		var form_data = $(IDForm).serialize();
      		var request   = $.ajax({
        		url:          pathFile+"?"+Act+"=edit_vendor_"+sLug+"&id="+id,
        		cache:        false,
        		data:         form_data,
        		dataType:     'json',
        		contentType:  'application/json; charset=utf-8',
        		type:         'get'
     		});
      		request.done(function(output){
        		if (output.result == sukses){
          			tablenya.ajax.reload(function(){
          				tablePrint.ajax.reload();
            			hide_loading_message();
            			show_message("'"+Infos+"' berhasil diubah.", 'success');
          				reset(); clean();
          			}, true);
        		} else if(output.result == 'invalid'){
		      		hide_loading_message();
		      		show_message(output.message+', silakan periksa dan coba kembali.', 'error');
		    	} else {
          			hide_loading_message();
          			show_message('Gagal diubah', 'error');
        		}
      		});
     		request.fail(function(jqXHR, textStatus){
        		hide_loading_message();
        		show_message('Gagal diubah: '+textStatus, 'error');
      		});
    	}
  	});

  	//////////////////////////////////////////
  	// Edit Item 
	/////////////////////////////////////////

	$(document).on('click', '.UbahItem a', function(e){
		e.preventDefault();
	    show_loading_message();
	    var id      = $(this).data('id');
	    var request = $.ajax({
	    	url:          pathFile+"?"+Act+"=get_item_"+sLug,
	      	cache:        false,
	      	data:         'id='+id,
	      	dataType:     'json',
	      	contentType:  'application/json; charset=utf-8',
	      	type:         'get'
	    });
	    request.done(function(output){
	    	if (output.result == sukses){
	    		$('h2.FormTitle').text('UBAH ITEM '+FormsLug);
	        	$(IDForm).attr('class', 'form edit_item');
	        	$(IDForm).attr('data-id', id);
	        	$(IDForm +' .field_container label.error').hide();
		        $('#detail').val(output.data.value[0].detail);
				$('#size').val(output.data.value[0].size);
				$('#merk').val(output.data.value[0].merk);
				$('#type').val(output.data.value[0].type);
				$('#core').val(output.data.value[0].core);
				$('#gulungan').val(output.data.value[0].gulungan);
				$('#bahan').val(output.data.value[0].bahan);
				$('#price_1').val(output.data.value[0].price_1);
				$('#price_2').val(output.data.value[0].price_2);
				$('#qty').val(output.data.value[0].qty);
				$('#unit').val(output.data.value[0].unit);
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

		        var split_input = output.data.input[0].attribute.split(',');
		        for(var i = 0; i < split_input.length; i++)
		        {
		        	$('.'+split_input[i]).show();
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


	///////////////////////////
	// Edit item form
  	/////////////////////////

  	$(document).on('submit', IDForm+'.edit_item', function(e){
    	e.preventDefault();
    	if (FormNYA.valid() == true){
      		hide_ipad_keyboard();
      		hide_lightbox();
      		show_loading_message();
      		var id        = $(IDForm).attr('data-id');
      		var form_data = $(IDForm).serialize();
      		var request   = $.ajax({
        		url:          pathFile+"?"+Act+"=edit_item_"+sLug+"&id="+id,
        		cache:        false,
        		data:         form_data,
        		dataType:     'json',
        		contentType:  'application/json; charset=utf-8',
        		type:         'get'
     		});
      		request.done(function(output){
        		if (output.result == sukses){
          			tablenya.ajax.reload(function(){
          				tablePrint.ajax.reload();
            			hide_loading_message();
            			show_message("Berhasil diubah.", 'success');
            			reset(); clean();
          			}, true);
        		} else {
        			reset(); clean();
          			hide_loading_message();
          			show_message('Gagal diubah', 'error');
        		}
      		});
     		request.fail(function(jqXHR, textStatus){
     			reset(); clean();
        		hide_loading_message();
        		show_message('Gagal diubah: '+textStatus, 'error');
      		});
    	}
  	});

  	//////////////////////////////////////
  	// Delete item button
  	////////////////////////////////////

  	$(document).on('click', '.HapusItem a', function(e){
	    e.preventDefault();
	    var Infos = $(this).data('name');
	    if (confirm("Anda yakin ingin menghapus '"+Infos+"'?")){
	    	show_loading_message();
	      	var id      = $(this).data('id');
	      	var request = $.ajax({
	        	url:          pathFile+"?"+Act+"=del_"+sLug+"&id="+id,
	        	cache:        false,
	        	dataType:     'json',
	        	contentType:  'application/json; charset=utf-8',
	        	type:         'get'
	      	});
	      	
	      	request.done(function(output){
	        	if (output.result == sukses){
	          		tablenya.ajax.reload(function(){
	          			tablePrint.ajax.reload();
	            		hide_loading_message();
	            		show_message("'"+Infos+"' berhasil dihapus.", 'success');
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

  	/////////////////////////////////////////
  	// Print view button
	////////////////////////////////////////

	$(document).on('click', '.PrintView a', function(e){
		e.preventDefault();
	    show_loading_message();
	    reset();
	    var id      = $(this).data('id');
	    var request = $.ajax({
	    	url:          pathFile+"?"+Act+"=get_print_"+sLug,
	      	cache:        false,
	      	data:         'id='+id,
	      	dataType:     'json',
	      	contentType:  'application/json; charset=utf-8',
	      	type:         'get'
	    });
	    request.done(function(output){
	    	if (output.result == sukses){
	    		$('h2.FormTitle').text('PRATINJAU PRINT '+output.data.value[0].po_type);
	        	$(IDForm+' .field_container label.error').hide();
	        	$(IDForm).attr('data-id', id);
	        	$(IDForm).attr('class', 'form printProses');
	        	$('.company').hide();
	        	$('.po_type').hide();
	        	$('.tambah_barang').hide();
	        	$('.address').show();
	        	$('.tanda_tangan').show();
	        	$('#vendor').val(output.data.value[0].vendor);
		        $('#po_date').val(output.data.value[0].po_date);
		        $('#ppns').val(output.data.value[0].ppn);
		        $('#note').val(output.data.value[0].note);
		        $('#address').val(output.data.value[0].address);
		        $('#tanda_tangan').val(output.data.value[0].ttd);
		        $('#vendor').attr('readonly', true);
		        $('#po_date').attr('readonly', true);
		        $('#ppns').attr('readonly', true);
		        $('#note').attr('readonly', true);
		        $('#address').attr('readonly', true);

		        var split_input = output.data.input[0].attribute.split(',');
		        for(var x = 0; x < output.data.value.length; x++)
		        {
		        	if(parseInt(x) === 0)
					{
						$('#detail').val(output.data.value[x].detail);
						$('#size').val(output.data.value[x].size);
						$('#merk').val(output.data.value[x].merk);
						$('#type').val(output.data.value[x].type);
						$('#core').val(output.data.value[x].core);
						$('#gulungan').val(output.data.value[x].gulungan);
						$('#bahan').val(output.data.value[x].bahan);
						$('#price_1').val(output.data.value[x].price_1);
						$('#price_2').val(output.data.value[x].price_2);
						$('#qty').val(output.data.value[x].qty);
						$('#unit').val(output.data.value[x].unit);
						for(var i = 0; i < split_input.length; i++)
	  					{
	  						$('.'+split_input[i]).show();
	  					}

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
							'<div class="looping_barang" id="looping-'+loopN+'"><hr><div class="form-group detail-'+loopN+'" style="display: none"><label for="detail">Detail: <span class="required">*</span></label><input type="text" class="form-control" name="data[detail][]" id="detail-'+loopN+'" value="'+output.data.value[x].detail+'" required></div><div class="form-group size-'+loopN+'" style="display: none"><label for="Size">Size: <span class="required">*</span></label><input type="number" class="form-control sizeval_'+loopN+'" name="data[size][]" id="size-'+loopN+'" value="'+output.data.value[x].size+'" required></div><div class="form-group merk-'+loopN+'" style="display: none"><label for="merk">Merk: <span class="required">*</span></label><input type="text" class="form-control" name="data[merk][]" id="merk-'+loopN+'" value="'+output.data.value[x].merk+'" required></div><div class="form-group type-'+loopN+'" style="display: none"><label for="type">Type: <span class="required">*</span></label><input type="text" class="form-control" name="data[type][]" id="type-'+loopN+'" value="'+output.data.value[x].type+'" required></div><div class="form-group core-'+loopN+'" style="display: none"><label for="core">Core: <span class="required">*</span></label><input type="text" class="form-control" name="data[core][]" id="core-'+loopN+'" value="'+output.data.value[x].core+'" required></div><div class="form-group gulungan-'+loopN+'" style="display: none"><label for="gulungan">Gulungan: <span class="required">*</span></label><input type="text" class="form-control" name="data[gulungan][]" id="gulungan-'+loopN+'" value="'+output.data.value[x].gulungan+'" required></div><div class="form-group bahan-'+loopN+'" style="display: none"><label for="bahan">Bahan: <span class="required">*</span></label><input type="text" class="form-control" name="data[bahan][]" id="bahan-'+loopN+'" value="'+output.data.value[x].bahan+'" required></div><div class="form-group price_1"><label for="price_1">Price: <span class="required">*</span></label><input type="text" class="form-control price_1_looping-'+loopN+'" name="data[price_1][]" id="price_1-'+loopN+'" value="'+output.data.value[x].price_1+'" required></div><div class="form-group price_2-'+loopN+'" style="display: none"><label for="price_2">Price (Secondary): <span class="required">*</span> <em class="label label-success">Size x Price = Price (secondary)</em></label><div class="row"><div class="col-md-10"><input type="text" class="form-control price_2_looping-'+loopN+'" name="data[price_2][]" id="price_2-'+loopN+'" value="'+output.data.value[x].price_2+'" required></div><div class="col-md-2"><button type="button" class="btn btn-primary hitung_'+loopN+'">Hitung</button></div></div></div><div class="form-group qty"><label for="qty">Qty: <span class="required">*</span></label><input type="number" min="0" class="form-control" name="data[qty][]" id="qty-'+loopN+'" value="'+output.data.value[x].qty+'" required></div><div class="form-group unit-'+loopN+'" style="display: none"><label for="unit">Unit: <span class="required">*</span></label><input type="text" class="form-control" name="data[unit][]" id="unit-'+loopN+'" value="'+output.data.value[x].unit+'" required></div><script>$(document).ready(function(){ $(".price_1_looping-'+loopN+'").mask("0.000.000.000.000,00", {reverse: true});$(".price_2_looping-'+loopN+'").mask("0.000.000.000.000,00", {reverse: true});$(document).on("click", ".hitung_'+loopN+'", function(e){ var ukuran_'+loopN+' = $(".sizeval_'+loopN+'").val();var harga_'+loopN+' = $(".price_1_looping-'+loopN+'").val().replace(/\\./g,"");$(".price_2_looping-'+loopN+'").val(parseInt(ukuran_'+loopN+' * harga_'+loopN+'.replace(/\\,/g,".")));});});</script></div>'
						);

						for(var i = 0; i < split_input.length; i++)
	  					{
	  						$('.'+split_input[i]+'-'+loopN).show();
	  					}

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

	        	show_lightbox();
	        	hide_loading_message();
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
	
	///////////////////////////
  	// Print view submit
  	//////////////////////////

  	$(document).on('submit', '.printProses', function(e){
    	e.preventDefault();
	    if ($('.printProses').valid() == true){
	      	hide_ipad_keyboard();
	      	hide_lightbox();
	      	show_loading_message();
	      	var id 			= $('.printProses').attr('data-id');
	      	var form_data 	= $('.printProses').serialize();
	      	$.ajax({
	        	url: 	pathFile+"?"+Act+"=print&id="+id,
	        	cache:  false,
	        	data:   form_data,
	        	type: 'POST',
	        	success: function(respon){
	        		var obj = JSON.parse(respon);
	        		if(obj.result == 'success'){
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
		                    stylesheet : "../lib/css/bootstrap/bootstrap.min.css",
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

  	//////////////////////////////////////////////////////
  	////////// Pemeriksaan tipe purchase order
  	//////////////////////////////////////////////////////

  	$(document).on('change', '#po_type', function(e){
    	e.preventDefault();
    	reset();
        $('.tambah_barang').show();
    	var id = $(this).val();
    	if(id)
    	{
	    	$.ajax({
	    		url : "../auth/json.php",
				dataType: "JSON",
				type : "GET",
				contentType: "application/json; charset=utf-8",
				cache: false,
				data: {
					req: 'po_attribute',
					id: id
				},
				success: function(output){
					po_type_attribute = output[0].field;
					var split_po_type_attribute = output[0].field.split(',');
					for(var x = 0; x < split_po_type_attribute.length; x++)
					{
						$('.'+split_po_type_attribute[x]).show();
					}
				}
	    	});
    	}
    });

});