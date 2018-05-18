$(document).ready(function(){ 

	// Select changes

    $(document).on('change', '.filter_sfw', function() {

        var new_href = '/my/show_sfw';
        
        if ($('#service_id').val() != '') {
            new_href = new_href + (new_href == '/my/show_sfw' ? '?' : '&');
            new_href = new_href + 'service_id=' + $('#service_id').val();
        }

        
        if ($('#int').val() != '') {
            new_href = new_href + (new_href == '/my/show_sfw' ? '?' : '&');
            new_href = new_href + 'int=' + $('#int').val();
        }  

        if ($('#country').val() != '') {
            new_href = new_href + (new_href == '/my/show_sfw' ? '?' : '&');
            new_href = new_href + 'country=' + $('#country').val();
        }

        window.location.replace(new_href);
        return true;

    });

	// Search IP

    $(document).on('click', '#search_ip_btn', function() {

        var new_href = '/my/show_sfw';

        if ($('#service_id').val() != '') {
            new_href = new_href + (new_href == '/my/show_sfw' ? '?' : '&');
            new_href = new_href + 'service_id=' + $('#service_id').val();
        }

        
        if ($('#int').val() != '') {
            new_href = new_href + (new_href == '/my/show_sfw' ? '?' : '&');
            new_href = new_href + 'int=' + $('#int').val();
        }  

        if ($('#country').val() != '') {
            new_href = new_href + (new_href == '/my/show_sfw' ? '?' : '&');
            new_href = new_href + 'country=' + $('#country').val();
        }

        if ($('#search_ip').value != '') {

        	new_href = new_href + (new_href == '/my/show_sfw' ? '?' : '&');
            new_href = new_href + 'ip=' + $('#search_ip').val();
    
            window.location.replace(new_href);

        }   
    });

    $(document).on('keypress', '#search_ip', function(e) {

        if (e.keyCode == 13) {

            var new_href = '/my/show_sfw';

            if ($('#service_id').val() != '') {
                new_href = new_href + (new_href == '/my/show_sfw' ? '?' : '&');
                new_href = new_href + 'service_id=' + $('#service_id').val();
            }
        
            if ($('#int').val() != '') {
                new_href = new_href + (new_href == '/my/show_sfw' ? '?' : '&');
                new_href = new_href + 'int=' + $('#int').val();
            }  

            if ($('#country').val() != '') {
                new_href = new_href + (new_href == '/my/show_sfw' ? '?' : '&');
                new_href = new_href + 'country=' + $('#country').val();
            }

            if ($('#search_ip').value != '') {

                new_href = new_href + (new_href == '/my/show_sfw' ? '?' : '&');
                new_href = new_href + 'ip=' + $('#search_ip').val();
            }

            window.location.replace(new_href);
        }   
    });


});