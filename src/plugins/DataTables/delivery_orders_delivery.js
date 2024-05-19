$(document).ready(function(){

	//////////////////////////
	//Default config
	/////////////////////////

	var idTablenya = $('#tablenya');
	var pathFile = '../auth/delivery_orders_delivery.php';
	var Act = 'action';
	var sLug = 'delivery_orders_delivery';
	var FormsLug = 'SURAT JALAN';
	var IDForm = "#form_inputSJ";
	var addButton = "#add_inputSJ";
	var sukses = 'success'; //Message alert

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
		"scrollX": true,
	    "ajax": pathFile+"?"+Act+"=result_"+sLug+"&curMonth="+getCookie("selectMonth"),
	    'columnDefs': [
	    	{
	    		'targets': [0,1,2,3,4,5,7,8,9,10,11,12,13,14],
	            'className': 'dt-nowrap'
	        }
	    ],
	    "columns": [
	      { "data": "no" },
	      { "data": "sj_date"},
	      { "data": "no_delivery"},
	      { "data": "customer"},
	      { "data": "po_customer"},
	      { "data": "no_spk"},
	      { "data": "shipto"},
	      { "data": "item"},
	      { "data": "send_qty"},
	      { "data": "unit"},
	      { "data": "courier"},
	      { "data": "no_tracking"},
	      { "data": "ongkir"},
	      { "data": "input_by"},
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

	var tablePrint = $("#tablePrint").DataTable({
		"scrollX": false,
		"bPaginate": false,
		"searching": false,
		"info": false,
	    "ajax": pathFile+"?"+Act+"=resultAll_"+sLug+"&curMonth="+getCookie("selectMonth"),
	    "columns": [
	      { "data": "no" },
	      { "data": "sj_date"},
	      { "data": "no_delivery"},
	      { "data": "customer"},
	      { "data": "po_customer"},
	      { "data": "no_spk"},
	      { "data": "shipto"},
	      { "data": "item"},
	      { "data": "send_qty"},
	      { "data": "unit"},
	      { "data": "courier"},
	      { "data": "no_tracking"},
	      { "data": "ongkir"},
	      { "data": "input_by"},
	    ],
	    iDisplayLength: -1,
	});

	var buttons = new $.fn.dataTable.Buttons(tablePrint, {
		buttons:[
        {
        	extend: 'excelHtml5',
        	messageTop: false,
        	footer: true,
        	text: 'Export to Excel',
        	filename : 'DO-Delivery_'+getCookie("selectMonth"),
        	title: 'Delivery Order Delivery '+getCookie("selectMonth"),
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

	/////////////////////////////////////////
  	// Print view button
	////////////////////////////////////////

	$(document).on('click', '.function_print a', function(e){
		e.preventDefault();
	    show_loading_message();
	    var id      = $(this).data('id');
	    var ex 		= id.split('-');
	    var request = $.ajax({
	    	url:          pathFile+"?"+Act+"=get_print_"+sLug,
	      	cache:        false,
	      	data:         'id='+ex[0]+'&id_fk='+ex[1]+'&id_sj='+ex[2],
	      	dataType:     'json',
	      	contentType:  'application/json; charset=utf-8',
	      	type:         'get'
	    });
	    request.done(function(output){
	    	if (output.result == sukses){
	    		$('#myModal2').show();
	    		$('h2.FormTitle').text('PRATINJAU PRINT');
	        	$('#form_print .field_container label.error').hide();
	        	$('#form_print').attr('data-id', id);
	        	$('#form_print').attr('class', 'form printProses');
	        	$('#sj_date').val(output.data[0].sj_date);
	        	$('#no_po_pratinjau').val(output.data[0].po_customer);
	        	$('#no_delivery').val(output.data[0].no_delivery);
	        	$('#custom').val(output.data[0].customer);
	        	$('#shipto').val(output.data[0].shipto);
				$('#telp').val(output.data[0].telp);
	        	var no = 0;
	        	for(var i = 0; i<output.data.length; i++){
	        		no++;
	        		$('.itemnya').append(
	        			'<div class="looping-item"><div class="form-group"><label for="item">Nama Barang '+no+' : <span class="required">*</span></label><input type="text" class="form-control" name="data[item][]" id="item" value="'+output.data[i].item+'" required readonly></div><div class="form-group"><label for="qty">Qty: <span class="required">*</span></label><input type="text" class="form-control" name="data[qty][]" id="qty" value="'+output.data[i].qty+' '+output.data[i].unit+'" required readonly></div>'
	        			);
	        	}

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

  	$(document).on('submit', '#form_print', function(e){
    	e.preventDefault();
	    if ($('#form_print').valid() == true){
	      	hide_ipad_keyboard();
	      	hide_lightbox();
	      	show_loading_message();
	      	var id        = $('#form_print').attr('data-id');
      		var ex 		= id.split('-');
	      	var form_data 	= $('#form_print').serialize();
	      	$.ajax({
	        	url: 	pathFile+"?"+Act+"=print&id="+ex[0]+"&id_fk="+ex[1]+"&id_sj="+ex[2],
	        	cache:  false,
	        	data:   form_data,
	        	type: 'POST',
	        	success: function(respon){
	        		var obj = JSON.parse(respon);
	        		if(obj.result == 'success'){
	        			hide_loading_message();
	        			$('#PrintModal').show();
	        			if(!!obj.data[0].logo){
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

  	////////////////////
  	// Delete button
  	//////////////////

  	$(document).on('click', '.customerDel a', function(e){
	    e.preventDefault();
	    var Infos = $(this).data('name');
	    if(confirm("Anda yakin ingin menghapus '"+Infos+"'?")){
	    	show_loading_message();
	      	var id      = $(this).data('id');
	      	var ex		= id.split('-');
	      	var request = $.ajax({
	        	url:          pathFile+"?"+Act+"=del_"+sLug+"&id="+ex[0]+"&id_fk="+ex[1]+"&item_to="+ex[2]+"&id_sj="+ex[3],
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
});