$(document).ready(function(){

	//////////////////////////
	//Default config
	/////////////////////////

	var idTablenya = $('#tablenya');
	var pathFile = '../auth/workorder.php';
	var Act = 'action';
	var sLug = 'workorder';
	var FormsLug = 'SPK';
	var IDForm = "#form_inputWO";
	var addButton = "#add_inputWO";
	var viewButton = ".function_view a";
	var undoButton = ".backWO";
	var delButton = ".function_delete a";
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
	    "columns": [
	      {"data": "no" },
	      {"data": "spk_date"},
	      {"data": "duration" },
	      {"data": "customer"},
	      {"data": "po_customer" },
	      {"data": "no_spk"},
	      {"data" : "item"},
	      {"data" : "size"},
	      {"data" : "qore"},
	      {"data" : "line"},
	      {"data" : "roll"},
	      {"data" : "ingredient"},
	      {"data" : "porporasi"},
	      {"data" : "qty"},
	      {"data" : "unit"},
	      {"data" : "volume"},
	      {"data" : "annotation"},
	      {"data" : "uk_bahan"},
	      {"data" : "qty_bahan"},
	      {"data" : "sources"},
	      {"data": "order_status"},
	      {"data": "input"},
	      {"data": "functions","sClass": "functions" }
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
	// Custom export 
	/////////////////////////////////////////////////////////////

	var buttons = new $.fn.dataTable.Buttons(tablenya, {
		buttons:[
        {
        	extend: 'excelHtml5',
        	messageTop: false,
        	text: 'Export to Excel',
        	filename : 'Workorder_'+getCookie("selectMonth"),
        	title: 'Work Order '+getCookie("selectMonth"),
        	exportOptions: {
            	columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20]
            }
        }
		]
	}).container().appendTo($('.dt-buttons'));


	///////////////////////////////
	// On page load: form validation
	//////////////////////////////
	var FormNYA = $(IDForm);
  	FormNYA.validate();

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

	function Rupiah(angka){
		var rupiah = '';		
		var angkarev = angka.toString().split('').reverse().join('');
		for(var i = 0; i < angkarev.length; i++) if(i%3 == 0) rupiah += angkarev.substr(i,3)+'.';
		return rupiah.split('',rupiah.length-1).reverse().join('');
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

	/////////////////////
  	// Edit button
	////////////////////

	$(document).on('click', '.UbahCustomer a', function(e){
		e.preventDefault();
	    show_loading_message();
	    var idx		= $(this).data('id');
	    var ex 		= idx.split('-');
	    var request = $.ajax({
	    	url:          pathFile+"?"+Act+"=get_"+sLug,
	      	cache:        false,
	      	data:         'id='+ex[0]+'&item_to='+ex[1],
	      	dataType:     'json',
	      	contentType:  'application/json; charset=utf-8',
	      	type:         'get'
	    });
	    request.done(function(output){
	    	if (output.result == sukses){
	    		$('h2.FormTitle').text('UBAH '+FormsLug);
	        	$(IDForm +'').attr('class', 'form edit_customer');
	        	$(IDForm +'').attr('data-id', idx);
	        	$(IDForm +' .field_container label.error').hide();
	        	$(".po_date").show();
	        	$(".customer").show();
	        	$(".no_po").show();
	        	$(".no_spk").show();
	        	$(".spk_date").show();
	        	$(".order_status").show();
	        	$("#po_date").val(output.data[0].po_date);
	        	$("#customer").val(output.data[0].customer);
	        	$("#po_customer").val(output.data[0].po_customer);
	        	$("#no_spk").val(output.data[0].no_spk);
	        	$("#spk_date").val(output.data[0].spk_date);
	        	$("#order_status").val(output.data[0].order_status);
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
	// Edit submit form
  	/////////////////////////

  	$(document).on('submit', IDForm+'.edit_customer', function(e){
    	e.preventDefault();
    	if (FormNYA.valid() == true){
      		hide_ipad_keyboard();
      		hide_lightbox();
      		show_loading_message();
      		var idx		= $(IDForm).attr('data-id');
	    	var ex 		= idx.split('-');
      		var form_data = $(IDForm).serialize();
      		var request   = $.ajax({
        		url:          pathFile+"?"+Act+"=edit_"+sLug+"&id="+ex[0]+"&item_to="+ex[1],
        		cache:        false,
        		data:         form_data,
        		dataType:     'json',
        		contentType:  'application/json; charset=utf-8',
        		type:         'get'
     		});
      		request.done(function(output){
        		if (output.result == sukses){
          			tablenya.ajax.reload(function(){
          				show_all_elemen();
            			hide_loading_message();
            			var Infos = $('#customer').val();
            			show_message("'"+Infos+"' berhasil diubah.", 'success');
            			show_all_elemen();
          			}, true);
        		} else {
          			hide_loading_message();
          			show_message('Gagal diubah', 'error');
          			show_all_elemen();
        		}
      		});
     		request.fail(function(jqXHR, textStatus){
        		hide_loading_message();
        		show_message('Gagal diubah: '+textStatus, 'error');
        		show_all_elemen();
      		});
    	}
  	});

  	/////////////////////
  	// Print view button
	////////////////////

	$(document).on('click', '.function_print a', function(e){
		e.preventDefault();
	    show_loading_message();
	    var idx		= $(this).data('id');
	    var ex 		= idx.split('-');
	    var request = $.ajax({
	    	url:          pathFile+"?"+Act+"=get_print_"+sLug,
	      	cache:        false,
	      	data:         'id='+ex[0]+'&item_to='+ex[1],
	      	dataType:     'json',
	      	contentType:  'application/json; charset=utf-8',
	      	type:         'get'
	    });
	    request.done(function(output){
	    	if (output.result == sukses){
	    		if(output.data[0].unit == 'PCS'){
	    			var total_unit = output.data[0].total;
					var lantotal_unit = "=    "+output.data[0].qty_produksi+" PCS";
					var isiunit = output.data[0].unit+"/ROLL";
	    			$('#isi').val(output.data[0].isi+" PCS");
	    		}
		        if(output.data[0].unit == 'ROLL'){
		        	var total_unit = output.data[0].qty_produksi;
					var lantotal_unit = "=    "+output.data[0].total+" PCS";
					var isiunit = output.data[0].unit+"/ROLL";
		        	$('#isi').val(output.data[0].isi+" PCS");		
		        }
				if(output.data[0].unit == 'PAK'){
		        	var total_unit = output.data[0].qty_produksi;
					var lantotal_unit = "=    "+output.data[0].total+" PCS";
					var isiunit = output.data[0].unit+"/PAK";
		        	$('#isi').val(output.data[0].isi+" PCS");		
		        }
				if(output.data[0].unit == 'CM'){
		        	var total_unit = output.data[0].qty_produksi;
					var lantotal_unit = "=    "+output.data[0].total+" PCS";
					var isiunit = output.data[0].unit+"/CM";
		        	$('#isi').val(output.data[0].isi+" PCS");		
		        }
				if(output.data[0].unit == 'MM'){
		        	var total_unit = output.data[0].qty_produksi;
					var lantotal_unit = "=    "+output.data[0].total+" PCS";
					var isiunit = output.data[0].unit+"/MM";
		        	$('#isi').val(output.data[0].isi+" PCS");		
		        }
				if(output.data[0].unit == 'METER'){
		        	var total_unit = output.data[0].qty_produksi;
					var lantotal_unit = "=    "+output.data[0].total+" PCS";
					var isiunit = output.data[0].unit+"/METER";
		        	$('#isi').val(output.data[0].isi+" PCS");		
		        }
				if(output.data[0].unit == 'DUSH'){
		        	var total_unit = output.data[0].qty_produksi;
					var lantotal_unit = "=    "+output.data[0].total+" PCS";
					var isiunit = output.data[0].unit+"/DUS";
		        	$('#isi').val(output.data[0].isi+" PCS");		
		        }
				if(output.data[0].unit == 'BOTOL'){
		        	var total_unit = output.data[0].qty_produksi;
					var lantotal_unit = "=    "+output.data[0].total+" PCS";
					var isiunit = output.data[0].unit+"/BOTOL";
		        	$('#isi').val(output.data[0].isi+" PCS");		
		        }
				if(output.data[0].unit == 'UNIT'){
		        	var total_unit = output.data[0].qty_produksi;
					var lantotal_unit = "=    "+output.data[0].total+" PCS";
					var isiunit = output.data[0].unit+"/UNIT";
		        	$('#isi').val(output.data[0].isi+" PCS");		
		        }
				if(output.data[0].unit == 'ONS'){
		        	var total_unit = output.data[0].qty_produksi;
					var lantotal_unit = "=    "+output.data[0].total+" PCS";
					var isiunit = output.data[0].unit+"/ONS";
		        	$('#isi').val(output.data[0].isi+" PCS");		
		        }
				if(output.data[0].unit == 'KG'){
		        	var total_unit = output.data[0].qty_produksi;
					var lantotal_unit = "=    "+output.data[0].total+" PCS";
					var isiunit = output.data[0].unit+"/KG";
		        	$('#isi').val(output.data[0].isi+" PCS");		
		        }
				if(output.data[0].unit == 'LITER'){
		        	var total_unit = output.data[0].qty_produksi;
					var lantotal_unit = "=    "+output.data[0].total+" PCS";
					var isiunit = output.data[0].unit+"/LITER";
		        	$('#isi').val(output.data[0].isi+" PCS");		
		        }
	    		$('h2.FormTitle').text('PRATINJAU PRINT');
	        	$('#form_print .field_container label.error').hide();
	        	$('#form_print').attr('data-id', idx);
	        	$('#form_print').attr('class', 'form printProses');
	        	$('#tgl').val(output.data[0].spk_date);
	        	$('#pcus').val(output.data[0].po_customer);
	        	$('#custom').val(output.data[0].customer);
	        	$('#nospk').val(output.data[0].no_spk);
	        	$('#keterangan').val(output.data[0].annotation);
	        	$('#size_label').val(output.data[0].size_label);
	        	$('#bahan').val(output.data[0].bahan);
	        	$('#gulungan').val(output.data[0].gulungan);
	        	$('#kor').val(output.data[0].kor);
	        	$('#lins').val(output.data[0].line);
	        	$('#size_baku').val(output.data[0].size_baku);
	        	$('#qty_baku').val(output.data[0].qty_baku);
	        	$('#porporasi').val(output.data[0].porporasi);
	        	$('#qty_produksi').val(total_unit);
				$('#qty_produksi2').val(lantotal_unit);
				$('#unit').text(isiunit);
	    		$('#myModal2').show();
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
  	// Proses Print view submit
  	//////////////////////////

  	$(document).on('submit', '#form_print', function(e){
    	e.preventDefault();
	    if ($('#form_print').valid() == true){
	      	hide_ipad_keyboard();
	      	hide_lightbox();
	      	show_loading_message();
	      	var form_data 	= $('#form_print').serialize();
	      	$.ajax({
	        	url: 	pathFile+"?"+Act+"=print",
	        	cache:  false,
	        	data:   form_data,
	        	type: 'POST',
	        	success: function(respon){
	        		var obj = JSON.parse(respon);
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
	        			$(".qproduk").text(obj.data[0].qty_produksi + " " + satuanunit.value + " " + qty_produksi2.value);
						$(".isiuni").text(satuanunit.value);
	        			$(".is").text(obj.data[0].isi);
	        			$(".td").text(obj.data[0].ttd);

		        		$('.printnow').print({
		                    stylesheet : "../lib/css/bootstrap/bootstrap.min.css",
		                    globalStyles : false,
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
});