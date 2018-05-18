$(document).ready(function(){

	if ($('#wrong_message').val() != ''){
		$('#mistake').html($('#wrong_message').val());
    	$('#mistake').show();
	}

    if ($('#success_message').val() != ''){
        $('#success').html($('#success_message').val());
        $('#success').show();
    }


	$(document).on('click', '#new_grant_btn', function(){
    	
    	if ($('#service_id').val() == 0 || $('#account').val() =='') { 
    		$('#mistake').html($('#fill_message').val());
    		$('#mistake').show();
    		setTimeout(function(){ $('#mistake').hide();}, 4000);
    		return false;
    	}
        else 
        	$('#new_grant_frm').submit();

    }); 

    $(document).on('click', '.write_off', function(){
        grid = this.id;
        $.ajax({ 
            url: '/my/grants?action=writeoff',
            type: "POST",
            dataType: "json",
            data: ({ grant_id : grid }),
            success: function(data) {
                if (data[0] == 1) {
                    $('#read_write_' + grid).html(data[2]);
                }
            }

        }); 

    });  
    $(document).on('click', '#grants_enable', function(){

    	var addon_active = false;

    	if (paid_addons.grants_addon.enabled || paid_addons.grants_addon.trial) {
        	addon_active = true;
    	} else {
        	$('#grants_addon_notice').show();
        	$('#grants_addon_notice').html(paid_addons.grants_addon.notice);
    	}
    	return addon_active;

    });

});
