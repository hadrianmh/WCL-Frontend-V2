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
	var pathFile = decodeURIComponent(getCookie('base_url_api')) +':'+ getCookie('base_port_api') + decodeURIComponent(getCookie('base_path_api')) + decodeURIComponent(getCookie('base_dashboard_api'));
	var Act = 'action';
	var sLug = 'vendor';
	var FormsLug = 'VENDOR';
	var IDForm = "#form_vendor";
	var addButton = "#tambah_vendor";

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
	    "ajax": {
			"url" : pathFile+"/vendor",
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
	      { "data": "vendorname" },
	      { "data": "address"},
	      { "data": "phone" }
	    ],
		"columnDefs": [
			{
				"targets": 3,
				"data": null,
				"defaultContent": "",
				"render": function (data, type, row) {
					return '<button class="btn btn-default function_edit" data-id="'+ data.id +'"><i class="fa fa-pencil"></i></button>';
					// return '<button class="btn btn-default function_edit" data-id="'+ data.id +'"><i class="fa fa-pencil"></i></button> <button class="btn btn-default function_delete" data-id="'+ data.id +'" data-name="'+ data.vendorname +'"><i class="fa fa-trash"></i></button>';
				}
			}
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
        	filename : 'Vendor',
        	title: 'Data Vendor',
        	exportOptions: {
            	columns: [ 0, 1, 2, 3]
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
		$('#vendor').val('');
	    $('#address').val('');
	    $('#phone').val('');
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
		$(IDForm).attr('class', 'form vendor_new');
		$(IDForm).attr('data-id', '');
  	});

  	///////////////////////////
  	// Add submit form
  	//////////////////////////
  	$(document).on('submit', IDForm+'.vendor_new', function(e){
    	e.preventDefault();
	    // Validate form
	    if (FormNYA.valid() == true){
	      	hide_ipad_keyboard();
	      	hide_lightbox();
	      	show_loading_message();
			
	      	var form_data 	= $(IDForm).serializeArray();
			var jsonData 	= {};
			$.each(form_data, function(){
				if(this.name == 'phone') {
					value = parseInt(this.value);
				} else {
					value = this.value;
				}
				jsonData[this.name] = value;
			});

			var request   = $.ajax({
				url:          pathFile+"/vendor",
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
	          		tablenya.ajax.reload(function(){
	            		hide_loading_message();
						var Infos = $('#vendor').val();
	            		show_message("'"+Infos+"' added successfully.", 'success');
	            		reset();
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
	    	url:          pathFile+"/vendor",
			type:         'GET',
	      	data:         'id='+id,
			beforeSend: function (xhr) {
				xhr.setRequestHeader('Authorization', getCookie('access_token'));
				xhr.setRequestHeader('Content-Type', 'application/json');
			}
	    });
	    request.done(function(output){
	    	if(output.status == "success"){
	    		$('h2.FormTitle').text('UBAH '+FormsLug);
	        	$(IDForm).attr('class', 'form edit_vendor');
	        	$(IDForm).attr('data-id', id);
	        	$('.field_container label.error').hide();
	        	$('#vendor').val(output.response.data[0].vendorname);
		        $('#address').val(output.response.data[0].address);
		        $('#phone').val(output.response.data[0].phone);
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
  	$(document).on('submit', IDForm+'.edit_vendor', function(e){
    	e.preventDefault();
    	// Validate form
    	if (FormNYA.valid() == true){
      		// Send company information to database
      		hide_ipad_keyboard();
      		hide_lightbox();
      		show_loading_message();
      		var form_data 	= $(IDForm).serializeArray();
      		var id			= $(IDForm).attr('data-id');

      		var jsonData = {};
			$.each(form_data, function(){
				if(this.name == 'phone') {
					value = parseInt(this.value);
				} else {
					value = this.value;
				}
				jsonData[this.name] = value;
			});

			jsonData.id = parseInt(id);

			var request   = $.ajax({
				url:          pathFile+"/vendor",
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
          			tablenya.ajax.reload(function(){
            			hide_loading_message();
            			var Infos = $('#vendor').val();
						show_message("'"+Infos+"' update successfully.", 'success');
						reset();
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
				url:          pathFile+"/vendor/"+id,
				type:         'DELETE',
				beforeSend: function (xhr) {
					xhr.setRequestHeader('Authorization', getCookie('access_token'));
					xhr.setRequestHeader('Content-Type', 'application/json');
				}
			});
	      	
	      	request.done(function(output){
	        	if(output.status == "success"){
	          		// Reload datable
	          		tablenya.ajax.reload(function(){
	            		hide_loading_message();
	            		show_message("'"+Infos+"' delete successfully.", 'success');
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