$(document).ready(function(){ 

	$(document).on('click', '#show_saccs', function() { 
		$('#tables_well').show();
		$('#saccs').show();
		$('#ppays').hide();
	});

	$(document).on('click', '#show_ppays', function() { 
		$('#tables_well').show();
		$('#saccs').hide();
		$('#ppays').show();
	});

	$(document).on('click', '.transfer-button', function() { 
		$('.transfer-button').removeClass('btn-primary');
		$(this).addClass('btn-primary');
		if ($(this).hasClass('ct-balance'))
			$('#transfer_type').val('ct');
		if ($(this).hasClass('ym-balance'))
			$('#transfer_type').val('ym');
	});

	$(document).on('click', '#make_transfer', function() { 
		if ($('#transfer_type').val() == '') {
			$('#transfer_hint').show();
			return false;
		}
		else{
			$('#transfer_form').submit();
			return true;
		}


	});

	$(document).on('click', '#new_account', function() { 
		window.open('/register' + '?new=1&show_partner=1&partner_id=' + $('#na_partner_id').val(), '_blank');
		window.focus();
		return true;
	}); 


});