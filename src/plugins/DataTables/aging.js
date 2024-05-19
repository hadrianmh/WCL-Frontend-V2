$(document).ready(function(){

	//////////////////////////
	//Default config
	/////////////////////////

	var idTablenya = $('#tablenya');
	var pathFile = '../auth/aging.php';
	var Act = 'action';
	var sLug = 'aging';
	var FormsLug = 'AGING';
	var IDForm = "#form_aging";
	var sukses = 'success'; //Message alert
	var barisN = 1;

	/////////////////////////////////////////////////////////////////
	// Set cookie as 'SelectMonth'
	/////////////////////////////////////////////////////////////////

	var mm = new Date().getMonth()+1;
	var yyyy = new Date().getFullYear();
	var arsip = yyyy+"/"+mm;

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

	//////////////////////////////////////////////////////////////
	// On page load: datatable
	//////////////////////////////////////////////////////////////

	var tablenya = idTablenya.DataTable({
		"scrollX": true,
	    "ajax": pathFile+"?"+Act+"=result_"+sLug+"&curMonth="+getCookie("selectMonth"),
	    'columnDefs': [
	    	{
	    		'targets': [0,1,2,3,4,5,6,7,8,9,10,11,12],
	            'className': 'dt-nowrap'
	        }
	    ],
	    "columns": [
	    	{ "data": "no" },
	      	{ "data": "customer" },
	      	{ "data": "company" },
	      	{ "data": "invoice" },
	      	{ "data": "nosj" },
	      	{ "data": "noso"},
	      	{ "data": "nopo"},
	      	{ "data": "date"},
	      	{ "data": "duedate"},
	      	{ "data": "amount"},
	      	{ "data": "complete_date"},
	      	{ "data": "annotation"},
	      	{ "data": "ongkir"}
	    ],
	    "lengthMenu": [[10, -1], [10, "All"]],
	    iDisplayLength: 10,
	    dom: 'Bfrtip',
        buttons: [ 
        	'pageLength',
        	{
                text: 'Periode View',
                action: function ( e ) {
                	e.preventDefault();
                	$('H2.FormTitle').text('INPUT PERIODE');
                	$('#Form_periode').attr('class', 'form add');
                	$('#Form_periode').attr('data-id', '');
                    periode_show();
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

	//////////////////////////////////////////////////////////////
	// Create function print all with hidden table
	/////////////////////////////////////////////////////////////

	var tablePrint = $("#tablePrint").DataTable({
		"scrollX": false,
		"bPaginate": false,
		"searching": false,
		"info": false,
	    "ajax": pathFile+"?"+Act+"=resultAll_"+sLug+"&curMonth="+getCookie("selectMonth"),
	    "columns": [
	    	{ "data": "no" },
	      	{ "data": "customer" },
	      	{ "data": "company" },
	      	{ "data": "invoice" },
	      	{ "data": "nosj" },
	      	{ "data": "noso"},
	      	{ "data": "nopo"},
	      	{ "data": "date"},
	      	{ "data": "duedate"},
	      	{ "data": "amount"},
	      	{ "data": "complete_date"},
	      	{ "data": "annotation"},
	      	{ "data": "ongkir"},
	    ],
	    iDisplayLength: -1,
	    "footerCallback": function ( row, data, start, end, display ) {
        	var api = this.api(), data;
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };
            Amounts = api.column( 9, { page: 'current'} ).data().reduce( function (a, b) {
            	return intVal(a) + intVal(b);
            }, 0 );

            Costs = api.column( 12, { page: 'current'} ).data().reduce( function (a, b) {
            	return intVal(a) + intVal(b);
            }, 0 );

            $( api.column( 9 ).footer() ).html(convertToRupiah(Amounts));
            $( api.column( 12 ).footer() ).html(convertToRupiah(Costs));
        }
	    
	});

	var buttons = new $.fn.dataTable.Buttons(tablePrint, {
		buttons:[
        {
        	extend: 'excelHtml5',
        	messageTop: false,
        	footer: true,
        	text: 'Export to Excel',
        	filename : 'Aging-'+getCookie("selectMonth"),
        	title: 'AGING '+getCookie("selectMonth"),
        }
		]
	}).container().appendTo($('.dt-buttons'));

	//////////////////////////////////////////////////////////////
	// On page load: form validation
	/////////////////////////////////////////////////////////////

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

	/////////////////////////////////////////////////////////
	////////////// PERIODE FUNCTION
	/////////////////////////////////////////////////////////

	function periode_show(){
		$('#PeriodeResult').show();
	}

	function periode_hide(){
		$('#PeriodeResult').hide();
	}

	function periode_reset(){
		$('#dari').val('');
		$('#sampai').val('');
	}

	$(document).on('click', '.periode_close', function(){
		periode_hide();
		periode_reset();
	});

	var FormPeriode = $('#form_periode');
  	FormPeriode.validate();

	$(document).on('submit', '#form_periode.add', function(e){
    	e.preventDefault();
    	hide_ipad_keyboard();
      	periode_hide();
      	show_loading_message();
      	var form_data = $(FormPeriode).serialize();
      	var request   = $.ajax({
        	url:          pathFile+"?"+Act+"=periode_"+sLug,
        	cache:        false,
        	data:         form_data,
        	method: 	  'GET',
        	dataType: 'json'
      	});

      	request.done(function(output){
	    	if (output.result == sukses){
	    		tablenya.ajax.url(pathFile+"?"+Act+"=periode_"+sLug+"&"+form_data).load();
      			tablePrint.ajax.url(pathFile+"?"+Act+"=periode_"+sLug+"&"+form_data).load();
      			tablenya.draw();
      			tablePrint.draw();
        		hide_loading_message();
        		show_message("Berhasil memuat dimasukan.", 'success');
        		periode_reset();

	    	} else {
	      		hide_loading_message();
	      		show_message(output.message, 'error');
	    	}
	  	});

	  	request.fail(function(jqXHR, textStatus){
	    	hide_loading_message();
	    	show_message('Gagal memuat data: '+textStatus, 'error');
	  	});
  	});
});