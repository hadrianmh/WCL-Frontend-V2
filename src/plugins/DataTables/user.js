$(document).ready(function(){

	//////////////////////////
	//Default config
	/////////////////////////

	var idTablenya = $('#tablenya');
	var pathFile = '../auth/user.php';
	var Act = 'action';
	var sLug = 'user';
	var FormsLug = 'USER';
	var IDForm = "#form_inputUSER";
	var addButton = "#add_inputUSER";
	var editButton = ".function_edit a";

	//Message alert
	var sukses = 'success';

	///////////////////////////
	// On page load: datatable
	///////////////////////////

	var tablenya = idTablenya.dataTable({
	    "ajax": pathFile+"?"+Act+"=result_"+sLug,
	    "columns": [
	      { "data": "no" },
	      { "data": "name" },
	      { "data": "email"},
	      { "data": "role" },
	      { "data": "status"},
	      { "data": "account"},
	      { "data": "functions","sClass": "functions" }
	    ],
	    "aoColumnDefs": [
	      { "bSortable": false, "aTargets": [-1] }
	    ],
	    "lengthMenu": [[10, 25, 50, 100, 1000, -1], [10, 25, 50, 100, 1000, "All"]],
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

	// Show lightbox
	function show_lightbox(){
		$('#myModal').show();
	}
	// Lightbox close button
	$(document).on('click', '.lightbox_close', function(){
	    hide_lightbox();
	    $(IDForm +' #name').val('');
		$(IDForm +' #email').val('');
		$(IDForm +' #password').val('');
		$(IDForm +' #role').val('');
		$(IDForm +' #status').val('');
		$(IDForm +' #account').val('');
	});
	// Hide lightbox
	function hide_lightbox(){
		$('#myModal').hide();
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

	///////////////////
	// Add button
  	//////////////////
  	$(document).on('click', addButton, function(e){
  		e.preventDefault();
		$('H2.FormTitle').text('INPUT '+FormsLug);
		$(IDForm +' button').text('Submit');
		$(IDForm).attr('class', 'form add');
		$(IDForm).attr('data-id', '');
		$(IDForm +' .field_container label.error').hide();
		$(IDForm +' .password').show();
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
	            		var Infos = $('#name').val();
	            		show_message("'"+Infos+"' added successfully.", 'success');
	          		}, true);
	        	} else if(output.result == 'registered') {
	        		hide_loading_message();
	          		show_message('Input request failed: '+output.message, 'error');
	        	} else {
	          		hide_loading_message();
	          		show_message('Input request failed', 'error');
	        	}
	      	});
	      	request.fail(function(jqXHR, textStatus){
	        	hide_loading_message();
	        	show_message('Input request failed: '+textStatus, 'error');
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
	    		$('h2.FormTitle').text('EDIT '+FormsLug);
	        	$(IDForm +' button').text('Submit');
	        	$(IDForm +'').attr('class', 'form edit');
	        	$(IDForm +'').attr('data-id', id);
	        	$(IDForm +' .field_container label.error').hide();
	        	$(IDForm +' #name').val(output.data[0].name);
	        	$(IDForm +' #email').val(output.data[0].email);
	        	$(IDForm +' #role').val(output.data[0].role);
		        $(IDForm +' #status').val(output.data[0].status);
		        $(IDForm +' #account').val(output.data[0].account);
		        $(IDForm +' .password').hide();
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
  	$(document).on('submit', IDForm+'.edit', function(e){
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
          			tablenya.api().ajax.reload(function(){
            			hide_loading_message();
            			var Infos = $('#name').val();
            			show_message("'"+Infos+"' berhasil diubah.", 'success');
          			}, true);
        		} else {
          			hide_loading_message();
          			show_message('Gagal diubah', 'error');
        		}
      		});
     		request.fail(function(jqXHR, textStatus){
        		hide_loading_message();
        		show_message('Gagal diubah: '+textStatus, 'error');
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
	          		tablenya.api().ajax.reload(function(){
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