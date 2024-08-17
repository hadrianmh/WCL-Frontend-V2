///////////////////////////////////
// ketika refresh page
///////////////////////////////////
function convertToRupiah(angka){
	let formatted = new Intl.NumberFormat('id-ID', {
		style: 'currency',
		currency: 'IDR',
		minimumFractionDigits: 2
	}).format(angka);
	return formatted;
}

$(document).ready(function(){
	//////////////////////////
	//Default config
	/////////////////////////

	var idTablenya = $('#tablenya');
	var pathFile = decodeURIComponent(getCookie('base_url_api')) +':'+ getCookie('base_port_api') + decodeURIComponent(getCookie('base_path_api')) + decodeURIComponent(getCookie('base_dashboard_api'));
	var Act = 'action';
	var sLug = 'dashboard';

	//////////////////////////////////////////////////////////////
	// On page load: datatable
	//////////////////////////////////////////////////////////////

	var tablenya = idTablenya.DataTable({
		initComplete : function() {
			var input = $('.dataTables_filter input').unbind(),
			self = this.api(),
			$searchButton = $(`<button class="btn btn-default"><i class="fa fa-search"></i></button>`).click(function(){ self.search(input.val()).draw(); });
			$resetButton = $(`<button class="btn btn-default"><i class="fa fa-times"></i></button>`).click(function() { input.val('');$searchButton.click(); }); 
			$('.dataTables_filter').append($searchButton, $resetButton);
		},
		"scrollX": false,
		"serverSide" : true,
		"searching": false,
		"paging":   false,
        "ordering": false,
        "info":     false,
	    "ajax": {
			"url" : pathFile+"/metrics/so-tracking",
			"type": "GET",
			data: {
				report : getCookie("report"),
				startdate: getCookie("startdate"),
				enddate: getCookie("enddate")
			},
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
	      { "data": "company" },
	      { "data": "order_grade" },
	      { "data": "no_spk"},
	      { "data": "so_date"},
	      { "data": "etd"},
	      { "data": "customer"},
	      { "data": "po_customer"},
	      { "data": "po_date"},
	      { "data": "item"},
	      { "data": "isi"},
	      { "data": "merk"},
	      { "data": "type"},
	      { "data" : "size"},
	      { "data" : "qore"},
	      { "data" : "lin"},
	      { "data" : "roll"},
	      { "data" : "ingredient"},
	      { "data" : "porporasi"},
	      { "data" : "qty"},
	      { "data": "unit"},
	      { "data" : "volume"},
	      { "data" : "uk_bahan_baku"},
	      { "data" : "qty_bahan_baku"},
	      { "data" : "annotation"},
	      { "data" : "sources"},
	      { "data": "price"},
	      { "data": "price_before"},
	      { "data": "tax"},
	      { "data": "total"},
	      { "data": "spk_date"},
	      { "data": "order_status"},
	      { "data": "sj_no"},
	      { "data": "sj_date"},
	      { "data": "courier"},
	      { "data": "resi"},
	      { "data": "send_qty"},
	      { "data": "cost"}
	    ],
	    dom: 'Bfrtip',
	    buttons: [
        {
        	extend: 'excel',
        	text: 'Export to Excel',
        	filename : 'SOTracking_'+getCookie("selectMonth"),
        	messageTop: false,
        	footer: true,
        	title: 'SO Tracking '+getCookie("selectMonth"),
        },
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
        ]
	});

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
		report = $("#report").val();
		startdate = $("#startdate").val();
		enddate = $("#enddate").val();
		setCookie("report", report, 1);
		setCookie("startdate", startdate, 1);
		setCookie("enddate", enddate, 1);
		hide_loading_message();
		window.location.reload();
  	});

	/////////////////////////////////////////////////////////////////
	// Set cookie as 'SelectMonth'
	/////////////////////////////////////////////////////////////////

	var mm = ("0" + (new Date().getMonth() + 1)).slice(-2);
	var yyyy = new Date().getFullYear();
	var startdate = yyyy+"/"+mm;
	var report = 'month';

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
	// Sort data from current month
	/////////////////////////////////////////////////////////////////

	var Sortdata = $.ajax({
		url: pathFile+"/sortdata/archive?data=po_date&from=preorder_customer",
		type: "GET",
		beforeSend: function (xhr) {
			xhr.setRequestHeader('Authorization', getCookie('access_token'));
			xhr.setRequestHeader('Content-Type', 'application/json');
		}
	});

	Sortdata.done(function(output){
		if(output.status == "success"){
			for(var i=0; i<output.response.data[0].year.length; i++) {
				$("#sortby").append("<option value='"+output.response.data[0].year[i]+"' data-name='year' "+(getCookie("startdate") == output.response.data[0].year[i] ? 'selected' : '')+" >Tahun: "+output.response.data[0].year[i]+"</option>");
			}
			
			for(var i = 0; i<output.response.data[0].month.length; i++){
				$("#sortby").append("<option value='"+output.response.data[0].month[i]+"' data-name='month' "+(getCookie("startdate") == output.response.data[0].month[i] ? 'selected' : '')+" >Bulan: "+output.response.data[0].month[i]+"</option>");
			}
			setCookie("report", report, 1);
			setCookie("startdate", startdate, 1);

		} else {
			show_message('Failed: sort data fetching.', 'error');
		}
	});

	Sortdata.fail(function(jqXHR, textStatus){
    	hide_loading_message();
    	show_message('Failed: '+jqXHR.responseJSON.response.message, 'error');
  	});

	$(document).on('change', '#sortby', function(){
		report = $(this).attr('name');
		startdate = $(this).find(":selected").val();
		setCookie("report", report, 1);
		setCookie("startdate", startdate, 1);
	});

	$(document).on('click', '#LoadData', function(){
		report = $('#sortby').find(":selected").attr('data-name');
		startdate = $('#sortby').find(":selected").val();
		setCookie("report", report, 1);
		setCookie("startdate", startdate, 1);
		location.reload();
	});

	/////////////////////////////////////////////////////////////////
	// Get data from current month
	/////////////////////////////////////////////////////////////////

	var Getdata = $.ajax({
		url: pathFile+"/metrics/static",
		type: "GET",
		data: {
			report : getCookie("report"),
			startdate: getCookie("startdate"),
			enddate: getCookie("enddate")
		},
		beforeSend: function (xhr) {
			xhr.setRequestHeader('Authorization', getCookie('access_token'));
			xhr.setRequestHeader('Content-Type', 'application/json');
		}
	});

	Getdata.done(function(output){
		if(output.status == "success"){
			$('.statistiPO').html(output.response.data[0].po_total);
            $('.statistiSPK').html(output.response.data[0].wo_total);
            $('.statistiSJ').html(output.response.data[0].do_total);
            $('.statistiFAKTUR').html(output.response.data[0].inv_total);
		} else {
	        show_message(output.message, 'error');
		}
	});

	Getdata.fail(function(jqXHR, textStatus){
    	hide_loading_message();
    	show_message('Gagal memuat data: '+textStatus, 'error');
  	});



});



