<?php require 'session.php'; ?>
  <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <p>Developed by <strong><a href="https://hadrian.my.id" target="_blank">Hadrian.MY.ID</a></strong></p>
    </div>
    <p>Copyright &copy; <script type='text/javascript'>var creditsyear=new Date();document.write(creditsyear.getFullYear());</script> <strong>CV. Wisnu Cahaya Label</strong>. All rights reserved.</p>
  </footer>
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->
<script src="../lib/js/bootstrap/bootstrap.min.js"></script>
<script src="../lib/js/theme/adminlte.min.js"></script>
<script src="../lib/js/theme/demo.js"></script>
<?php if(!empty($_GET["page"]) AND htmlspecialchars($_GET["page"]) == "preorder"){?>
<!-- DataTables properties -->
<script type="text/javascript" src="../plugins/DataTables/preorder.js"></script>
<link href="../plugins/DataTables/buttons.dataTables.min.css" rel="stylesheet" type="text/css">
<link href="../plugins/jQueryUI/jquery-ui.css" rel="stylesheet" type="text/css">
<script src="../plugins/jQueryUI/jquery-ui.js" type="text/javascript" ></script>
<style>
.ui-autocomplete {
    z-index:9999 !important;
    max-height: 150px;
    overflow-y: auto;
    overflow-x: hidden;
}
.ui-autocomplete-loading {
	background: white url("../files/img/mini-loading.gif") right center no-repeat;
}
.ui-autocomplete-category {
    font-weight: bold;
    padding: .2em .4em;
    margin: .8em 0 .2em;
    line-height: 1.5;
  }
</style>

<!-- DataTables properties -->
<script>
	$(document).ready(function(){
		$('#price').mask('0.000.000.000.000,00', {reverse: true});
		$('#ongkos_kirim').mask('0.000.000.000.000', {reverse: true});

		$('#qty').keyup(function(){
		    $('#ppns').val('0');
		});

		$('#price').keyup(function(){
		    $('#ppns').val('0');
		});

		$('.tambah_barang').click(function(){
		    $('#ppns').val('0');
		});
	});
</script>
<?php } elseif(!empty($_GET["page"]) AND htmlspecialchars($_GET["page"]) == "user"){ ?>
<!-- DataTables properties -->
<script type="text/javascript" src="../plugins/DataTables/user.js"></script>
<!-- DataTables properties -->
<?php } elseif(!empty($_GET["page"]) AND htmlspecialchars($_GET["page"]) == "label"){ ?>
<!-- DataTables properties -->
<script type="text/javascript" src="../plugins/DataTables/label.js"></script>
<link href="../plugins/DataTables/buttons.dataTables.min.css" rel="stylesheet" type="text/css">
<!-- DataTables properties -->
<?php } elseif(!empty($_GET["page"]) AND htmlspecialchars($_GET["page"]) == "ribbon"){ ?>
<!-- DataTables properties -->
<script type="text/javascript" src="../plugins/DataTables/ribbon.js"></script>
<link href="../plugins/DataTables/buttons.dataTables.min.css" rel="stylesheet" type="text/css">
<!-- DataTables properties -->
<?php } elseif(!empty($_GET["page"]) AND htmlspecialchars($_GET["page"]) == "bahan_baku"){ ?>
<!-- DataTables properties -->
<script type="text/javascript" src="../plugins/DataTables/bahan_baku.js"></script>
<link href="../plugins/DataTables/buttons.dataTables.min.css" rel="stylesheet" type="text/css">
<!-- DataTables properties -->
<?php } elseif(!empty($_GET["page"]) AND htmlspecialchars($_GET["page"]) == "workorder"){ ?>
<!-- DataTables properties -->
<script type="text/javascript" src="../plugins/DataTables/workorder.js"></script>
<script type="text/javascript" src="../plugins/jQuery.print/jQuery.print.js"></script>
<link href="../plugins/DataTables/buttons.dataTables.min.css" rel="stylesheet" type="text/css">
<!-- DataTables properties -->
<?php } elseif(!empty($_GET["page"]) AND htmlspecialchars($_GET["page"]) == "invoice_waiting"){ ?>
<!-- DataTables properties -->
<script type="text/javascript" src="../plugins/DataTables/invoice_waiting.js"></script>
<script type="text/javascript" src="../plugins/jQuery.print/jQuery.print.js"></script>
<link href="../plugins/DataTables/buttons.dataTables.min.css" rel="stylesheet" type="text/css">
 <script type="text/javascript" src="../plugins/DataTables/dataTables.checkboxes.min.js"></script>
<!-- DataTables properties -->
<?php } elseif(!empty($_GET["page"]) AND htmlspecialchars($_GET["page"]) == "invoice_procces"){ ?>
<!-- DataTables properties -->
<script type="text/javascript" src="../plugins/DataTables/invoice_procces.js"></script>
<script type="text/javascript" src="../plugins/jQuery.print/jQuery.print.js"></script>
<link href="../plugins/DataTables/buttons.dataTables.min.css" rel="stylesheet" type="text/css">
<!-- DataTables properties -->
<style type="text/css">
.ttd_person {
	padding-top: 100px;
}
</style>
<?php } elseif(!empty($_GET["page"]) AND htmlspecialchars($_GET["page"]) == "invoice_duedate"){ ?>
<!-- DataTables properties -->
<script type="text/javascript" src="../plugins/DataTables/invoice_duedate.js"></script>
<script type="text/javascript" src="../plugins/jQuery.print/jQuery.print.js"></script>
<link href="../plugins/DataTables/buttons.dataTables.min.css" rel="stylesheet" type="text/css">
<!-- DataTables properties -->
<style type="text/css">
.ttd_person {
	padding-top: 100px;
}
</style>
<?php } elseif(!empty($_GET["page"]) AND htmlspecialchars($_GET["page"]) == "invoice_done"){ ?>
<!-- DataTables properties -->
<script type="text/javascript" src="../plugins/DataTables/invoice_done.js"></script>
<script type="text/javascript" src="../plugins/jQuery.print/jQuery.print.js"></script>
<link href="../plugins/DataTables/buttons.dataTables.min.css" rel="stylesheet" type="text/css">
<!-- DataTables properties -->
<style type="text/css">
.ttd_person {
	padding-top: 100px;
}
</style>
<?php } else if(!empty($_GET["page"]) AND htmlspecialchars($_GET["page"]) == "aging"){?>
<!-- DataTables properties -->
<script type="text/javascript" src="../plugins/DataTables/aging.js"></script>
<link href="../plugins/DataTables/buttons.dataTables.min.css" rel="stylesheet" type="text/css">
<!-- DataTables properties -->
<?php }  elseif(!empty($_GET["page"]) AND htmlspecialchars($_GET["page"]) == "cashflow"){ ?>
<!-- DataTables properties -->
<script type="text/javascript" src="../plugins/DataTables/cashflow.js"></script>
<link href="../plugins/DataTables/buttons.dataTables.min.css" rel="stylesheet" type="text/css">
<script>
	$(document).ready(function(){
		$('#uang').mask('0.000.000.000.000', {reverse: true});
		var RolexXx = <?php echo $_SESSION['role']; ?>;
		if(RolexXx == 5){
			$('.buttons-csv').hide();
			$('.buttons-excel').hide();
		}
	});
</script>
<!-- DataTables properties -->
<?php } elseif(!empty($_GET["page"]) AND htmlspecialchars($_GET["page"]) == "tol"){ ?>
<!-- DataTables properties -->
<script type="text/javascript" src="../plugins/DataTables/tol.js"></script>
<link href="../plugins/DataTables/buttons.dataTables.min.css" rel="stylesheet" type="text/css">
<script>
	$(document).ready(function(){
		$('#uang').mask('0.000.000.000.000', {reverse: true});
		var RolexXx = <?php echo $_SESSION['role']; ?>;
		if(RolexXx == 5){
			$('.buttons-csv').hide();
			$('.buttons-excel').hide();
		}
	});
</script>
<!-- DataTables properties -->
<?php } elseif(!empty($_GET["page"]) AND htmlspecialchars($_GET["page"]) == "profile"){ ?>
<!-- DataTables properties -->
<script type="text/javascript" src="../plugins/DataTables/profile.js"></script>
<!-- DataTables properties -->
<?php } elseif(!empty($_GET["page"]) AND htmlspecialchars($_GET["page"]) == "setting"){ ?>
<!-- DataTables properties -->
<script type="text/javascript" src="../plugins/DataTables/setting.js"></script>
<script type="text/javascript" src="../plugins/TagsAutocomplete/jquery.tagsinput-revisited.js"></script>
<script type="text/javascript" src="../plugins/jQueryUI/jquery-ui.js"></script>
<link href="../plugins/DataTables/buttons.dataTables.min.css" rel="stylesheet" type="text/css">
<link href="../plugins/TagsAutocomplete/jquery.tagsinput-revisited.css" rel="stylesheet" type="text/css">
<link href="../plugins/jQueryUI/jquery-ui.css" rel="stylesheet" type="text/css">
<!-- DataTables properties -->
<style>
.ui-autocomplete {
    z-index:9999 !important;
    max-height: 150px;
    overflow-y: auto;
    overflow-x: hidden;
}
</style>
<?php } elseif(!empty($_GET["page"]) AND htmlspecialchars($_GET["page"]) == "delivery_orders_waiting"){ ?>
<!-- DataTables properties -->
<script type="text/javascript" src="../plugins/DataTables/delivery_orders_waiting.js"></script>
<link href="../plugins/DataTables/buttons.dataTables.min.css" rel="stylesheet" type="text/css">
<!-- DataTables properties -->
<?php } elseif(!empty($_GET["page"]) AND htmlspecialchars($_GET["page"]) == "delivery_orders_delivery"){ ?>
<!-- DataTables properties -->
<script type="text/javascript" src="../plugins/DataTables/delivery_orders_delivery.js"></script>
<script type="text/javascript" src="../plugins/jQuery.print/jQuery.print.js"></script>
<link href="../plugins/DataTables/buttons.dataTables.min.css" rel="stylesheet" type="text/css">
<!-- DataTables properties -->
<style>
.margin-bottom-lg { margin-bottom: 1.5em; }
</style>
<?php } elseif(!empty($_GET["page"]) AND htmlspecialchars($_GET["page"]) == "dashboard"){ ?>
<!-- DataTables properties -->
<script type="text/javascript" src="../plugins/DataTables/dashboard.js"></script>
<link href="../plugins/DataTables/buttons.dataTables.min.css" rel="stylesheet" type="text/css">
<!-- DataTables properties -->
<?php } elseif(!empty($_GET["page"]) AND htmlspecialchars($_GET["page"]) == "company"){ ?>
<!-- DataTables properties -->
<script type="text/javascript" src="../plugins/DataTables/company.js"></script>
<link href="../plugins/DataTables/buttons.dataTables.min.css" rel="stylesheet" type="text/css">
<!-- DataTables properties -->
<style>
.margin-x { margin: 1.5em 0px; }
</style>
<script>
function readImage(input) {
	$('#ImageResult').empty();
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#ImageResult').append('<img src="'+e.target.result+'" class="img-thumbnail img_default">');
            $('#RemoveLogo').show();
        }
        reader.readAsDataURL(input.files[0]);
    } else {
    	$('#RemoveLogo').show();
    }
}
</script>
<?php } elseif(!empty($_GET["page"]) AND htmlspecialchars($_GET["page"]) == "vendor"){ ?>
<!-- DataTables properties -->
<script type="text/javascript" src="../plugins/DataTables/vendor.js"></script>
<link href="../plugins/DataTables/buttons.dataTables.min.css" rel="stylesheet" type="text/css">
<!-- DataTables properties -->
<?php } elseif(!empty($_GET["page"]) AND htmlspecialchars($_GET["page"]) == "customer"){ ?>
<!-- DataTables properties -->
<script type="text/javascript" src="../plugins/DataTables/customer.js"></script>
<link href="../plugins/DataTables/buttons.dataTables.min.css" rel="stylesheet" type="text/css">
<!-- DataTables properties -->
<?php } else if(!empty($_GET["page"]) AND htmlspecialchars($_GET["page"]) == "purchase"){ ?>
<!-- DataTables properties -->
<script type="text/javascript" src="../plugins/DataTables/purchase.js"></script>
<link href="../plugins/DataTables/buttons.dataTables.min.css" rel="stylesheet" type="text/css">
<link href="../plugins/jQueryUI/jquery-ui.css" rel="stylesheet" type="text/css">
<script src="../plugins/jQueryUI/jquery-ui.js" type="text/javascript" ></script>
<script type="text/javascript" src="../plugins/jQuery.print/jQuery.print.js"></script>
<style>
.ui-autocomplete {
    z-index:9999 !important;
    max-height: 150px;
    overflow-y: auto;
    overflow-x: hidden;
}
.ui-autocomplete-loading {
	background: white url("../files/img/mini-loading.gif") right center no-repeat;
}
.ui-autocomplete-category {
    font-weight: bold;
    padding: .2em .4em;
    margin: .8em 0 .2em;
    line-height: 1.5;
}

.ttd_person {
	padding-top: 75px;
}

.notes {
	word-wrap: break-word;
	max-width: 100px;
}
</style>

<!-- DataTables properties -->
<script>
	$(document).ready(function(){
		$('#price_1').mask('0.000.000.000.000,00', {reverse: true});
		$('#price_2').mask('0.000.000.000.000,00', {reverse: true});

		 $('#qty').keyup(function(){
		    $('#ppns').val('');
		});

		$('#price2').keyup(function(){
		    $('#ppns').val('');
		});

		$('.tambah_barang').click(function(){
		    $('#ppns').val('');
		});

		$(document).on('click', '.hitung_1', function(e){
			var ukuran_1 = $('.sizeval_1').val();
			var harga_1 = $('.price1val_1').val().replace(/\./g,'');
			$('.price2val_1').val(parseInt(ukuran_1 * harga_1.replace(/\,/g,'.')));
		});
	});
</script>
<?php } ?>
</body>
</html>