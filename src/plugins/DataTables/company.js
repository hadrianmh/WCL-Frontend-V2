$(document).ready(function(){

	//////////////////////////
	//Default config
	/////////////////////////

	var idTablenya = $('#tablenya');
	var pathFile = '../auth/company.php';
	var Act = 'action';
	var sLug = 'company';
	var FormsLug = 'COMPANY';
	var IDForm = "#form_company";
	var addButton = "#tambah_company";
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
	    		'targets': [0,1,3,4,5,6],
	    		'className': 'dt-nowrap'
	        },
	        { 
	        	'targets': 5,
	        	render: function(data) {
	        		return '<img src="'+data+'" class="datatable_img">'
	        	}
	        }  
	    ],
	    "columns": [
	      { "data": "no" },
	      { "data": "company" },
	      { "data": "address"},
	      { "data": "email"},
	      { "data": "phone" },
	      { "data": "logo" },
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
        	filename : 'Company',
        	title: 'Data Company',
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
	    reset();
	});

	function reset(){
		$('#company').val('');
	    $('#address').val('');
		$('#email').val('');
	    $('#phone').val('');
	    $('#logo').val('');
	    $('#ImageResult').empty();
	    $('#RemoveLogo').hide();
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

	///////////////////
	// Add button
  	//////////////////
  	$(document).on('click', addButton, function(e){
  		e.preventDefault();
		show_lightbox();
		$('H2.FormTitle').text('INPUT '+FormsLug);
		$(IDForm).attr('class', 'form company_new');
		$(IDForm).attr('data-id', '');
  	});

  	///////////////////////////
  	// Add submit form
  	//////////////////////////
  	$(document).on('submit', IDForm+'.company_new', function(e){
    	e.preventDefault();
	    // Validate form
	    if (FormNYA.valid() == true){
	    	// Send company information to database
	      	hide_ipad_keyboard();
	      	hide_lightbox();
	      	show_loading_message();
	      	var Infos = $('#company').val();
	      	var request   = $.ajax({
	        	url: pathFile+"?"+Act+"=add_"+sLug,
	        	data: new FormData(this),
	        	cache: false,
	        	contentType: false,
	        	processData: false,
	        	type: 'POST'
	      	});
	      	request.done(function(output){
	      		var obj = JSON.parse(output);
	        	if (obj.result == sukses){
	          		// Reload datable
	          		tablenya.ajax.reload(function(){
	            		hide_loading_message();
	            		show_message("'"+Infos+"' berhasil dimasukan.", 'success');
	            		reset();
	          		}, true);
	        	} else {
	          		hide_loading_message();
	          		show_message(obj.message, 'error');
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
	        	$(IDForm).attr('class', 'form edit_company');
	        	$(IDForm).attr('data-id', id);
	        	$('.field_container label.error').hide();
	        	$('#company').val(output.data[0].company);
		        $('#address').val(output.data[0].address);
	        	$('#email').val(output.data[0].email);
		        $('#phone').val(output.data[0].phone);
		        $('#tmp_logo').val(output.data[0].logo);
		        $('#tmp_logo').attr('data-img', output.data[0].logo);
		        if(output.data[0].logo){
		        	$('#ImageResult').append('<img src="'+output.data[0].logo+'" class="img-thumbnail img_default">');
		        	$('#RemoveLogo').show();
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
	// Edit submit form
  	/////////////////////////
  	$(document).on('submit', IDForm+'.edit_company', function(e){
    	e.preventDefault();
    	// Validate form
    	if (FormNYA.valid() == true){
      		// Send company information to database
      		hide_ipad_keyboard();
      		hide_lightbox();
      		show_loading_message();
      		var Infos = $('#company').val();
      		var id        = $(IDForm).attr('data-id');
      		var img       = $('#tmp_logo').attr('data-img');
      		var request   = $.ajax({
        		url: pathFile+"?"+Act+"=edit_"+sLug+"&id="+id+"&img="+img,
        		cache: false,
        		data: new FormData(this),
        		cache: false,
	        	contentType: false,
	        	processData: false,
	        	type: 'POST'
     		});
      		request.done(function(output){
      			var obj = JSON.parse(output);
        		if (obj.result == sukses){
          			// Reload datable
          			tablenya.ajax.reload(function(){
            			hide_loading_message();
            			show_message("'"+Infos+"' berhasil diubah.", 'success');
            			reset();
          			}, true);
        		} else {
          			hide_loading_message();
          			show_message(obj.message, 'error');
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

  	$(document).on('click', '#RemoveLogo', function(e){
  		e.preventDefault();
  		$('#logo').val('');
  		$('#tmp_logo').val('');
  		$('#ImageResult').empty();
  		$('#RemoveLogo').hide();
  		$('#UploadLogo').hide();
  	});
});