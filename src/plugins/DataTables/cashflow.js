$(document).ready(function(){

	/////////////////////////////////////////////////////////////////
	//Default config
	/////////////////////////////////////////////////////////////////

	var idTablenya = $('#tablenya');
	var pathFile = '../auth/cashflow.php';
	var Act = 'action';
	var sLug = 'cashflow';
	var FormsLug = 'KAS';
	var IDForm = "#form_inputKAS";
	var sukses = 'success'; //Message alert

	var mm = ("0" + (new Date().getMonth() + 1)).slice(-2);
	var yyyy = new Date().getFullYear();
	var arsip = yyyy+"/"+mm;


	/////////////////////////////////////////////////////////////////
	// On page load: datatable
	////////////////////////////////////////////////////////////////
	
	var tablenya = idTablenya.dataTable({
		'scrollX': true,
		'bPaginate': false,
	    'ajax': pathFile+"?"+Act+"=result_"+sLug+"&curMonth="+getCookie("selectMonth"),
	    "columns": [
	      	{ "data": "no" },
	      	{ "data": "tgl" },
	      	{ "data": "nama"},
	      	{ "data": "tujuan" },
	      	{ "data": "keterangan"},
	      	{ "data": "masuk"},
	      	{ "data": "keluar"},
	      	{ "data": "sisa" },  
	      	{ "data": "functions","sClass": "functions" }
	    ],
	    iDisplayLength: -1,
	    dom: 'Bfrti',
        buttons: [
        {
        	extend: 'csv',
        	text: 'Export to CSV',
        	filename : 'CSV-Cashflow',
        	footer: true,
            exportOptions: {
            	columns: [ 1, 2, 3, 4, 5, 6, 7 ]
            }
        },
        {
        	extend: 'excel',
        	messageTop: false,
        	text: 'Export to Excel',
        	filename : 'EXCEL-Cashflow',
        	footer: true,
        	title: 'LAPORAN KAS '+getCookie("selectMonth"),
            exportOptions: {
            	columns: [ 1, 2, 3, 4, 5, 6, 7 ]
            }
        },
        ],

        "footerCallback": function ( row, data, start, end, display ) {
        	var api = this.api(), data;
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };
            uangMasuk = api.column( 5, { page: 'current'} ).data().reduce( function (a, b) {
            	return intVal(a) + intVal(b);
            }, 0 );

            uangKeluar = api.column( 6, { page: 'current'} ).data().reduce( function (a, b) {
            	return intVal(a) + intVal(b);
            }, 0 );

            Saldos = uangMasuk - uangKeluar;

            $( api.column( 5 ).footer() ).html(convertToRupiah(uangMasuk));
            $( api.column( 6 ).footer() ).html(convertToRupiah(uangKeluar));
            $( api.column( 7 ).footer() ).html(convertToRupiah(Saldos));
        }
	});

	/////////////////////////////////////////////////////////////////
	// On page load: form validation
	/////////////////////////////////////////////////////////////////
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
	    $("#tujuan").val('');
	    $("#keterangan").val('');
	    $("#uang").val('');
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

	function convertToRupiah(angka){
		var checked = angka.toString().split('.').join(',');
		var filter = 'Rp. ' + checked.replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1.");
		return filter;
	}

	function setCookie(cname, cvalue, exdays) {
	    var d = new Date();
	    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
	    var expires = "expires="+d.toUTCString();
	    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
	}

	function getCookie(cname) {
	    var name = cname + "=";
	    var ca = document.cookie.split(';');
	    for(var i = 0; i < ca.length; i++) {
	        var c = ca[i];
	        while (c.charAt(0) == ' ') {
	            c = c.substring(1);
	        }
	        if (c.indexOf(name) == 0) {
	            return c.substring(name.length, c.length);
	        }
	    }
	    return "";
	}

	/////////////////////////////////////////////////////////////////
	// Sort datatable from current month
	/////////////////////////////////////////////////////////////////

	var req = $.ajax({
		url: pathFile+"?"+Act+"=sortdata_"+sLug,
		cache: false,
		dataType: 'json',
		contentType: 'application/json; charset=utf-8',
		type: 'get'
	});

	req.done(function(output){
		if(output.result == sukses){
			for(var i = 0; i<output.data.length; i++){
				$("#sortby").append("<option value='"+output.data[i].montly+"' "+(getCookie("selectMonth") == output.data[i].montly ? 'selected' : '')+" >"+output.data[i].montly+"</option>");
			}
			setCookie("selectMonth", arsip, 1);

		} else {
	        show_message('Gagal memuat data', 'error');
		}
	});

	$(document).on('change', '#sortby', function(){
		var valMonth = $(this).find(":selected").val();
		setCookie("selectMonth", valMonth, 1);
	});

	$(document).on('click', '#LoadData', function(){
		var valMonth = $('#sortby').find(":selected").val();
		setCookie("selectMonth", valMonth, 1);
		location.reload();
	});


	/////////////////////////////////////////////////////////////////
	// Kas masuk button
  	/////////////////////////////////////////////////////////////////

  	$(document).on('click', '#KasMasuk', function(e){
  		e.preventDefault();
		show_lightbox();
		$(IDForm).attr('class', 'form masuk');
		$(IDForm +' .field_container label.error').hide();
		$('H2.FormTitle').text(FormsLug+" MASUK");
		$("label[for='uang']").text("Uang Masuk: *");
		$("#tgl").attr('readonly', false);
  	});

  	/////////////////////////////////////////////////////////////////
  	// Kas masuk form
  	////////////////////////////////////////////////////////////////

  	$(document).on('submit', IDForm+'.masuk', function(e){
    	e.preventDefault();
	    // Validate form
	    if (FormNYA.valid() == true){
	    	// Send company information to database
	      	hide_ipad_keyboard();
	      	hide_lightbox();
	      	show_loading_message();
	      	var form_data = $(IDForm).serialize();
	      	var request   = $.ajax({
	        	url:          pathFile+"?"+Act+"=masuk_"+sLug,
	        	cache:        false,
	        	data:         form_data,
	        	dataType:     'json',
	        	contentType:  'application/json; charset=utf-8',
	        	type:         'get'
	      	});
	      	request.done(function(output){
	        	if (output.result == sukses){
	        		$("#sortby").empty();

	          		// Reload datable
	          		tablenya.api().ajax.reload(function(){
	            		hide_loading_message();
	            		var Infos = $('#keterangan').val();
	            		show_message("'"+Infos+"' berhasil dimasukan.", 'success');
	            		$("#tujuan").val('');
	            		$("#keterangan").val('');
	            		$("#uang").val('');
	          		}, true);

	          		$.ajax({
	          			url: pathFile+"?"+Act+"=sortdata_"+sLug,
	          			cache: false,
	          			dataType: 'json',
	          			contentType: 'application/json; charset=utf-8',
	          			type: 'get',
	          			success: function(output){
	          				if(output.result == sukses){
	          					for(var i = 0; i<output.data.length; i++){
	          						$("#sortby").append("<option value='"+output.data[i].montly+"' "+(getCookie("selectMonth") == output.data[i].montly ? 'selected' : '')+" >"+output.data[i].montly+"</option>");
	          					}
	          					setCookie("selectMonth", arsip, 1);
	          				} else {
	          					show_message('Gagal memuat data', 'error');
	          				}
	          			}
	          		});

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

  	////////////////////////////////////////////////////////////////
	// Kas keluar button
  	////////////////////////////////////////////////////////////////

  	$(document).on('click', '#KasKeluar', function(e){
  		e.preventDefault();
		show_lightbox();
		$("#tgl").attr('readonly', false);
		$(IDForm).attr('class', 'form keluar');
		$(IDForm +' .field_container label.error').hide();
		$('H2.FormTitle').text(FormsLug+" KELUAR");
		$("label[for='uang']").text("Uang Keluar: *");
  	});

  	////////////////////////////////////////////////////////////////
  	// Kas keluar form
  	////////////////////////////////////////////////////////////////

  	$(document).on('submit', IDForm+'.keluar', function(e){
    	e.preventDefault();
	    // Validate form
	    if (FormNYA.valid() == true){
	    	// Send company information to database
	      	hide_ipad_keyboard();
	      	hide_lightbox();
	      	show_loading_message();
	      	var form_data = $(IDForm).serialize();
	      	var request   = $.ajax({
	        	url:          pathFile+"?"+Act+"=keluar_"+sLug,
	        	cache:        false,
	        	data:         form_data,
	        	dataType:     'json',
	        	contentType:  'application/json; charset=utf-8',
	        	type:         'get'
	      	});
	      	request.done(function(output){
	        	if (output.result == sukses){
	        		$("#sortby").empty();

	          		// Reload datable
	          		tablenya.api().ajax.reload(function(){
	            		hide_loading_message();
	            		var Infos = $('#keterangan').val();
	            		show_message("'"+Infos+"' berhasil dimasukan.", 'success');
	            		$("#tujuan").val('');
	            		$("#keterangan").val('');
	            		$("#uang").val('');
	          		}, true);

	          		$.ajax({
	          			url: pathFile+"?"+Act+"=sortdata_"+sLug,
	          			cache: false,
	          			dataType: 'json',
	          			contentType: 'application/json; charset=utf-8',
	          			type: 'get',
	          			success: function(output){
	          				if(output.result == sukses){
	          					for(var i = 0; i<output.data.length; i++){
	          						$("#sortby").append("<option value='"+output.data[i].montly+"' "+(getCookie("selectMonth") == output.data[i].montly ? 'selected' : '')+" >"+output.data[i].montly+"</option>");
	          					}
	          					setCookie("selectMonth", arsip, 1);
	          				} else {
	          					show_message('Gagal memuat data', 'error');
	          				}
	          			}
	          		});

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

  	////////////////////////////////////////////////////////////////
  	// Edit button
	////////////////////////////////////////////////////////////////

	$(document).on('click', '.function_edit a', function(e){
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
	        	$(IDForm +'').attr('class', 'form edit');
	        	$(IDForm +'').attr('data-id', id);
	        	$(IDForm +' .field_container label.error').hide();
	    		if(output.data[0].type == 0){
	    			$('h2.FormTitle').text('UBAH '+FormsLug+' MASUK');
	    			$("#uang").val(output.data[0].masuk);
		        	$("#type").val(output.data[0].type);
		        	$("label[for='uang']").text("Uang Masuk: *");  	

	    		} else if(output.data[0].type == 1){
	    			$('h2.FormTitle').text('UBAH '+FormsLug+' KELUAR');
		        	$("#uang").val(output.data[0].keluar);
		        	$("#type").val(output.data[0].type);
		        	$("label[for='uang']").text("Uang Keluar: *");
	    		}
	        	$("#tgl").val(output.data[0].tgl);
	        	$("#nama").val(output.data[0].nama);
	        	$("#tujuan").val(output.data[0].tujuan);
	        	$("#keterangan").val(output.data[0].keterangan);
	        	$("#tgl").attr('readonly', true);
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

  	////////////////////////////////////////////////////////////////
	// Edit submit form
  	////////////////////////////////////////////////////////////////

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
            			var Infos = $('#keterangan').val();
            			show_message("'"+Infos+"' berhasil mengubah.", 'success');
            			$("#tujuan").val('');
					    $("#keterangan").val('');
					    $("#uang").val('');
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

  	////////////////////////////////////////////////////////////////
  	// Delete button
  	////////////////////////////////////////////////////////////////

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
	        		$("#sortby").empty();
	        		
	          		// Reload datable
	          		tablenya.api().ajax.reload(function(){
	            		hide_loading_message();
	            		show_message("'"+Infos+"' berhasil dihapus.", 'success');
	          		}, true);

	          		$.ajax({
	          			url: pathFile+"?"+Act+"=sortdata_"+sLug,
	          			cache: false,
	          			dataType: 'json',
	          			contentType: 'application/json; charset=utf-8',
	          			type: 'get',
	          			success: function(output){
	          				if(output.result == sukses){
	          					for(var i = 0; i<output.data.length; i++){
	          						$("#sortby").append("<option value='"+output.data[i].montly+"' "+(getCookie("selectMonth") == output.data[i].montly ? 'selected' : '')+" >"+output.data[i].montly+"</option>");
	          					}
	          					setCookie("selectMonth", arsip, 1);
	          				} else {
	          					show_message('Gagal memuat data', 'error');
	          				}
	          			}
	          		});

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