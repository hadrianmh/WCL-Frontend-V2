$(document).ready(function(){

	//////////////////////////
	//Default config
	/////////////////////////

	var IDFormAPI = $('#API');
	var IDFormBANK = $('#BANK');
	var FormNYA1 = $(IDFormAPI);
	var FormNYA2 = $(IDFormBANK);
  	FormNYA1.validate();
  	FormNYA2.validate();
	var pathFile = '../auth/setting.php';
	var Act = 'action';
	var sukses = 'success';
	var so_attribute = [];
	var po_attribute = [];

	$.ajax({
		url: pathFile+'?action=so_attribute',
		dataType: 'JSON',
		type: 'GET',
		contentType: 'application/json; charset=utf-8',
		success: function(output){
            so_attribute = output.data[0];
		}
	});

	$.ajax({
		url: pathFile+'?action=po_attribute',
		dataType: 'JSON',
		type: 'GET',
		contentType: 'application/json; charset=utf-8',
		success: function(output){
            po_attribute = output.data[0];
		}
	});

    function arrayUnique(array)
    {
        var a = array.concat();
        for(var i=0; i<a.length; ++i)
        {
            for(var j=i+1; j<a.length; ++j) {
                if(a[i] === a[j]) a.splice(j--, 1);
            }
        }
        return a;
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

	function hide_ipad_keyboard(){
	    document.activeElement.blur();
	    $('input').blur();
	}

	// Show lightbox
	function show_lightbox(){
		$('#myModal').show();
	}

	// Hide lightbox
	function hide_lightbox(){
		$('#myModal').hide();
	}

	//Reset value

	function reset(){
		$('#item').val('');
        $('#type').val('');
		$('#attribute').val('');
		$('.tag').remove();
		$('#attr_input').importTags('');
		$('#attr_print').importTags('');
	}

	// Lightbox close button
	$(document).on('click', '.lightbox_close', function(){
	    hide_lightbox();
	    reset();
	});

  	/////////////////////
  	// Simpan API Url button
	////////////////////
	$(document).on('click', '.simpanURL', function(e){
		e.preventDefault();
		if (FormNYA1.valid() == true){
		    show_loading_message();
		    var form_data = $(IDFormAPI).serialize();
		    var request = $.ajax({
		    	url:          pathFile+"?"+Act+"=simpan",
		      	cache:        false,
		      	data:         form_data,
		      	dataType:     'json',
		      	contentType:  'application/json; charset=utf-8',
		      	type:         'get'
		    });
		    request.done(function(output){
		    	if (output.result == sukses){
		        	hide_loading_message();
        			show_message("Berhasil disimpan", 'success');
		      	} else {
		        	hide_loading_message();
		        	show_message('Gagal menyimpan data', 'error');
		      	}
		    });
		    request.fail(function(jqXHR, textStatus){
		    	hide_loading_message();
		      	show_message('Gagal menyimpan data: '+textStatus, 'error');
		    });
		}
	});

	/////////////////////
  	// Sinkronisasi button
	////////////////////
	$(document).on('click', '.sincata', function(e){
		e.preventDefault();
		if (FormNYA1.valid() == true){
		    show_loading_message();
		    var form_data = $(IDFormAPI).serialize();
		    var request = $.ajax({
		    	url:          pathFile+"?"+Act+"=sinkronisasi",
		      	cache:        false,
		      	data:         form_data,
		      	dataType:     'json',
		      	contentType:  'application/json; charset=utf-8',
		      	type:         'get'
		    });
		    request.done(function(output){
		    	if (output.result == sukses){
		        	hide_loading_message();
        			show_message("Berhasil sinkronisasi", 'success');
		      	} else {
		        	hide_loading_message();
		        	show_message('Gagal sinkronisasi', 'error');
		      	}
		    });
		    request.fail(function(jqXHR, textStatus){
		    	hide_loading_message();
		      	show_message('Gagal sinkronisasi: '+textStatus, 'error');
		    });
		}
	});

	/////////////////////
  	// Simpan BANK
	////////////////////
	$(document).on('click', '.simpanBANK', function(e){
		e.preventDefault();
		if (FormNYA2.valid() == true){
		    show_loading_message();
		    var form_data = $(IDFormBANK).serialize();
		    var request = $.ajax({
		    	url:          pathFile+"?"+Act+"=simpanBANK",
		      	cache:        false,
        		data:         form_data,
        		method: 	  'POST'
		    });
		    request.done(function(output){
		    	var obj = JSON.parse(output);
		    	if (obj.result == sukses){
		        	hide_loading_message();
        			show_message("Berhasil disimpan", 'success');
		      	} else {
		        	hide_loading_message();
		        	show_message('Gagal menyimpan data', 'error');
		      	}
		    });
		    request.fail(function(jqXHR, textStatus){
		    	hide_loading_message();
		      	show_message('Gagal menyimpan data: '+textStatus, 'error');
		    });
		}
	});

	var barisN = 1;
	$(document).on('click', '.tambah_bank', function(e){
		e.preventDefault();
		barisN++;
		$('#looping_bank').append(
		'<div class="looping_bank" id="looping-'+barisN+'" ><hr class="looping-bank"><p><button type="button" name="remove" idx="'+barisN+'" class="btn btn-danger btn_remove">Hapus</button></p><div class="form-group bank"><label for="Bank">Nama Bank: <span class="required">*</span></label><input type="text" class="form-control" name="data[bank][]" id="bank" placeholder="Mandiri" required></div><div class="form-group norek"><label for="norek">No Rekening: <span class="required">*</span></label><input type="text" class="form-control" name="data[norek][]" id="norek" placeholder="12900010690001" required></div><div class="form-group atasnama"><label for="atasnama">Atas Nama: <span class="required">*</span></label><input type="text" class="form-control" name="data[atasnama][]" id="atasnama" placeholder="PT. Swallow Makmur" required></div>');
	});

	$(document).on('click', '.btn_remove', function(){
		var button_id = $(this).attr('idx');
		$('#looping-'+button_id+'').remove();
	});

	/////////////////////////////
	/////// View log button
	////////////////////////////

	$(document).on('click', '.viewLog', function(e){
		e.preventDefault();
		show_loading_message();
		var request = $.ajax({
	    	url:          '../auth/setting.php?action=result',
	      	cache:        false,
	      	dataType:     'json',
	      	contentType:  'application/json; charset=utf-8',
	      	type:         'get'
	    });
	    request.done(function(output){
	    	if (output.result == sukses){
	    		hide_loading_message();
	    		$('.btn-container_log').remove();
	    		$("#tablenya").show();

	    		var tablenya = $('#tablenya').dataTable({
	    			"ajax": '../auth/setting.php?action=result',
				    "columns": [
				      { "data": "no" },
				      { "data": "user"},
				      { "data": "date" },
				      { "data": "query" },
				      { "data": "data"}
				    ],
				    "lengthMenu": [[10, 100, 1000, -1], [10, 100, 1000, "All"]],
				    iDisplayLength: 10,
				    dom: 'Bfrtip',
			        buttons: [
			        'pageLength',
			        {
			        	text: 'Refresh Log',
			        	action: function ( e ) {
			        		e.preventDefault();
			        		location.reload();
			        	}
			        }
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

	    	
	    	} else {
	        	hide_loading_message();
	        	show_message('Gagal mengambil data.', 'error');
	      	}

	     });

	    request.fail(function(jqXHR, textStatus){
	    	hide_loading_message();
	      	show_message('Gagal mengambil data: '+textStatus, 'error');
	    });
	});


	//////////////////////////////////////////////////////////
	/////// View button (item list)
	/////////////////////////////////////////////////////////

	$(document).on('click', '.item_view', function(e){
		e.preventDefault();
        merged_input = arrayUnique(po_attribute['input'].concat(so_attribute['input']));
        merged_print = arrayUnique(po_attribute['print'].concat(so_attribute['print']));
		show_loading_message();
		var request = $.ajax({
	    	url:          '../auth/setting.php?action=item_result',
	      	cache:        false,
	      	dataType:     'json',
	      	contentType:  'application/json; charset=utf-8',
	      	type:         'get'
	    });
	    request.done(function(output){
	    	if (output.result == sukses){
	    		hide_loading_message();
	    		$('.btn_item').remove();
	    		$("#item_table").show();
                $('#attr_input').tagsInput({
                    'autocomplete': {
                        source: merged_input
                    },
                    placeholder: 'Add a attribute',
                });

                $('#attr_print').tagsInput({
                    'autocomplete': {
                        source: merged_print
                    },
                    placeholder: 'Add a attribute',
                });
	    		var item_table = $('#item_table').DataTable({
	    			"data" : output.data,
	    			'columnDefs': [
				    	{
				    		'targets': [0,1,2,5],
				            'className': 'dt-nowrap'
				        }
				    ],
				    "columns": [
				      { "data": "no" },
				      { "data": "item"},
                      { "data": "type"},
				      { "data": "input"},
				      { "data": "print"},
				      { "data": "functions","sClass": "functions" }
				    ],
				    "lengthMenu": [[10, -1], [10, "All"]],
				    iDisplayLength: 10,
				    dom: 'Bfrtip',
			        buttons: [ 
			        'pageLength',
			        {
			        	text: 'Refresh Item',
			        	action: function ( e ) {
			        		e.preventDefault();
			        		location.reload();
			        	}
			        },
			        {
			        	text: 'Add Item',
			        	action: function ( e ) {
			        		e.preventDefault();
			        		show_lightbox();
			        		$('H2.FormTitle').text('TAMBAH ITEM');
			        		$('#form_input').attr('class', 'form item_add');
			        		$('#form_input').attr('data-id', '');
			        		$('#form_input .field_container label.error').hide();
			        	}
			        }
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

	    	
	    	} else {
	        	hide_loading_message();
	        	show_message('Gagal mengambil data.', 'error');
	      	}

	     });

	    request.fail(function(jqXHR, textStatus){
	    	hide_loading_message();
	      	show_message('Gagal mengambil data: '+textStatus, 'error');
	    });
	});

	//////////////////////////////////////////////////////
  	// Add submit form (sales order item)
  	/////////////////////////////////////////////////////

  	$(document).on('submit', '#form_input.item_add', function(e){
    	e.preventDefault();
	    // Validate form
	    if ($('#form_input').valid() == true){
	      	hide_ipad_keyboard();
	      	hide_lightbox();
	      	show_loading_message();
	      	var Infos = $('#item').val();
	      	var form_data = $('#form_input').serialize();
	      	var request   = $.ajax({
	        	url:          pathFile+"?"+Act+"=item_add",
	        	cache:        false,
	        	data:         form_data,
	        	dataType:     'json',
	        	contentType:  'application/json; charset=utf-8',
	        	type:         'get'
	      	});
	      	request.done(function(output){
	        	if (output.result == sukses)
	        	{
					hide_loading_message();
					reset();
					show_message("'"+Infos+"' berhasil dimasukkan.", 'success');
					$('#item_table').DataTable().ajax.url('../auth/setting.php?action=item_result').load();
					$('#item_table').DataTable().draw();
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

  	//////////////////////////////////////////
  	// Edit button (sales order item)
	/////////////////////////////////////////
	$(document).on('click', '.btn-edit', function(e){
		e.preventDefault();
	    show_loading_message();
	    var id      = $(this).data('id');
	    var request = $.ajax({
	    	url:          pathFile+"?"+Act+"=item_get",
	      	cache:        false,
	      	data:         'id='+id,
	      	dataType:     'json',
	      	contentType:  'application/json; charset=utf-8',
	      	type:         'get'
	    });
	    request.done(function(output){
	    	if (output.result == sukses){
	    		$('h2.FormTitle').text('EDIT ITEM');
	        	$('#form_input').attr('class', 'form item_edit');
	        	$('#form_input').attr('data-id', id);
	        	$('#form_input .field_container label.error').hide();
				$('#item').val(output.data[0].item);
                $('#type').val(output.data[0].type);
				$('#attribute_input').val(output.data[0].input);
				$('#attribute_print').val(output.data[0].print);
				var attribute_input = output.data[0].input.split(',');
				var attribute_print = output.data[0].print.split(',');
				for(var a = 0; a < attribute_input.length; a++){
					if(attribute_input[a]){
						$('#attr_input').addTag(attribute_input[a]);
					}
				}

				for(var b = 0; b < attribute_print.length; b++){
					if(attribute_print[b]){
						$('#attr_print').addTag(attribute_input[b]);
					}
				}
                if(output.data[0].type === 'PO_ITEM')
                {
                    $('#input_list').val('[' + po_attribute['input'].join('], [') + ']');
                    $('#print_list').val('[' + po_attribute['print'].join('], [') + ']');
                } else {
                    $('#input_list').val('[' + so_attribute['input'].join('], [') + ']');
                    $('#print_list').val('[' + so_attribute['print'].join('], [') + ']');
                }
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

	//////////////////////////////////////////////////////
	// Edit submit form (sales order item)
  	////////////////////////////////////////////////////

  	$(document).on('submit', '#form_input.item_edit', function(e){
    	e.preventDefault();
    	if ($('#form_input').valid() == true){
      		hide_ipad_keyboard();
      		hide_lightbox();
      		show_loading_message();
      		var Infos = $('#item').val();
      		var id        = $('#form_input').attr('data-id');
      		var form_data = $('#form_input').serialize();
      		var request   = $.ajax({
        		url:          pathFile+"?"+Act+"=item_edit&id="+id,
        		cache:        false,
        		data:         form_data,
        		dataType:     'json',
        		contentType:  'application/json; charset=utf-8',
        		type:         'get'
     		});
      		request.done(function(output){
        		if (output.result == sukses)
        		{
        			$('#item_table').DataTable().ajax.url('../auth/setting.php?action=item_result').load();
					$('#item_table').DataTable().draw();
        			hide_loading_message();
        			reset();
        			show_message("'"+Infos+"' berhasil diubah.", 'success');
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

  	////////////////////////////////////////
  	// Delete button (sales order item)
  	//////////////////////////////////////

  	$(document).on('click', '.btn-del', function(e){
	    e.preventDefault();
	    var Infos = $(this).data('name');
	    if (confirm("Anda yakin ingin menghapus '"+Infos+"'?")){
	    	show_loading_message();
	      	var id      = $(this).data('id');
	      	var request = $.ajax({
	        	url:          pathFile+"?"+Act+"=item_del&id="+id,
	        	cache:        false,
	        	dataType:     'json',
	        	contentType:  'application/json; charset=utf-8',
	        	type:         'get'
	      	});
	      	
	      	request.done(function(output){
	        	if (output.result == sukses)
	        	{
	          		$('#item_table').DataTable().ajax.url('../auth/setting.php?action=item_result').load();
					$('#item_table').DataTable().draw();
					hide_loading_message();
					show_message("'"+Infos+"' deleted successfully.", 'success');
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

    $(document).on('change', '#type', function(e){
        e.preventDefault();
        var selected = $(this).val();
        if(selected === 'SO_ITEM')
        {
            $('#input_list').val('[' + so_attribute['input'].join('], [') + ']');
            $('#print_list').val('[' + so_attribute['print'].join('], [') + ']');

        } else {
            $('#input_list').val('[' + po_attribute['input'].join('], [') + ']');
            $('#print_list').val('[' + po_attribute['print'].join('], [') + ']');
        }
    });
});