$(document).ready(function(){

	//////////////////////////
	//Default config
	/////////////////////////

	var idTablenya = $('#tablenya');
	var pathFile = decodeURIComponent(getCookie('base_url_api')) +':'+ getCookie('base_port_api') + decodeURIComponent(getCookie('base_path_api')) + decodeURIComponent(getCookie('base_dashboard_api'));
	var Act = 'action';
	var sLug = 'workorder';
	var FormsLug = 'SPK';
	var IDForm = "#form_inputWO";
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
		url: pathFile+"/sortdata/archive?data=po_date&from=preorder_customer",
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

	$(document).on('change', '#sortby', function(e){
		e.preventDefault();
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
			$searchButton = $(`<a class="btn btn-default"><i class="fa fa-search"></i></a>`).click(function(){ self.search(input.val()).draw(); });
			$resetButton = $(`<a class="btn btn-default"><i class="fa fa-times"></i></a>`).click(function() { input.val('');$searchButton.click(); }); 
			$('.dataTables_filter').append($searchButton, $resetButton);
		},
		"serverSide" : true,
		"scrollX": true,
		'scrollCollapse': true,
		'scrollY': '600px',
	    "ajax": {
			"url" : pathFile+"/workorder",
			"type": "GET",
			data: {
				report : getCookie("report"),
				startdate: getCookie("startdate"),
				enddate: getCookie("enddate")
			},
			"dataFilter": function(data) {
				var obj = JSON.parse(data);
				obj.data = obj.response.data;
				obj.recordsTotal = obj.response.recordsTotal;
				obj.recordsFiltered = obj.response.recordsFiltered;
				return JSON.stringify( obj );
			},
			"dataSrc": function (json) {
				if(json.code == 200) {
					return json.response.data;
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
				window.location.href = '/auth/signout.php';
			}
		},
		'columnDefs': [
			{
	    		'targets': [0,1,2,3,4,6,8,9,10,11,12,13,14,16,17,18,19,20,21],
	            'className': 'dt-nowrap'
	        },
			{
				"targets": 21,
				"data": null,
				"defaultContent": "",
				"render": function (data, type, row) {
					return '<button class="btn btn-default WoProses" data-id="'+ data.customerid +'-'+data.sequence_item+'" title="Proses"><i class="fa fa-share"></i></button> <button class="btn btn-default WoPrint" data-id="'+ data.customerid +'-'+data.sequence_item+'" title="Print"><i class="fa fa-print"></i></button>';
				}
			}
		],
	    "columns": [
	      {"data": "spk_date"},
	      {"data": "duration" },
	      {"data": "customer"},
	      {"data": "po_customer" },
	      {"data": "no_spk"},
	      {"data" : "item"},
	      {"data" : "size"},
	      {"data" : "qore"},
	      {"data" : "lin"},
	      {"data" : "roll"},
	      {"data" : "ingredient"},
	      {"data" : "porporasi"},
	      {"data" : "qty"},
	      {"data" : "unit"},
	      {"data" : "volume"},
	      {"data" : "annotation"},
	      {"data" : "uk_bahan_baku"},
	      {"data" : "qty_bahan_baku"},
	      {"data" : "sources"},
	      {"data": "orderstatus"},
	      {"data": "input_by"},
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
      	report = $("#report").val();
		startdate = $("#startdate").val();
		enddate = $("#enddate").val();
      	var request = $.ajax({
	    	url:          pathFile+"/workorder",
			type:         'GET',
			data:         'report='+report+'&startdate='+startdate+'&enddate='+enddate,
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
	    	show_message('Failed: '+jqXHR.responseJSON.response.message, 'error');
	  	});
  	});

	/////////////////////
  	// Edit button
	////////////////////

	$(document).on('click', '.WoProses', function(e){
		e.preventDefault();
	    show_loading_message();
	    var idx		= $(this).data('id');
	    var ex 		= idx.split('-');
	    var request = $.ajax({
	    	url:          pathFile+"/workorder/process/"+ex[0]+"/"+ex[1],
			type:         'GET',
			beforeSend: function (xhr) {
				xhr.setRequestHeader('Authorization', getCookie('access_token'));
				xhr.setRequestHeader('Content-Type', 'application/json');
			}
	    });

	    request.done(function(output){
	    	if(output.status == "success"){
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
	        	$("#po_date").val(output.response.data[0].po_date);
	        	$("#customer").val(output.response.data[0].customer);
	        	$("#po_customer").val(output.response.data[0].po_customer);
	        	$("#no_spk").val(output.response.data[0].no_spk);
	        	$("#spk_date").val(output.response.data[0].spk_date);
	        	$("#order_status").val(output.response.data[0].order_status);
	        	hide_loading_message();
	        	show_lightbox();
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
			var form_data 	= $(IDForm).serializeArray();

			var jsonData = {};
			$.each(form_data, function(){
				if(this.name == 'order_status') {
					value = parseInt(this.value)
				} else {
					value = this.value
				}
				jsonData[this.name] = value;
			});
			
			jsonData.id = parseInt(ex[0]);
			jsonData.item_to = parseInt(ex[1]);

			var request   = $.ajax({
				url:          pathFile+"/workorder",
				type:         'POST',
				data:         JSON.stringify(jsonData),
				beforeSend: function (xhr) {
					xhr.setRequestHeader('Authorization', getCookie('access_token'));
					xhr.setRequestHeader('Content-Type', 'application/json');
				}
     		});

      		request.done(function(output){
        		if(output.status == "success"){
          			tablenya.ajax.reload(function(){
          				show_all_elemen();
            			hide_loading_message();
            			var Infos = $('#customer').val();
            			show_message("'"+Infos+"' update successfully.", 'success');
            			show_all_elemen();
          			}, true);
        		} else {
          			hide_loading_message();
          			show_all_elemen();
          			show_message('Failed: '+output.response.message, 'error');
        		}
      		});
     		request.fail(function(jqXHR, textStatus){
        		hide_loading_message();
        		show_all_elemen();
        		show_message('Failed: '+jqXHR.responseJSON.response.message, 'error');
      		});
    	}
  	});

  	/////////////////////
  	// Print view button
	////////////////////

	$(document).on('click', '.WoPrint', function(e){
		e.preventDefault();
	    show_loading_message();
	    var idx		= $(this).data('id');
	    var ex 		= idx.split('-');
	    var request = $.ajax({
	    	url:          pathFile+"/workorder/print/"+ex[0]+"/"+ex[1],
			type:         'GET',
			beforeSend: function (xhr) {
				xhr.setRequestHeader('Authorization', getCookie('access_token'));
				xhr.setRequestHeader('Content-Type', 'application/json');
			}
	    });
	    request.done(function(output){
	    	if(output.status == "success"){
	    		if(output.response.data[0].unit == 'PCS'){
	    			var total_unit = output.response.data[0].ttl;
					var lantotal_unit = "=    "+output.response.data[0].qty_produksi+" PCS";
					var isiunit = output.response.data[0].unit+"/ROLL";
	    			$('#isi').val(output.response.data[0].isi+" PCS");
	    		}
		        if(output.response.data[0].unit == 'ROLL'){
		        	var total_unit = output.response.data[0].qty_produksi;
					var lantotal_unit = "=    "+output.response.data[0].ttl+" PCS";
					var isiunit = output.response.data[0].unit+"/ROLL";
		        	$('#isi').val(output.response.data[0].isi+" PCS");		
		        }
				if(output.response.data[0].unit == 'PAK'){
		        	var total_unit = output.response.data[0].qty_produksi;
					var lantotal_unit = "=    "+output.response.data[0].ttl+" PCS";
					var isiunit = output.response.data[0].unit+"/PAK";
		        	$('#isi').val(output.response.data[0].isi+" PCS");		
		        }
				if(output.response.data[0].unit == 'CM'){
		        	var total_unit = output.response.data[0].qty_produksi;
					var lantotal_unit = "=    "+output.response.data[0].ttl+" PCS";
					var isiunit = output.response.data[0].unit+"/CM";
		        	$('#isi').val(output.response.data[0].isi+" PCS");		
		        }
				if(output.response.data[0].unit == 'MM'){
		        	var total_unit = output.response.data[0].qty_produksi;
					var lantotal_unit = "=    "+output.response.data[0].ttl+" PCS";
					var isiunit = output.response.data[0].unit+"/MM";
		        	$('#isi').val(output.response.data[0].isi+" PCS");		
		        }
				if(output.response.data[0].unit == 'METER'){
		        	var total_unit = output.response.data[0].qty_produksi;
					var lantotal_unit = "=    "+output.response.data[0].ttl+" PCS";
					var isiunit = output.response.data[0].unit+"/METER";
		        	$('#isi').val(output.response.data[0].isi+" PCS");		
		        }
				if(output.response.data[0].unit == 'DUSH'){
		        	var total_unit = output.response.data[0].qty_produksi;
					var lantotal_unit = "=    "+output.response.data[0].ttl+" PCS";
					var isiunit = output.response.data[0].unit+"/DUS";
		        	$('#isi').val(output.response.data[0].isi+" PCS");		
		        }
				if(output.response.data[0].unit == 'BOTOL'){
		        	var total_unit = output.response.data[0].qty_produksi;
					var lantotal_unit = "=    "+output.response.data[0].ttl+" PCS";
					var isiunit = output.response.data[0].unit+"/BOTOL";
		        	$('#isi').val(output.response.data[0].isi+" PCS");		
		        }
				if(output.response.data[0].unit == 'UNIT'){
		        	var total_unit = output.response.data[0].qty_produksi;
					var lantotal_unit = "=    "+output.response.data[0].ttl+" PCS";
					var isiunit = output.response.data[0].unit+"/UNIT";
		        	$('#isi').val(output.response.data[0].isi+" PCS");		
		        }
				if(output.response.data[0].unit == 'ONS'){
		        	var total_unit = output.response.data[0].qty_produksi;
					var lantotal_unit = "=    "+output.response.data[0].ttl+" PCS";
					var isiunit = output.response.data[0].unit+"/ONS";
		        	$('#isi').val(output.response.data[0].isi+" PCS");		
		        }
				if(output.response.data[0].unit == 'KG'){
		        	var total_unit = output.response.data[0].qty_produksi;
					var lantotal_unit = "=    "+output.response.data[0].ttl+" PCS";
					var isiunit = output.response.data[0].unit+"/KG";
		        	$('#isi').val(output.response.data[0].isi+" PCS");		
		        }
				if(output.response.data[0].unit == 'LITER'){
		        	var total_unit = output.response.data[0].qty_produksi;
					var lantotal_unit = "=    "+output.response.data[0].ttl+" PCS";
					var isiunit = output.response.data[0].unit+"/LITER";
		        	$('#isi').val(output.response.data[0].isi+" PCS");		
		        }
	    		$('h2.FormTitle').text('PRATINJAU PRINT');
	        	$('#form_print .field_container label.error').hide();
	        	$('#form_print').attr('data-id', idx);
	        	$('#form_print').attr('class', 'form printProses');
	        	$('#tgl').val(output.response.data[0].spk_date);
	        	$('#pcus').val(output.response.data[0].po_customer);
	        	$('#custom').val(output.response.data[0].customer);
	        	$('#nospk').val(output.response.data[0].no_spk);
	        	$('#keterangan').val(output.response.data[0].annotation);
	        	$('#size_label').val(output.response.data[0].size);
	        	$('#bahan').val(output.response.data[0].ingredient);
	        	$('#gulungan').val(output.response.data[0].roll);
	        	$('#kor').val(output.response.data[0].qore);
	        	$('#lins').val(output.response.data[0].lin);
	        	$('#size_baku').val(output.response.data[0].qty_bahan_baku);
	        	$('#qty_baku').val(output.response.data[0].qty_bahan_baku);
	        	$('#porporasi').val(output.response.data[0].porporasi);
	        	$('#qty_produksi').val(total_unit);
				$('#qty_produksi2').val(lantotal_unit);
				$('#unit').text(isiunit);
	    		$('#myModal2').show();
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
  	// Proses Print view submit
  	//////////////////////////

  	$(document).on('submit', '#form_print', function(e){
    	e.preventDefault();
	    if ($('#form_print').valid() == true){
	      	hide_ipad_keyboard();
	      	hide_lightbox();
	      	show_loading_message();
			var id		= $('#form_print').attr('data-id');
			var form_data 	= $('#form_print').serializeArray();

			var jsonData = {};
			$.each(form_data, function(){
				if(this.name == 'isi' || this.name == 'qty_produksi') {
					value = parseInt(this.value)
				} else {
					value = this.value
				}
				jsonData[this.name] = value;
			});
			
			// jsonData.id = parseInt(ex[0]);
			// jsonData.item_to = parseInt(ex[1]);
			  
	      	$.ajax({
	        	url:	pathFile+"/workorder/print",
				type:	'POST',
				data:	JSON.stringify(jsonData),
				beforeSend: function (xhr) {
					xhr.setRequestHeader('Authorization', getCookie('access_token'));
					xhr.setRequestHeader('Content-Type', 'application/json');
				},
	        	success: function(output){
	        		if(output.status == "success"){
	        			hide_loading_message();
	        			$('#PrintModal').show();
	        			$(".spkdate").text(output.response.data[0].spk_date);
	        			$(".pcus").text(output.response.data[0].po_customer);
	        			$(".cus").text(output.response.data[0].customer);
	        			$(".spk").text(output.response.data[0].no_spk);
	        			$(".ann").text(output.response.data[0].annotation);
	        			$(".slabel").text(output.response.data[0].size);
	        			$(".sbaku").text(output.response.data[0].uk_bahan_baku);
	        			$(".bah").text(output.response.data[0].ingredient);
	        			$(".gul").text(output.response.data[0].roll);
	        			$(".kore").text(output.response.data[0].qore);
	        			$(".lie").text(output.response.data[0].lin);
	        			$(".por").text(output.response.data[0].porporasi);
	        			$(".qbaku").text(output.response.data[0].qty_bahan_baku);
	        			$(".qproduk").text(output.response.data[0].qty_produksi + " " + satuanunit.value + " " + qty_produksi2.value);
						$(".isiuni").text(satuanunit.value);
	        			$(".is").text(output.response.data[0].isi);
	        			$(".td").text(output.response.data[0].ttd);

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
						show_message('Failed: '+output.response.message, 'error');
	        		}
	        	},
		        error: function(jqXHR, textStatus, errorThrown){
		            show_message('Failed: '+jqXHR.responseJSON.response.message, 'error');
		        }
	      	});
	    }
  	});
});