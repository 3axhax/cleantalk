$(document).ready(function(){ 

	// Filters work

    $('.filter').click(function() {
        var new_href = '';
        if (this.id == '7' || this.id == '30' || this.id == '365') {
            new_href = new_href + (new_href == '' ? '?' : '&');
            new_href = new_href + 'int=' + this.id;    
        }
        else {
            if ($('#customdates').val() != '') {
                var datesarr = $('#customdates').val().split('-');
                new_href = new_href + (new_href == '' ? '?' : '&');
        
                start_from = new Date(Date.parse(datesarr[0])).getTime() / 1000; 
                new_href = new_href + 'start_from=' + start_from;
        
                end_to = new Date(Date.parse(datesarr[1])).getTime() / 1000; 
                new_href = new_href + '&end_to=' + end_to;   
            } 
        }   
        
        if ($('#stat_type').val() != 0) {
            new_href = new_href + (new_href == '' ? '?' : '&');
            new_href = new_href + 'stat_type=' + $('#stat_type').val();
        }
        

        if ($('#service_id').val() != 0) {
            new_href = new_href + (new_href == '' ? '?' : '&');
            new_href = new_href + 'service_id=' + $('#service_id').val();
        }

        window.location.replace(new_href);
        return true;

    });

    $('.stat_select').change(function() {
        var new_href = '';
        if ($('#customdates').val() != '') {
            var datesarr = $('#customdates').val().split('-');
            new_href = new_href + (new_href == '' ? '?' : '&');
        
            start_from = new Date(Date.parse(datesarr[0])).getTime() / 1000; 
            new_href = new_href + 'start_from=' + start_from;
        
            end_to = new Date(Date.parse(datesarr[1])).getTime() / 1000; 
            new_href = new_href + '&end_to=' + end_to;   
        }

        if ($('#stat_type').val() != 0) {
            new_href = new_href + (new_href == '' ? '?' : '&');
            new_href = new_href + 'stat_type=' + $('#stat_type').val();
        }
        

        if ($('#service_id').val() != 0) {
            new_href = new_href + (new_href == '' ? '?' : '&');
            new_href = new_href + 'service_id=' + $('#service_id').val();
        }

        window.location.replace(new_href);
        return true;

    });



});