$(document).ready(function(){

	//////////////////////////
	//Default config
	/////////////////////////

	var idTablenya = $('#tablenya');
	var pathFile = decodeURIComponent(getCookie('base_url_api')) +':'+ getCookie('base_port_api') + decodeURIComponent(getCookie('base_path_api')) + decodeURIComponent(getCookie('base_dashboard_api'));
	var FormsLug = 'SURAT JALAN';
	var IDForm = "#form_inputSJ";

	//Message alert
	var sukses = 'success';

	//Validasi ketentuan form menggunakan Jquery Validate form
	var FormNYA = $(IDForm);
  	FormNYA.validate();

	// Show lightbox
	function show_lightbox(){
		$('#myModal').show();
	}

	// Hide lightbox
	function hide_lightbox(){
		$('#myModal').hide();
	}

	// Show loading message
	function show_loading_message(){
	    $('#loading_container').show();
	}
	// Hide loading message
	function hide_loading_message(){
	    $('#loading_container').hide();
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

	// Lightbox close button
	$(document).on('click', '.lightbox_close', function(){
	    hide_lightbox();
	    clean_value_elemen();
	});

	//clean javascript effect
	function clean_value_elemen(){
        $(".looping-item").remove();
        $("#nama_kurir").val('');
        $("#no_resi").val('');
        $("#cost").val('0');
	}

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

	var tablenya = idTablenya.dataTable({
		initComplete : function() {
			var input = $('.dataTables_filter input').unbind(),
			self = this.api(),
			$searchButton = $(`<a class="btn btn-default"><i class="fa fa-search"></i></a>`).click(function(){ self.search(input.val()).draw(); });
			$resetButton = $(`<a class="btn btn-default"><i class="fa fa-times"></i></a>`).click(function() { input.val('');$searchButton.click(); }); 
			$('.dataTables_filter').append($searchButton, $resetButton);
		},
		"ajax": {
			"url" : pathFile+"/delivery-order/waiting",
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
		"serverSide" : true,
		"scrollX": true,
		'scrollCollapse': true,
		'scrollY': '600px',
	    'columnDefs': [
	    	{
	    		'targets': [0,1,2,4,5],
	            'className': 'dt-nowrap'
	        },
			{
				"targets": 5,
				"data": null,
				"defaultContent": "",
				"render": function (data, type, row) {
					return '<button class="btn btn-default function_process" data-id="'+ data.id +'"><i class="fa fa-share"></i></button>';
				}
			}
	    ],
	    "columns": [
			{ "data": "spk_date"},
			{ "data": "customer"},
			{ "data": "po_customer"},
			{ "data": "no_so"},
			{ "data": "duration"},
	    ],
	    "lengthMenu": [[10, -1], [10, "All"]],
	    iDisplayLength: 10,
	    dom: 'Bfrtip',
        buttons: [
        {
        	extend: 'excel',
        	text: 'Export to Excel',
        	filename : 'DO-Waiting_list',
        	footer: true,
        	title: 'Delivery Order Waiting',
            exportOptions: {
            	columns: [ 0, 1, 2, 3, 4, 5 ]
            }
        },
        'pageLength'
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

	///////////////////////////////////////////////////////////
  	// Proses DO button
	///////////////////////////////////////////////////////////

	$(document).on('click', '.function_process', function(e){
		e.preventDefault();
		show_loading_message();
	    var id      = $(this).data('id');
	    var request = $.ajax({
			url:          pathFile+"/delivery-order/item/"+id,
			type:         'GET',
			beforeSend: function (xhr) {
				xhr.setRequestHeader('Authorization', getCookie('access_token'));
				xhr.setRequestHeader('Content-Type', 'application/json');
			}
		  });
	    request.done(function(output){
	    	if(output.status == "success"){
				$('H2.FormTitle').text('INPUT '+FormsLug);
				$(IDForm).attr('class', 'form ProsesSJ');
	        	$(IDForm +'').attr('data-id', id);
	        	$('#spk_date').val(output.response.data[0].spk_date);
		        $('#customer').val(output.response.data[0].customer);
		        $('#po_customer').val(output.response.data[0].po_customer);
	        	$('#shipto').val(output.response.data[0].shipto);
	        	for(var i = 0; i<output.response.data.length; i++){
	        		if(output.response.data[i].req_qty == 0){
	        			var inputQTY = '<input type="number" class="form-control" name="data[qty][]" id="qty" value="0" readonly>';
	        		} else {
	        			var inputQTY = '<input type="number" min="0" class="form-control" name="data[qty][]" id="qty" placeholder="0" required>';
	        		}

	        		$('.datanyanih').append(
		        		'<div class="looping-item"><hr><div class="form-group no_spk"><label for="no_spk">No SO: <span class="required">*</span></label><input type="text" class="form-control" name="no_so" id="no_so" value="'+output.response.data[i].no_so+'" readonly></div><div class="form-group"><label for="item">Nama Barang:</label><input type="text" class="form-control" name="data[item][]" id="item" value="'+output.response.data[i].item+'" readonly></div><div class="form-group"><label for="req_qty">Request qty:</label><input type="text" class="form-control" name="data[req_qty][]" id="req_qty" value="'+output.response.data[i].req_qty+'" readonly></div><div class="form-group"><label for="qty">Send qty: <span class="required">*</span></label>'+inputQTY+'</div><div class="form-group"><label for="Unit">Satuan:</label><input type="text" class="form-control" name="data[unit][]" id="unit" value="'+output.response.data[i].unit+'" readonly></div><input type="hidden" class="form-control" name="data[item_to][]" id="item_to" value="'+output.response.data[i].item_to+'"></div>'
		        		);
	        	}

	        	$.ajax({
					url: pathFile+"/delivery-order/number",
					type:'GET',
					beforeSend: function (xhr) {
						xhr.setRequestHeader('Authorization', getCookie('access_token'));
						xhr.setRequestHeader('Content-Type', 'application/json');
					},
	        		success: function(output){
	        			$('#no_sj').val(output.response.data[0].no_sj);
	        			show_lightbox();
	        			hide_loading_message();
	        		}
	        	});

	    	} else {
	    		hide_loading_message();
	        	show_message('Failed: '+output.response.message, 'error');
	    	}

	    });
	    request.fail(function(jqXHR, textStatus){
	    	hide_loading_message();
			show_message('Gagal mengambil data: '+textStatus, 'error');
	      	clean_value_elemen();
	    });
	});

	///////////////////////////////////////////////////////////
  	// Proses DO submit
	///////////////////////////////////////////////////////////

  	$(document).on('submit', IDForm+'.ProsesSJ', function(e){
    	e.preventDefault();
    	// Validate form
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
		
						var value = (fieldName === 'qty' || fieldName === 'item_to')? parseInt(item.value): item.value;
						dataGroups[index][fieldName] = value;
						arr.push(matches[1]);
					}

				} else {
					var value = (item.name === 'order_grade' || item.name === 'tax' || item.name === 'customerid' || item.name === 'companyid')? parseInt(item.value) : item.value; 
					formDataObject[item.name] = value;
				}
			});

			formDataObject.id = parseInt(id);

			for (var key in dataGroups) {
				formDataObject.items.push(dataGroups[key]);
			}

      		var request   = $.ajax({
				url:          pathFile+"/delivery-order",
				type:         'POST',
				data:         JSON.stringify(formDataObject, null, 2),
				beforeSend: function (xhr) {
					xhr.setRequestHeader('Authorization', getCookie('access_token'));
					xhr.setRequestHeader('Content-Type', 'application/json');
				}
     		});
      		request.done(function(output){
        		if(output.status == "success"){
          			tablenya.api().ajax.reload(function(){
            			hide_loading_message();
            			var Infos = $('#customer').val();
            			show_message("'"+Infos+"' create successfully.", 'success');
          			}, true);
          			clean_value_elemen();
        		} else {
          			hide_loading_message();
          			show_message('Failed: '+output.response.message, 'error');
          			clean_value_elemen();
        		}
      		});
     		request.fail(function(jqXHR, textStatus){
        		hide_loading_message();
        		show_message('Failed: '+jqXHR.responseJSON.response.message, 'error');
        		clean_value_elemen();
      		});
    	}
  	});
});