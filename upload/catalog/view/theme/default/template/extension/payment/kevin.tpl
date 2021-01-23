<?php if ($kevin_instr) { ?> 
	<h2><?php echo $kevin_instr_title; ?></h2>
	<div class="well well-sm"><p><?php echo $kevin_instr; ?></p></div>
<?php } ?>
<div id="kevin-container">
<?php if ($text_sandbox_alert) { ?> 
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $text_sandbox_alert; ?> 
     <!-- <button type="button" class="close" data-dismiss="alert">&times;</button>-->
    </div>
<?php } ?>
<?php if ($error_currency) { ?> 
<div class="alert-currency"></div>  
<?php } ?>
<div class="alert-bank"></div> 
<?php if ($error_bank_missing) { ?>  	
<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_bank_missing; ?><button type="button" class="close" data-dismiss="alert">&times;</button></div>
<?php } ?>
<div class="bank-container">
	
<ul class="list-unstyled row li-grid"> 
<?php foreach ($banks as $bank) { ?>
	<li class="col-md-2 col-sm-4 col-xs-12 ">
    <p class="bank-grid">
         <input type="image" src="<?php echo $bank['imageUri']; ?>" id="bank-selected" class="bank-<?php echo $bank['id']; ?> bank-logo bank-grid-img" onclick="SelectBank('<?php echo $bank['id']; ?>');"/>
		<?php if ($bank_name_enable) { ?>
		<span class="bankgrid-title title-color-<?php echo $bank['id']; ?>"><?php echo $bank['name']; ?></span>
		<?php } ?>
	</p>
    </li>
<form id="kevin_form-<?php echo $bank['id']; ?>" action="<?php echo $action; ?>&bank=<?php echo $bank['id']; ?>" enctype="multipart/form-data" method="POST">
   <input  type="hidden" name="bank" value="<?php echo $bank['id']; ?>"/>
</form>
<?php } ?>
</ul>
</div>
</div>
<div class="buttons">
    <div class="pull-right">
        <input  type="button" value="<?php echo $button_confirm; ?>" id="button-confirm" class="btn btn-primary"  data-loading-text="<?php echo $text_loading; ?>" />
    </div>
</div>

<script type="text/javascript"><!--
	
	var windowWidth = $(window).width();
	function scrollUp() {
		if (windowWidth < 1024) {
			$('html, body').animate({
				scrollTop: $("#kevin-container").offset().top + (-50)
			}, 200);
		}
	}
		 
	
	var currency = '<?php echo isset($currency) ? $currency : 0; ?>';
	var error_currency = '<?php echo isset($error_currency) ? $error_currency : ''; ?>';
	var error_bank = '<?php echo isset($error_bank) ? $error_bank : ''; ?>';
	//$('#button-confirm, #quick-checkout-button-confirm').prop('disabled', true);
	function SelectBank(bankId) {
		event.preventDefault();
		$('.bankgrid-title').css({'color': ''});
		$('.bank-logo').css({'border': ''});
		$('.bank-' + bankId).css({'border': 'solid 2px #26a5d6'});
		$('.title-color-' + bankId).css({'color': '#26a5d6'});
		$('#button-confirm').attr('data-id', bankId);
		//$('#button-confirm, #quick-checkout-button-confirm').prop('disabled', false);
		$('.alert-bank').children().eq(0).remove();
		$('#quick-checkout-button-confirm').button('reset');
	}
	$('#button-confirm').on('click', function() {
		$('.alert-currency').children().eq(0).remove();
		$('.alert-bank').children().eq(0).remove();
		var bankId = $(this).attr('data-id');
		//alert(bankId);
		if (currency != 'EUR') {
			event.preventDefault();
			scrollUp();
			$('.alert-currency').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + error_currency + '<button type="button" class="close" data-dismiss="alert">&times;</button>');
		} else if (!bankId) {
			event.preventDefault();
			scrollUp();
			$('#quick-checkout-button-confirm').button('reset');
			$('.alert-bank').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + error_bank + '<button type="button" class="close" data-dismiss="alert">&times;</button>');
			
		} else {
			$('#kevin_form-'+bankId).submit();
		}
    });	
//--></script>

<style>	
.li-grid {
	display: flex;
	flex-wrap: wrap;
}

.bank-logo:hover {
    border: solid 2px #dddddd; 
    overflow: hidden;
    moz-box-shadow: 0px 0px 8px 2px rgba(119,119,119,0.2);
    -webkit-box-shadow: 0px 0px 8px 2px rgba(119,119,119,0.2);
    box-shadow: 0px 0px 8px 2px rgba(119,119,119,0.2);
    -webkit-transition: all .1s ease-in-out;
    -moz-transition: all .1s ease-in-out;
    -o-transition: all .1s ease-in-out;
    -ms-transition: all .1s ease-in-out;
    transition: all .1s ease-in-out;
    -webkit-transform: scale(1.02);
    transform: scale(1.02);
}

.bank-grid-img {
	display:block; 
	max-width: 100%;
    height: auto;
	margin-left:auto; 
	margin-right:auto;
}
	
.bankgrid-title {
	text-align: center;
	padding-top: 5px;
	font-size: 14px;
    margin-bottom: 15px;
    display: block;
}

input:focus{
    outline: none;
}

.bank-logo {
	display:block;
    margin:auto;
	max-height: 100px; 
	text-align: center; 
	border: solid 2px #dddddd; 
}
</style>
	
