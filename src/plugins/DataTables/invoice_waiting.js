$(document).ready(function(){

	//////////////////////////
	//Default config
	/////////////////////////

	var idTablenya = $('#tablenya');
	var pathFile = decodeURIComponent(getCookie('base_url_api')) +':'+ getCookie('base_port_api') + decodeURIComponent(getCookie('base_path_api')) + decodeURIComponent(getCookie('base_dashboard_api'));
	var IDForm = "#form_print";

	///////////////////////////
	// On page load: datatable
	///////////////////////////

	let arr = {};
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
		'bPaginate': false,
		'scrollCollapse': true,
		'scrollY': '600px',
	    "ajax": {
			"url" : pathFile+"/invoice",
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
	    'columnDefs': [
	    	{
	    		'targets': 0,
	            'checkboxes': {
	               'selectRow': true
	            }
					
	        },
			{
	    		'targets': [0,1,2,3,4,6,8,9,10,11,12,13],
	            'className': 'dt-nowrap'
	        },
			{
                "targets": [8,9,10,11,12],
                "render": function(data, type, row) {		
                    return new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        minimumFractionDigits: 2
                    }).format(parseFloat(data));
                }
            }
	    ],
	    'select': {
         	'style': 'multi'
      	},
	    "columns": [
			{ "data": "id" },
			{ "data": "sj_date" },
			{ "data": "customer"},
			{ "data": "po_customer" },
			{ "data": "no_so" },
			{ "data": "no_sj"},
			{ "data": "send_qty"},
			{ "data": "unit"},
			{ "data": "price"},
			{ "data": "bill"},
			{ "data": "ppn"},
			{ "data": "total"},
			{ "data": "cost"},
			{
				"data": "id",
				"render": function(data, type, row, meta) {
					if(arr[data]) {
						return "";
					} else {
						arr[data] = true;
						return '<button class="btn btn-default function_process" data-id="'+ row.id +'" data-name="'+ row.no_sj +'" title="Create Invoice"><i class="fa fa-share"></i></button>';
					}

				}
			},
	    ],
	    "lengthMenu": [[-1], ["All"]],
	    iDisplayLength: -1,
		dom: 'Bfrtp',
	    buttons: [ 
	    'pageLength',
	    { 
	    	text: '<span class="custom_procces">Create Invoice custom</span>',
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
            
            Bills = api.column( 9, { page: 'current'} ).data().reduce( function (a, b) {
            	return intVal(a) + intVal(b);
            }, 0 );

            Ppns = api.column( 10, { page: 'current'} ).data().reduce( function (a, b) {
            	return intVal(a) + intVal(b);
            }, 0 );

            Totals = Bills + Ppns;
            
            Ship_cost = api.column( 12, { page: 'current'} ).data().reduce( function (a, b) {
            	return intVal(a) + intVal(b);
            }, 0 );

            $( api.column( 9 ).footer() ).html(Rupiah(Bills));
            $( api.column( 10 ).footer() ).html(Rupiah(Ppns));
            $( api.column( 11 ).footer() ).html(Rupiah(Totals));
            $( api.column( 12 ).footer() ).html(Rupiah(Ship_cost));
        }
	});

	var buttons = new $.fn.dataTable.Buttons(tablenya, {
		buttons:[
        {
        	extend: 'excelHtml5',
        	messageTop: false,
        	footer: true,
        	text: 'Export to Excel',
        	filename : 'INVOICE-Waiting',
        	title: 'Invoice Waiting',
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

	function Rupiah(angka){
		var checked = angka.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
		var filter = 'Rp. ' + checked.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
		return filter;
	}

	// Lightbox close button
	$(document).on('click', '.lightbox_close', function(){
	    hide_lightbox();
	    hide_invoice();
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

	/////////////////////////////////////////
  	// Modal Invoice
	////////////////////////////////////////

	function show_invoice(){
		$('#InvoiceModal').show();
	}

	function hide_invoice(){
		$('#InvoiceModal').hide();
	}

	/////////////////////////////////////////
  	// Single invoice
	////////////////////////////////////////

  	$(document).on('click', '.function_process', function(e){
	    e.preventDefault();
	    var id = $(this).data('id');
	    var name = $(this).data('name');
	    if(confirm("Anda yakin ingin membuat faktur '"+name+"'?")){
	    	show_invoice();
	    	$('#form_inputINV').attr('data-id', id);
	    	$('#form_inputINV').attr('class', 'form single');
	    }
  	});

  	$(document).on('submit', '#form_inputINV.single', function(e){
	    e.preventDefault();
	    hide_invoice();
	    show_loading_message();
		var jsonData = {};
	    var id       = $(this).data('id');
	    var date     = $('#date').val();

		jsonData.id = id;
		jsonData.invoice_date = date;

      	var request = $.ajax({
	    	url:          pathFile+"/invoice",
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
            		show_message("Create successfully.", 'success');
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
	});

  	/////////////////////////////////////////
  	// Multiple invoice
	////////////////////////////////////////

	$(document).on('click', '.custom_procces', function(e){
		e.preventDefault();
		var form = $('#create_invoice');
		var rows_selected = tablenya.column(0).checkboxes.selected();		 
		if(typeof rows_selected[0] === 'undefined'){
			alert("Silahkan pilih dan centang terlebih dahulu.");

		} else {
			$.each(rows_selected, function(index, rowId){
				$(form).append(
					$('<input>').attr('type', 'hidden').attr('name', 'id[]').val(rowId)
				);
			});

			if(confirm("Anda yakin ingin membuat kustom faktur yang dicentang?")){
		      	var id = rows_selected.join(",");
		    	show_invoice();
		    	$('#form_inputINV').attr('data-id', id);
		    	$('#form_inputINV').attr('class', 'form multiple');
			}

			$('input[name="id\[\]"]', form).remove();
		}
	});

	$(document).on('submit', '#form_inputINV.multiple', function(e){
	    e.preventDefault();
	    hide_invoice();
	    show_loading_message();
		var jsonData = {};

		var id       = $(this).data('id');
	    var date     = $('#date').val();

		jsonData.id = id;
		jsonData.invoice_date = date;

      	var request = $.ajax({
	    	url:          pathFile+"/invoice",
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
            		show_message("Create successfully.", 'success');
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
	});
});