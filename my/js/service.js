$(document).ready(function(){ 

    $(document).on('click', ".auth_key_link", function(){
    	var arr3 = (this.id).split('_');
    	var key = arr3[1];
    	if ($('#auth_key_' + key).html() == $('#auth_key_h_' + key).val()) 
    		$('#auth_key_' + key).html('****************');
        else
        	$('#auth_key_' + key).html($('#auth_key_h_' + key).val());
        
    }); 

    $('#service_change').change(function() {
        var new_href = '';
        
        if ($('#service_change').val() != '') {
            new_href = new_href + (new_href == '' ? '?' : '&');
            new_href = new_href + 'action=edit&service_id=' + $('#service_change').val();
        }

        window.location.replace(new_href);
        return true;

    });

    $(document).on('click', '#stop_list_enable', function(){

    	var addon_active = false;

    	if (paid_addons.words_stop_list.enabled) {
        	addon_active = true;
    	};
    	if (!paid_addons.words_stop_list.enabled) {
        	$('#stop_list_notice').show();
        	$('#stop_list_notice').html(paid_addons.words_stop_list.notice)
        	// Allow use the addon on trial.
        	if (!paid_addons.words_stop_list.trial) {
            	setTimeout(function(){ $(this).prop('checked', false) ;}, 50);
        	}
    	}
    	return addon_active;

    });

    $(document).on('click', '#server_response_enable', function(){

    	var addon_active = false;

    	if (paid_addons.server_response_addon.enabled) {
        	addon_active = true;
    	};
    	if (!paid_addons.server_response_addon.enabled) {
        	$('#server_response_addon_notice').show();
        	$('#server_response_addon_notice').html(paid_addons.server_response_addon.notice)
        	// Allow use the addon on trial.
        	if (!paid_addons.server_response_addon.trial) {
            	setTimeout(function(){ $(this).prop('checked', false) ;}, 50);
        	}
    	}
    	return addon_active;

    });
});
