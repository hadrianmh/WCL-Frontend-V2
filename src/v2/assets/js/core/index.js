///////////////////////////////////
// ketika refresh page
///////////////////////////////////

$(document).ready(function(){
	//////////////////////////
	//Default config
	/////////////////////////

	var idTablenya = $('#tablenya');
	var pathFile = '../auth/dashboard.php';
	var Act = 'action';
	var sLug = 'dashboard';

	//////////////////////////////////////////////////////////////
	// On page load: datatable
	//////////////////////////////////////////////////////////////

	var tablenya = idTablenya.DataTable({
		//"scrollX": true,
		"searching": false,
		"paging":   false,
        "ordering": false,
        "info":     false,
	    "ajax": pathFile+"?"+Act+"=result_"+sLug+"&curMonth="+getCookie("selectMonth"),
	    "columns": [
	      { "data": "no" },
	      { "data": "company" },
	      { "data": "order_grade" },
	      { "data": "no_spk"},
	      { "data": "so_date"},
	      { "data": "etd"},
	      { "data": "customer"},
	      { "data": "po_customer"},
	      { "data": "po_date"},
	      { "data": "item"},
	      { "data": "detail"},
	      { "data": "merk"},
	      { "data": "type"},
	      { "data" : "size"},
	      { "data" : "qore"},
	      { "data" : "line"},
	      { "data" : "roll"},
	      { "data" : "ingredient"},
	      { "data" : "porporasi"},
	      { "data" : "qty"},
	      { "data": "unit"},
	      { "data" : "volume"},
	      { "data" : "uk_bahan"},
	      { "data" : "qty_bahan"},
	      { "data" : "annotation"},
	      { "data" : "sources"},
	      { "data": "price", render: $.fn.dataTable.render.number( '', '', 0, '' )},
	      { "data": "price_before"},
	      { "data": "tax"},
	      { "data": "total"},
	      { "data": "spk_date"},
	      { "data": "order_status"},
	      { "data": "no_delivery"},
	      { "data": "sj_date"},
	      { "data": "courier"},
	      { "data": "no_tracking"},
	      { "data": "send_qty"},
	      { "data": "cost"}
	    ],
	    dom: 'Bfrtip',
	    buttons: [
        {
        	extend: 'excel',
        	text: 'Export to Excel',
        	filename : 'SOTracking_'+getCookie("selectMonth"),
        	messageTop: false,
        	footer: true,
        	title: 'SO Tracking '+getCookie("selectMonth"),
        },
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
      	setCookie("selectMonth", $('#dari').val()+"_"+$('#sampai').val(), 1);
      	var form_data = $(FormPeriode).serialize();
      	var request   = $.ajax({
        	url:          pathFile+"?"+Act+"=periode_"+sLug,
        	cache:        false,
        	data:         form_data,
        	method: 	  'GET',
        	dataType: 'json'
      	});

      	request.done(function(output){
	    	if (output.result == 'success'){

	    		var Ambil = $.ajax({
					url: pathFile+"?"+Act+"=statistik_periode_"+sLug+"&"+form_data,
					cache: false,
					dataType: 'json',
					contentType: 'application/json; charset=utf-8',
					type: 'get'
				});

				Ambil.done(function(output){
					if(output.result == 'success'){
						$('.statistiPO').html(output.data[0].jml_po);
			            $('.statistiSPK').html(output.data[0].jml_wo);
			            $('.statistiSJ').html(output.data[0].jml_do);
			            $('.statistiFAKTUR').html(output.data[0].jml_in);
			            show_message("Berhasil memuat data.", 'success');
					} else {
				        show_message('Gagal memuat data', 'error');
					}
				});

				Ambil.fail(function(jqXHR, textStatus){
			    	hide_loading_message();
			    	show_message('Gagal memuat data: '+textStatus, 'error');
			  	});

	    		tablenya.ajax.url(pathFile+"?"+Act+"=periode_"+sLug+"&"+form_data).load();
      			tablenya.draw();
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
	// Sort data from current month
	/////////////////////////////////////////////////////////////////

	var Sortdata = $.ajax({
		url: pathFile+"?"+Act+"=sortdata_"+sLug,
		cache: false,
		dataType: 'json',
		contentType: 'application/json; charset=utf-8',
		type: 'get'
	});

	Sortdata.done(function(output){
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

	Sortdata.fail(function(jqXHR, textStatus){
    	hide_loading_message();
    	show_message('Gagal memuat data: '+textStatus, 'error');
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
	// Get data from current month
	/////////////////////////////////////////////////////////////////

	var Getdata = $.ajax({
		url: pathFile+"?"+Act+"=statistik_"+sLug+"&curMonth="+getCookie("selectMonth"),
		cache: false,
		dataType: 'json',
		contentType: 'application/json; charset=utf-8',
		type: 'get'
	});

	Getdata.done(function(output){
		if(output.result == 'success'){
			$('.statistiPO').html(output.data[0].jml_po);
            $('.statistiSPK').html(output.data[0].jml_wo);
            $('.statistiSJ').html(output.data[0].jml_do);
            $('.statistiFAKTUR').html(output.data[0].jml_in);
            show_message("Berhasil memuat data.", 'success');
		} else {
	        show_message('Gagal memuat data', 'error');
		}
	});

	Getdata.fail(function(jqXHR, textStatus){
    	hide_loading_message();
    	show_message('Gagal memuat data: '+textStatus, 'error');
  	});



});



