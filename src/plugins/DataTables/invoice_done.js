$(document).ready(function(){
	
	//////////////////////////
	//Default config
	/////////////////////////

	var idTablenya = $('#tablenya');
	var pathFile = '../auth/invoice_done.php';
	var Act = 'action';
	var sLug = 'invoice_done';
	var IDForm = "#form_print";
	
	//Message alert
	var sukses = 'success';

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

	///////////////////////////
	// On page load: datatable
	///////////////////////////

	var tablenya = idTablenya.DataTable({
		'scrollX': true,
		'bPaginate': false,
	    "ajax": pathFile+"?"+Act+"=result_"+sLug+"&curMonth="+getCookie("selectMonth"),
	    'columnDefs': [
	    	{
	    		'targets': [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,17,18,19],
	            'className': 'dt-nowrap'
	        }
	    ],
	    "columns": [
	      { "data": "no" },
	      { "data": "invoice_date" },
	      { "data": "duration" },
	      { "data": "customer"},
	      { "data": "no_po" },
	      { "data": "no_so" },
	      { "data": "no_sj"},
	      { "data": "no_invoice"},
	      { "data": "send_qty"},
	      { "data": "unit"},
	      { "data": "price"},
	      { "data": "bill"},
	      { "data": "ppn"},
	      { "data": "total"},
	      { "data": "shipping_costs"},
	      { "data": "ekspedisi"},
	      { "data": "uom"},
	      { "data": "jml"},
	      { "data": "note"},
	      { "data": "dicetak"},
	      { "data": "diinput"},
	      { "data": "functions","sClass": "functions" }
	    ],
	    "lengthMenu": [[-1], ["All"]],
	    iDisplayLength: -1,
	    dom: 'Bfrtp',
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
	    "footerCallback": function ( row, data, start, end, display ) {
        	var api = this.api(), data;
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };
            
            Bills = api.column( 11, { page: 'current'} ).data().reduce( function (a, b) {
            	return intVal(a) + intVal(b);
            }, 0 );

            Ppns = api.column( 12, { page: 'current'} ).data().reduce( function (a, b) {
            	return intVal(a) + intVal(b);
            }, 0 );

            Totals = Bills + Ppns;
            
            Ship_cost = api.column( 14, { page: 'current'} ).data().reduce( function (a, b) {
            	return intVal(a) + intVal(b);
            }, 0 );

            $( api.column( 11 ).footer() ).html(Rupiah(Bills));
            $( api.column( 12 ).footer() ).html(Rupiah(Ppns));
            $( api.column( 13 ).footer() ).html(Rupiah(Totals));
            $( api.column( 14 ).footer() ).html(Rupiah(Ship_cost));
        }
	});

	//////////////////////////////////////////////////////////////
	// Create function print all with hidden table
	/////////////////////////////////////////////////////////////

	var tablePrint = $("#tablePrint").DataTable({
		"scrollX": true,
		"bPaginate": false,
		"searching": false,
		"info": false,
	    "ajax": pathFile+"?"+Act+"=resultAll_"+sLug+"&curMonth="+getCookie("selectMonth"),
	    "columns": [
	    	{ "data": "no" },
	    	{ "data": "invoice_date" },
	    	{ "data": "duration" },
	      	{ "data": "customer"},
	      	{ "data": "no_po" },
	      	{ "data": "no_so" },
	      	{ "data": "no_sj"},
	      	{ "data": "no_invoice"},
	      	{ "data": "send_qty"},
	      	{ "data": "unit"},
	      	{ "data": "price"},
	      	{ "data": "bill"},
	      	{ "data": "ppn"},
	      	{ "data": "total"},
	      	{ "data": "shipping_costs"},
	      	{ "data": "ekspedisi"},
	      	{ "data": "uom"},
	      	{ "data": "jml"},
	      	{ "data": "note"},
	      	{ "data": "dicetak"},
	      	{ "data": "diinput"},
	    ],
	    "footerCallback": function ( row, data, start, end, display ) {
        	var api = this.api(), data;
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };
            
           	Bills = api.column( 11, { page: 'current'} ).data().reduce( function (a, b) {
            	return intVal(a) + intVal(b);
            }, 0 );

            Ppns = api.column( 12, { page: 'current'} ).data().reduce( function (a, b) {
            	return intVal(a) + intVal(b);
            }, 0 );

            Totals = Bills + Ppns;
            
            Ship_cost = api.column( 14, { page: 'current'} ).data().reduce( function (a, b) {
            	return intVal(a) + intVal(b);
            }, 0 );

            $( api.column( 11 ).footer() ).html(Rupiah(Bills));
            $( api.column( 12 ).footer() ).html(Rupiah(Ppns));
            $( api.column( 13 ).footer() ).html(Rupiah(Totals));
            $( api.column( 14 ).footer() ).html(Rupiah(Ship_cost));
        }
	});

	var buttons = new $.fn.dataTable.Buttons(tablePrint, {
		buttons:[
        {
        	extend: 'excelHtml5',
        	messageTop: false,
        	footer: true,
        	text: 'Export to Excel',
        	filename : 'INVOICE-Done_'+getCookie("selectMonth"),
        	title: 'Invoice Done '+getCookie("selectMonth"),
        }
		]
	}).container().appendTo($('.dt-buttons'));

	///////////////////////////////
	// On page load: form validation
	//////////////////////////////
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
		$('#PrintModal').hide();
	}

	function Rupiah(angka){
		var checked = angka.toString().split('.').join(',');
		var filter = 'Rp. ' + checked.replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1.");
		return filter;
	}

	// Lightbox close button
	$(document).on('click', '.lightbox_close', function(){
	    hide_lightbox();
	    $('.looping_barang').remove();
	    $('.suratjalan').empty();
		$('.tbody').empty();
		$('.delivery-orders-header').empty();
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

  	/////////////////////
  	// Print view button
	////////////////////

	$(document).on('click', '.function_print a', function(e){
		e.preventDefault();
	    show_loading_message();
	    var id      = $(this).data('id');
	    var request = $.ajax({
	    	url:          pathFile+"?"+Act+"=get_"+sLug,
	      	cache:        false,
	      	data:         'id='+id,
	      	dataType:     'json',
	      	contentType:  'application/json; charset=utf-8',
	      	type:         'get'
	    });
	    request.done(function(output){
	    	if (output.result == sukses){
		    	$('H2.FormTitle').text('PRATINJAU PRINT');
	        	$(IDForm +' .field_container label.error').hide();
	        	$(IDForm +'').attr('data-id', id);
	        	$(IDForm +'').attr('class', 'form printProses');
	        	$('#no_faktur').val(output.data[0].no_invoice);
	        	$('#company').val(output.data[0].company);
	        	$('#address').val(output.data[0].address);
	        	$('#phone').val(output.data[0].phone);
	        	$('#tagihan').val(Rupiah(output.data[0].tagihan));
	        	$('#s_cost').val(Rupiah(output.data[0].biaya_kirim));
	        	$('#bill').val(output.data[0].tagihan);
	        	$('#biaya_kirim').val(output.data[0].biaya_kirim);
	        	$('#customer').val(output.data[0].customer);
	        	$('#no_po').val(output.data[0].no_po);
	        	$('#billto').val(output.data[0].billto);
	        	$('#shipto').val(output.data[0].shipto);
	        	$('#ship_name').val(output.data[0].ship_name);
	        	$('#telp').val(output.data[0].telp);
	        	$('#status_ppn').val(output.data[0].ppn);
	        	$('#id_fk').val(output.data[0].id_fk);
	        	$('#tgl').val(output.data[0].invoice_date);
	        	for(var i = 0; i<output.data[0].item.length; i++){
	        		$('.datanyanih').append(
  						'<div class="looping_barang"><hr><div class="form-group item"><label for="no_sj">Surat Jalan: <span class="required">*</span></label><input type="text" class="form-control" name="data[no_sj][]" id="no_sj" value="'+output.data[0].no_sj[i]+'" required readonly></div><div class="form-group item"><label for="no_so">No SO: <span class="required">*</span></label><input type="text" class="form-control" name="data[no_so][]" id="no_so" value="'+output.data[0].no_so[i]+'" required readonly></div><div class="form-group item"><label for="item">Nama Barang: <span class="required">*</span></label><input type="text" class="form-control" name="data[item][]" id="item" value="'+output.data[0].item[i]+'" required readonly></div><div class="form-group qty"><label for="qty">Qty: <span class="required">*</span></label><input type="text" class="form-control" name="data[qty][]" id="qty" value="'+output.data[0].send_qty[i]+'" required readonly></div><div class="form-group unit"><label for="unit">Satuan: <span class="required">*</span></label><input type="text" class="form-control" name="data[unit][]" id="unit" value="'+output.data[0].unit[i]+'" required readonly></div><div class="form-group price" style="display:none"><label for="price">Price: <span class="required">*</span></label><input type="text" class="form-control" name="data[price][]" id="price" value="'+output.data[0].price[i]+'" required readonly></div></div>'
  					);
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
  	// Proses Print submit
  	//////////////////////////

  	$(document).on('submit', IDForm+ '.printProses', function(e){
    	e.preventDefault();
	    if (FormNYA.valid() == true){
	      	hide_ipad_keyboard();
	      	hide_lightbox();
	      	show_loading_message();
	      	var id 			= $(this).data('id');
	      	var form_data 	= $(IDForm).serialize();
	      	$.ajax({
	        	url: 	pathFile+"?"+Act+"=print&id="+id,
	        	cache:  false,
	        	data:   form_data,
	        	type: 	'POST',
	        	success: function(respon){
	        		var obj = JSON.parse(respon);
	        		if(obj.result == 'success'){
	        			tablenya.ajax.reload(function(){
	        				var dataBANK = obj.data[0].bank.split("-");
		        			hide_loading_message();
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
		        			$('.subtotal').text(Rupiah(obj.data[loop- 1].subtotal));
		        			$('.vat').text(Rupiah(obj.data[loop- 1].ppn));
		        			$('.jumlah').text(Rupiah(obj.data[0].total));
		        			$('.ttd_person').text(obj.data[0].ttd);

		        			if(parseInt(obj.data[0].ongkoskir) > 0 ){
		        				$('.line_cost').show();
		        				$('.label_cost').show();
		        				$('.cost').show();
		        				$('.cost').text(Rupiah(obj.data[0].ongkoskir));
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
		        					'<tr><td class="text-center">'+obj.data[x].no+'</td><td>'+obj.data[x].item+'</td><td class="text-center">'+obj.data[x].no_so+'</td><td class="text-center">'+obj.data[x].qty+'</td><td class="text-center">'+obj.data[x].unit+'</td><td class="text-center">'+Rupiah(parseFloat(obj.data[x].price))+'</td><td class="text-right">'+Rupiah(parseInt(obj.data[x].qty) * parseFloat(obj.data[x].price))+'</td></tr>'
		        				);
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

	        			}, true);

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

  	/////////////////////////////////////////
  	// Complete invoice
	////////////////////////////////////////

	$(document).on('click', '.function_canceled a', function(e){
	    e.preventDefault();
	    var Infos = $(this).data('name');
	    if(confirm("Anda yakin ingin memproses '"+Infos+"'?")){
	    	show_loading_message();
	      	var id      = $(this).data('id');
	      	var request = $.ajax({
	        	url:          pathFile+"?"+Act+"=canceled&id="+id,
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
	            		show_message("'"+Infos+"' berhasil diproses.", 'success');
	          		}, true);
	        	} else {
	          		hide_loading_message();
	          		show_message('Gagal memproses', 'error');
	       		}
	      	});
	      	
	      	request.fail(function(jqXHR, textStatus){
	        	hide_loading_message();
	        	show_message('Gagal memproses: '+textStatus, 'error');
	      	});
	    }
  	});
});