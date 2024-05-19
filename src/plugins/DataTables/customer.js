$(document).ready(function(){

	//////////////////////////
	//Default config
	/////////////////////////

	var idTablenya = $('#tablenya');
	var pathFile = '../auth/customer.php';
	var Act = 'action';
	var sLug = 'customer';
	var FormsLug = 'CUSTOMER';
	var IDForm = "#form_cs";
	var addButton = "#tambah_customer";
	var editButton = ".function_edit a";

	//Message alert
	var sukses = 'success';

	///////////////////////////
	// On page load: datatable
	///////////////////////////

	var tablenya = idTablenya.DataTable({
	    "ajax": pathFile+"?"+Act+"=result_"+sLug,
	    'columnDefs': [
	    	{
	    		'targets': [0,1,3,5],
	    		'className': 'dt-nowrap'
	        }
	    ],
	    "columns": [
	      { "data": "no" },
	      { "data": "b_nama" },
	      { "data": "b_alamat"},
	      { "data": "s_nama" },
	      { "data": "s_alamat"},
	      { "data": "functions","sClass": "functions dt-nowrap" }
	    ],
	    "lengthMenu": [[10, -1], [10, "All"]],
	    iDisplayLength: 10,
	    dom: 'Bfrtip',
	    buttons: [ 'pageLength' ],
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
        	filename : 'Customer',
        	title: 'Data Customer',
        	exportOptions: {
            	columns: [ 0, 1, 2, 3, 4]
            }
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
	}

	// Lightbox close button
	$(document).on('click', '.lightbox_close', function(){
	    hide_lightbox();
	    $('#b_nama').val('');
	    $('#b_alamat').val('');
	    $('#b_kota').val('');
	    $('#b_negara').val('');
	    $('#b_provinsi').val('');
	    $('#b_kodepos').val('');
	    $('#b_telp').val('');
	    $('#s_nama').val('');
	    $('#s_alamat').val('');
	    $('#s_kota').val('');
	    $('#s_negara').val('');
	    $('#s_provinsi').val('');
	    $('#s_kodepos').val('');
	    $('#s_telp').val('');
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
		show_lightbox();
		$('H2.FormTitle').text('INPUT '+FormsLug);
		$('#form_cs').attr('class', 'form cs_new');
		$('#form_cs').attr('data-id', '');
  	});

  	///////////////////////////
  	// Add submit form
  	//////////////////////////
  	$(document).on('submit', IDForm+'.cs_new', function(e){
    	e.preventDefault();
	    // Validate form
	    if (FormNYA.valid() == true){
	    	// Send company information to database
	      	hide_ipad_keyboard();
	      	hide_lightbox();
	      	show_loading_message();
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
	          		tablenya.ajax.reload(function(){
	            		hide_loading_message();
	            		var Infos = $('#b_nama').val();
	            		show_message("'"+Infos+"' berhasil dimasukan.", 'success');
	            		$('#b_nama').val('');
	            		$('#b_alamat').val('');
	            		$('#b_kota').val('');
		        		$('#b_provinsi').val('');
		        		$('#b_negara').val('');
		        		$('#b_kodepos').val('');
	            		$('#b_telp').val('');
	            		$('#s_nama').val('');
	            		$('#s_alamat').val('');
	            		$('#s_kota').val('');
		        		$('#s_provinsi').val('');
		        		$('#s_negara').val('');
		        		$('#s_kodepos').val('');
	            		$('#s_telp').val('');
	          		}, true);
	        	} else {
	          		hide_loading_message();
	          		show_message('Gagal memasukan data', 'error');
	        	}
	      	});
	      	request.fail(function(jqXHR, textStatus){
	        	hide_loading_message();
	        	show_message('Gagal memasukan data: '+textStatus, 'error');
	      	});
	    }
  	});

  	/////////////////////
  	// Edit button
	////////////////////

	$(document).on('click', editButton, function(e){
		e.preventDefault();
	    // Get company information from database
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
	    		$('h2.FormTitle').text('UBAH '+FormsLug);
	        	$(IDForm).attr('class', 'form edit_cs');
	        	$(IDForm).attr('data-id', id);
	        	$('.field_container label.error').hide();
	        	$('#b_nama').val(output.data[0].b_nama);
		        $('#b_alamat').val(output.data[0].b_alamat);
		        $('#b_kota').val(output.data[0].b_kota);
		        $('#b_negara').val(output.data[0].b_negara);
		        $('#b_provinsi').val(output.data[0].b_provinsi);
		        $('#b_kodepos').val(output.data[0].b_kodepos);
		        $('#b_telp').val(output.data[0].b_telp);
		        $('#s_nama').val(output.data[0].s_nama);
		        $('#s_alamat').val(output.data[0].s_alamat);
		        $('#s_kota').val(output.data[0].s_kota);
		        $('#s_negara').val(output.data[0].s_negara);
		        $('#s_provinsi').val(output.data[0].s_provinsi);
		        $('#s_kodepos').val(output.data[0].s_kodepos);
		        $('#s_telp').val(output.data[0].s_telp);
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
  	$(document).on('submit', IDForm+'.edit_cs', function(e){
    	e.preventDefault();
    	// Validate form
    	if (FormNYA.valid() == true){
      		// Send company information to database
      		hide_ipad_keyboard();
      		hide_lightbox();
      		show_loading_message();
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
          			tablenya.ajax.reload(function(){
            			hide_loading_message();
            			var Infos = $('#b_nama').val();
            			show_message("'"+Infos+"' berhasil mengubah.", 'success');
            			$('#b_nama').val('');
	            		$('#b_alamat').val('');
	            		$('#b_kota').val('');
		        		$('#b_provinsi').val('');
		        		$('#b_negara').val('');
		        		$('#b_kodepos').val('');
	            		$('#b_tlp').val('');
	            		$('#s_nama').val('');
	            		$('#s_alamat').val('');
	            		$('#s_kota').val('');
		        		$('#s_provinsi').val('');
		        		$('#s_negara').val('');
		        		$('#s_kodepos').val('');
	            		$('#s_telp').val('');
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
	          		// Reload datable
	          		tablenya.ajax.reload(function(){
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