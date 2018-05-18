window.addEventListener('load', function () {
	$('#hostname, #hostnames').on('input change', function(){
        if(!$('#error-box').hasClass('hidden')){
            $('#error-box').addClass('hidden');
        }
        if($(this).val()!=''){
            $('#add_new, #add_new_multiple').removeClass('disabled');
        }else{
            $('#add_new, #add_new_multiple').addClass('disabled');
        }
    });
    $('.sform').submit(function(e){
        e.preventDefault();
        if($('#add_new').hasClass('disabled'))
            return false;
        $('#add_new').addClass('disabled');
        $('#preloader-api').removeClass('hidden');
        var hostname = $('#hostname').val();
        $.ajax({
        	url: 'https://api.cleantalk.org/?method_name=get_api_key',
            data: {
                product_name: product_name,
                website: hostname,
                email: email,
                user_token: user_token
            },
            dataType: 'json',
            method: "POST"
        }).done(function(data) {
            $('#preloader-api').addClass('hidden');
            if(data.error_message){
                $('#error-box').text(data.error_message).removeClass('hidden');
            }
            if(data.data.auth_key && !data.data.service_id){
                $('#error-box').text(l_already_exists.replace('%s',hostname)).removeClass('hidden');   
            }
            if(data.data.service_id){
                $('#add-box').addClass('hidden');
                document.title = l_service_created_title.replace(':SITENAME:', hostname);
                $('#success-message').html(document.title + ' ' + l_service_created_js
                    .replace(':SERVICE_ID:', data.data['service_id'])
                    .replace(':KEY:', data.data['auth_key'])
                );
                $('#success-box').removeClass('hidden');
                $('.cms').removeClass('hidden');
                $.ajax({url: '/my/?update_services=1'});
            }
        }).fail(function(data) {
            $('#preloader-api').addClass('hidden');
            if(data.responseText){
                var response = $.parseJSON(data.responseText);
                response.error_message=localMeggage(response.error_message);
                $('#error-box').text(response.error_message).removeClass('hidden');
            }else{
                $('#error-box').text('Internal server error').removeClass('hidden');
                $.ajax({
                    url: '/my/ajax?action=log_api',
                    method: "POST",
                    dataType: "json",
                    data: ({api_rq: JSON.stringify({
                        product_name: product_name,
                        website: hostname,
                        email: email,
                        user_token: user_token
                    }),api_rs: JSON.stringify(data)})
                });
            }
            
        });
        
        return false;
    });
    $('.mform').submit(function(e){
        e.preventDefault();
        if($('#add_new_multiple').hasClass('disabled'))
            return false;
        $('#add_new_multiple').addClass('disabled');
        $('#add-box').addClass('hidden');
        $('#multiple-box').removeClass('hidden');
        var hostnames = [];
        
        if($('#hostnames').val()){
            hostnames = $('#hostnames').val().split(/,|;|\s|\n/);
            hostnames = hostnames.filter(i=>i.length);
        }
        hostnames.forEach(function(hostname) {
            $.ajax({
                url: 'https://api.cleantalk.org/?method_name=get_api_key',
                data: {
                    product_name: product_name,
                    website: hostname,
                    email: email,
                    user_token: user_token
                },
                dataType: 'json',
                method: "POST"
            }).done(function(data) {
                if(data.error_message){
                    $('#error-box').text(data.error_message).removeClass('hidden');
                }
                if(data.data.auth_key && !data.data.service_id){
                    var row = row_error.replace(':MESSAGE:',l_already_exists.replace('%s',hostname));
                    $('#multiple-box tbody').append(row);
                }
                if(data.data.service_id){
                    var row = row_tpl
                        .replaceAll(':SERVICE_ID:', data.data['service_id'])
                        .replaceAll(':HOSTNAME:', hostname)
                        .replaceAll(':AUTH_KEY:', data.data['auth_key']);
                    $('#multiple-box tbody').append(row);
                    $.ajax({url: '/my/?update_services=1'});
                }
            }).fail(function(data) {
                if(data.responseText){
                    var response = $.parseJSON(data.responseText);
                    response.error_message=localMeggage(response.error_message,hostname);
                    var row = row_error.replace(':MESSAGE:',response.error_message);
                    $('#multiple-box tbody').append(row);
                }else{
                    var row = row_error.replace(':MESSAGE:','Internal server error');
                    $('#multiple-box tbody').append(row);
                    $.ajax({
                        url: '/my/ajax?action=log_api',
                        method: "POST",
                        dataType: "json",
                        data: ({api_rq: JSON.stringify({
                            product_name: product_name,
                            website: hostname,
                            email: email,
                            user_token: user_token
                        }),api_rs: JSON.stringify(data)})
                    });
                }
                
            });
        });
        return false;
    });
    $('.order a').click(function(){
        if(!$(this).hasClass('disabled')){
            $(".cms_list a").sort(function (a, b){
                var direction = $('.order a').not('.disabled').data('order');
                if(direction=='name'){
                    return ($(b).data(direction).toLowerCase()) < ($(a).data(direction).toLowerCase()) ? 1 : -1;
                }else{
                    return (parseInt($(a).data(direction))) < (parseInt($(b).data(direction))) ? 1 : -1;
                }
            }).appendTo('.cms_list');
            $('.order a').removeClass('disabled');
            $(this).addClass('disabled');
        }
        return false;
    });
    function localMeggage(str, hostname){
        if(lang=='ru'){
            if(str=='No free websites, please upgrade package.'){
                str='Для добавления нового сайта переключитесь на более производительный тарифный план!';
            }
            if(str=='Website is unset.'){
                str= hostname + ' имя не корректно.';
            }            
        }else{
            if(str=='Website is unset.'){
                str= hostname + '  is incorrect URL.';
            }
        }
        return str;
    }
});