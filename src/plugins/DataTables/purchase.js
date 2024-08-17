$(document).ready(function(){

	//////////////////////////
	//Default config
	/////////////////////////

	var idTablenya = $('#tablenya');
	var pathFile = decodeURIComponent(getCookie('base_url_api')) +':'+ getCookie('base_port_api') + decodeURIComponent(getCookie('base_path_api')) + decodeURIComponent(getCookie('base_dashboard_api'));
	var FormsLug = 'PURCHASE ORDER';
	var IDForm = "#form_inputPO";
	var barisN = 1;
	var loopN = 1;
	var companyJSON = [];
	var po_typeJSON = [];
	var po_type_attribute = [];

	/////////////////////////////////////////////////////////////////
	// Set cookie as 'archive'
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

	/////////////////////////////////////////////////////////////////
	// Get po type list
	/////////////////////////////////////////////////////////////////

	var reqDetail = $.ajax({
		url: pathFile+"/purchase-order/suggest/type",
		type: "GET",
		beforeSend: function (xhr) {
			xhr.setRequestHeader('Authorization', getCookie('access_token'));
			xhr.setRequestHeader('Content-Type', 'application/json');
		}
	});

	reqDetail.done(function(output){
		po_typeJSON = output.response.data;
	});

	reqDetail.fail(function(jqXHR, textStatus)
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
			"url" : pathFile+"/purchase-order",
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
	    'columnDefs': [
	    	{
	    		'targets': [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,18,19,20,21],
	            'className': 'dt-nowrap'
	        },
			{
                "targets": [7,8,17,18,19],
                "render": function(data, type, row) {
                    return new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        minimumFractionDigits: 2
                    }).format(parseFloat(data));
                }
            },
			{
				"targets": 21,
				"data": null,
				"defaultContent": "",
				"render": function (data, type, row) {
					//return '<button class="btn btn-default function_edit" data-id="'+ data.id +'"><i class="fa fa-pencil"></i></button>';
					return '<button class="btn btn-default UbahVendor" data-id="'+ data.po_id +'" title="Edit Vendor"><i class="fa fa-user"></i></button> <button class="btn btn-default UbahItem" data-id="'+ data.itemid +'" title="Edit Item"><i class="fa fa-cube"></i></button> <button class="btn btn-default PrintView" data-id="'+ data.po_id +'" title="Print View"><i class="fa fa-print"></i></button> <button class="btn btn-default HapusItem" data-id="'+ data.itemid +'" data-name="'+data.detail+'" title="Delete"><i class="fa fa-trash"></i></button>';
				}
			}
	    ],
	    "columns": [
	      { "data": "po_date" },
	      { "data": "company" },
	      { "data": "vendor" },
	      { "data": "po_number" },
	      { "data": "po_type" },
	      { "data": "detail"},
	      { "data": "size"},
	      { "data": "price_1"},
	      { "data": "price_2"},
	      { "data": "qty"},
	      { "data": "unit"},
	      { "data": "merk"},
	      { "data": "item_type"},
	      { "data": "core"},
	      { "data": "roll"},
	      { "data": "material"},
	      { "data": "note"},
	      { "data": "subtotal"},
	      { "data": "tax"},
	      { "data": "total"},
	      { "data": "user"},
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
            SubTotal = api.column( 17, { page: 'current'} ).data().reduce( function (a, b) {
            	return intVal(a) + intVal(b);
            }, 0 );

            Tax = api.column( 18, { page: 'current'} ).data().reduce( function (a, b) {
            	return intVal(a) + intVal(b);
            }, 0 );

            Totals = SubTotal + Tax;

            $( api.column( 17 ).footer() ).html(convertToRupiah(SubTotal));
            $( api.column( 18 ).footer() ).html(convertToRupiah(Tax));
            $( api.column( 19 ).footer() ).html(convertToRupiah(Totals));
        }
	});

	var buttons = new $.fn.dataTable.Buttons(tablenya, {
		buttons:[
        {
        	extend: 'excelHtml5',
        	messageTop: false,
        	footer: true,
        	text: 'Export to Excel',
        	filename : 'PurchaseOrder-'+(getCookie("report") == "month" || getCookie("report") == "year" ? getCookie("report") + "_" + getCookie("startdate") : getCookie("startdate") +"_"+ getCookie("enddate")),
        	title: 'PURCHASE ORDER '+(getCookie("report") == "month" || getCookie("report") == "year" ? getCookie("report") + "_" + getCookie("startdate") : getCookie("startdate") +"_"+ getCookie("enddate")),
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

	function clean(){
		$('#vendor').val('');
		$('#id_vendor').val('');
		$('#detail').val('');
		$('#size').val('');
		$('#merk').val('');
		$('#type').val('');
		$('#core').val('');
		$('#gulungan').val('');
		$('#bahan').val('');
		$('#price_1').val('');
		$('#price_2').val('');
		$('#qty').val('');
		$('#unit').val('');
		$('#note').val('');
		$('#ppns').val('0');
		$('.po_type').val('');
		$('#looping_barang').empty();
		$('.logo_surat').empty();
		$('.tbody').empty();
		$('.thead').empty();
		$('.thead').empty();
		$('.tfoot-heading').empty();
		$('.tfoot-value1').empty();
		$('.tfoot-value2').empty();
		$('.tfoot-value3').empty();
	}

	function reset(){
		if(document.getElementById("form_AddItemPO")) {
			$('#form_AddItemPO').attr('id', 'form_inputPO');
		}
		$('.tambah_barang').hide();
		$('.tanda_tangan').hide();
        $('.address').hide();
		$('#PrintModal').hide();
		$('.company').show();
		$('.vendor').show();
        $('.po_date').show();
        $('.po_type').show();
        $('.price_1').show();
        $('.hitung_1').show();
        $('.qty').show();
        $('.ppns').show();
        $('.note').show();
		$('.header-item').show();
		$('.footer-item').show();
        $('.detail').hide();
		$('.size').hide();
		$('.merk').hide();
		$('.type').hide();
		$('.core').hide();
		$('.gulungan').hide();
		$('.bahan').hide();
		$('.price_2').hide();
		$('.unit').hide();
		$('.po_number').hide();
		$('#detail').attr('name','data[detail][]');
        $('#size').attr('name','data[size][]');
        $('#merk').attr('name','data[merk][]');
        $('#type').attr('name','data[type][]');
        $('#core').attr('name','data[core][]');
        $('#gulungan').attr('name','data[roll][]');
        $('#bahan').attr('name','data[material][]');
        $('#price_1').attr('name','data[price_1][]');
        $('#price_2').attr('name','data[price_2][]');
        $('#qty').attr('name','data[qty][]');
        $('#unit').attr('name','data[unit][]');
        $('#vendor').attr('readonly', false);
		$('#id_vendor').attr('readonly', false);
		$('#po_date').attr('readonly', false);
		$('#po_type_add_item').attr('id', 'po_type');
		if(document.getElementById("po_type")){
			document.getElementById("po_type").disabled = false;
		}
		$('#detail').attr('readonly', false);
		$('#size').attr('readonly', false);
		$('#merk').attr('readonly', false);
		$('#type').attr('readonly', false);
		$('#core').attr('readonly', false);
		$('#gulungan').attr('readonly', false);
		$('#bahan').attr('readonly', false);
		$('#price_1').attr('readonly', false);
		$('#price_2').attr('readonly', false);
		$('#qty').attr('readonly', false);
		$('#unit').attr('readonly', false);
		$('#note').attr('readonly', false);
		$('#ppns').attr('readonly', false);
	}

	// Lightbox close button
	$(document).on('click', '.lightbox_close', function(){
	    hide_lightbox();
	    reset();
	    clean();
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
		let formatted = new Intl.NumberFormat('id-ID', {
			style: 'currency',
			currency: 'IDR',
			minimumFractionDigits: 2
		}).format(angka);
		return formatted;
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
	    	url:          pathFile+"/purchase-order",
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
	
	
	///////////////////
	// Add Item PO
  	//////////////////

  	$(document).on('click', '#add_item_po', function(e){
		e.preventDefault();
		$(IDForm).attr('id', 'form_AddItemPO');
		$('H2.FormTitle').text('ADD ITEM '+FormsLug);
		$('#form_AddItemPO').attr('class', 'form add');
		$('#form_AddItemPO').attr('data-id', '');
		$('#po_type').attr('id', 'po_type_add_item');
		$('.po_number').show();
		$('.company').hide();
		$('.vendor').hide();
		$('.po_date').hide();
		$('.ppns').hide();
		$('.note').hide();
		$('#po_type_add_item').empty();
		show_lightbox();

		$('#po_type_add_item').append('<option value="" selected disabled>Pilih Tipe</option>');
		for(var z = 0; z < po_typeJSON.length; z++)	
		{
			$('#po_type_add_item').append('<option value="'+po_typeJSON[z].id+'">' +po_typeJSON[z].item+ '</option>');
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

		$('#po_number').catcomplete({
			minLength: 4,
			source: function(request, response)
			{
				$.ajax({
					url: pathFile+"/purchase-order/suggest/po?keyword="+request.term,
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
				var po_number = ui.item.category;
				var fkid = (ui.item.fkid > 0 ? ui.item.fkid : '' );
				var item_type = (ui.item.item_type > 0 ? ui.item.item_type : '');

				$('#po_type_add_item').attr('id', 'po_type');
				reset();
				$('#form_inputPO').attr('id', 'form_AddItemPO');
				$('#po_type').attr('id', 'po_type_add_item');
				$('.po_number').show();
				$('.company').hide();
				$('#company').val('');
				$('.vendor').hide();
				$('#vendor').val('');
				$('.po_date').hide();
				$('#po_date').val('');
				$('.note').hide();
				$('#note').val('');
				$('.ppns').hide();
				$('#tax').val('');
				$('#looping_barang').empty();
				$('.tambah_barang').show();
				document.getElementById("po_type_add_item").disabled = true;

				$('#po_number').val(po_number);
				$('#fkid').val(fkid);

				if(item_type > 0)
				{
					$('#po_type_add_item').val(item_type);
					$('.tambah_barang').show();

					$.ajax({
						url: pathFile+"/purchase-order/suggest/attr?id="+item_type,
						type: "GET",
						beforeSend: function (xhr) {
							xhr.setRequestHeader('Authorization', getCookie('access_token'));
							xhr.setRequestHeader('Content-Type', 'application/json');
						},
						success: function(output){
							po_type_attribute = output.response.data.field;
							split_po_type_attribute = output.response.data.field.split(',');
							for(var x = 0; x < split_po_type_attribute.length; x++)
								$('.'+split_po_type_attribute[x]).show();
						}
					});
				}				
			}
		});

		$('#po_number').keyup(function(){
			$('#po_type').val('');
			$('.po_type').val('');
			$('#detail').val('');
			$('#size').val('');
			$('#merk').val('');
			$('#type').val('');
			$('#core').val('');
			$('#gulungan').val('');
			$('#bahan').val('');
			$('#price_1').val('');
			$('#price_2').val('');
			$('#qty').val('');
			$('#unit').val('');
			$('#looping_barang').empty();
		});
	});

	///////////////////////////
	// submit form Add Item PO
  	/////////////////////////

  	$(document).on('submit', '#form_AddItemPO.add', function(e){
    	e.preventDefault();
    	if (FormNYA.valid() == true)
		{
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
		
						var value = (fieldName === 'qty')? parseInt(item.value): item.value;
						dataGroups[index][fieldName] = value;
						arr.push(matches[1]);
					}
				} else {
					var value = (item.name === 'fkid')? parseInt(item.value) : item.value; 
					formDataObject[item.name] = value;
				}
			});
	
			for (var key in dataGroups) {
				formDataObject.items.push(dataGroups[key]);
			}

			var request   = $.ajax({
				url:          pathFile+"/purchase-order/item",
				type:         'POST',
				data:         JSON.stringify(formDataObject, null, 2),
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
            			var Infos = $('#po_number').val();
            			show_message("'"+Infos+"' create successfully.", 'success');
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

	///////////////////
	// Add PO button
  	//////////////////

  	$(document).on('click', '#create_po', function(e){
  		e.preventDefault();
		$('H2.FormTitle').text('INPUT '+FormsLug);
		$(IDForm).attr('class', 'form add');
		$(IDForm).attr('data-id', '');
		$('.ppns').show();
		$('.tambah_barang').hide();
		$('#company').empty();
	    $('#po_type').empty();
		show_lightbox();

		$('#company').append('<option selected disabled>Pilih Entitas</option>');
		for(var x = 0; x<companyJSON.length; x++)
		{
			$('#company').append('<option value="'+companyJSON[x].id+'">'+companyJSON[x].companyname+'</option>');
		}

		$('#po_type').append('<option value="" selected disabled>Pilih Tipe</option>');
		for(var z = 0; z < po_typeJSON.length; z++)	
		{
			$('#po_type').append('<option value="'+po_typeJSON[z].id+'">' +po_typeJSON[z].item+ '</option>');
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

	  	$('#vendor').catcomplete({
	  		minLength: 2,
	  		source: function(request, response)
	  		{
				$.ajax({
					url: pathFile+"/purchase-order/suggest/vendor?keyword="+request.term,
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
	  			var poid = ui.item.po_id;
	  			var vendorid = ui.item.vendorid;
				if(!poid) {
					poid = 0;
				}
				
	  			var getSuggestItem = $.ajax({
					url: pathFile+"/purchase-order/suggest/item?vendorid="+vendorid+"&poid="+poid,
	  				type: 'GET',
	  				beforeSend: function (xhr) {
						xhr.setRequestHeader('Authorization', getCookie('access_token'));
						xhr.setRequestHeader('Content-Type', 'application/json');
					},
	  			});

	  			getSuggestItem.done(function(output) {
					reset();
	  				$('#id_vendor').val(output.response.data[0].vendorid);
	  				if(parseInt(output.response.data[0].po_id) > 0)
	  				{
	  					po_type_attribute = output.response.data[0].attr; 
	  					var split_input = output.response.data[0].attr.split(',');
	  					$('#po_type').val(output.response.data[0].type);
	  					$('.po_type').val(output.response.data[0].type);
	  					$('.tambah_barang').show();

	  					for(var x = 0; x < output.response.data[0].items.length; x++)
	  					{
	  						if(parseInt(x) === 0)
	  						{
	  							$('#detail').val(output.response.data[0].items[x].detail);
								$('#size').val(output.response.data[0].items[x].size);
								$('#merk').val(output.response.data[0].items[x].merk);
								$('#type').val(output.response.data[0].items[x].type);
								$('#core').val(output.response.data[0].items[x].core);
								$('#gulungan').val(output.response.data[0].items[x].roll);
								$('#bahan').val(output.response.data[0].items[x].material);
								$('#price_1').val(output.response.data[0].items[x].price_1);
								$('#price_2').val(output.response.data[0].items[x].price_2);
								$('#qty').val(output.response.data[0].items[x].qty);
								$('#unit').val(output.response.data[0].items[x].unit);
								for(var i = 0; i < split_input.length; i++)
			  					{
			  						$('.'+split_input[i]).show();
			  					}

	  						} else {
	  							loopN++;
	  							$('#looping_barang').append(
	  								'<div class="looping_barang" id="looping-'+loopN+'"><hr class="looping-item"><p><button type="button" name="remove" data-id="'+loopN+'" class="btn btn-danger btn_remove">Hapus</button></p><div class="form-group detail-'+loopN+'" style="display: none"><label for="detail">Detail: <span class="required">*</span></label><input type="text" class="form-control" name="data[detail][]" id="detail" value="'+output.response.data[0].items[x].detail+'" required></div><div class="form-group size-'+loopN+'" style="display: none"><label for="Size">Size: <span class="required">*</span></label><input type="number" class="form-control sizeval_'+loopN+'" name="data[size][]" id="size" value="'+output.response.data[0].items[x].size+'" required></div><div class="form-group merk-'+loopN+'" style="display: none"><label for="merk">Merk: <span class="required">*</span></label><input type="text" class="form-control" name="data[merk][]" id="merk" value="'+output.response.data[0].items[x].merk+'" required></div><div class="form-group type-'+loopN+'" style="display: none"><label for="type">Type: <span class="required">*</span></label><input type="text" class="form-control" name="data[type][]" id="type" value="'+output.response.data[0].items[x].type+'" required></div><div class="form-group core-'+loopN+'" style="display: none"><label for="core">Core: <span class="required">*</span></label><input type="text" class="form-control" name="data[core][]" id="core" value="'+output.response.data[0].items[x].core+'" required></div><div class="form-group gulungan-'+loopN+'" style="display: none"><label for="gulungan">Gulungan: <span class="required">*</span></label><input type="text" class="form-control" name="data[roll][]" id="gulungan" value="'+output.response.data[0].items[x].roll+'" required></div><div class="form-group bahan-'+loopN+'" style="display: none"><label for="bahan">Bahan: <span class="required">*</span></label><input type="text" class="form-control" name="data[material][]" id="bahan" value="'+output.response.data[0].items[x].material+'" required></div><div class="form-group price_1"><label for="price_1">Price: <span class="required">*</span></label><input type="text" class="form-control price_1_looping-'+loopN+'" name="data[price_1][]" id="price_1" value="'+output.response.data[0].items[x].price_1+'" required></div><div class="form-group price_2-'+loopN+'" style="display: none"><label for="price_2">Price (Secondary): <span class="required">*</span> <em class="label label-success">Size x Price = Price (secondary)</em></label><div class="row"><div class="col-md-10"><input type="text" class="form-control price_2_looping-'+loopN+'" name="data[price_2][]" id="price_2" value="'+output.response.data[0].items[x].price_2+'" required></div><div class="col-md-2"><button type="button" class="btn btn-primary hitung_'+loopN+'">Hitung</button></div></div><div class="form-group qty"><label for="qty">Qty: <span class="required">*</span></label><input type="number" min="0" class="form-control" name="data[qty][]" id="qty" value="'+output.response.data[0].items[x].qty+'" required></div><div class="form-group unit-'+loopN+'" style="display: none"><label for="unit">Unit: <span class="required">*</span></label><input type="text" class="form-control" name="data[unit][]" id="unit" value="'+output.response.data[0].items[x].unit+'" required></div><script>$(document).ready(function(){ $(".price_1_looping-'+loopN+'").mask("0.000.000.000.000,00", {reverse: true});$(".price_2_looping-'+loopN+'").mask("0.000.000.000.000,00", {reverse: true});$(document).on("click", ".hitung_'+loopN+'", function(e){ var ukuran_'+loopN+' = $(".sizeval_'+loopN+'").val();var harga_'+loopN+' = $(".price_1_looping-'+loopN+'").val().replace(/\\./g,"");$(".price_2_looping-'+loopN+'").val(parseInt(ukuran_'+loopN+' * harga_'+loopN+'.replace(/\\,/g,".")));});});</script></div>'
	  							);

	  							for(var i = 0; i < split_input.length; i++)
			  					{
			  						$('.'+split_input[i]+'-'+loopN).show();
			  					}
	  						}
	  					}
	  				}
	  			});

	  			getSuggestItem.fail(function(jqXHR, textStatus)
	  			{
	  				hide_loading_message();
	  				show_message('Failed: '+jqXHR.responseJSON.response.message, 'error');
	  			});

	  		}
	  	});

	  	$('#vendor').keyup(function(){
	  		$('#id_vendor').val('');
	  		$('#po_type').val('');
	  		$('.po_type').val('');
	  		$('#detail').val('');
			$('#size').val('');
			$('#merk').val('');
			$('#type').val('');
			$('#core').val('');
			$('#gulungan').val('');
			$('#bahan').val('');
			$('#price_1').val('');
			$('#price_2').val('');
			$('#qty').val('');
			$('#unit').val('');
			$('#note').val('');
			$('#ppns').val('0');
	  		$('#looping_barang').empty();
	  		reset();
	  	});
  	});

	///////////////////////////
  	// Add PO (item)
  	//////////////////////////

  	$(document).on('click', '.tambah_barang', function(e){
  		e.preventDefault();
  		barisN++;
  		var split_po_type_attribute = po_type_attribute.split(',');
  		$('#looping_barang').append(
  			'<div class="looping_barang" id="looping-'+barisN+'"><hr class="looping-item"><p><button type="button" name="remove" data-id="'+barisN+'" class="btn btn-danger btn_remove">Hapus</button></p><div class="form-group detail-'+barisN+'" style="display: none"><label for="detail">Detail: <span class="required">*</span></label><input type="text" class="form-control" name="data[detail][]" id="detail" value="" required></div><div class="form-group size-'+barisN+'" style="display: none"><label for="Size">Size: <span class="required">*</span></label><input type="number" class="form-control sizeval_'+barisN+'" name="data[size][]" id="size" value="" required></div><div class="form-group merk-'+barisN+'" style="display: none"><label for="merk">Merk: <span class="required">*</span></label><input type="text" class="form-control" name="data[merk][]" id="merk" required></div><div class="form-group type-'+barisN+'" style="display: none"><label for="type">Type: <span class="required">*</span></label><input type="text" class="form-control" name="data[type][]" id="type" required></div><div class="form-group core-'+barisN+'" style="display: none"><label for="core">Core: <span class="required">*</span></label><input type="text" class="form-control" name="data[core][]" id="core" required></div><div class="form-group gulungan-'+barisN+'" style="display: none"><label for="gulungan">Gulungan: <span class="required">*</span></label><input type="text" class="form-control" name="data[roll][]" id="gulungan" required></div><div class="form-group bahan-'+barisN+'" style="display: none"><label for="bahan">Bahan: <span class="required">*</span></label><input type="text" class="form-control" name="data[material][]" id="bahan" required></div><div class="form-group price_1"><label for="price_1">Price: <span class="required">*</span></label><input type="text" class="form-control price_1_looping-'+barisN+'" name="data[price_1][]" id="price_1" required></div><div class="form-group price_2-'+barisN+'" style="display: none"><label for="price_2">Price (Secondary): <span class="required">*</span> <em class="label label-success">Size x Price = Price (secondary)</em></label><div class="row"><div class="col-md-10"><input type="text" class="form-control price_2_looping-'+barisN+'" name="data[price_2][]" id="price_2" required></div><div class="col-md-2"><button type="button" class="btn btn-primary hitung_'+barisN+'">Hitung</button></div></div></div><div class="form-group qty"><label for="qty">Qty: <span class="required">*</span></label><input type="number" min="0" class="form-control" name="data[qty][]" id="qty" required></div><div class="form-group unit-'+barisN+'" style="display: none"><label for="unit">Unit: <span class="required">*</span></label><input type="text" class="form-control" name="data[unit][]" id="unit" required></div><script>$(document).ready(function(){	$(".price_1_looping-'+barisN+'").mask("0.000.000.000.000,00", {reverse: true});	$(".price_2_looping-'+barisN+'").mask("0.000.000.000.000,00", {reverse: true});$(document).on("click", ".hitung_'+barisN+'", function(e){ var ukuran_'+barisN+' = $(".sizeval_'+barisN+'").val();var harga_'+barisN+' = $(".price_1_looping-'+barisN+'").val().replace(/\\./g,"");$(".price_2_looping-'+barisN+'").val(parseInt(ukuran_'+barisN+' * harga_'+barisN+'.replace(/\\,/g,".")));});});</script></div>'
  		);

		for(var x = 0; x < split_po_type_attribute.length; x++)
		{
			$('.'+split_po_type_attribute[x]+'-'+barisN).show();
		}
  	});

  	$(document).on('click', '.btn_remove', function(){  
           var button_id = $(this).data('id');
           $('#looping-'+button_id+'').remove();
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
	
					var value = (fieldName === 'qty')? parseInt(item.value): item.value;
					dataGroups[index][fieldName] = value;
					arr.push(matches[1]);
				}
			} else {
				var value = (item.name === 'companyid' || item.name === 'vendorid' || item.name === 'fkid')? parseInt(item.value) : item.value; 
				formDataObject[item.name] = value;
			}
		});
	
		for (var key in dataGroups) {
			formDataObject.items.push(dataGroups[key]);
		}

		var request   = $.ajax({
			url:          pathFile+"/purchase-order",
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
					var Infos = $('#vendor').val();
	        		show_message("'"+Infos+"' berhasil dimasukan.", 'success');
	        		$('#company').empty();
	        		$('#po_type').empty();
	        		reset();
	        		clean();
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
	    		$('#po_type').empty();
	    		$('.po_type').empty();
	      		hide_loading_message();
	      		show_message('Failed: '+output.response.message, 'error');
	    	}
	  	});
	  	request.fail(function(jqXHR, textStatus){
	  		$('#company').empty();
	  		$('#po_type').empty();
	  		$('.po_type').empty();
	    	hide_loading_message();
	    	show_message('Failed: '+jqXHR.responseJSON.response.message, 'error');
	  	});
  	});

	/////////////////////
  	// Edit Vendor
	////////////////////

	$(document).on('click', '.UbahVendor', function(e){
		e.preventDefault();
	    show_loading_message();
	    $('.ppns').show();
	    $('#company').empty();
		$('.saving').text('Simpan');
	    document.getElementById("po_type").disabled = true;
		var id      = $(this).data('id');
	    var request = $.ajax({
	    	url:          pathFile+"/purchase-order/vendor/"+id,
			type:         'GET',
			beforeSend: function (xhr) {
				xhr.setRequestHeader('Authorization', getCookie('access_token'));
				xhr.setRequestHeader('Content-Type', 'application/json');
			}
	    });
	    request.done(function(output){
	    	if(output.status == "success"){
	        	hide_loading_message();
	        	show_lightbox();
	    		$('h2.FormTitle').text('UBAH '+FormsLug);
	        	$(IDForm).attr('class', 'form edit_vendor');
	        	$(IDForm).attr('data-id', id);
	        	$(IDForm +' .field_container label.error').hide();

	        	$('#company').append('<option selected disabled>Pilih Tipe</option>');
	        	for(var x = 0; x<companyJSON.length; x++)
	        	{
	        		$('#company').append('<option value="'+companyJSON[x].id+'" '+(companyJSON[x].id == output.response.data[0].companyid ? 'selected':'')+'>'+companyJSON[x].companyname+'</option>');
	        	}

	        	$('#po_type').append('<option value="" selected disabled>Pilih Tipe</option>');
				for(var z = 0; z < po_typeJSON.length; z++)	
				{
					$('#po_type').append('<option value="'+po_typeJSON[z].id+'" '+ (po_typeJSON[z].id == output.response.data[0].po_type ? 'selected' : '' ) +'>' +po_typeJSON[z].item+ '</option>');
				}

	        	$('#vendor').val(output.response.data[0].vendor);
	        	$('#id_vendor').val(output.response.data[0].vendorid);
		        $('#po_date').val(output.response.data[0].po_date);
	        	$('.po_type').val(output.response.data[0].po_type);
		        $('#ppns').val(output.response.data[0].tax);
		        $('#note').val(output.response.data[0].note);
		        $('.header-item').hide();
		        $('.footer-item').hide();
		        $('.tambah_barang').hide();
		        $('.detail').hide();
		        $('.size').hide();
		        $('.merk').hide();
		        $('.type').hide();
		        $('.core').hide();
		        $('.gulungan').hide();
		        $('.bahan').hide();
		        $('.price_1').hide();
		        $('.price_2').hide();
		        $('.qty').hide();
		        $('.unit').hide();

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

			  	$('#vendor').catcomplete({
			  		minLength: 2,
			  		source: function(request, response)
			  		{
						$.ajax({
							url: pathFile+"/purchase-order/suggest/vendor?keyword="+request.term,
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
			  			$('#vendor').val(ui.item.category);
			  			$('#id_vendor').val(ui.item.vendorid);
			  		}
			  	});

			  	$('#vendor').keyup(function(){
			  		$('#id_vendor').val('');
			  	});

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

	//////////////////////////////////////////////////////
	// Edit Vendor form
  	////////////////////////////////////////////////////

  	$(document).on('submit', IDForm+'.edit_vendor', function(e){
    	e.preventDefault();
    	if (FormNYA.valid() == true){
      		hide_ipad_keyboard();
      		hide_lightbox();
      		show_loading_message();
      		var form_data 	= $(IDForm).serializeArray();
      		var id			= $(IDForm).attr('data-id');

      		var jsonData = {};
			$.each(form_data, function(){
				if(this.name == 'companyid' || this.name == 'vendorid') {
					value = parseInt(this.value)
				} else {
					value = this.value
				}
				jsonData[this.name] = value;
			});
			
			jsonData.po_id = parseInt(id);

			var request   = $.ajax({
				url:          pathFile+"/purchase-order/vendor",
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
            			var Infos = $('#vendor').val();
						show_message("'"+Infos+"' update successfully.", 'success');
          				reset(); clean();
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

  	//////////////////////////////////////////
  	// Edit Item 
	/////////////////////////////////////////

	$(document).on('click', '.UbahItem', function(e){
		e.preventDefault();
	    show_loading_message();
		var id			= $(this).data('id');
		var request = $.ajax({
			url:          pathFile+"/purchase-order/item/"+id,
			type:         'GET',
			beforeSend: function (xhr) {
				xhr.setRequestHeader('Authorization', getCookie('access_token'));
				xhr.setRequestHeader('Content-Type', 'application/json');
			}
		 });

	    request.done(function(output){
	    	if(output.status == "success"){
	    		$('h2.FormTitle').text('UBAH ITEM '+FormsLug);
	        	$(IDForm).attr('class', 'form edit_item');
	        	$(IDForm).attr('data-id', id);
	        	$(IDForm +' .field_container label.error').hide();
				$('.saving').text('Simpan');
		        $('#detail').val(output.response.data[0].detail);
				$('#size').val(output.response.data[0].size);
				$('#merk').val(output.response.data[0].merk);
				$('#type').val(output.response.data[0].type);
				$('#core').val(output.response.data[0].core);
				$('#gulungan').val(output.response.data[0].roll);
				$('#bahan').val(output.response.data[0].material);
				$('#price_1').val(output.response.data[0].price_1);
				$('#price_2').val(output.response.data[0].price_2);
				$('#qty').val(output.response.data[0].qty);
				$('#unit').val(output.response.data[0].unit);
				$('#detail').attr('name','detail');
		        $('#size').attr('name','size');
		        $('#merk').attr('name','merk');
		        $('#type').attr('name','type');
		        $('#core').attr('name','core');
		        $('#gulungan').attr('name','roll');
		        $('#bahan').attr('name','material');
		        $('#price_1').attr('name','price_1');
		        $('#price_2').attr('name','price_2');
		        $('#qty').attr('name','qty');
		        $('#unit').attr('name','unit');
	        	$('.company').hide();
	        	$('.vendor').hide();
		        $('.po_date').hide();
		        $('.po_type').hide();
		        $('.header-item').hide();
		        $('.tambah_barang').hide();
		        $('.footer-item').hide();
		        $('.ppns').hide();
		        $('.note').hide();

		        var split_input = output.response.data[0].inputattr.split(',');
		        for(var i = 0; i < split_input.length; i++)
		        {
		        	$('.'+split_input[i]).show();
		        }

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
				if(this.name == "qty") {
					value = parseInt(this.value);
				} else {
					value = this.value;
				}
				jsonData[this.name] = value;
			});

			jsonData.itemid = parseInt(id);

			var request   = $.ajax({
				url:          pathFile+"/purchase-order/item",
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
            			reset(); clean();
          			}, true);
        		} else {
        			reset(); clean();
          			hide_loading_message();
          			show_message('Failed: '+output.response.message, 'error');
        		}
      		});
     		request.fail(function(jqXHR, textStatus){
     			reset(); clean();
        		hide_loading_message();
        		show_message('Failed: '+jqXHR.responseJSON.response.message, 'error');
      		});
    	}
  	});

  	//////////////////////////////////////
  	// Delete item button
  	////////////////////////////////////

  	$(document).on('click', '.HapusItem', function(e){
	    e.preventDefault();
		var Infos = $(this).data('name');
		if (confirm("Anda yakin ingin menghapus '"+Infos+"'?")){
	    	show_loading_message();
			var id      = $(this).data('id');
			var request = $.ajax({
				url:          pathFile+"/purchase-order/item/"+id,
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

  	/////////////////////////////////////////
  	// Print view button
	////////////////////////////////////////

	$(document).on('click', '.PrintView', function(e){
		e.preventDefault();
	    show_loading_message();
	    reset();
		var id      = $(this).data('id');
	    var request = $.ajax({
	    	url:          pathFile+"/purchase-order/printview/"+id,
			type:         'GET',
			beforeSend: function (xhr) {
				xhr.setRequestHeader('Authorization', getCookie('access_token'));
				xhr.setRequestHeader('Content-Type', 'application/json');
			}
	    });
	    request.done(function(output){
	    	if(output.status == "success"){
	    		$('h2.FormTitle').text('PRATINJAU PRINT '+output.response.data[0].po_type);
	        	$(IDForm+' .field_container label.error').hide();
	        	$(IDForm).attr('data-id', id);
	        	$(IDForm).attr('class', 'form printProses');
	        	$('.saving').text('Print');
	        	$('.company').hide();
	        	$('.po_type').hide();
	        	$('.tambah_barang').hide();
	        	$('.address').show();
	        	$('.tanda_tangan').show();
	        	$('#vendor').val(output.response.data[0].vendor);
		        $('#po_date').val(output.response.data[0].po_date);
		        $('#ppns').val(output.response.data[0].tax);
		        $('#note').val(output.response.data[0].note);
		        $('#address').val(output.response.data[0].vendor_address);
		        $('#tanda_tangan').val(output.response.data[0].ttd);
		        $('#vendor').attr('readonly', true);
		        $('#po_date').attr('readonly', true);
		        $('#ppns').attr('readonly', true);
		        $('#note').attr('readonly', true);
		        $('#address').attr('readonly', true);

		        for(var x = 0; x < output.response.data[0].items.length; x++)
		        {
		        	if(parseInt(x) === 0)
					{
						$('#detail').val(output.response.data[0].items[x].detail);
						$('#size').val(output.response.data[0].items[x].size);
						$('#merk').val(output.response.data[0].items[x].merk);
						$('#type').val(output.response.data[0].items[x].type);
						$('#core').val(output.response.data[0].items[x].core);
						$('#gulungan').val(output.response.data[0].items[x].roll);
						$('#bahan').val(output.response.data[0].items[x].material);
						$('#price_1').val(output.response.data[0].items[x].price_1);
						$('#price_2').val(output.response.data[0].items[x].price_2);
						$('#qty').val(output.response.data[0].items[x].qty);
						$('#unit').val(output.response.data[0].items[x].unit);
						
						split_input = output.response.data[0].items[x].inputattr.split(",");
						for(var i = 0; i < split_input.length; i++)
	  					{
	  						$('.'+split_input[i]).show();
	  					}

	  					$('#detail').attr('readonly', true);
						$('#size').attr('readonly', true);
						$('#merk').attr('readonly', true);
						$('#type').attr('readonly', true);
						$('#core').attr('readonly', true);
						$('#gulungan').attr('readonly', true);
						$('#bahan').attr('readonly', true);
						$('#price_1').attr('readonly', true);
						$('#price_2').attr('readonly', true);
						$('#qty').attr('readonly', true);
						$('#unit').attr('readonly', true);
						$('.hitung_1').hide();

					} else {
						loopN++;
						$('#looping_barang').append(
							'<div class="looping_barang" id="looping-'+loopN+'"><hr><div class="form-group detail-'+loopN+'" style="display: none"><label for="detail">Detail: <span class="required">*</span></label><input type="text" class="form-control" name="data[detail][]" id="detail-'+loopN+'" value="'+output.response.data[0].items[x].detail+'" required></div><div class="form-group size-'+loopN+'" style="display: none"><label for="Size">Size: <span class="required">*</span></label><input type="number" class="form-control sizeval_'+loopN+'" name="data[size][]" id="size-'+loopN+'" value="'+output.response.data[0].items[x].size+'" required></div><div class="form-group merk-'+loopN+'" style="display: none"><label for="merk">Merk: <span class="required">*</span></label><input type="text" class="form-control" name="data[merk][]" id="merk-'+loopN+'" value="'+output.response.data[0].items[x].merk+'" required></div><div class="form-group type-'+loopN+'" style="display: none"><label for="type">Type: <span class="required">*</span></label><input type="text" class="form-control" name="data[type][]" id="type-'+loopN+'" value="'+output.response.data[0].items[x].type+'" required></div><div class="form-group core-'+loopN+'" style="display: none"><label for="core">Core: <span class="required">*</span></label><input type="text" class="form-control" name="data[core][]" id="core-'+loopN+'" value="'+output.response.data[0].items[x].core+'" required></div><div class="form-group gulungan-'+loopN+'" style="display: none"><label for="gulungan">Gulungan: <span class="required">*</span></label><input type="text" class="form-control" name="data[roll][]" id="gulungan-'+loopN+'" value="'+output.response.data[0].items[x].roll+'" required></div><div class="form-group bahan-'+loopN+'" style="display: none"><label for="bahan">Bahan: <span class="required">*</span></label><input type="text" class="form-control" name="data[material][]" id="bahan-'+loopN+'" value="'+output.response.data[0].items[x].material+'" required></div><div class="form-group price_1"><label for="price_1">Price: <span class="required">*</span></label><input type="text" class="form-control price_1_looping-'+loopN+'" name="data[price_1][]" id="price_1-'+loopN+'" value="'+output.response.data[0].items[x].price_1+'" required></div><div class="form-group price_2-'+loopN+'" style="display: none"><label for="price_2">Price (Secondary): <span class="required">*</span> <em class="label label-success">Size x Price = Price (secondary)</em></label><div class="row"><div class="col-md-10"><input type="text" class="form-control price_2_looping-'+loopN+'" name="data[price_2][]" id="price_2-'+loopN+'" value="'+output.response.data[0].items[x].price_2+'" required></div><div class="col-md-2"><button type="button" class="btn btn-primary hitung_'+loopN+'">Hitung</button></div></div></div><div class="form-group qty"><label for="qty">Qty: <span class="required">*</span></label><input type="number" min="0" class="form-control" name="data[qty][]" id="qty-'+loopN+'" value="'+output.response.data[0].items[x].qty+'" required></div><div class="form-group unit-'+loopN+'" style="display: none"><label for="unit">Unit: <span class="required">*</span></label><input type="text" class="form-control" name="data[unit][]" id="unit-'+loopN+'" value="'+output.response.data[0].items[x].unit+'" required></div><script>$(document).ready(function(){ $(".price_1_looping-'+loopN+'").mask("0.000.000.000.000,00", {reverse: true});$(".price_2_looping-'+loopN+'").mask("0.000.000.000.000,00", {reverse: true});$(document).on("click", ".hitung_'+loopN+'", function(e){ var ukuran_'+loopN+' = $(".sizeval_'+loopN+'").val();var harga_'+loopN+' = $(".price_1_looping-'+loopN+'").val().replace(/\\./g,"");$(".price_2_looping-'+loopN+'").val(parseInt(ukuran_'+loopN+' * harga_'+loopN+'.replace(/\\,/g,".")));});});</script></div>'
						);

						split_input = output.response.data[0].items[x].inputattr.split(",");
						for(var i = 0; i < split_input.length; i++)
	  					{
	  						$('.'+split_input[i]+'-'+loopN).show();
	  					}

						$('#unit-'+loopN).attr('readonly', true);
						$('#detail-'+loopN).attr('readonly', true);
						$('#size-'+loopN).attr('readonly', true);
						$('#merk-'+loopN).attr('readonly', true);
						$('#type-'+loopN).attr('readonly', true);
						$('#core-'+loopN).attr('readonly', true);
						$('#gulungan-'+loopN).attr('readonly', true);
						$('#bahan-'+loopN).attr('readonly', true);
						$('#price_1-'+loopN).attr('readonly', true);
						$('#price_2-'+loopN).attr('readonly', true);
						$('#qty-'+loopN).attr('readonly', true);
						$('#unit-'+loopN).attr('readonly', true);
						$(".hitung_"+loopN).hide();
					}
		        }

	        	show_lightbox();
	        	hide_loading_message();
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
  	// Print view submit
  	//////////////////////////

  	$(document).on('submit', '.printProses', function(e){
    	e.preventDefault();
	    if ($('.printProses').valid() == true){
	      	hide_ipad_keyboard();
	      	hide_lightbox();
	      	show_loading_message();
	      	var id 			= $('.printProses').attr('data-id');
	      	var form_data 	= $('.printProses').serialize();
			
			$.ajax({
				url:	pathFile+"/purchase-order/printnow/"+id,
				type:	'GET',
				beforeSend: function (xhr) {
					xhr.setRequestHeader('Authorization', getCookie('access_token'));
					xhr.setRequestHeader('Content-Type', 'application/json');
				},
				success: function(output){
	        		if(output.status == "success"){
	        			hide_loading_message();
	        			var split_print = output.response.data[0].printattr.split(',');
        				var thead = '';
	        			$('.thead').append('<th class="text-center">NO</th>');
	        			for(var i = 0; i < split_print.length; i++)
	        			{
	        				if(split_print[i] === 'price_1'){
	        					thead = 'PRICE';
	        				} else if(split_print[i] === 'price_2') {
	        					thead = 'PRICE/ROLL';
	        				} else {
	        					thead = split_print[i].toUpperCase();
	        				}

	        				$('.thead').append(
	        					'<th class="text-center">'+thead+'</th>'
	        				);
	        			}
	        			$('.thead').append('<th class="text-center">TOTAL</th>');

	        			var itemnya = output.response.data[0].items;
	        			for(var x = 0; x < itemnya.length; x++)
	        			{
	        				$('.tbody').append('<tr class="tbody-value-'+x+'"></tr>');
	        				$('.tbody-value-'+x).append('<td class="text-center">'+parseInt( x + 1)+'</td>');

							for(var z = 0; z < split_print.length; z++) 
							{
								if(split_print[z] === 'detail' || split_print[z] === 'merk')
	        					{
	        						$('.tbody-value-'+x).append('<td class="text-left">'+itemnya[x][split_print[z]] +'</td>');
	        					} else if(split_print[z] === 'price_1'){
	        						$('.tbody-value-'+x).append('<td class="text-center">'+convertToRupiah(itemnya[x][split_print[z]])+'</td>');
	        					} else if(split_print[z] === 'price_2'){
	        						$('.tbody-value-'+x).append('<td class="text-center">'+convertToRupiah(itemnya[x][split_print[z]])+'</td>');
	        					} else {
	        						$('.tbody-value-'+x).append('<td class="text-center">'+ itemnya[x][split_print[z]] +'</td>');
	        					}
	        				}

	        				$('.tbody-value-'+x).append('<td class="text-right">'+convertToRupiah(itemnya[x].subtotal)+'</td>');	        				
	        			}

	        			$('.tfoot-heading').append('<th colspan="'+parseInt(split_print.length + 2)+'">ADDITIONAL NOTES</th>');
	        			$('.tfoot-value1').append('<th class="notes" colspan="'+parseInt(split_print.length)+'" rowspan="3"></th><th>SUBTOTAL</th><th class="subtotal text-right"></th>');
	        			$('.tfoot-value2').append('<th>TAX</th><th class="pajak text-right"></th>');
	        			$('.tfoot-value3').append('<th>TOTAL</th><th class="jumlah text-right"></th>');
	        			$('#PrintModal').show();
	        			$('.tgl_po').text('DATE : '+output.response.data[0].po_date);
	        			$('.penjual').text('VENDOR NAME : '+output.response.data[0].vendor);
	        			$('.nomor').text('NO PO : '+output.response.data[0].po_number);
	        			$('.alamat').text('ADDRESS : '+output.response.data[0].vendor_address);
	        			$('.notes').text(output.response.data[0].note);
	        			$('.ttd_tgl').text('Depok, '+output.response.data[0].print_date);
	        			$('.ttd_person').text('( '+output.response.data[0].ttd+' )');
	        			$('.subtotal').text(convertToRupiah(output.response.data[0].subtotal));
	        			$('.pajak').text(convertToRupiah(output.response.data[0].taxtotal));
	        			$('.jumlah').text(convertToRupiah(output.response.data[0].total));
	        			$('.company_surat strong').text(output.response.data[0].companyname);
	        			$('.alamat_surat').text(output.response.data[0].companyaddress);
	        			$('.telp_surat').text('Telp : '+output.response.data[0].companyphone+', Email '+output.response.data[0].companyemail);
		        		if(!!output.response.data[0].companylogo.length){
	        				$('.logo_surat').append('<img src="'+output.response.data[0].companylogo+'" height="75px" width="150px" class="center-block">');
	        			}

		        		$('.printnow').print({
		                    stylesheet : "../lib/css/bootstrap/bootstrap.min.css",
		                    globalStyles : true,
		                    mediaPrint : false,
		                    iframe : true,
		                    append: null,
        					prepend: null,
		                    deferred: $.Deferred().done(function() { console.log('Print berhasil.', arguments); })
		                });

	        		} else {
	        			hide_loading_message();
						show_message('Failed: '+output.response.message, 'error');
	        		}
	        	},
		        error: function(jqXHR, textStatus, errorThrown){
					show_message('Failed: '+jqXHR.responseJSON.response.message, 'error');
		        }
			});
	    }
  	});

  	//////////////////////////////////////////////////////
  	////////// Pemeriksaan tipe purchase order
  	//////////////////////////////////////////////////////

  	$(document).on('change', '#po_type', function(e){
    	e.preventDefault();
    	reset();
        $('.tambah_barang').show();
    	var id = $(this).val();
    	if(id) {
	    	$.ajax({
				url: pathFile+"/purchase-order/suggest/attr?id="+id,
				type: "GET",
				beforeSend: function (xhr) {
					xhr.setRequestHeader('Authorization', getCookie('access_token'));
					xhr.setRequestHeader('Content-Type', 'application/json');
				},
				success: function(output){
					po_type_attribute = output.response.data.field;
					split_po_type_attribute = output.response.data.field.split(',');
					for(var x = 0; x < split_po_type_attribute.length; x++)
						$('.'+split_po_type_attribute[x]).show();
				}
	    	});
    	}
    });

	$(document).on('change', '#po_type_add_item', function(e){
    	e.preventDefault();
		$('#po_type_add_item').attr('id', 'po_type');
		reset();
		$('#form_inputPO').attr('id', 'form_AddItemPO');
		$('#po_type').attr('id', 'po_type_add_item');
		$('.po_number').show();
		$('.company').hide();
		$('#company').val('');
		$('.vendor').hide();
		$('#vendor').val('');
		$('.po_date').hide();
		$('#po_date').val('');
		$('.note').hide();
		$('#note').val('');
		$('.ppns').hide();
		$('#tax').val('');
		$('#looping_barang').empty();
        $('.tambah_barang').show();
    	var id = $(this).val();
    	if(id) {
	    	$.ajax({
				url: pathFile+"/purchase-order/suggest/attr?id="+id,
				type: "GET",
				beforeSend: function (xhr) {
					xhr.setRequestHeader('Authorization', getCookie('access_token'));
					xhr.setRequestHeader('Content-Type', 'application/json');
				},
				success: function(output){
					po_type_attribute = output.response.data.field;
					split_po_type_attribute = output.response.data.field.split(',');
					for(var x = 0; x < split_po_type_attribute.length; x++)
						$('.'+split_po_type_attribute[x]).show();
				}
	    	});
    	}
    });

});