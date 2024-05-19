$(document).ready(function(){

	//////////////////////////
	//Default config
	/////////////////////////

	var idTablenya = $('#tablenya');
	var pathFile = '../auth/delivery_orders_waiting.php';
	var Act = 'action';
	var sLug = 'delivery_orders_waiting';
	var FormsLug = 'SURAT JALAN';
	var IDForm = "#form_inputSJ";
	var prosesButton = ".function_process a";

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
		"scrollX": true,
	    "ajax": pathFile+"?"+Act+"=result_"+sLug,
	    'columnDefs': [
	    	{
	    		'targets': [0,1,2,3,5,6],
	            'className': 'dt-nowrap'
	        }
	    ],
	    "columns": [
	      { "data": "no" },
	      { "data": "spk_date"},
	      { "data": "customer"},
	      { "data": "po_customer"},
	      { "data": "no_spk"},
	      { "data": "duration"},
	      { "data": "functions","sClass": "functions" }
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

	$(document).on('click', prosesButton, function(e){
		e.preventDefault();
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
	    		$('H2.FormTitle').text('INPUT '+FormsLug);
				$(IDForm).attr('class', 'form ProsesSJ');
	        	$(IDForm +'').attr('data-id', id);
	        	$('#spk_date').val(output.data[0].spk_date);
		        $('#customer').val(output.data[0].customer);
		        $('#po_customer').val(output.data[0].po_customer);
	        	$('#shipto').val(output.data[0].shipto);
	        	for(var i = 0; i<output.data.length; i++){
	        		if(output.data[i].req_qty == 0){
	        			var inputQTY = '<input type="number" class="form-control" name="data[qty][]" id="qty" value="0" readonly>';
	        		} else {
	        			var inputQTY = '<input type="number" min="0" class="form-control" name="data[qty][]" id="qty" placeholder="0" required>';
	        		}

	        		$('.datanyanih').append(
		        		'<div class="looping-item"><hr><div class="form-group no_spk"><label for="no_spk">No SO: <span class="required">*</span></label><input type="text" class="form-control" name="no_so" id="no_so" value="'+output.data[i].no_so+'" readonly></div><div class="form-group"><label for="item">Nama Barang:</label><input type="text" class="form-control" name="data[item][]" id="item" value="'+output.data[i].item+'" readonly></div><div class="form-group"><label for="req_qty">Request qty:</label><input type="text" class="form-control" name="data[req_qty][]" id="req_qty" value="'+output.data[i].req_qty+'" readonly></div><div class="form-group"><label for="qty">Send qty: <span class="required">*</span></label>'+inputQTY+'</div><div class="form-group"><label for="Unit">Satuan:</label><input type="text" class="form-control" name="data[unit][]" id="unit" value="'+output.data[i].unit+'" readonly></div><input type="hidden" class="form-control" name="data[item_to][]" id="item_to" value="'+output.data[i].item_to+'"></div>'
		        		);
	        	}

	        	$.ajax({
	        		url:			pathFile+"?"+Act+"=no_sj_"+sLug,
	        		cache:      	false,
	        		dataType:		'json',
	        		contentType:	'application/json; charset=utf-8',
	        		type:			'get',
	        		success: function(get_nosj){
	        			$('#no_sj').val(get_nosj.data);
	        			show_lightbox();
	        			hide_loading_message();
	        		}
	        	});

	    	} else {
	    		hide_loading_message();
	        	show_message('Gagal mengambil data', 'error');
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
      		var id        = $(IDForm).attr('data-id');
      		var form_data = $(IDForm).serialize();
      		var request   = $.ajax({
        		url:          pathFile+"?"+Act+"=add_"+sLug+"&id="+id,
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
            			var Infos = $('#customer').val();
            			show_message("'"+Infos+"' berhasil diproses.", 'success');
          			}, true);
          			clean_value_elemen();
        		} else {
          			hide_loading_message();
          			show_message('Gagal diproses', 'error');
          			clean_value_elemen();
        		}
      		});
     		request.fail(function(jqXHR, textStatus){
        		hide_loading_message();
        		show_message('Gagal diproses: '+textStatus, 'error');
        		clean_value_elemen();
      		});
    	}
  	});
});