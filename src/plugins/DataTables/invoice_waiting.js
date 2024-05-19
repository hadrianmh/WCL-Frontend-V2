$(document).ready(function(){

	//////////////////////////
	//Default config
	/////////////////////////

	var idTablenya = $('#tablenya');
	var pathFile = '../auth/invoice_waiting.php';
	var Act = 'action';
	var sLug = 'invoice_waiting';
	var IDForm = "#form_print";

	//Message alert
	var sukses = 'success';

	///////////////////////////
	// On page load: datatable
	///////////////////////////

	var tablenya = idTablenya.DataTable({  
		'scrollX': true,
		'bPaginate': false,
	    "ajax": pathFile+"?"+Act+"=result_"+sLug,
	    'columnDefs': [
	    	{
	    		'targets': 0,
	            'checkboxes': {
	               'selectRow': true
	            }

	        },
	        {
	        	'targets': [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14],
	            'className': 'dt-nowrap'
	        }
	    ],
	    'select': {
         	'style': 'multi'
      	},
	    "columns": [
	      { "data": "id" },
	      { "data": "no" },
	      { "data": "sj_date" },
	      { "data": "customer"},
	      { "data": "no_po" },
	      { "data": "no_so" },
	      { "data": "no_sj"},
	      { "data": "send_qty"},
	      { "data": "unit"},
	      { "data": "price"},
	      { "data": "bill"},
	      { "data": "ppn"},
	      { "data": "total"},
	      { "data": "shipping_costs"},
	      { "data": "functions","sClass": "functions" }
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

            $( api.column( 10 ).footer() ).html(Rupiah(Bills));
            $( api.column( 11 ).footer() ).html(Rupiah(Ppns));
            $( api.column( 12 ).footer() ).html(Rupiah(Totals));
            $( api.column( 13 ).footer() ).html(Rupiah(Ship_cost));
        }
	});

	//////////////////////////////////////////////////////////////
	// Create function print all with hidden table
	/////////////////////////////////////////////////////////////

	var tablePrint = $("#tablePrint").DataTable({
		"scrollX": true,
		"bPaginate": false,
		"searching": false,
		"info": false,
	    "ajax": pathFile+"?"+Act+"=resultAll_"+sLug,
	    "columns": [
	    { "data": "no" },
	      { "data": "sj_date" },
	      { "data": "customer"},
	      { "data": "no_po" },
	      { "data": "no_so" },
	      { "data": "no_sj"},
	      { "data": "send_qty"},
	      { "data": "unit"},
	      { "data": "price"},
	      { "data": "bill"},
	      { "data": "ppn"},
	      { "data": "total"},
	      { "data": "shipping_costs"},
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

	var buttons = new $.fn.dataTable.Buttons(tablePrint, {
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

  	$(document).on('click', '.function_process a', function(e){
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
	    var id      = $(this).data('id');
      	var ex 		= id.split('-');
      	var date 	= $('#date').val();
      	var request = $.ajax({
        	url:          pathFile+"?"+Act+"=create_"+sLug,
        	data : {
        		id: ex[0],
        		id_sj : ex[1],
        		date: date,
        	},
        	cache:        false,
        	type:         'POST'
      	});
      	
      	request.done(function(output){
      		var obj = JSON.parse(output);
        	if (obj.result == sukses){
          		tablenya.ajax.reload(function(){
          			tablePrint.ajax.reload();
            		hide_loading_message();
            		show_message("Berhasil diproses.", 'success');
          		}, true);
        	} else {
          		hide_loading_message();
          		show_message('Gagal memproses', 'error');
       		}
      	});
      	
      	request.fail(function(jqXHR, textStatus){
        	hide_loading_message();
        	show_message('Gagal memproses: '+textStatus, 'error');
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
	    var id      = $(this).data('id');
      	var date 	= $('#date').val();
      	var request = $.ajax({
        	url:          pathFile+"?"+Act+"=create_custom_"+sLug,
        	data : {
        		id: id,
        		date: date,
        	},
        	cache:        false,
        	type:         'POST'
      	});
      	
      	request.done(function(output){
      		var obj = JSON.parse(output);
        	if (obj.result == sukses){
          		tablenya.ajax.reload(function(){
          			tablePrint.ajax.reload();
            		hide_loading_message();
            		show_message("Berhasil diproses.", 'success');
          		}, true);
        	} else {
          		hide_loading_message();
          		show_message('Gagal memproses', 'error');
       		}
      	});
      	
      	request.fail(function(jqXHR, textStatus){
        	hide_loading_message();
        	show_message('Gagal memproses: '+textStatus, 'error');
      	});
	});
});