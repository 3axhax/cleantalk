$(document).ready(function(){

    if ($('input').is('#uda_banner')) 
        $('#banner_modal').modal('show');

    $(document).tooltip({
    	selector: '.tt'
	});

	$(document).on('click', ".auth_key_link", function(){
    	var arr3 = (this.id).split('_');
    	var key = arr3[1];
    	if ($('#auth_key_' + key).html() == $('#auth_key_h_' + key).val()) 
    		$('#auth_key_' + key).html('************');
        else
        	$('#auth_key_' + key).html($('#auth_key_h_' + key).val());
        
    }); 

    $(document).on('click', "#more_trial_notice", function(){
        $('#trial_notice_text').toggle();
    });

    $('#reviewalert').on('closed.bs.alert', function () {
        var date = new Date(new Date().getTime() + 30 * 24 * 60 * 60 * 1000);
        document.cookie = "review_hint=0; path=/; expires=" + date.toUTCString();
    })

    $(document).on('click', "#erase_search_btn", function(){
        window.location.href = '/my';
    });

    $(document).on('change', "#change_num_pages", function(){
        var date = new Date(new Date().getTime() + 7 * 24 * 60 * 60 * 1000);
        document.cookie = "num_per_page=" + $('#change_num_pages').val() + "; path=/; expires=" + date.toUTCString();
        window.location.href = '/my';
    });
});
