$(document).ready(function(){
	$(document).on('change', '#tariff_id,#renew_options', function(e) {

		var url = '?';
    
    	if ($('select').is('#renew_options')) 
        	url = url + 'renew_options=' + $('#renew_options').val();

    	if ($('select').is('#tariff_id')) {
        	if (url != '?') 
            	url = url + '&';
        	url = url + 'tariff_id=' + $('#tariff_id').val();
    	}

    	if ($('select').is('#currency')) {
        	if (url != '?') 
            	url = url + '&';
        	url = url + 'currency=' + $('#currency').val();
    	}

        var extra_package_value = 0;

        if ($('#extra_package').is(':checked')) 
            extra_package_value = 1;

        if (url != '?') 
            url = url + '&';
        
        url = url + 'extra_package=' + extra_package_value;
  
    	if (url == '?') {
        	url = '';
    	}

    	window.location.replace(url);
        return true; 

	});

    $(document).on('change', '#currency', function(e) { 
        var date = new Date();
        date.setTime(date.getTime()+24*60*60);
        var expires = "; expires="+date.toGMTString();
        document.cookie = "currency="+ $('#currency').val() + expires+"; path=/";
        $.ajax({
            url: "?change_currency="+$('#currency').val(),
        }).done(function(data) {
            window.location.reload();
        });
    });

	$(document).on('click', '#extra_package,#promobutton', function(e) {

		var url = '?';
    
    	if ($('select').is('#renew_options'))  
        	url = url + 'renew_options=' + $('#renew_options').val();

    	if ($('select').is('#tariff_id')) {
        	if (url != '?') 
            	url = url + '&';
        	url = url + 'tariff_id=' + $('#tariff_id').val();
    	}

    	if ($('select').is('#currency')) {
        	if (url != '?') 
            	url = url + '&';
        	url = url + 'currency=' + $('#currency').val();
    	}

        if ($('input').is('#promokey')) {
            if (url != '?') 
                url = url + '&';
            url = url + 'promokey=' + $('#promokey').val();
        }

        var extra_package_value = 0;

        if ($('#extra_package').is(':checked')) 
            extra_package_value = 1;

        if (url != '?') 
            url = url + '&';
        
        url = url + 'extra_package=' + extra_package_value;
  
    	if (url == '?') {
        	url = '';
    	}

    	window.location.replace(url);
        return true; 

	});

    $(document).on('click', '#pp_btn', function(e) {
        e.preventDefault();
        $('#recharge-table').addClass('loading');
        $('#charge').submit();
    });

    $(document).on('click', '#paw_btn', function(e) {
       $('#charge_payanyway').submit(); 
    });

    $(document).on('click', '#show_recalc_hint', function(e) { 
        $('#current_tariff_info').toggle();
    });

});