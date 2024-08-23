$(document).ready(function(){
	
	//////////////////////////
	//Default config
	/////////////////////////

	var idTablenya = $('#tablenya');
	var pathFile = decodeURIComponent(getCookie('base_url_api')) +':'+ getCookie('base_port_api') + decodeURIComponent(getCookie('base_path_api')) + decodeURIComponent(getCookie('base_dashboard_api'));
	var IDForm = "#form_print";

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

	function setCookie(cname, cvalue, exdays) {
	    var d = new Date();
	    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
	    var expires = "expires="+d.toUTCString();
	    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
	}

	var req = $.ajax({
		url: pathFile+"/sortdata/archive?data=po_date&from=preorder_customer",
		type: "GET",
		beforeSend: function (xhr) {
			xhr.setRequestHeader('Authorization', getCookie('access_token'));
			xhr.setRequestHeader('Content-Type', 'application/json');
		}
	});

	/////////////////////////////////////////////////////////////////
	// Load bank list from setting
	/////////////////////////////////////////////////////////////////
	var req = $.ajax({
		url: pathFile+"/setting/bank",
		type: "GET",
		beforeSend: function (xhr) {
			xhr.setRequestHeader('Authorization', getCookie('access_token'));
			xhr.setRequestHeader('Content-Type', 'application/json');
		}
	});

	req.done(function(output){
		if(output.status == "success"){
			for(var i = 0; i<output.response.data.length; i++){
				ex = output.response.data[i].detail.split('-')
				$("#pilihBANK").append("<option value='"+output.response.data[i].detail+"'>"+ex[0]+" - "+ex[1]+" - "+ex[2]+"</option>");
			}

		} else {
	        show_message('Failed: '+output.response.message, 'error');
		}
	});

	req.fail(function(jqXHR, textStatus){
		show_message('Failed: '+jqXHR.responseJSON.response.message, 'error');
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
		"serverSide": true,
		'scrollX': true,
		'scrollCollapse': true,
		'scrollY': '600px',
	    "ajax": {
			"url" : pathFile+"/invoice",
			data: {
				data: 'proccess',
				report: getCookie("report"),
				startdate: getCookie("startdate"),
				enddate: getCookie("enddate"),
			},
			"type": "GET",
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
				setCookie("report", '', 1);
			},
			"error": function (xhr, error, thrown) {
				console.error('Error fetching data:', xhr, error, thrown);
				alert('Terjadi kesalahan, silahkan login kembali.');
				window.location.href = '/auth/signout.php';
			}
		},
	    'columnDefs': [
	    	{
	    		'targets': [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19],
	            'className': 'dt-nowrap'
	        },
			{
                "targets": [9, 10, 11, 12, 13],
                "render": function(data, type, row) {		
                    return new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        minimumFractionDigits: 2
                    }).format(parseFloat(data));
                }
            },
			{
				"targets": 19,
				"data": null,
				"defaultContent": "",
				"render": function (data, type, row) {
					//return '<button class="btn btn-default function_edit" data-id="'+ data.id +'"><i class="fa fa-pencil"></i></button>';
					return '<button class="btn btn-default function_print" data-id="'+ data.invoiceid +'" title="Print"><i class="fa fa-print"></i></button> <button class="btn btn-default function_complete" data-id="'+ data.invoiceid +'" data-name="'+data.no_invoice+'" title="Paid"><i class="fa fa-check"></i></button> <button class="btn btn-default function_delete" data-id="'+ data.invoiceid +'" data-name="'+data.no_invoice+'" title="Delete"><i class="fa fa-trash"></i></button>';
				}
			}
	    ],
	    "columns": [
	      { "data": "invoice_date" },
	      { "data": "duration" },
	      { "data": "customer"},
	      { "data": "po_customer" },
	      { "data": "no_so" },
	      { "data": "no_sj"},
	      { "data": "no_invoice"},
	      { "data": "send_qty"},
	      { "data": "unit"},
	      { "data": "price"},
	      { "data": "bill"},
	      { "data": "ppn"},
	      { "data": "total"},
	      { "data": "cost"},
	      { "data": "ekspedisi"},
	      { "data": "uom"},
	      { "data": "jml"},
	      { "data": "print_by"},
	      { "data": "input_by"}
	    ],
	    "lengthMenu": [[10, 100, 1000, -1], [10, 100, 1000, "All"]],
	    iDisplayLength: 10,
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
            
            Bills = api.column( 10, { page: 'current'} ).data().reduce( function (a, b) {
            	return intVal(a) + intVal(b);
            }, 0 );

            Ppns = api.column( 11, { page: 'current'} ).data().reduce( function (a, b) {
            	return intVal(a) + intVal(b);
            }, 0 );

            Totals = Bills + Ppns;
            
            Ship_cost = api.column( 13, { page: 'current'} ).data().reduce( function (a, b) {
            	return intVal(a) + intVal(b);
            }, 0 );

            $( api.column( 10 ).footer() ).html(convertToconvertToRupiah(Bills));
            $( api.column( 11 ).footer() ).html(convertToconvertToRupiah(Ppns));
            $( api.column( 12 ).footer() ).html(convertToconvertToRupiah(Totals));
            $( api.column( 13 ).footer() ).html(convertToconvertToRupiah(Ship_cost));
        }
	});

	var buttons = new $.fn.dataTable.Buttons(tablenya, {
		buttons:[
        {
        	extend: 'excelHtml5',
        	messageTop: false,
        	footer: true,
        	text: 'Export to Excel',
        	filename : 'INVOICE-Procces',
        	title: 'Invoice Procces',
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

	function convertToconvertToRupiah(angka){
		let formatted = new Intl.NumberFormat('id-ID', {
			style: 'currency',
			currency: 'IDR',
			minimumFractionDigits: 2
		}).format(angka);
		return formatted;
	}

	// Lightbox close button
	$(document).on('click', '.lightbox_close', function(){
	    hide_lightbox();
	    hide_invoice();
		$('.looping_barang').empty();
		$('.suratjalan').empty();
		$('.tbody').empty();
		$('.delivery-orders-header').empty();
		$('#date').val('');
		$('#ket').val('');
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
		setCookie("report", report, 1);
		setCookie("startdate", startdate, 1);
		setCookie("enddate", enddate, 1);
		location.reload();
  	});

  	/////////////////////
  	// Print view button
	////////////////////

	$(document).on('click', '.function_print', function(e){
		e.preventDefault();
	    show_loading_message();
	    var id      = $(this).data('id');
	    var request = $.ajax({
	    	url:          pathFile+"/invoice/print/"+id,
			type:         'GET',
			beforeSend: function (xhr) {
				xhr.setRequestHeader('Authorization', getCookie('access_token'));
				xhr.setRequestHeader('Content-Type', 'application/json');
			}
	    });
	    request.done(function(output){
	    	if(output.status == "success") {
		    	$('H2.FormTitle').text('PRATINJAU PRINT');
	        	$(IDForm +' .field_container label.error').hide();
	        	$(IDForm +'').attr('data-id', id);
	        	$(IDForm +'').attr('class', 'form printProses');
	        	$('#no_faktur').val(output.response.data[0].no_invoice);
	        	$('#company').val(output.response.data[0].company);
	        	$('#address').val(output.response.data[0].address);
	        	$('#phone').val(output.response.data[0].phone);
	        	$('#tagihan').val(convertToRupiah(output.response.data[0].total));
	        	$('#s_cost').val(convertToRupiah(output.response.data[0].cost));
	        	$('#bill').val(output.response.data[0].tagihan);
	        	$('#biaya_kirim').val(output.response.data[0].cost);
	        	$('#customer').val(output.response.data[0].customer);
	        	$('#no_po').val(output.response.data[0].po_customer);
	        	$('#billto').val(output.response.data[0].billto);
	        	$('#shipto').val(output.response.data[0].shipto);
	        	$('#ship_name').val(output.response.data[0].sname);
	        	$('#telp').val(output.response.data[0].telp);
	        	$('#id_fk').val(output.response.data[0].id_fk);
	        	$('#tgl').val(output.response.data[0].invoice_date);
				arrppn = [];
	        	for(var i = 0; i<output.response.data.length; i++){
	        		$('.datanyanih').append(
  						'<div class="looping_barang"><hr><div class="form-group item"><label for="no_sj">Surat Jalan: <span class="required">*</span></label><input type="text" class="form-control" name="data[no_sj][]" id="no_sj" value="'+output.response.data[i].no_sj+'" required readonly></div><div class="form-group item"><label for="no_so">No SO: <span class="required">*</span></label><input type="text" class="form-control" name="data[no_so][]" id="no_so" value="'+output.response.data[i].no_so+'" required readonly></div><div class="form-group item"><label for="item">Nama Barang: <span class="required">*</span></label><input type="text" class="form-control" name="data[item][]" id="item" value="'+output.response.data[i].item+'" required readonly></div><div class="form-group qty"><label for="qty">Qty: <span class="required">*</span></label><input type="text" class="form-control" name="data[qty][]" id="qty" value="'+output.response.data[i].send_qty+'" required readonly></div><div class="form-group unit"><label for="unit">Satuan: <span class="required">*</span></label><input type="text" class="form-control" name="data[unit][]" id="unit" value="'+output.response.data[i].unit+'" required readonly></div><div class="form-group price" style="display:none"><label for="price">Price: <span class="required">*</span></label><input type="text" class="form-control" name="data[price][]" id="price" value="'+output.response.data[i].price+'" required readonly></div></div>'
  					);

					arrppn.push(parseFloat(output.response.data[i].ppn));
	        	}

				const totalppn = arrppn.reduce((partialSum, a) => partialSum + a, 0);
				$('#status_ppn').val(totalppn.toFixed(2));

	        	hide_loading_message();
	        	show_lightbox();
	      	} else {
	        	hide_loading_message();
	        	show_message('Failed: '+output.response.message, 'error');
	      	}
	    });
	    request.fail(function(jqXHR, textStatus){
	    	hide_loading_message();
			show_message('Failed: '+textStatus, 'error');
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
			var id = $(IDForm).attr('data-id');
			var formDataArray = $(this).serializeArray();
			var formDataObject = {
				items: []
			};

			var arr = [];
			var dataGroups = {};

			formDataArray.forEach(function(item) {
				if (item.name.startsWith("data[")) {
					var matches = item.name.match(/data\[([^\]]+)\]\[(\d*)\]/);
					if (matches) {
						let count = arr.reduce(function(accumulator, currentValue) {
							return currentValue === matches[1] ? accumulator + 1 : accumulator;
						}, 0);
	
						var fieldName = matches[1];
						var index = count;
		
						if (!dataGroups[index]) {
							dataGroups[index] = {};
						}
		
						if (fieldName === 'qty') {
							value = parseInt(item.value);
							fieldName = 'send_qty';

						} else if(fieldName === 'price') {
							value = parseFloat(item.value);
						} else {
							value = item.value;
						}

						dataGroups[index][fieldName] = value;
						arr.push(matches[1]);
					}
				} else {
					if (item.name === 'tgl') {
						formDataObject.invoice_date = item.value
					} else if (item.name === 'no_faktur') {
						formDataObject.no_invoice = item.value;
					} else if (item.name === 'no_po') {
						formDataObject.po_customer = item.value;
					} else {
						formDataObject[item.name] = item.value;
					}
				}
			});

			for (var key in dataGroups) {
				formDataObject.items.push(dataGroups[key]);
			}

			formDataObject.id = id;

			var request   = $.ajax({
				url:          pathFile+"/invoice/print",
				type:         'POST',
				data:         JSON.stringify(formDataObject, null, 2),
				beforeSend: function (xhr) {
					xhr.setRequestHeader('Authorization', getCookie('access_token'));
					xhr.setRequestHeader('Content-Type', 'application/json');
				},
				success: function(output){
	        		if(output.status == "success"){
	        			tablenya.ajax.reload(function(){
		        			hide_loading_message();
		        			$('#PrintModal').show();
		        			if(!!output.response.data[0].logo){
		        				$('.delivery-orders-header').append(
		        					'<div class="col-md-2 col-xs-2"><img src="'+output.response.data[0].logo+'" width="100px" height="50px" style="margin-top: 20px"></div><div class="col-md-7 col-xs-7"><h4 class="perusahaan" style="letter-spacing:2px;margin-bottom: 0px"><strong></strong></h4><p class="alamat" style="font-size:12px;letter-spacing:2px;margin-bottom:0px"></p><p style="font-size:12px;letter-spacing:2px;margin-bottom:0px"><span class="telpon"></span><span class="email"></span></p></div><div class="col-md-2 col-xs-2"><h4 class="text-right" style="letter-spacing:2px;margin-bottom:0px"><strong>INVOICE</strong></h4><h5 class="no-invoice text-right"></h5></div>'
		        				);

		        			} else {
		        				$('.delivery-orders-header').append(
		        					'<div class="col-md-6 col-xs-6"><h4 class="perusahaan" style="letter-spacing:2px;margin-bottom: 0px"><strong></strong></h4><p class="alamat" style="font-size:12px;letter-spacing:2px;margin-bottom: 0px"></p><p style="font-size:12px;letter-spacing:2px;margin-bottom: 0px"><span class="telpon"></span><span class="email"></span></p></div><div class="col-md-6 col-xs-6"><h4 class="text-right" style="letter-spacing:2px;margin-bottom: 0px">INVOICE</h4><h5 class="no-invoice text-right"></h5></div>'
		        				);
		        			}
		        			$('.perusahaan strong').text(output.response.data[0].company);
		        			$('.alamat').text(output.response.data[0].address);
		        			$('.telpon').text('Telp : '+output.response.data[0].phone);
		        			$('.no-invoice').text('No. '+output.response.data[0].no_invoice);
		        			$('.email').text('. '+output.response.data[0].email);
		        			$('.bill_nama').text(output.response.data[0].customer);
		        			$('.bill_alamat').text(output.response.data[0].billto);
		        			$('.ship_nama').text(output.response.data[0].ship_name);
		        			$('.ship_alamat').text(output.response.data[0].shipto);
		        			$('.invoice_date').text(output.response.data[0].invoice_date);
		        			$('.po_customer').text(output.response.data[0].po_customer);
		        			$('.payment_due').text(output.response.data[0].duration);
		        			$('.rekening').text(output.response.data[0].rek);
		        			$('.atasnama').text(output.response.data[0].an);
		        			$('.namabank').text(output.response.data[0].bank);
		        			$('.subtotal').text(convertToRupiah(output.response.data[0].subtotal));
		        			$('.jumlah').text(convertToRupiah(output.response.data[0].total));
		        			$('.vat').text(convertToRupiah(output.response.data[0].ppn));
		        			$('.ttd_person').text(output.response.data[0].ttd);

		        			if(parseInt(output.response.data[0].ongkoskir) > 0 ){
		        				$('.line_cost').show();
		        				$('.label_cost').show();
		        				$('.cost').show();
		        				$('.cost').text(convertToRupiah(output.response.data[0].ongkoskir));
		        			} else {
		        				$('.line_cost').hide();
		        				$('.label_cost').hide();
		        				$('.cost').hide();
		        			}

		        			arrsj = [];
							for(var x = 0; x < output.response.data[0].items.length; x++)
		        			{
								no = x + 1;
		        				if(!!output.response.data[0].items[x].no_sj.length && !arrsj.includes(output.response.data[0].items[x].no_sj)) {
									$('.suratjalan').append(' - '+output.response.data[0].items[x].no_sj);
									arrsj.push(output.response.data[0].items[x].no_sj);
								}

		        				$('.tbody').append(
		        					'<tr><td class="text-center">'+no+'</td><td>'+output.response.data[0].items[x].item+'</td><td class="text-center">'+output.response.data[0].items[x].no_so+'</td><td class="text-center">'+output.response.data[0].items[x].send_qty+'</td><td class="text-center">'+output.response.data[0].items[x].unit+'</td><td class="text-center">'+convertToRupiah(parseFloat(output.response.data[0].items[x].price))+'</td><td class="text-right">'+convertToRupiah(parseInt(output.response.data[0].items[x].send_qty) * parseFloat(output.response.data[0].items[x].price))+'</td></tr>'
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
	      				show_message('Failed: '+output.response.message, 'error');
					}
	        	},
				error: function(jqXHR, textStatus, errorThrown){
					show_message('Failed: '+jqXHR.responseJSON.response.message, 'error');
				}
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

	$(document).on('click', '.function_complete', function(e){
	    e.preventDefault();
	    var id = $(this).data('id');
	    var name = $(this).data('name');
	    if(confirm("Anda yakin ingin memproses '"+name+"'?")){
	    	show_invoice();
	    	$('#form_inputINV').attr('data-id', id);
	    }
  	});

  	$(document).on('submit', '#form_inputINV.lunas', function(e){
  		e.preventDefault();
  		$('#form_inputINV').validate();
	    if ($('#form_inputINV').valid() == true){
	    	hide_invoice();
	    	show_loading_message();
			var jsonData = {};
	    	var id = $(this).data('id');
	    	var form_data 	= $('#form_inputINV').serializeArray();
			$.each(form_data, function(){
				if(this.name == 'ket') {
					jsonData.note = this.value;
				} else {
					jsonData[this.name] = this.value;
				}
			});

			jsonData.id = id.toString();

			var request   = $.ajax({
				url:          pathFile+"/invoice/paid",
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
	            		hide_loading_message();
	            		show_message("Successfully.", 'success');
	            		$('#date').val('');
	            		$('#ket').val('');
	          		}, true);
	        	} else {
	          		hide_loading_message();
	          		show_message('Failed: '+output.response.message, 'error');
	       		}
	      	});
	      	
	      	request.fail(function(jqXHR, textStatus){
	        	hide_loading_message();
	        	show_message('Failed: '+textStatus, 'error');
	      	});
	    }
  	});

  	////////////////////////////////////////
  	// Delete invoice button
  	//////////////////////////////////////

  	$(document).on('click', '.function_delete', function(e){
	    e.preventDefault();
	    var Infos = $(this).data('name');
	    if (confirm("Anda yakin ingin menghapus '"+Infos+"'?")){
	    	show_loading_message();
	      	var id      = $(this).data('id');
	      	var request = $.ajax({
				url:          pathFile+"/invoice/"+id,
				type:         'DELETE',
				beforeSend: function (xhr) {
					xhr.setRequestHeader('Authorization', getCookie('access_token'));
					xhr.setRequestHeader('Content-Type', 'application/json');
				}
			});
	      	
	      	request.done(function(output){
	        	if(output.status == "success"){
					tablenya.ajax.reload(function(){
	            		hide_loading_message();
	            		show_message("'"+Infos+"' berhasil dihapus.", 'success');
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