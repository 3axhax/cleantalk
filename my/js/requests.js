$(document).ready(function(){

    $('#resultsy').val($('#requests_table').height());

    $(document).on("mouseover", ".authoripemail", function(){
        var arr1 = this.id.split('_');
        $('#menu_' + arr1[1]).show();
    });

    $(document).on("mouseout", ".authoripemail", function(){
        var arr2 = this.id.split('_');
        $('#menu_' + arr2[1]).hide();
    });

    $(document).on('click', ".show_details", function(){
    	var arr4 = (this.id).split('_');
    	var request_id = arr4[2];
        $('#details_' + request_id).toggle();

    });

    // Scroll requests load

    $(window).scroll(function(){
        $('#poss').val($(window).scrollTop());
        var count = parseInt($('#count').val());
        var resultsy = parseInt($('#resultsy').val());
        var recs_found = parseInt($('#recs_found').val());
        var requests_limit = parseInt($('#requests_limit').val());
        if (($(window).scrollTop() + 700) > (resultsy*count)) {
            newcount = count + 1;
            $('#count').val(newcount);
            if (count*requests_limit < recs_found) {
                $.ajax({
                        url: $('#ajax_url').val(),
                        type: "GET",
                        data: ({ page : newcount,
                        }),

                        beforeSend: function(){
                            $('#requests_results').after('<tr id="ajaxl' + newcount + '"><td colspan="3" class="center"><br><img src="images/loading.gif"></td></tr>');
                        },

                        success: function(response) {
                            $('#ajaxl' + newcount).hide();
                            $('#requests_results').append(response);
                        }

                });
            }
        }

    });

    // Filters work

    $('.filter').click(function() {
        var new_href = '';
        if (this.id == 'today' || this.id == 'yesterday' || this.id == 'week') {
            new_href = new_href + (new_href == '' ? '?' : '&');
            new_href = new_href + 'int=' + this.id;
            window.location.replace(new_href);
            return true;
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

        if ($('#statuses').val() != '') {
            new_href = new_href + (new_href == '' ? '?' : '&');
            new_href = new_href + 'allow=' + $('#statuses').val();
        }


        if ($('#services').val() != '') {
            new_href = new_href + (new_href == '' ? '?' : '&');
            new_href = new_href + 'service_id=' + $('#services').val();
        }


        if ($('#countries').val() != '') {
            new_href = new_href + (new_href == '' ? '?' : '&');
            new_href = new_href + 'country=' + $('#countries').val();
        }

        if ($('#ipemailnick').val() != '') {
            new_href = new_href + (new_href == '' ? '?' : '&');
            new_href = new_href + 'ipemailnick=' + $('#ipemailnick').val();
        }

        if ($(this).hasClass('seaemail') || $(this).hasClass('seaip')) {
            new_href = new_href + (new_href == '' ? '?' : '&');
            new_href = new_href + 'ipemailnick=' + $(this).text();
        }


        window.location.replace(new_href);
        return true;

    });

    // Search IP Email Nick button

    $(document).on('keypress', '#ipemailnick', function(e) {
        if (e.keyCode == 13) {
            var new_href = '';

            if ($('#customdates').val() != '') {
                var datesarr = $('#customdates').val().split('-');
                new_href = new_href + (new_href == '' ? '?' : '&');

                start_from = new Date(Date.parse(datesarr[0])).getTime() / 1000;
                new_href = new_href + 'start_from=' + start_from;

                end_to = new Date(Date.parse(datesarr[1])).getTime() / 1000;
                new_href = new_href + '&end_to=' + end_to;
            }


            if ($('#statuses').val() != '') {
                new_href = new_href + (new_href == '' ? '?' : '&');
                new_href = new_href + 'allow=' + $('#statuses').val();
            }


            if ($('#services').val() != '') {
                new_href = new_href + (new_href == '' ? '?' : '&');
                new_href = new_href + 'service_id=' + $('#services').val();
            }

            if ($('#countries').val() != '') {
                new_href = new_href + (new_href == '' ? '?' : '&');
                new_href = new_href + 'country=' + $('#countries').val();
            }

            if ($('#ipemailnick').val() != '') {
                new_href = new_href + (new_href == '' ? '?' : '&');
                new_href = new_href + 'ipemailnick=' + $('#ipemailnick').val();
            }

            window.location.replace(new_href);
            return true;
        }
    });

    // Select filters

    $('.filter_select').change(function() {
        var new_href = '';
        if ($('#customdates').val() != '') {
            var datesarr = $('#customdates').val().split('-');
            new_href = new_href + (new_href == '' ? '?' : '&');

            start_from = new Date(Date.parse(datesarr[0])).getTime() / 1000;
            new_href = new_href + 'start_from=' + start_from;

            end_to = new Date(Date.parse(datesarr[1])).getTime() / 1000;
            new_href = new_href + '&end_to=' + end_to;
        }

        if ($('#statuses').val() != '') {
            new_href = new_href + (new_href == '' ? '?' : '&');
            new_href = new_href + 'allow=' + $('#statuses').val();
        }


        if ($('#services').val() != '') {
            new_href = new_href + (new_href == '' ? '?' : '&');
            new_href = new_href + 'service_id=' + $('#services').val();
        }


        if ($('#countries').val() != '') {
            new_href = new_href + (new_href == '' ? '?' : '&');
            new_href = new_href + 'country=' + $('#countries').val();
        }

        if ($('#ipemailnick').val() != '') {
            new_href = new_href + (new_href == '' ? '?' : '&');
            new_href = new_href + 'ipemailnick=' + $('#ipemailnick').val();
        }

        window.location.replace(new_href);
        return true;

    });

    // Leave feedback

    $(document).on('click', '.feedback', function() {
        var arr5 = this.id.split('_');
        var rqid = arr5[1];
        var appr = arr5[2];
        $.ajax({
                url: "/my/ajax",
                type: "GET",
                data: ({ request_id : rqid,
                         approve : appr,
                         action : 'request_feedback'
                       }),

                beforeSend: function(){
                    $('#message_' + rqid).show();
                    $('#message_' + rqid).html('Loading....');
                },

                success: function(response) {
                    response = $.parseJSON(response);
                    $('#message_' + rqid).html(response['message']);
                    if (response['notification']) {
                        $('#app_notification_alert_' + rqid + ' > div').html(response['notification']);
                        $('#app_notification_alert_' + rqid).show();
                    }
                }

        });

    });

    // Show notice modal window

    $('#notice_modal').on('show.bs.modal', function (e) {
        var link = $(e.relatedTarget)
        var rqid = link.data('rq');
        $('#notice_rq_id').val(rqid);
    });

    // Save notice

    $('#save_notice').on('click', function() {

        if ($('#notice_text').val() != '') {
            $.ajax({
                    url: "/my/ajax",
                    type: "GET",
                    data: ({ request_id : $('#notice_rq_id').val(),
                             notice_text : $('#notice_text').val(),
                             action : 'save_notice'
                            }),

                    success: function(response) {
                        $('#notice_modal').modal('hide');
                        $('#thanks').modal('show');
                    }

            });

        }

    });

    // Delete request

    $(document).on('click', '.deletereq', function() {
        var arr6 = this.id.split('_');
        var rqid = arr6[1];
        if (confirm($('#delete_message').val())) {
            $.ajax({
                    url: "/my/ajax",
                    type: "GET",
                    data: ({ request_id : rqid,
                             action : 'delete_request'
                            }),

                    success: function(response) {
                        $('#tr_' + rqid).hide();
                    }

            });
        }
        else
            return false;

    });

    // Delete all approved records

    $('#delete_bulk').click(function() {

        if (confirm($('#delete_bulk_message').val())) {
            var new_url = location.pathname + '?delete_records=1&return_url=' + encodeURIComponent(window.location) + '&service_id=' + $('#services').val();
            window.location.replace(new_url);
        }
        return null;

    });



});



