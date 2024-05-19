$(document).ready(function(){

	//////////////////////////
	//Default config
	/////////////////////////

	var idTablenya = $('#tablenya');
	var pathFile = '../auth/bahan_baku.php';
	var Act = 'action';
	var sLug = 'bahan_baku';
	var FormsLug = 'BAHAN BAKU';
	var IDForm = "#form_inputRIBBON";
	var addButton = "#add_inputRIBBON";
	var editButton = ".function_edit a";
	var insButton = ".function_ins a";
	var outsButton = ".function_outs a";
	var dataName = "type";

	//Message alert
	var sukses = 'success';

	/////////////////////////////////////////////////////////////////
	// Set cookie as 'SelectMonth'
	/////////////////////////////////////////////////////////////////

	var mm = new Date().getMonth()+1;
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

	///////////////////////////
	// On page load: datatable
	///////////////////////////

	var tablenya = idTablenya.dataTable({
		'scrollX': true,
	    "ajax": pathFile+"?"+Act+"=result_"+sLug,
	    "columns": [
	      { "data": "no" },
	      { "data": "ukuran"},
	      { "data": "bahan"},
	      { "data": "warna"},
	      { "data": "ket"},
	      { "data": "satuan"},
	      { "data": "stock"},
	      { "data": "input"},
	      { "data": "functions","sClass": "functions" }
	    ],
	    "aoColumnDefs": [
	      { "bSortable": false, "aTargets": [-1] }
	    ],
	    "lengthMenu": [[10, -1], [10, "All"]],
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
	}

	function reset(){
		$('.date').hide();
		$('.customer').hide();
		$('.nopo').hide();
		$('.ukuran').hide();
		$('.size').show();
		$('.ingredient').show();
		$('.color').show();
		$('.stock').show();
		$('.s_masuk').hide();
		$('.s_keluar').hide();
		$('#date').val('');
		$('#customer').val('');
		$('#nopo').val('');
		$('#ukuran').val('');
		$('#size').val('');
		$('#ingredient').val('');
		$('#color').val('');
		$('#note').val('');
		$('#unit').val('');
		$('#stock').val('');
		$('#s_masuk').val('');
		$('#s_keluar').val('');
		$('#stock').attr('readonly', false);
	}

	// Lightbox close button
	$(document).on('click', '.lightbox_close', function(){
	    hide_lightbox();
	    reset();
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

	///////////////////
	// Add button
  	//////////////////
  	$(document).on('click', addButton, function(e){
  		e.preventDefault();
		$('H2.FormTitle').text('INPUT '+FormsLug);
		$(IDForm).attr('class', 'form add');
		$(IDForm).attr('data-id', '');
		$(IDForm +' .field_container label.error').hide();
		show_lightbox();
  	});

  	///////////////////////////
  	// Add submit form
  	//////////////////////////
  	$(document).on('submit', IDForm+'.add', function(e){
    	e.preventDefault();
	    // Validate form
	    if (FormNYA.valid() == true){
	    	// Send company information to database
	      	hide_ipad_keyboard();
	      	hide_lightbox();
	      	show_loading_message();
	      	var Infos = $('#size').val();
	      	var form_data = $(IDForm).serialize();
	      	var request   = $.ajax({
	        	url:          pathFile+"?"+Act+"=add_"+sLug,
	        	cache:        false,
	        	data:         form_data,
	        	dataType:     'json',
	        	contentType:  'application/json; charset=utf-8',
	        	type:         'get'
	      	});
	      	request.done(function(output){
	        	if (output.result == sukses){
	          		// Reload datable
	          		tablenya.api().ajax.reload(function(){
	            		hide_loading_message();
	            		show_message("'"+Infos+"' berhasil dimasukkan.", 'success');
	            		reset();
	          		}, true);
	        	} else {
	          		hide_loading_message();
	          		show_message('Gagal memasukkan data', 'error');
	        	}
	      	});
	      	request.fail(function(jqXHR, textStatus){
	        	hide_loading_message();
	        	show_message('Gagal memasukkan data: '+textStatus, 'error');
	      	});
	    }
  	});

  	/////////////////////
  	// Edit button
	////////////////////
	$(document).on('click', editButton, function(e){
		e.preventDefault();
		reset();
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
	    		$('h2.FormTitle').text('EDIT '+FormsLug);
	        	$(IDForm +'').attr('class', 'form edit');
	        	$(IDForm +'').attr('data-id', id);
	        	$(IDForm +' .field_container label.error').hide();
				$('#size').val(output.data[0].size);
				$('#ingredient').val(output.data[0].ingredient);
				$('#color').val(output.data[0].color);
				$('#note').val(output.data[0].note);
				$('#unit').val(output.data[0].satuan);
				$('#stock').val(output.data[0].stock);
				$('#stock').attr('readonly', true);
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
	// Edit submit form
  	/////////////////////////
  	$(document).on('submit', IDForm+'.edit', function(e){
    	e.preventDefault();
    	// Validate form
    	if (FormNYA.valid() == true){
      		// Send company information to database
      		hide_ipad_keyboard();
      		hide_lightbox();
      		show_loading_message();
      		var Infos = $('#size').val();
      		var id        = $(IDForm).attr('data-id');
      		var form_data = $(IDForm).serialize();
      		var request   = $.ajax({
        		url:          pathFile+"?"+Act+"=edit_"+sLug+"&id="+id,
        		cache:        false,
        		data:         form_data,
        		dataType:     'json',
        		contentType:  'application/json; charset=utf-8',
        		type:         'get'
     		});
      		request.done(function(output){
        		if (output.result == sukses){
          			// Reload datable
          			tablenya.api().ajax.reload(function(){
            			hide_loading_message();
            			show_message("'"+Infos+"' berhasil diubah.", 'success');
            			reset();
          			}, true);
        		} else {
          			hide_loading_message();
          			show_message('Gagal mengubah', 'error');
        		}
      		});
     		request.fail(function(jqXHR, textStatus){
        		hide_loading_message();
        		show_message('Gagal mengubah: '+textStatus, 'error');
      		});
    	}
  	});

  	////////////////////
  	// Delete button
  	//////////////////
  	$(document).on('click', '.function_delete a', function(e){
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
	          		tablenya.api().ajax.reload(function(){
	            		hide_loading_message();
	            		show_message("'"+Infos+"' deleted successfully.", 'success');
	          		}, true);
	        	} else {
	          		hide_loading_message();
	          		show_message('Delete request failed', 'error');
	       		}
	      	});
	      	
	      	request.fail(function(jqXHR, textStatus){
	        	hide_loading_message();
	        	show_message('Delete request failed: '+textStatus, 'error');
	      	});
	    }
  	});

  	/////////////////////
  	// In stock
	////////////////////
	$(document).on('click', insButton, function(e){
		e.preventDefault();
	    var id = $(this).data('id');
		$('h2.FormTitle').text('STOK MASUK '+FormsLug);
    	$(IDForm +'').attr('class', 'form stokMasuk');
    	$(IDForm +'').attr('data-id', id);
    	$(IDForm +' .field_container label.error').hide();
    	$('.date').show();
		$('.customer').show();
		$('.nopo').show();
		$('.ukuran').show();
		$('.size').hide();
		$('.ingredient').hide();
		$('.color').hide();
		$('.stock').hide();
		$('.s_masuk').show();
		$('.s_keluar').hide();
    	show_lightbox();
	});

	///////////////////////////
	// In stock submit form
  	/////////////////////////

  	$(document).on('submit', IDForm+'.stokMasuk', function(e){
    	e.preventDefault();
    	// Validate form
    	if (FormNYA.valid() == true){
      		// Send in stock to database
      		hide_ipad_keyboard();
      		hide_lightbox();
      		show_loading_message();
      		var id        = $(IDForm).attr('data-id');
      		var form_data = $(IDForm).serialize();
      		var request   = $.ajax({
        		url:          pathFile+"?"+Act+"=ins_"+sLug+"&id="+id,
        		cache:        false,
        		data:         form_data,
        		dataType:     'json',
        		contentType:  'application/json; charset=utf-8',
        		type:         'get'
     		});
      		request.done(function(output){
        		if (output.result == sukses){
          			tablenya.api().ajax.reload(function(){
            			hide_loading_message();
            			show_message("berhasil menambahkan stok.", 'success');
            			reset();
          			}, true);
        		} else {
          			hide_loading_message();
          			show_message('Gagal menambahkan stok', 'error');
        		}
      		});
     		request.fail(function(jqXHR, textStatus){
        		hide_loading_message();
        		show_message('Gagal menambahkan stok: '+textStatus, 'error');
      		});
    	}
  	});

  	/////////////////////
  	// Out stock
	////////////////////
	$(document).on('click', outsButton, function(e){
		e.preventDefault();
	    e.preventDefault();
	    var id = $(this).data('id');
		$('h2.FormTitle').text('STOK KELUAR '+FormsLug);
    	$(IDForm +'').attr('class', 'form stokKeluar');
    	$(IDForm +'').attr('data-id', id);
    	$(IDForm +' .field_container label.error').hide();
    	$('.date').show();
		$('.customer').show();
		$('.nopo').show();
		$('.ukuran').show();
		$('.size').hide();
		$('.ingredient').hide();
		$('.color').hide();
		$('.stock').hide();
		$('.s_masuk').hide();
		$('.s_keluar').show();
    	show_lightbox();
	});

	///////////////////////////
	// Out stock submit form
  	/////////////////////////
  	$(document).on('submit', IDForm+'.stokKeluar', function(e){
    	e.preventDefault();
    	// Validate form
    	if (FormNYA.valid() == true){
      		// Send out stock to database
      		hide_ipad_keyboard();
      		hide_lightbox();
      		show_loading_message();
      		var id        = $(IDForm).attr('data-id');
      		var form_data = $(IDForm).serialize();
      		var request   = $.ajax({
        		url:          pathFile+"?"+Act+"=outs_"+sLug+"&id="+id,
        		cache:        false,
        		data:         form_data,
        		dataType:     'json',
        		contentType:  'application/json; charset=utf-8',
        		type:         'get'
     		});
      		request.done(function(output){
        		if (output.result == sukses){
          			tablenya.api().ajax.reload(function(){
            			hide_loading_message();
            			show_message("Berhasil mengurangkan stok.", 'success');
            			reset();
          			}, true);
        		} else {
          			hide_loading_message();
          			show_message('Gagal mengurangkan stok', 'error');
        		}
      		});
     		request.fail(function(jqXHR, textStatus){
        		hide_loading_message();
        		show_message('Gagal mengurangkan stok: '+textStatus, 'error');
      		});
    	}
  	});


  	/////////////////////
  	// View bahan baku history
	////////////////////
	$(document).on('click', '#log_bahanbaku', function(e){
		e.preventDefault();
		show_loading_message();
	    var request = $.ajax({
	    	url:          pathFile,
	      	cache:        false,
	      	data:         Act+'=histori_bahanbaku',
	      	dataType:     'json',
	      	contentType:  'application/json; charset=utf-8',
	      	type:         'get'
	    });

	    request.done(function(output){
	    	if(output.result == sukses){
	    		hide_loading_message();
	    		tablenya.parents('div.dataTables_wrapper').first().hide();
	    		$('#tablenya2').show();
	    		$('#log_bahanbaku').text('Back');
	    		$('#log_bahanbaku').attr('id', 'undo');
	    		$('#add_inputRIBBON').hide();
	    		console.log(output.data);

	    		var tablenyax = $('#tablenya2').DataTable({
	    			"scrollX": true,
	    			"ajax": pathFile+'?'+Act+'=histori_bahanbaku'+'&curMonth='+getCookie('selectMonth'),
	    			"columns": [
	    				{"data" : "no"},
	    				{"data" : "date"},
					    {"data"	: "size" },
	    				{"data" : "ingredient"},
	    				{"data" : "color"},
					    {"data" : "customer"},
					    {"data" : "nopo"},
					    {"data" : "status"},
					    {"data": "ukuran"},
					    {"data": "note"},  
					    {"data": "unit"},
					    {"data": "s_awal"},
					    {"data": "s_masuk"},
					    {"data": "s_keluar"},
					    {"data": "s_akhir"},
					    {"data": "input"},
	      				{"data": "functions","sClass": "functions" }
	    			],
	    			"lengthMenu": [[10, -1], [10, "All"]],
				    iDisplayLength: 10,
				    dom: 'Bfrtip',
			        buttons: [ 'pageLength' ],
	    		});

	    		var buttons = new $.fn.dataTable.Buttons(tablenyax, {
	    			buttons:[
			        {
			        	extend: 'excelHtml5',
			        	messageTop: false,
			        	footer: true,
			        	text: 'Export to Excel',
			        	filename : 'Bahan Baku History-'+getCookie("selectMonth"),
			        	title: 'Bahan Baku History '+getCookie("selectMonth"),
			        	exportOptions: {
			            	columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15]
			            }
			        }]
			    }).container().appendTo($('.dt-buttons'));

			    /////////////////////////////////////////////////////////////////
				// Sort datatable from current month
				/////////////////////////////////////////////////////////////////

				var req = $.ajax({
					url: pathFile+"?"+Act+"=sortdata",
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
					tablenyax.ajax.url(pathFile+'?'+Act+'=histori_bahanbaku'+'&curMonth='+valMonth).load();
					tablenyax.draw();
				});

				////////////////////
			  	// Delete Label History
			  	//////////////////

			  	$(document).on('click', '.delhis a', function(e){
				    e.preventDefault();
				    var Infos = $(this).data('name');
				    if (confirm("Anda yakin ingin menghapus '"+Infos+"'?")){
				    	show_loading_message();
				      	var id      = $(this).data('id');
				      	var request = $.ajax({
				        	url:          pathFile+"?"+Act+"=delhis&id="+id,
				        	cache:        false,
				        	dataType:     'json',
				        	contentType:  'application/json; charset=utf-8',
				        	type:         'get'
				      	});
				      	
				      	request.done(function(output){
				        	if (output.result == sukses){
				          		tablenyax.ajax.reload(function(){
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

	    	} else {
	    		hide_loading_message();
	        	show_message('Gagal mengambil data.', 'error');
	    	}
	    });
	});

	/////////////////////
  	// Undo 
	////////////////////

	$(document).on('click', '#undo', function(e){
		e.preventDefault();
		location.reload();
	});

});