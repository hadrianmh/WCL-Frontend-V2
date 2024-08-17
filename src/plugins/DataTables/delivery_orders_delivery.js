$(document).ready(function(){

	//////////////////////////
	//Default config
	/////////////////////////

	var idTablenya = $('#tablenya');
	var pathFile = decodeURIComponent(getCookie('base_url_api')) +':'+ getCookie('base_port_api') + decodeURIComponent(getCookie('base_path_api')) + decodeURIComponent(getCookie('base_dashboard_api'));
	var Act = 'action';
	var sLug = 'delivery_orders_delivery';
	var IDForm = "#form_inputSJ";
	var sukses = 'success'; //Message alert

	/////////////////////////////////////////////////////////////////
	// Set cookie as 'SelectMonth'
	/////////////////////////////////////////////////////////////////

	var mm = ("0" + (new Date().getMonth() + 1)).slice(-2);
	var yyyy = new Date().getFullYear();
	var startdate = yyyy+"/"+mm;
	var report = 'month';

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
		url: pathFile+"/sortdata/archive?data=sj_date&from=delivery_orders_customer",
		type: "GET",
		beforeSend: function (xhr) {
			xhr.setRequestHeader('Authorization', getCookie('access_token'));
			xhr.setRequestHeader('Content-Type', 'application/json');
		}
	});

	req.done(function(output){
		if(output.status == "success"){
			for(var i=0; i<output.response.data[0].year.length; i++) {
				$("#sortby").append("<option value='"+output.response.data[0].year[i]+"' data-name='year' "+(getCookie("startdate") == output.response.data[0].year[i] ? 'selected' : '')+" >Tahun: "+output.response.data[0].year[i]+"</option>");
			}
			
			for(var i = 0; i<output.response.data[0].month.length; i++){
				$("#sortby").append("<option value='"+output.response.data[0].month[i]+"' data-name='month' "+(getCookie("startdate") == output.response.data[0].month[i] ? 'selected' : '')+" >Bulan: "+output.response.data[0].month[i]+"</option>");
			}
			setCookie("report", report, 1);
			setCookie("startdate", startdate, 1);

		} else {
	        show_message('Failed: sort data fetching.', 'error');
		}
	});

	req.fail(function(jqXHR, textStatus){
		show_message('Failed: '+jqXHR.responseJSON.response.message, 'error');
	});

	$(document).on('change', '#sortby', function(){
		report = $(this).attr('name');
		startdate = $(this).find(":selected").val();
		setCookie("report", report, 1);
		setCookie("startdate", startdate, 1);
	});

	$(document).on('click', '#LoadData', function(){
		report = $('#sortby').find(":selected").attr('data-name');
		startdate = $('#sortby').find(":selected").val();
		setCookie("report", report, 1);
		setCookie("startdate", startdate, 1);
		location.reload();
	});

	///////////////////////////
	// On page load: datatable
	///////////////////////////

	var tablenya = idTablenya.DataTable({
		initComplete : function() {
			var input = $('.dataTables_filter input').unbind(),
			self = this.api(),
			$searchButton = $(`<button class="btn btn-default"><i class="fa fa-search"></i></button>`).click(function(){ self.search(input.val()).draw(); });
			$resetButton = $(`<button class="btn btn-default"><i class="fa fa-times"></i></button>`).click(function() { input.val('');$searchButton.click(); }); 
			$('.dataTables_filter').append($searchButton, $resetButton);
		},
		"serverSide" : true,
		"scrollX": true,
		"ajax": {
			"url" : pathFile+"/delivery-order",
			"type": "GET",
			"data": {
				report : getCookie("report"),
				startdate: getCookie("startdate"),
				enddate: getCookie("enddate")
			},
			"dataFilter": function(data) {
				var obj = JSON.parse(data);
				obj.data = obj.response.datatables;
				obj.recordsTotal = obj.response.recordsTotal;
				obj.recordsFiltered = obj.response.recordsFiltered;
				return JSON.stringify( obj );
			},
			"dataSrc": function (json) {
				if(json.code == 200) {
					return json.response.datatables;
				} else {
					console.error('Error fetching data:', json);
					return [];
				}
            },
			"beforeSend": function (xhr) {
				xhr.setRequestHeader('Authorization', getCookie('access_token'));
				xhr.setRequestHeader('Content-Type', 'application/json');
			},
			"error": function (xhr, error, thrown) {
				console.error('Error fetching data:', xhr, error, thrown);
				alert('Terjadi kesalahan, silahkan login kembali.');
				// window.location.href = '/auth/signout.php';
			}
		},
	    'columnDefs': [
	    	{
	    		'targets': [0,1,2,3,4,6,7,8,9,10,11,12,13],
	            'className': 'dt-nowrap'
	        },
			{
				"targets": 13,
				"data": null,
				"defaultContent": "",
				"render": function (data, type, row) {
					//return '<button class="btn btn-default function_edit" data-id="'+ data.id +'"><i class="fa fa-pencil"></i></button>';
					return '<button class="btn btn-default function_print" data-id="'+ data.id +'" title="Print"><i class="fa fa-print"></i></button> <button class="btn btn-default function_delete" data-id="'+ data.id +'" data-name="'+data.item+'" title="Delete"><i class="fa fa-trash"></i></button>';
				}
			}
	    ],
	    "columns": [
	      { "data": "sj_date"},
	      { "data": "no_sj"},
	      { "data": "customer"},
	      { "data": "po_customer"},
	      { "data": "no_spk"},
	      { "data": "shipto"},
	      { "data": "item"},
	      { "data": "send_qty"},
	      { "data": "unit"},
	      { "data": "courier"},
	      { "data": "resi"},
	      { "data": "cost"},
	      { "data": "username"},
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

	var buttons = new $.fn.dataTable.Buttons(tablenya, {
		buttons:[
        {
        	extend: 'excelHtml5',
        	messageTop: false,
        	footer: true,
        	text: 'Export to Excel',
        	filename : 'DO-Delivery_'+getCookie("report"),
        	title: 'Delivery Order Delivery '+getCookie("report"),
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
		report = $("#report").val();
		startdate = $("#startdate").val();
		enddate = $("#enddate").val();
      	var request = $.ajax({
	    	url:          pathFile+"/delivery-order",
			type:         'GET',
			data: {
				report : getCookie("report"),
				startdate: getCookie("startdate"),
				enddate: getCookie("enddate")
			},
			beforeSend: function (xhr) {
				xhr.setRequestHeader('Authorization', getCookie('access_token'));
				xhr.setRequestHeader('Content-Type', 'application/json');
			}
	    });

      	request.done(function(output){
	    	if(output.status == "success"){
        		setCookie("report", report, 1);
				setCookie("startdate", startdate, 1);
				setCookie("enddate", enddate, 1);
        		location.reload();

	    	} else {
	      		hide_loading_message();
	      		show_message('Failed: '+output.response.message, 'error');
	    	}
	  	});

	  	request.fail(function(jqXHR, textStatus){
	    	hide_loading_message();
	    	show_message('Failed: '+output.response.message, 'error');
	  	});
  	});

	/////////////////////////////////////////
  	// Print view button
	////////////////////////////////////////

	$(document).on('click', '.function_print', function(e){
		e.preventDefault();
	    show_loading_message();
	    var id      = $(this).data('id');
	    var request = $.ajax({
	    	url:          pathFile+"/delivery-order/printview/"+id,
			type:         'GET',
			beforeSend: function (xhr) {
				xhr.setRequestHeader('Authorization', getCookie('access_token'));
				xhr.setRequestHeader('Content-Type', 'application/json');
			}
	    });
	    request.done(function(output){
	    	if(output.status == "success"){
	    		$('#myModal2').show();
	    		$('h2.FormTitle').text('PRATINJAU PRINT');
	        	$('#form_print .field_container label.error').hide();
	        	$('#form_print').attr('data-id', id);
	        	$('#form_print').attr('class', 'form printProses');
	        	$('#sj_date').val(output.response.data[0].sj_date);
	        	$('#no_po_pratinjau').val(output.response.data[0].po_customer);
	        	$('#no_delivery').val(output.response.data[0].no_sj);
	        	$('#custom').val(output.response.data[0].customer);
	        	$('#shipto').val(output.response.data[0].shipto);
				$('#telp').val(output.response.data[0].telp);
	        	var no = 0;
	        	for(var i = 0; i<output.response.data.length; i++){
	        		no++;
	        		$('.itemnya').append(
	        			'<div class="looping-item"><div class="form-group"><label for="item">Nama Barang '+no+' : <span class="required">*</span></label><input type="text" class="form-control" name="data[item][]" id="item" value="'+output.response.data[i].item+'" required readonly></div><div class="form-group"><label for="qty">Qty: <span class="required">*</span></label><input type="text" class="form-control" name="data[qty][]" id="qty" value="'+output.response.data[i].qty+' '+output.response.data[i].unit+'" required readonly></div>'
	        			);
	        	}

	        	hide_loading_message();
	      	} else {
	        	hide_loading_message();
	        	show_message('Failed: '+output.response.message, 'error');
	      	}
	    });
	    request.fail(function(jqXHR, textStatus){
	    	hide_loading_message();
			show_message('Failed: '+jqXHR.responseJSON.response.message, 'error');
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
	      	var id = $('#form_print').attr('data-id');
	      	$.ajax({
	        	url:	pathFile+"/delivery-order/printnow/"+id,
				type:	'GET',
				beforeSend: function (xhr) {
					xhr.setRequestHeader('Authorization', getCookie('access_token'));
					xhr.setRequestHeader('Content-Type', 'application/json');
				},
	        	success: function(output){
	        		if(output.status == "success"){
	        			hide_loading_message();
	        			$('#PrintModal').show();
	        			if(!!output.response.data[0].logo){
	        				$('.delivery-orders-title').append(
	        					'<div class="col-md-2 col-xs-2"><img src="'+output.response.data[0].logo+'" width="100px" height="50px" style="margin-top: 20px"></div><div class="col-md-10 col-xs-10"><h4 class="company" style="letter-spacing:2px;margin-bottom: 0px"><strong></strong></h4><p class="address" style="letter-spacing:2px;margin-bottom: 0px"></p><p class="telp" style="letter-spacing:2px;margin-bottom: 0px"></p></div>'
	        				);

	        			} else {
	        				$('.delivery-orders-title').append(
	        					'<div class="col-md-12 col-xs-12"><h4 class="company" style="letter-spacing:2px;margin-bottom: 0px"><strong></strong></h4><p class="address" style="letter-spacing:2px;margin-bottom: 0px"></p><p class="telp" style="letter-spacing:2px;margin-bottom: 0px"></p></div>'
	        				);
	        			}
	        			$('.company strong').text(output.response.data[0].company);
	        			$('.address').text(output.response.data[0].address);
	        			$('.telp').text('Telp : '+output.response.data[0].phone);
	        			$('.bill').text(output.response.data[0].customer);
	        			$('.tgl').text(output.response.data[0].sj_date);
	        			$('.ship').text(output.response.data[0].shipto);
	        			$('.nosj').text(output.response.data[0].no_sj);
	        			for(var i = 0; i<output.response.data.length; i++){
							no = i + 1;
		        			$('.tbody').parent().append(
		        				'<tr><td class="text-center">'+no+'</td><td class="text-center">'+output.response.data[i].no_so+'</td><td class="text-center">'+output.response.data[i].po_customer+'</td><td>'+output.response.data[i].item+'</td><td class="text-center">'+output.response.data[i].qty+'</td><td class="text-center">'+output.response.data[i].unit+'</td></tr>'
		        			);
		        		}
	        			
	        			$('.ttd').append('<strong>Name : </strong>'+output.response.data[0].ttd);
	        			$('.ttd_date').append('<strong>Date : </strong>'+output.response.data[0].sj_date);

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
						show_message('Failed: '+output.response.message, 'error');
	        		}
	        	},
		        error: function(jqXHR, textStatus, errorThrown){
					show_message('Failed: '+jqXHR.responseJSON.response.message, 'error');
		        }
	      	});
	    }
  	});

  	////////////////////
  	// Delete button
  	//////////////////

  	$(document).on('click', '.function_delete', function(e){
	    e.preventDefault();
	    var Infos = $(this).data('name');
	    if(confirm("Anda yakin ingin menghapus '"+Infos+"'?")){
	    	show_loading_message();
	      	var id      = $(this).data('id');
	      	var request = $.ajax({
	        	url:	pathFile+"/delivery-order/"+id,
				type:	'DELETE',
				beforeSend: function (xhr) {
					xhr.setRequestHeader('Authorization', getCookie('access_token'));
					xhr.setRequestHeader('Content-Type', 'application/json');
				},
	      	});
	      	
	      	request.done(function(output){
	        	if(output.status == "success"){
	          		tablenya.ajax.reload(function(){
	            		hide_loading_message();
	            		show_message("'"+Infos+"' deleted successfully.", 'success');
						setCookie("report", 'year', 1);
	          		}, true);
	        	} else {
	          		hide_loading_message();
	          		show_message('Failed: '+output.response.message, 'error');
	       		}
	      	});
	      	
	      	request.fail(function(jqXHR, textStatus){
	        	hide_loading_message();
	        	show_message('Failed: '+jqXHR.responseJSON.response.message, 'error');
	      	});
	    }
  	});
});