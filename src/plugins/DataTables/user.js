// Function to get a cookie value by name
function getCookie(name) {
	var nameEQ = name + "=";
	var cookies = document.cookie.split(';');
	for (var i = 0; i < cookies.length; i++) {
		var cookie = cookies[i].trim();
		if (cookie.indexOf(nameEQ) === 0) {
			return cookie.substring(nameEQ.length);
		}
	}
	return null;
}

$(document).ready(function(){

	//////////////////////////
	//Default config
	/////////////////////////

	var idTablenya = $('#tablenya');
	var pathFile = 'http://localhost:8082/api/v1/dashboard';
	var FormsLug = 'USER';
	var IDForm = "#form_inputUSER";
	var addButton = "#add_inputUSER";

	//Message alert
	var sukses = 'success';

	///////////////////////////
	// On page load: datatable
	///////////////////////////

	var tablenya = idTablenya.dataTable({
		initComplete : function() {
			var input = $('.dataTables_filter input').unbind(),
			self = this.api(),
			$searchButton = $(`<button class="btn btn-default"><i class="fa fa-search"></i></button>`).click(function(){ self.search(input.val()).draw(); });
			$resetButton = $(`<button class="btn btn-default"><i class="fa fa-times"></i></button>`).click(function() { input.val('');$searchButton.click(); }); 
			$('.dataTables_filter').append($searchButton, $resetButton);
		},
		"serverSide" : true,
	    "ajax": {
			"url" : pathFile+"/user",
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
			},
			"error": function (xhr, error, thrown) {
				console.error('Error fetching data:', xhr, error, thrown);
				alert('Terjadi kesalahan, silahkan login kembali.');
				window.location.href = '/auth/signout.php';
			}
		},
	    "columns": [
	      { "data": "name" },
	      { "data": "email"},
	      { "data": "role" },
	      { "data": "status"},
	      { "data": "account"},
	    ],
		"columnDefs": [
			{
				"targets": 5,
				"data": null,
				"defaultContent": "",
				"render": function (data, type, row) {
					return '<button class="btn btn-default function_edit" data-id="'+ data.id +'" data-name="'+ data.name +'"><i class="fa fa-pencil"></i></button> <button class="btn btn-default function_delete" data-id="'+ data.id +'" data-name="'+ data.name +'"><i class="fa fa-trash"></i></button>';
				}
			}
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
			var form_data 	= $(IDForm).serializeArray();
			var jsonData 	= {};
			$.each(form_data, function(){
				if(this.name == "role") {
					jsonData['role'] = parseInt(this.value);
				} else if(this.name == "status") {
					jsonData['status'] = parseInt(this.value);
				} else if(this.name == "account") {
					jsonData['account'] = parseInt(this.value);
				} else {
					jsonData[this.name] = this.value;
				}
			});
			var request   = $.ajax({
				url:          pathFile+"/user",
				type:         'POST',
				data:         JSON.stringify(jsonData),
				beforeSend: function (xhr) {
					xhr.setRequestHeader('Authorization', getCookie('access_token'));
					xhr.setRequestHeader('Content-Type', 'application/json');
				}
     		});
	      	request.done(function(output){
	        	if(output.status == "success"){
	          		// Reload datable
	          		tablenya.api().ajax.reload(function(){
	            		hide_loading_message();
	            		var Infos = $('#name').val();
	            		show_message("'"+Infos+"' added successfully.", 'success');
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

  	/////////////////////
  	// Edit button
	////////////////////
	$(document).on('click', '.function_edit', function(e){
		e.preventDefault();
	    show_loading_message();
	    var id      = $(this).data('id');
	    var request = $.ajax({
	    	url:          pathFile+"/user",
			type:         'GET',
	      	data:         'id='+id,
			beforeSend: function (xhr) {
				xhr.setRequestHeader('Authorization', getCookie('access_token'));
				xhr.setRequestHeader('Content-Type', 'application/json');
			}
	    });
	    request.done(function(output){
	    	if(output.status == "success"){
	    		$('h2.FormTitle').text('EDIT '+FormsLug);
	        	$(IDForm +' button').text('Submit');
	        	$(IDForm +'').attr('class', 'form edit');
	        	$(IDForm +'').attr('data-id', id);
	        	$(IDForm +' .field_container label.error').hide();
	        	$(IDForm +' #name').val(output.response.data[0].name);
	        	$(IDForm +' #email').val(output.response.data[0].email);
		        $('select#role option').filter(function() {return $(this).text() === output.response.data[0].role;}).prop('selected', true);
		        $('select#status option').filter(function() {return $(this).text() === output.response.data[0].status;}).prop('selected', true);
		        $('select#account option').filter(function() {return $(this).text() === output.response.data[0].account;}).prop('selected', true);
		        $(IDForm +' .password').hide();
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
  	$(document).on('submit', IDForm+'.edit', function(e){
    	e.preventDefault();
    	// Validate form
    	if (FormNYA.valid() == true){
      		hide_ipad_keyboard();
      		hide_lightbox();
      		show_loading_message();
      		var form_data 	= $(IDForm).serializeArray();
      		var id			= $(IDForm).attr('data-id');
			
			var jsonData = {};
			$.each(form_data, function(){
				if(this.name == "role") {
					jsonData['role'] = parseInt(this.value);
				} else if(this.name == "status") {
					jsonData['status'] = parseInt(this.value);
				} else if(this.name == "account") {
					jsonData['account'] = parseInt(this.value);
				} else {
					jsonData[this.name] = this.value;
				}
			});

			jsonData.id = parseInt(id);

      		var request   = $.ajax({
				url:          pathFile+"/user",
				type:         'PUT',
				data:         JSON.stringify(jsonData),
				beforeSend: function (xhr) {
					xhr.setRequestHeader('Authorization', getCookie('access_token'));
					xhr.setRequestHeader('Content-Type', 'application/json');
				}
     		});
      		request.done(function(output){
        		if(output.status == "success"){
					// Reload datable
          			tablenya.api().ajax.reload(function(){
            			hide_loading_message();
            			var Infos = $('#name').val();
            			show_message("'"+Infos+"' update successfully.", 'success');
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

  	////////////////////
  	// Delete button
  	//////////////////
  	$(document).on('click', '.function_delete', function(e){
	    e.preventDefault();
	    var Infos = $(this).data('name');
	    if (confirm("Anda yakin ingin menghapus '"+Infos+"'?")){
	    	show_loading_message();
	      	var id      = $(this).data('id');
			var request = $.ajax({
				url:          pathFile+"/user/"+id,
				type:         'DELETE',
				beforeSend: function (xhr) {
					xhr.setRequestHeader('Authorization', getCookie('access_token'));
					xhr.setRequestHeader('Content-Type', 'application/json');
				}
			});
	      	
	      	request.done(function(output){
	        	if(output.status == "success"){
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