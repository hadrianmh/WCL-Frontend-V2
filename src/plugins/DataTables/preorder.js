$(document).ready(function(){

	//////////////////////////
	//Default config
	/////////////////////////

	var idTablenya = $('#tablenya');
	var pathFile = 'http://localhost:8082/api/v1/dashboard';
	var FormsLug = 'PO';
	var IDForm = "#form_inputPO";
	var barisN = 1;
	var detailJSON = [];
	var companyJSON = [];

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
	// Sort datatable from current month
	/////////////////////////////////////////////////////////////////

	var req = $.ajax({
		url: pathFile+"/sortdata/archive?data=po_date&from=po_customer",
		type: "GET",
		beforeSend: function (xhr) {
			xhr.setRequestHeader('Authorization', getCookie('access_token'));
			xhr.setRequestHeader('Content-Type', 'application/json');
		}
	});

	req.done(function(output){
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

	req.fail(function(jqXHR, textStatus){
		show_message('Failed: '+jqXHR.responseJSON.response.message, 'error');
	});

	$(document).on('change', '#sortby', function(e){
		e.preventDefault()
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
	// Get item list
	/////////////////////////////////////////////////////////////////

	var reqDetail = $.ajax({
		url: pathFile+"/sales-order/suggest/type",
		type: "GET",
		beforeSend: function (xhr) {
			xhr.setRequestHeader('Authorization', getCookie('access_token'));
			xhr.setRequestHeader('Content-Type', 'application/json');
		}
	});

	reqDetail.done(function(output){
		detailJSON = output.response.data;
	});

	reqDetail.fail(function(jqXHR, textStatus)
	{
		hide_loading_message();
		show_message('Failed: '+jqXHR.responseJSON.response.message, 'error');
	});

	/////////////////////////////////////////////////////////////////
	// Get company list
	/////////////////////////////////////////////////////////////////

	var reqCompany = $.ajax({
		url: pathFile+"/company?limit=-1&offset=0",
		type: "GET",
		beforeSend: function (xhr) {
			xhr.setRequestHeader('Authorization', getCookie('access_token'));
			xhr.setRequestHeader('Content-Type', 'application/json');
		}
	});

	reqCompany.done(function(output){
		companyJSON = output.response.data;
	});

	reqCompany.fail(function(jqXHR, textStatus)
	{
		hide_loading_message();
		show_message('Failed: '+jqXHR.responseJSON.response.message, 'error');
	});

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
		"serverSide" : true,
		"scrollX": true,
	    "ajax": {
			"url" : pathFile+"/sales-order?report="+getCookie("report")+"&startdate="+getCookie("startdate")+"&enddate="+getCookie("enddate"),
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
	    		'targets': [0,1,2,3,4,5,6,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,27,28,29,30],
	            'className': 'dt-nowrap'
	        },
			{
                "targets": [22,23,24,25,28],
                "render": function(data, type, row) {
                    return new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        minimumFractionDigits: 2
                    }).format(parseFloat(data));
                }
            },
			{
				"targets": 30,
				"data": null,
				"defaultContent": "",
				"render": function (data, type, row) {
					//return '<button class="btn btn-default function_edit" data-id="'+ data.id +'"><i class="fa fa-pencil"></i></button>';
					return '<button class="btn btn-default UbahCustomer" data-id="'+ data.customerid +'" title="Edit Customer"><i class="fa fa-user"></i></button> <button class="btn btn-default UbahItem" data-id="'+ data.itemid +'" title="Edit Item"><i class="fa fa-cube"></i></button> <button class="btn btn-default HapusItem" data-id="'+ data.itemid +'" data-name="'+data.item+'" title="Delete"><i class="fa fa-trash"></i></button> <button class="btn btn-default function_ongkir" data-id="'+ data.fkid +'" title="Input Ongkir"><i class="fa fa-truck"></i></button>';
				}
			}
	    ],
	    "columns": [
	      { "data": "po_date" },
	      { "data": "customer"},
	      { "data": "estimasi" },
	      { "data": "company" },
	      { "data": "order_grade"},
	      { "data": "po_customer"},
	      { "data": "so_no"},
	      { "data": "item"},
	      { "data": "detail"},
	      { "data": "size"},
	      { "data": "merk"},
	      { "data": "type"},
	      { "data": "uk_bahan_baku"},
	      { "data": "qty"},
	      { "data": "unit"},
	      { "data": "qore"},
	      { "data": "lin"},
	      { "data": "qty_bahan_baku"},
	      { "data": "roll"},
	      { "data": "ingredient"},
	      { "data": "porporasi"},
	      { "data": "volume"},
	      { "data": "price"},
	      { "data": "etd"},
	      { "data": "ppn"},
	      { "data": "total"},
	      { "data": "annotation"},
	      { "data": "sources"},
	      { "data": "ongkir"},
	      { "data": "username"},
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
	    },
		"footerCallback": function ( row, data, start, end, display ) {
        	var api = this.api(), data;
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };
            SubTotal = api.column( 23, { page: 'current'} ).data().reduce( function (a, b) {
            	return intVal(a) + intVal(b);
            }, 0 );

            Tax = api.column( 24, { page: 'current'} ).data().reduce( function (a, b) {
            	return intVal(a) + intVal(b);
            }, 0 );

			Ongkir = api.column( 28, { page: 'current'} ).data().reduce( function (a, b) {
            	return intVal(a) + intVal(b);
            }, 0 );

            Totals = SubTotal + Tax;

            $( api.column( 23 ).footer() ).html(convertToRupiah(SubTotal));
            $( api.column( 24 ).footer() ).html(convertToRupiah(Tax));
            $( api.column( 25 ).footer() ).html(convertToRupiah(Totals));
            $( api.column( 28 ).footer() ).html(convertToRupiah(Ongkir));
        }
	});

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

	function show_input(){
		$('.merk').show();
		$('.type').show();
		$('.size').show();
		$('.uk_bahan_baku').show();
		$('.qore').show();
		$('.lin').show();
		$('.qty_bahan_baku').show();
		$('.roll').show();
		$('.ingredient').show();
		$('.porporasi').show();
		$('.unit').show();
		$('.volume').show();
	}

	function hidden_input(){
		$('.merk').hide();
		$('.type').hide();
		$('.size').hide();
		$('.uk_bahan_baku').hide();
		$('.qore').hide();
		$('.lin').hide();
		$('.qty_bahan_baku').hide();
		$('.roll').hide();
		$('.ingredient').hide();
		$('.porporasi').hide();
		$('.unit').hide();
		$('.volume').hide();
	}

	function reset(){
		$('#customer').val('');
		$('#po_customer').val('');
		$('#detail').val('');
        $('#item').val('');
        $('#size').val('');
        $('#merk').val('');
        $('#type').val('');
        $('#qty').val('');
        $('#unit').val('');
        $('#price').val('');
        $('#ppn').val('');
        $('#qore').val('');
        $('#lin').val('');
        $('#roll').val('');
        $('#ingredient').val('');
        $('#volume').val('');
        $('#annotation').val('');
		$('#etc1-input-1').val('');
		$('#etc2-input').val('');
		$('#ppns').val('0');
		$('.company').show();
		$('.customer').show();
		$('.po_date').show();
		$('.po_customer').show();
		$('.header-item').show();
        $('.tambah_barang').show();
        $('.order_grade').show();
        $('.source').show();
        $('.item').show();
        $('.detail').show();
        $('.size').show();
        $('.qore').show();
        $('.lin').show();
        $('.uk_bahan_baku').show();
        $('.qty_bahan_baku').show();
        $('.roll').show();
        $('.ingredient').show();
        $('.porporasi').show();
        $('.volume').show();
        $('.annotation').show();
        $('.qty').show();
        $('.unit').show();
        $('.price').show();
        $('.footer-item').show();
        $('#source').attr('name','data[sources][]');
        $('#etc1-input-1').attr('name','data[etc1][]');
        $('#etc2-input').attr('name','data[etc2][]');
        $('#qore').attr('name','data[qore][]');
        $('#lin').attr('name','data[lin][]');
        $('#roll').attr('name','data[roll][]');
        $('#ingredient').attr('name','data[ingredient][]');
        $('#porporasi').attr('name','data[porporasi][]');
        $('#volume').attr('name','data[volume][]');
        $('#annotation').attr('name','data[annotation][]');
        $('#uk_bahan_baku').attr('name','data[uk_bahan_baku][]');
        $('#qty_bahan_baku').attr('name','data[qty_bahan_baku][]');
        $('#detail').attr('name','data[detail][]');
		$('#merk').attr('name','data[merk][]');
		$('#type').attr('name','data[type][]');
		$('#item').attr('name','data[item][]');
		$('#unit').attr('name','data[unit][]');
		$('#qty').attr('name','data[qty][]');
		$('#price').attr('name','data[price][]');
		$('.looping_barang').remove();
		$('#company').empty();
		$('#detail').empty();
	}

	// Lightbox close button
	$(document).on('click', '.lightbox_close', function(){
	    hide_lightbox();
	    reset();
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
		$('#startdate').val('');
		$('#enddate').val('');
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
      	var request = $.ajax({
	    	url:          pathFile+"/sales-order",
			type:         'GET',
			data:         'report='+report+'&startdate='+startdate+'&enddate='+enddate,
			beforeSend: function (xhr) {
				xhr.setRequestHeader('Authorization', getCookie('access_token'));
				xhr.setRequestHeader('Content-Type', 'application/json');
			}
	    });

      	request.done(function(output){
	    	if(output.status == "success"){
	    		setCookie("report", report, 1);
				setCookie("startdate", startdate, 1);
				setCookie("enddate", enddate, 1);
        		location.reload();

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

	/////////////////////////////////////////////////////////
	////////////// ONGKIR FROM FUNCTION
	/////////////////////////////////////////////////////////

	var FormOngkir = $('#form_inputOngkir');
  	FormOngkir.validate();

	function ongkirbox_show(){
		$('#OngkirModal').show();
	}

	function ongkirbox_close(){
		$('#OngkirModal').hide();
	}

	function ongkirbox_reset(){
		$('#ongkos_kirim').val('');
		$('#surat_jalan').empty();
	}

	$(document).on('click', '.ongkirbox_close', function(e){
		ongkirbox_close();
		ongkirbox_reset();
	});

	$(document).on('click', '.function_ongkir', function(e){
  		e.preventDefault();
  		show_loading_message();
  		var id = $(this).data('id');
  		var getSJ = $.ajax({
	    	url:          pathFile+"/sales-order/shipping-cost/"+id,
			type:         'GET',
			beforeSend: function (xhr) {
				xhr.setRequestHeader('Authorization', getCookie('access_token'));
				xhr.setRequestHeader('Content-Type', 'application/json');
			}
	    });

  		getSJ.done(function(output) {
  			if(output.status == "success") {
  				hide_loading_message();
				if(output.response.data.length > 0 )
				{
					$(IDForm).attr('data-id', id);
					ongkirbox_show();

					$('#surat_jalan').append(
						'<option selected disabled>Pilih</option>'
					);
  
					for(var j = 0; j < output.response.data.length; j++)
					{
						$('#surat_jalan').append(
							'<option value="'+output.response.data[j].id+'" data-cost="'+output.response.data[j].cost+'" data-ekspedisi="'+output.response.data[j].ekspedisi+'" data-uom="'+output.response.data[j].uom+'" data-jml="'+output.response.data[j].jml+'">' +output.response.data[j].detail+ '</option>'
						);
					}
				} else {
					hide_loading_message();
					show_message('No delivery yet.', 'error');
				}

  			} else {
  				hide_loading_message();
  				show_message('Failed: '+output.response.message, 'error');
  			}
  		});

  		getSJ.fail(function(jqXHR, textStatus) {
  			hide_loading_message();
  			show_message('Failed: '+jqXHR.responseJSON.response.message, 'error');
  		});
  	});

  	$(document).on('change', '#surat_jalan', function(e){
  		e.preventDefault();
  		var sjVal = $(this).find(':selected').data('cost')
  		var sjEkspedisi = $(this).find(':selected').data('ekspedisi')
  		var sjUom = $(this).find(':selected').data('uom')
  		var sjJml = $(this).find(':selected').data('jml')
  		$('#ongkos_kirim').val(sjVal);
  		$('#ekspedisi').val(sjEkspedisi);
  		$('#uom').val(sjUom);
  		$('#jml').val(sjJml);
  	});

  	$(document).on('submit', '#form_inputOngkir.add', function(e){
    	e.preventDefault();
	    // Validate form
	    if(FormOngkir.valid() == true){
	      	hide_ipad_keyboard();
	      	ongkirbox_close();
	      	show_loading_message();
			var id			= $('##form_inputOngkir').attr('data-id');
			var form_data 	= $('##form_inputOngkir').serializeArray();
			
			var jsonData = {};
			$.each(form_data, function(){
				jsonData[this.name] = this.value;
			});

			jsonData.id = parseInt(id);

	      	var request   = $.ajax({
				url:          pathFile+"/sales-order/shipping-cost",
				type:         'PUT',
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
	            		ongkirbox_reset();
	            		show_message("Update sucessfully.", 'success');
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

	//////////////////////////////////////////////////////
  	////////// Pemeriksaan Jenis Item
  	//////////////////////////////////////////////////////

  	$(document).on('change', '#detail', function(e){
  		e.preventDefault();
  		hidden_input();
  		var id = $(this).val();
  		if(id)
  		{
  			var attributeGET = $.ajax({
	  			url : '../auth/json.php',
	  			dataType: 'JSON',
	  			type : 'GET',
	  			contentType: 'application/json; charset=utf-8',
	  			cache: false,
	  			data: {
	  				req: 'so_attribute',
	  				id: id
	  			}
	  		});

	  		attributeGET.done(function(output){
	  			var split = output[0].field.split(',');
	  			for(var loop = 0; loop < split.length; loop++)
	  			{
	  				$('.'+split[loop]).show();
	  			}
	  		});

	  		attributeGET.fail(function(jqXHR, textStatus)
	  		{
	  			hide_loading_message();
	  			show_message('Gagal mengambil data: '+textStatus, 'error');
	  		});
  		}
  	});

	///////////////////////////////////////////////////////
	// Add PO button
  	//////////////////////////////////////////////////////

  	$(document).on('click', '#add_inputPO', function(e){
  		e.preventDefault();
		$('H2.FormTitle').text('INPUT '+FormsLug);
		$(IDForm).attr('class', 'form add');
		$(IDForm).attr('data-id', '');
		$('.company').show();
    	$('.customer').show();
        $('.po_date').show();
        $('.po_customer').show();
        $('.order_grade').show();
        $('.header-item').show();
        $('.tambah_barang').show();
        $('.footer-item').show();
        $('.ppns').show();
		hidden_input();
		show_lightbox();

		$('#company').append('<option value="" selected>Pilih Perusahaan</option>');
		for(var x = 0; x<companyJSON.length; x++)
		{
			$('#company').append('<option value="'+companyJSON[x].id+'">'+companyJSON[x].companyname+'</option>');
		}

		$('#detail').append('<option value="" selected>Pilih Item</option>');
		for(var z = 0; z < detailJSON.length; z++)	
		{
			$('#detail').append('<option value="'+detailJSON[z].id+'">' +detailJSON[z].item+ '</option>');
		}

		////////////////////////////////////////
	  	// auto complete customer
	  	//////////////////////////////////////

	  	$.widget( "custom.catcomplete", $.ui.autocomplete, {
	  		_create: function()
	  		{
	  			this._super();
	  			this.widget().menu( "option", "items", "> :not(.ui-autocomplete-category)" );
	  		},

	  		_renderMenu: function( ul, items )
	  		{
	  			var that = this,
	  			currentCategory = "";
	  			$.each( items, function( index, item )
	  			{
	  				var li;
	  				if ( item.category != currentCategory )
	  				{
	  					ul.append( "<li class='ui-autocomplete-category'>" + item.category + "</li>" );
	  					currentCategory = item.category;
	  				}
	  				li = that._renderItemData( ul, item );
	  				if ( item.category )
	  				{
	  					li.attr( "aria-label", item.category + " : " + item.label );
	  				}
	  			});
	  		}
	  	});

	  	$('#customer').catcomplete({
	  		minLength: 2,
	  		source: function(request, response)
	  		{
				$.ajax({
					url: pathFile+"/sales-order/suggest/customer?keyword="+request.term,
					type: "GET",
					beforeSend: function (xhr) {
						xhr.setRequestHeader('Authorization', getCookie('access_token'));
						xhr.setRequestHeader('Content-Type', 'application/json');
					},
					success: function(output)
	  				{
	  					response(output.response.data);
	  				}
				});
	  		},
	  		select: function(event, ui)
	  		{
	  			show_input();
	  			var customerid = ui.item.customerid;
	  			var poid = ui.item.po_id;

	  			var meminta = $.ajax({
					url: pathFile+"/sales-order/suggest/item?customerid="+customerid+"&poid="+poid,
	  				type: 'GET',
	  				beforeSend: function (xhr) {
						xhr.setRequestHeader('Authorization', getCookie('access_token'));
						xhr.setRequestHeader('Content-Type', 'application/json');
					},
	  			});

	  			meminta.done(function(output)
	  			{
	  				var options = '';
	  				$('#id_customer').val(output.response.data[0].customerid);
	  				if(parseInt(output.response.data[0].po_id) > 0)
	  				{
	  					for(var x = 0; x < output.response.data[0].items.length; x++)
	  					{
	  						if(parseInt(x) == '0')
	  						{
	  							$('#item').val(output.response.data[0].items[x].item);
	  							$('#size').val(output.response.data[0].items[x].size);
	  							$('#uk_bahan_baku').val(output.response.data[0].items[x].uk_bahan_baku);
	  							$('#qore').val(output.response.data[0].items[x].qore);
	  							$('#lin').val(output.response.data[0].items[x].lin);
	  							$('#qty_bahan_baku').val(output.response.data[0].items[x].qty_bahan_baku);
	  							$('#roll').val(output.response.data[0].items[x].roll);
	  							$('#ingredient').val(output.response.data[0].items[x].ingredient);
	  							$('#porporasi').val(output.response.data[0].items[x].porporasi);
	  							$('#unit').val(output.response.data[0].items[x].unit);
	  							$('#qty').val(output.response.data[0].items[x].qty);
	  							$('#volume').val(output.response.data[0].items[x].volume);
	  							$('#price').val(output.response.data[0].items[x].price);
	  							$('#annotation').val(output.response.data[0].items[x].annotation);
	  							$('#detail').val(output.response.data[0].items[x].detail);
	  							$('#merk').val(output.response.data[0].items[x].merk);
	  							$('#type').val(output.response.data[0].items[x].type);

	  						} else {

								for(var z = 0; z < detailJSON.length; z++)
								{
									options += '<option value="'+detailJSON[z].id+'" '+(detailJSON[z].id == output.response.data[0].items[x].detail ? 'selected' : '')+'>' +detailJSON[z].item+ '</option>';
								}

	  							$('#looping_barang').append(
	  								'<div class="looping_barang" id="looping-'+barisN+'"><hr class="looping-item"><p><button type="button" name="remove" idx="'+barisN+'" class="btn btn-danger btn_remove">Hapus</button></p><div class="form-group source-'+barisN+'"><label for="source">Sources: <span class="required">*</span></label><select class="form-control" name="data[sources][]" id="source" id_source="'+barisN+'" required><option selected disabled>Pilih sources</option><option value="1">Internal</option><option value="2">Subcont</option><option value="3">In Stock</option></select></div><div class="form-group etc1-class-'+barisN+'" style="display:none"><label for="Subcont-'+barisN+'">Subcont kepada: <span class="required">*</span></label><input type="text" class="form-control" name="data[etc1][]" id="etc1-input-'+barisN+'" required></div><div class="form-group etc2-class-'+barisN+'" style="display:none"><label for="Estimasi">Estimasi: <span class="required">*</span></label><input type="date" class="form-control" name="data[etc2][]" id="etc2-input" required></div><div class="form-group item-'+barisN+'"><label for="Item">Nama Barang: <span class="required">*</span></label><input type="text" class="form-control" name="data[item][]" id="item" value="'+output.response.data[0].items[x].item+'" required></div><div class="form-group detail-'+barisN+'"><label for="detail">Jenis Item: <span class="required">*</span></label><select class="form-control" name="data[detail][]" id="detail-'+barisN+'" required><option value="">Pilih Item</option>'+options+'</select></div><div class="form-group merk-'+barisN+'"><label for="merk">Merk: <span class="required">*</span></label><input type="text" class="form-control" name="data[merk][]" id="merk" value="'+output.response.data[0].items[x].merk+'" required></div><div class="form-group type-'+barisN+'"><label for="type">Type: <span class="required">*</span></label><input type="text" class="form-control" name="data[type][]" id="type" value="'+output.response.data[0].items[x].type+'" required></div><div class="form-group size-'+barisN+'"><label for="Size">Ukuran: <span class="required">*</span></label><input type="text" class="form-control" name="data[size][]" id="size" value="'+output.response.data[0].items[x].size+'" required></div><div class="form-group uk_bahan_baku-'+barisN+'"><label for="uk_bahan_baku">Uk. Bahan baku: <span class="required">*</span></label><input type="text" class="form-control" name="data[uk_bahan_baku][]" id="uk_bahan_baku" value="'+output.response.data[0].items[x].uk_bahan_baku+'" required></div><div class="form-group qore-'+barisN+'"><label for="Qore">Kor: <span class="required">*</span></label><input type="text" class="form-control" name="data[qore][]" id="qore" value="'+output.response.data[0].items[x].qore+'" required></div><div class="form-group lin-'+barisN+'"><label for="lin">Line: <span class="required">*</span></label><input type="text" class="form-control" name="data[lin][]" id="lin" value="'+output.response.data[0].items[x].lin+'" required></div><div class="form-group qty_bahan_baku-'+barisN+'"><label for="qty_bahan_baku">QTY Bahan baku: <span class="required">*</span></label><input type="text" class="form-control" name="data[qty_bahan_baku][]" id="qty_bahan_baku" value="'+output.response.data[0].items[x].qty_bahan_baku+'" required></div><div class="form-group roll-'+barisN+'"><label for="roll">Gulungan: <span class="required">*</span></label><select class="form-control" name="data[roll][]" id="roll" required><option disabled>Select roll</option><option value="FI" '+(output.response.data[0].items[x].roll == "FI" ? "selected": "")+'>FI</option><option value="FO" '+(output.response.data[0].items[x].roll == "FO" ? "selected": "")+'>FO</option><option value="LIPAT" '+(output.response.data[0].items[x].roll == "LIPAT" ? "selected": "")+'>LIPAT</option><option value="SHEET" '+(output.response.data[0].items[x].roll == "SHEET" ? "selected": "")+'>SHEET</option></select></div><div class="form-group ingredient-'+barisN+'"><label for="ingredient">Bahan: </label><input type="text" class="form-control" name="data[ingredient][]" id="ingredient" value="'+output.response.data[0].items[x].ingredient+'"></div><div class="form-group porporasi-'+barisN+'"><label for="porporasi">Porporasi: <span class="required">*</span></label><select class="form-control" id="porporasi" name="data[porporasi][]" required><option disabled>Pilih Porporasi</option><option value="YA" '+(output.response.data[0].items[x].porporasi == "1" ? "selected": "")+'>YA</option><option value="TIDAK" '+(output.response.data[0].items[x].porporasi == "0" ? "selected": "")+'>TIDAK</option></select></div><div class="form-group unit-'+barisN+'"><label for="Unit">Satuan: <span class="required">*</span></label><select class="form-control" name="data[unit][]" id="unit" value="'+output.response.data[0].items[x].unit+'" required><option disabled>Select satuan</option><option value="PCS" '+(output.response.data[0].items[x].unit == "PCS" ? "selected": "")+'>PCS</option><option value="ROLL" '+(output.response.data[0].items[x].unit == "ROLL" ? "selected": "")+'>ROLL</option><option value="PAK" '+(output.response.data[0].items[x].unit == "PAK" ? "selected": "")+'>PAK</option><option value="METER" '+(output.response.data[0].items[x].unit == "METER" ? "selected": "")+'>METER</option></select></div><div class="form-group qty-'+barisN+'"><label for="Qty">Qty: <span class="required">*</span></label><input type="number" min="1" class="form-control" name="data[qty][]" id="qty-"'+barisN+' placeholder="0" value="'+output.response.data[0].items[x].qty+'" required></div><div class="form-group volume-'+barisN+'"><label for="volume">Isi Roll/Pcs: <span class="required">*</span></label><input type="number" min="0" class="form-control" name="data[volume][]" id="volume" placeholder="1" value="'+output.response.data[0].items[x].volume+'" required></div><div class="form-group price-'+barisN+'"><label for="Price">Harga: <span class="required">*</span></label><input type="text" class="form-control" name="data[price][]" id="price-'+barisN+'" placeholder="0" value="'+output.response.data[0].items[x].price+'" required></div><div class="form-group annotation-'+barisN+'"><label for="Annotation">Catatan:</label><input type="text" class="form-control" name="data[annotation][]" id="annotation" value="'+output.response.data[0].items[x].annotation+'"></div><script>$(document).ready(function(){ $("#price-'+barisN+'").mask("0.000.000.000.000,00", {reverse: true});$("#qty-'+barisN+'").keyup(function(){ $("#ppns").val("0"); });$("#price-'+barisN+'").keyup(function(){ $("#ppns").val("0"); });$(document).on("change", "#detail-'+barisN+'", function(e){e.preventDefault();$(".merk-'+barisN+'").hide();$(".type-'+barisN+'").hide();$(".size-'+barisN+'").hide();$(".uk_bahan_baku-'+barisN+'").hide();$(".qore-'+barisN+'").hide();$(".lin-'+barisN+'").hide();$(".qty_bahan_baku-'+barisN+'").hide();$(".roll-'+barisN+'").hide();$(".ingredient-'+barisN+'").hide();$(".porporasi-'+barisN+'").hide();$(".unit-'+barisN+'").hide();$(".volume-'+barisN+'").hide();var id_'+barisN+' = $(this).val();if(id_'+barisN+'){$.ajax({url: pathFile+"/purchase-order/suggest/attr?id="+id_'+barisN+',type: "GET",beforeSend: function (xhr){xhr.setRequestHeader("Authorization", getCookie("access_token"));xhr.setRequestHeader("Content-Type", "application/json");},success: function(output){var split_'+barisN+' = output.response.data[0].field.split(",");for(var loop_'+barisN+' = 0; loop_'+barisN+' < split_'+barisN+'.length; loop_'+barisN+'++){$("."+split_'+barisN+'[loop_'+barisN+']+"-'+barisN+'").show();}}});}});});</script></div>'
	  							);
	  						}
	  					}
	  				}
	  			});

	  			meminta.fail(function(jqXHR, textStatus)
	  			{
	  				hide_loading_message();
	  				show_message('Failed: '+jqXHR.responseJSON.response.message, 'error');
	  			});
	  		}
	  	});
	  	
		$('#customer').keyup(function()
		{
			$("#id_customer").val('0');
			$('#item').val('');
			$('#size').val('');
			$('#merk').val('');
			$('#type').val('');
			$('#detail').val('');
			$('#uk_bahan_baku').val('');
			$('#qore').val('');
			$('#lin').val('');
			$('#qty_bahan_baku').val('');
			$('#roll').val('');
			$('#ingredient').val('');
			$('#porporasi').val('');
			$('#unit').val('');
			$('#qty').val('');
			$('#volume').val('');
			$('#price').val('');
			$('#annotation').val('');
			$('.looping_barang').remove();
			hidden_input();
		});
  	});

	///////////////////////////
  	// Add PO (item)
  	//////////////////////////

  	$(document).on('click', '.tambah_barang', function(e){
  		e.preventDefault();
  		barisN++;

  		var options = '';
  		for(var z = 0; z < detailJSON.length; z++)
  		{
  			options += '<option value="'+detailJSON[z].id+'">' +detailJSON[z].item+ '</option>';
  		}

  		$('#looping_barang').append(
  			'<div class="looping_barang" id="looping-'+barisN+'"><hr class="looping-item"><p><button type="button" name="remove" idx="'+barisN+'" class="btn btn-danger btn_remove">Hapus</button></p><div class="form-group source-'+barisN+'"><label for="source">Sources: <span class="required">*</span></label><select class="form-control" name="data[sources][]" id="source" id_source="'+barisN+'" required><option selected disabled>Pilih sources</option><option value="1">Internal</option><option value="2">Subcont</option><option value="3">In Stock</option></select></div><div class="form-group etc1-class-'+barisN+'" style="display:none"><label for="Subcont-'+barisN+'">Subcont kepada: <span class="required">*</span></label><input type="text" class="form-control" name="data[etc1][]" id="etc1-input-'+barisN+'" required></div><div class="form-group etc2-class-'+barisN+'" style="display:none"><label for="Estimasi">Estimasi: <span class="required">*</span></label><input type="date" class="form-control" name="data[etc2][]" id="etc2-input" required></div><div class="form-group item-'+barisN+'"><label for="Item">Nama Barang: <span class="required">*</span></label><input type="text" class="form-control" name="data[item][]" id="item" required></div><div class="form-group detail-'+barisN+'"><label for="detail">Jenis Item: <span class="required">*</span></label><select class="form-control" name="data[detail][]" id="detail-'+barisN+'" required><option value="" selected>Pilih Item</option>'+options+'</select></div><div class="form-group merk-'+barisN+'" style="display:none"><label for="merk">Merk: <span class="required">*</span></label><input type="text" class="form-control" name="data[merk][]" id="merk" value="" required></div><div class="form-group type-'+barisN+'" style="display:none"><label for="type">Type: <span class="required">*</span></label><input type="text" class="form-control" name="data[type][]" id="type" value="" required></div><div class="form-group size-'+barisN+'" style="display:none"><label for="Size">Ukuran: <span class="required">*</span></label><input type="text" class="form-control" name="data[size][]" id="size" required></div><div class="form-group uk_bahan_baku-'+barisN+'" style="display:none"><label for="uk_bahan_baku">Uk. Bahan baku: <span class="required">*</span></label><input type="text" class="form-control" name="data[uk_bahan_baku][]" id="uk_bahan_baku" required></div><div class="form-group qore-'+barisN+'" style="display:none"><label for="Qore">Kor: <span class="required">*</span></label><input type="text" class="form-control" name="data[qore][]" id="qore" required></div><div class="form-group lin-'+barisN+'" style="display:none"><label for="lin">Line: <span class="required">*</span></label><input type="text" class="form-control" name="data[lin][]" id="lin" required></div><div class="form-group qty_bahan_baku-'+barisN+'" style="display:none"><label for="qty_bahan_baku">QTY Bahan baku: <span class="required">*</span></label><input type="text" class="form-control" name="data[qty_bahan_baku][]" id="qty_bahan_baku" required></div><div class="form-group roll-'+barisN+'" style="display:none"><label for="roll">Gulungan: <span class="required">*</span></label><select class="form-control" name="data[roll][]" id="roll" required><option disabled selected>Select roll</option><option value="FI">FI</option><option value="FO">FO</option><option value="LIPAT">LIPAT</option><option value="SHEET">SHEET</option></select></div><div class="form-group ingredient-'+barisN+'" style="display:none"><label for="ingredient">Bahan: </label><input type="text" class="form-control" name="data[ingredient][]" id="ingredient"></div><div class="form-group porporasi-'+barisN+'" style="display:none"><label for="porporasi">Porporasi: <span class="required">*</span></label><select class="form-control" id="porporasi" name="data[porporasi][]" required><option selected>Pilih Porporasi</option><option value="YA">YA</option><option value="TIDAK">TIDAK</option></select></div><div class="form-group unit-'+barisN+'" style="display:none"><label for="Unit">Satuan: <span class="required">*</span></label><select class="form-control" name="data[unit][]" id="unit" required><option disabled selected>Select satuan</option><option value="PCS">PCS</option><option value="ROLL">ROLL</option><option value="PAK">PAK</option><option value="METER">METER</option></select></div><div class="form-group qty-'+barisN+'"><label for="Qty">Qty: <span class="required">*</span></label><input type="number" value="" min="1" class="form-control" name="data[qty][]" id="qty-"'+barisN+' placeholder="0" required></div><div class="form-group volume-'+barisN+'" style="display:none"><label for="volume">Isi Roll/Pcs: <span class="required">*</span></label><input type="number" min="0" class="form-control" name="data[volume][]" id="volume" placeholder="1" required></div><div class="form-group price-'+barisN+'"><label for="Price">Harga: <span class="required">*</span></label><input type="text" value="" class="form-control" name="data[price][]" id="price-'+barisN+'" placeholder="0" required></div><div class="form-group annotation-'+barisN+'"><label for="Annotation">Catatan:</label><input type="text" class="form-control" name="data[annotation][]" id="annotation"></div><script>$(document).ready(function(){ $("#price-'+barisN+'").mask("0.000.000.000.000,00", {reverse: true});$("#qty-'+barisN+'").keyup(function(){ $("#ppns").val("0"); });$("#price-'+barisN+'").keyup(function(){ $("#ppns").val("0"); });$(document).on("change", "#detail-'+barisN+'", function(e){e.preventDefault();$(".merk-'+barisN+'").hide();$(".type-'+barisN+'").hide();$(".size-'+barisN+'").hide();$(".uk_bahan_baku-'+barisN+'").hide();$(".qore-'+barisN+'").hide();$(".lin-'+barisN+'").hide();$(".qty_bahan_baku-'+barisN+'").hide();$(".roll-'+barisN+'").hide();$(".ingredient-'+barisN+'").hide();$(".porporasi-'+barisN+'").hide();$(".unit-'+barisN+'").hide();$(".volume-'+barisN+'").hide();var id_'+barisN+' = $(this).val();if(id_'+barisN+'){$.ajax({url : "../auth/json.php",dataType: "JSON",type : "GET",contentType: "application/json; charset=utf-8",cache: false,data: {req: "so_attribute",id: id_'+barisN+'},success: function(output){var split_'+barisN+' = output[0].field.split(",");for(var loop_'+barisN+' = 0; loop_'+barisN+' < split_'+barisN+'.length; loop_'+barisN+'++){$("."+split_'+barisN+'[loop_'+barisN+']+"-'+barisN+'").show();}}});}});});</script></div>'
  			);
  		
  	});

  	$(document).on('click', '.btn_remove', function(){  
           var button_id = $(this).attr('idx');
           $('#looping-'+button_id+'').remove();
           $('#ppns').val('0');
    });

    //////////////////////////////////////////////////////
  	// Pemeriksaan tipe item subcont, internal, in stock
  	//////////////////////////////////////////////////////

  	$(document).on("change", "#source", function(){
  		var idsumber = $(this).attr("id_source");  
  		var valSource = $(this).val();
  		if(valSource == "2"){
  			$("label[for='Subcont-"+idsumber+"']").html("Subcont kepada: <span class='required'>*</span>");
           	$("#etc1-input-"+idsumber+"").attr("type", "text");
           	$(".etc1-class-"+idsumber+"").show();
           	$(".etc2-class-"+idsumber+"").show();
        } else if(valSource == "3"){
  			$("label[for='Subcont-"+idsumber+"']").html("Stok tersedia: <span class='required'>*</span>");
           	$("#etc1-input-"+idsumber+"").attr("type", "number");
           	$(".etc1-class-"+idsumber+"").show();
           	$(".etc2-class-"+idsumber+"").hide();
        } else {
           	$(".etc1-class-"+idsumber+"").hide();
           	$(".etc2-class-"+idsumber+"").hide();
        }
    });

  	///////////////////////////
  	// Add PO form
  	//////////////////////////

  	$(document).on('submit', IDForm+'.add', function(e){
    	e.preventDefault();
    	hide_ipad_keyboard();
      	hide_lightbox();
      	show_loading_message();
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
	
					var value = (fieldName === 'qty' || fieldName === 'detail' || fieldName === 'porporasi' || fieldName === 'volume')? parseInt(item.value): item.value;
					dataGroups[index][fieldName] = value;
					arr.push(matches[1]);
				}
			} else {
				var value = (item.name === 'order_grade' || item.name === 'tax' || item.name === 'customerid' || item.name === 'companyid')? parseInt(item.value) : item.value; 
				formDataObject[item.name] = value;
			}
		});

		for (var key in dataGroups) {
			formDataObject.items.push(dataGroups[key]);
		}

		
		
		var request   = $.ajax({
			url:          pathFile+"/sales-order",
			type:         'POST',
			data:         JSON.stringify(formDataObject, null, 2),
			beforeSend: function (xhr) {
				xhr.setRequestHeader('Authorization', getCookie('access_token'));
				xhr.setRequestHeader('Content-Type', 'application/json');
			}
		});

      	request.done(function(output){
			if(output.status == "success"){
	    		$("#sortby").empty();
	      		tablenya.ajax.reload(function(){
	        		hide_loading_message();
	        		var Infos = $('#customer').val();
	        		show_message("'"+Infos+"' create successfully.", 'success');
	        		reset();
	        		$('.looping_barang').remove();
	      		}, true);

				$.ajax({
					url: pathFile+"/sortdata/archive?data=po_date&from=po_customer",
					type: "GET",
					beforeSend: function (xhr) {
						xhr.setRequestHeader('Authorization', getCookie('access_token'));
						xhr.setRequestHeader('Content-Type', 'application/json');
					},
					success: function(output){
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
          			}
				});

	    	} else {
	    		$('#company').empty();
	    		$('#detail').empty();	
	      		hide_loading_message();
	      		show_message('Failed: '+output.response.message, 'error');
	    	}
	  	});
	  	request.fail(function(jqXHR, textStatus){
			$('#company').empty();
			$('#detail').empty();
			hide_loading_message();
	    	show_message('Failed: '+jqXHR.responseJSON.response.message, 'error');
	  	});
  	});

	/////////////////////
  	// Edit Customer
	////////////////////

	$(document).on('click', '.UbahCustomer', function(e){
		e.preventDefault();
	    show_loading_message();
	    hidden_input();
	    var id      = $(this).data('id');
	    var request = $.ajax({
	    	url:          pathFile+"/sales-order/customer/"+id,
			type:         'GET',
			beforeSend: function (xhr) {
				xhr.setRequestHeader('Authorization', getCookie('access_token'));
				xhr.setRequestHeader('Content-Type', 'application/json');
			}
	    });
	    request.done(function(output){
	    	if(output.status == "success"){
	    		$('h2.FormTitle').text('UBAH '+FormsLug);
	        	$(IDForm +'').attr('class', 'form edit_customer');
	        	$(IDForm +'').attr('data-id', id);
	        	$(IDForm +' .field_container label.error').hide();
	        	$('#company').append('<option value="">Pilih Perusahaan</option>');
	        	for(var x = 0; x<companyJSON.length; x++)
	        	{
	        		$('#company').append('<option value="'+companyJSON[x].id+'" '+(companyJSON[x].id == output.response.data[0].companyid ? 'selected':'')+'>'+companyJSON[x].companyname+'</option>');
	        	}
	        	$('#customer').val(output.response.data[0].customer);
	        	$('#id_customer').val(output.response.data[0].id_customer);
	        	$('#order_grade').val(output.response.data[0].order_grade);
		        $('#po_date').val(output.response.data[0].po_date);
		        $('#po_customer').val(output.response.data[0].po_customer);
		        $('#ppns').val(output.response.data[0].tax);
		        $('.ppns').show();
		        $('.source').hide();
		        $('.item').hide();
		        $('.detail').hide();
		        $('.price').hide();
		        $('.qty').hide();
		        $('.annotation').hide();
		        $('.header-item').hide();
		        $('.tambah_barang').hide();
		        $('.footer-item').hide();
	        	hide_loading_message();
	        	show_lightbox();

	        	////////////////////////////////////////
			  	// auto complete customer
			  	//////////////////////////////////////

			  	$.widget( "custom.catcomplete", $.ui.autocomplete, {
			  		_create: function()
			  		{
			  			this._super();
			  			this.widget().menu( "option", "items", "> :not(.ui-autocomplete-category)" );
			  		},

			  		_renderMenu: function( ul, items )
			  		{
			  			var that = this,
			  			currentCategory = "";
			  			$.each( items, function( index, item )
			  			{
			  				var li;
			  				if ( item.category != currentCategory )
			  				{
			  					ul.append( "<li class='ui-autocomplete-category'>" + item.category + "</li>" );
			  					currentCategory = item.category;
			  				}
			  				li = that._renderItemData( ul, item );
			  				if ( item.category )
			  				{
			  					li.attr( "aria-label", item.category + " : " + item.label );
			  				}
			  			});
			  		}
			  	});

			  	$('#customer').catcomplete({
			  		minLength: 2,
			  		source: function(request, response)
			  		{
						$.ajax({
							url: pathFile+"/sales-order/suggest/customer?keyword="+request.term,
							type: "GET",
							beforeSend: function (xhr) {
								xhr.setRequestHeader('Authorization', getCookie('access_token'));
								xhr.setRequestHeader('Content-Type', 'application/json');
							},
							success: function(output)
							  {
								  response(output.response.data);
							  }
						});
			  		},
			  		select: function(event, ui)
			  		{
						var customerid = ui.item.customerid;
						var poid = ui.item.po_id;

			  			var meminta = $.ajax({
							url: pathFile+"/sales-order/suggest/item?customerid="+customerid+"&poid="+poid,
							type: 'GET',
							beforeSend: function (xhr) {
								xhr.setRequestHeader('Authorization', getCookie('access_token'));
								xhr.setRequestHeader('Content-Type', 'application/json');
							},
						});
		
			  			meminta.done(function(output){ $('#id_customer').val(output.response.data[0].customerid); });

			  			meminta.fail(function(jqXHR, textStatus)
			  			{
			  				hide_loading_message();
			  				show_message('Failed: '+jqXHR.responseJSON.response.message, 'error');
			  			});
			  		}
			  	});

			  	$('#customer').keyup(function(){ $("#id_customer").val('0'); });

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
	// Edit Customer form
  	/////////////////////////
  	$(document).on('submit', IDForm+'.edit_customer', function(e){
    	e.preventDefault();
    	if (FormNYA.valid() == true){
      		hide_ipad_keyboard();
      		hide_lightbox();
      		show_loading_message();
      		var form_data 	= $(IDForm).serializeArray();
      		var id			= $(IDForm).attr('data-id');

      		var jsonData = {};
			$.each(form_data, function(){
				if(this.name == 'companyid' || this.name == 'customerid' || this.name == 'order_grade' || this.name == 'tax') {
					value = parseInt(this.value)
				} else {
					value = this.value
				}
				jsonData[this.name] = value;
			});
			
			jsonData.poid = parseInt(id);

			var request   = $.ajax({
				url:          pathFile+"/sales-order/customer",
				type:         'PUT',
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
						var Infos = $('#customer').val();
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

  	/////////////////////
  	// Edit Item Button
	////////////////////

	$(document).on('click', '.UbahItem', function(e){
		e.preventDefault();
	    show_loading_message();
	    show_input();
	    var id			= $(this).data('id');
		var request = $.ajax({
			url:          pathFile+"/sales-order/item/"+id,
			type:         'GET',
			beforeSend: function (xhr) {
				xhr.setRequestHeader('Authorization', getCookie('access_token'));
				xhr.setRequestHeader('Content-Type', 'application/json');
			}
		 });
	    request.done(function(output){
	    	if(output.status == "success"){
	    		$('h2.FormTitle').text('UBAH BARANG '+FormsLug);
	        	$(IDForm +'').attr('class', 'form edit_item');
	        	$(IDForm +'').attr('data-id', id);
	        	$(IDForm +' .field_container label.error').hide();
	        	var exSources = output.response.data[0].sources.split('|');
		        if(exSources[0] == 2){
		        	$("label[for='Subcont-1']").html("Subcont kepada: <span class='required'>*</span>");
		           	$("#etc1-input-1").attr("type", "text");
		           	$("#source").val(exSources[0]);
		           	$("#etc1-input-1").val(exSources[1]);
		           	$("#etc2-input").val(exSources[2]);
		           	$(".etc1-class-1").show();
		           	$(".etc2-class-1").show();
		        } else if(exSources[0] == 3) {
		        	$("label[for='Subcont-1']").html("Stok tersedia: <span class='required'>*</span>");
		           	$("#etc1-input-1").attr("type", "number");
		           	$("#source").val(exSources[0]);
		           	$("#etc1-input-1").val(exSources[1]);
		           	$(".etc1-class-1").show();
		           	$(".etc2-class-1").hide();
		        } else if(exSources[0] == 1) {
		        	$("#source").val(exSources[0]);
		        }
		        $('#item').val(output.response.data[0].item);
		        $('#detail').append('<option value="" selected>Pilih Item</option>');
				for(var z = 0; z < detailJSON.length; z++)	
				{
					$('#detail').append('<option value="'+detailJSON[z].id+'" '+(detailJSON[z].id == output.response.data[0].detail ? 'selected': '')+'>' +detailJSON[z].item+ '</option>');
				}
		        $('#size').val(output.response.data[0].size);
		        $('#merk').val(output.response.data[0].merk);
		        $('#type').val(output.response.data[0].type);
		        $('#uk_bahan_baku').val(output.response.data[0].uk_bahan_baku);
		        $('#qore').val(output.response.data[0].qore);
		        $('#lin').val(output.response.data[0].lin);
		        $('#qty_bahan_baku').val(output.response.data[0].qty_bahan_baku);
		        $('#roll').val(output.response.data[0].roll);
		        $('#ingredient').val(output.response.data[0].ingredient);
		        $('#porporasi').val(output.response.data[0].porporasi);
		        $('#unit').val(output.response.data[0].unit);
		        $('#volume').val(output.response.data[0].volume);
		        $('#annotation').val(output.response.data[0].annotation);
		        $('#qty').val(output.response.data[0].qty);
		        $('#unit').val(output.response.data[0].unit);
		        $('#price').val(output.response.data[0].price);
		        $('#id_wo').val(output.response.data[0].woitemid);
		        $('#source').attr('name','sources');
        		$('#etc1-input-1').attr('name','etc1');
        		$('#etc2-input').attr('name','etc2');
		        $('#item').attr('name','item');
		        $('#detail').attr('name','detail');
		        $('#merk').attr('name','merk');
		        $('#type').attr('name','type');
		        $('#size').attr('name','size');
		        $('#uk_bahan_baku').attr('name','uk_bahan_baku');
		        $('#qty').attr('name','qty');
		        $('#unit').attr('name','unit');
		        $('#price').attr('name','price');
		        $('#qore').attr('name','qore');
		        $('#lin').attr('name','lin');
		        $('#qty_bahan_baku').attr('name','qty_bahan_baku');
		        $('#roll').attr('name','roll');
		        $('#ingredient').attr('name','ingredient');
        		$('#porporasi').attr('name','porporasi');
		        $('#volume').attr('name','volume');
		        $('#annotation').attr('name','annotation');
		        $('.company').hide();
	        	$('.customer').hide();
		        $('.po_date').hide();
		        $('.po_customer').hide();
		        $('.order_grade').hide();
		        $('.header-item').hide();
		        $('.tambah_barang').hide();
		        $('.footer-item').hide();
		        $('.ppns').hide();
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
	// Edit item form
  	/////////////////////////

  	$(document).on('submit', IDForm+'.edit_item', function(e){
    	e.preventDefault();
    	if (FormNYA.valid() == true){
      		hide_ipad_keyboard();
      		hide_lightbox();
      		show_loading_message();
      		
      		var form_data 	= $(IDForm).serializeArray();
      		var id			= $(IDForm).attr('data-id');

			var jsonData = {};
			$.each(form_data, function(){
				if(this.name == "qty" | this.name == "porporasi" || this.name == "volume" || this.name == "detail") {
					value = parseInt(this.value);
				} else if(this.name == "id_wo") {
					jsonData.woitemid = parseInt(this.value);
				} else {
					value = this.value;
				}
				jsonData[this.name] = value;
			});

			jsonData.poitemid = parseInt(id);

			var request   = $.ajax({
				url:          pathFile+"/sales-order/item",
				type:         'PUT',
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
            			show_message("Update sucessfully.", 'success');
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
  	// Delete item button
  	//////////////////
  	$(document).on('click', '.HapusItem', function(e){
	    e.preventDefault();
	    var Infos = $(this).data('name');
	    if (confirm("Anda yakin ingin menghapus '"+Infos+"'?")){
	    	show_loading_message();
	      	var id      = $(this).data('id');
			var request = $.ajax({
				url:          pathFile+"/sales-order/item/"+id,
				type:         'DELETE',
				beforeSend: function (xhr) {
					xhr.setRequestHeader('Authorization', getCookie('access_token'));
					xhr.setRequestHeader('Content-Type', 'application/json');
				}
			});
	      	
	      	request.done(function(output){
	        	if(output.status == "success"){
	          		tablenya.ajax.reload(function(){
	            		hide_loading_message();
	            		show_message("'"+Infos+"' berhasil dihapus.", 'success');
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