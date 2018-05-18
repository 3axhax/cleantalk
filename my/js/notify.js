$(document).ready(function(){
	$('#license-modal .modal-title').text('License has been expired');
	var template = '<tr class="log-row :row_status:"><td><input type="checkbox" class="r-check" value=":notification_id:"></td><td>:service_name:</td><td>:event:</td><td>:type:</td><td>:last_sent:</td><td>:notifications_sent:</td><td>:bell:<a href="#" title=":l_delete:" class="a-btn delete"><i class="fa fa-trash"></i></a></td></tr>';
	$('#notify-table').on('click','.delete',function() {
		$('.bulk-row .selectpicker').val('delete');
		$('.r-check').prop('checked',false);
		$(this).parents('tr').find('.r-check').prop('checked',true);
		$('.bulk-btn').removeClass('disabled').trigger('click');
		return false;
	});
	$('#notify-table').on('click','.enable',function() {
		$('.bulk-row .selectpicker').val('enable');
		$('.r-check').prop('checked',false);
		$(this).parents('tr').find('.r-check').prop('checked',true);
		$('.bulk-btn').removeClass('disabled').trigger('click');
		return false;
	});
	$('#notify-table').on('click','.disable',function() {
		$('.bulk-row .selectpicker').val('disable');
		$('.r-check').prop('checked',false);
		$(this).parents('tr').find('.r-check').prop('checked',true);
		$('.bulk-btn').removeClass('disabled').trigger('click');
		return false;
	});
	$('.bulk-btn').click(function(){
		if($('.bulk-btn').hasClass('disabled')){
			return false;
		}
		var action = $('#bulk-action-top').val();
		var ids = $('.r-check:checked').map(function() {
		    return $( this ).val();
		}).get().join();
		$('.bulk-btn').addClass('disabled');
		$('.bulk-row .selectpicker').val('').selectpicker('refresh');
		$('.select-all').prop('checked',false);
		$.ajax({
			url: '/my/notify?ajax='+action,
			data: {ids: ids},
			dataType: 'json',
			method: 'POST'
		}).done(function(data) {
			$('#filter-form').trigger('submit');
		});
	});
	$('#new-form').submit(function(){
		if($('#new-form input[type=submit]').hasClass('disabled')){
			return false;
		}
		$('#success-msg').addClass('hidden');
		$('#new-form input[type=submit]').addClass('disabled');
		$('#modal-loading').removeClass('hidden');
		$('#error-msg').addClass('hidden');
		var service = $('#new-service').val();
		var event   = $('#new-event').val();
		var method  = $('#new-method').val();
		var url     = $('#new-url').val();
		$.ajax({
			url: '/my/notify?ajax=new',
			data: {service: service, event: event, method: method, url: url},
			dataType: 'json',
			method: 'POST'
		}).done(function(data) {
			$('#modal-loading').addClass('hidden');
			$('#new-form input[type=submit]').removeClass('disabled');
			if(data.records_added){
				$('#filter-form').trigger('submit');
				$('#success-msg').text(l_records_added.replace('%s',data.records_added)).removeClass('hidden');
			}
			if(data.records_exist){
				$('#error-msg').text(l_records_exist.replace('%s',data.records_exist)).removeClass('hidden');
			}if(data.error){
				$('#error-msg').text(data.error).removeClass('hidden');
			}
			if(!data.error && !data.records_exist && data.records_added){
				setTimeout(function(){
					$('#success-msg').addClass('hidden');
					$('#add-modal').modal('toggle');	
				}, 3000);
			}
		});
		
		return false;
	});
	$('#add-modal').on('hidden.bs.modal', function () {
	    $('#error-msg').addClass('hidden');
	    $('#success-msg').addClass('hidden');
	})
	$('#add-modal').on('change','#new-method',function() {
		if($(this).val()=='url'){
			$('.url-box').removeClass('hidden');
		}else{
			$('.url-box').addClass('hidden');
		}
	});
    $('[data-toggle="tooltip"]').tooltip();
    $('#notify-table').on('change','.select-all, .r-check',function() {
        if($('.select-all, .r-check').is(":checked")) {
            $('.bulk-row').removeClass('block-muted');
            $('.bulk-row .selectpicker').prop('disabled',false).selectpicker('refresh');
        }else{
            $('.bulk-row').addClass('block-muted');
            $('.bulk-row .selectpicker').prop('disabled',true).selectpicker('refresh');
        }
    });
    $('#notify-table').on('change','.r-check',function() {
        if($(this).prop('checked')){
            $(this).parents('.log-row').addClass('selected');
        }else{
            $(this).parents('.log-row').removeClass('selected');
        }
    });
    $('.bulk-row').on('change','.select-all',function() {
        if($(this).prop('checked')){
            $('.log-row').addClass('selected');
            $('.r-check').prop('checked',true);
            $('.select-all').prop('checked',true);
            $('.bulk-row').removeClass('block-muted');
            $('.bulk-row .selectpicker').prop('disabled',false).selectpicker('refresh');
        }else{
            $('.log-row').removeClass('selected');
            $('.r-check').prop('checked',false);
            $('.select-all').prop('checked',false);
            $('.bulk-row').addClass('block-muted');
            $('.bulk-row .selectpicker').prop('disabled',true).selectpicker('refresh');
        }
    });
    $('.bulk-row').on('change','select',function() {
    	$('.bulk-row select').val($(this).val()).selectpicker('refresh');
    	$('.bulk-btn').removeClass('disabled');
    });
    $(document).on('click','#new-record',function(e){
		$('#add-modal').modal();
	});
	function load(url){
		$('#notify-table tbody').html('<tr><td colspan="7" class="text-center"><img src="/images/loading.gif"></td></tr>');		
		history.pushState(false,false,url);
		$.ajax({
			url: url,
			data: {ajax:''},
			dataType: 'json'
		}).done(function(data) {
			$('#current-page').text(data.page);
            $('#total-pages').text(data.total_pages);
            $('.pagination').html('');
            $("#records-found").text(data.records_count);
            url = data.url;
            if(url=='/my/notify')url+='?'
            if(data.pages && data.pages.length>1){
                page = data.page;                
                if(page=='1'){
                    $('.pagination').append('<li class="disabled"><span aria-hidden="true">«</span></li>');
                }else if(page=='2'){
                    $('.pagination').append('<li><a href="'+url+'"><span aria-hidden="true">«</span></a></li>');
                }else{
                    $('.pagination').append('<li><a href="'+url+'&page='+(parseInt(page)-1)+'"><span aria-hidden="true">«</span></a></li>');
                }
                var last = 0;
                data.pages.forEach(function(e) {
                    if(page==e){
                        $('.pagination').append('<li class="active"><span>'+e+'</span></li>');
                    }else{
                        if(e==1){
                            $('.pagination').append('<li><a href="'+url+'">'+e+'</a></li>');
                        }else{
                            $('.pagination').append('<li><a href="'+url+'&page='+e+'">'+e+'</a></li>');
                        }
                    }
                    last = e;
                });
                if(page==data.total_pages){
                    $('.pagination').append('<li class="disabled"><span aria-hidden="true">»</span></li>');
                }else{
                    $('.pagination').append('<li><a href="'+url+'&page='+(parseInt(page)+1)+'"><span aria-hidden="true">»</span></a></li>');
                }
                
            }
			if(data.rows.length){
				$('#notify-table tbody').html('');
				data.rows.forEach(function(row){
					$('#notify-table tbody').append(template
						.replace(':row_status:',row.status=='DISABLED' ? 'text-muted' : '')
						.replace(':notification_id:',row.notification_id)
						.replace(':service_name:',row.service_name)
						.replace(':event:',row.allow=='1' ? l_allowed : l_denied)
						.replace(':type:',row.type=='EMAIL' ? l_email : ':type:')
						.replace(':type:',row.type=='URL' ? 'URL <span class="text-muted">'+row.notification_url+'</span>' : ':type:')
						.replace(':type:',row.type=='APN' ? l_apn : '')
						.replace(':last_sent:',row.last_sent)
						.replace(':notifications_sent:',row.notifications_sent)
						.replace(':l_delete:',l_delete)
						.replace(':bell:',row.status=='ACTIVE' ? 
							'<a href="#" title="'+l_disable+'" class="a-btn disable"><i class="fa fa-bell-slash-o" aria-hidden="true"></i></a> ' : 
							'<a href="#" title="'+l_enable+'" class="a-btn enable"><i class="fa fa-bell-o" aria-hidden="true"></i></a> ')
						
					);
				});
				$('[data-toggle="tooltip"]').tooltip();
			}else{
				$('#notify-table tbody').html('<tr><td colspan="7" class="text-center text-danger"><i class="glyphicon glyphicon-search"></i>&nbsp;&nbsp;'+l_log_no_data+'</td></tr>');				
			}
		});
	}

	$(document).on('click','.pagination a',function(e){
		e.preventDefault();		
		var url = $(this).attr('href');
		load(url);
	});
	
	$('#filter-form').submit(function(e){
		e.preventDefault();
		$('.pagination').html('');
		var filter = [];
		var url = '/my/notify';
		var method = $('#method').val();
		var event = $('#event').val();
		var service = $('#service').val();

		if(service) filter.push('service='+service);
		if(event)  filter.push('event='+event);
		if(method)    filter.push('method='+method);

		if(filter.length) url = '?'+filter.join('&');
		load(url);
	});
});