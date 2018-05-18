$(document).ready(function(){	
	var template = '<tr><td>:submited:</td><td>:service_name:</td><td>:total_core_files:</td><td><span class=":text-class:" :hint:>:result:</span></td><td><a href="#" data-id=":log_id:" class="more :display:">'+l_details+'</a></td></tr>';
	$(document).on('click','.more',function(e){
		e.preventDefault();
		$('#log-details-modal-info').addClass('hidden');
		$('#log-details-modal-info-un').addClass('hidden');
		$('#log-details-modal-loading').removeClass('hidden');
		$('#log-details-modal').modal();
		var id = $(this).data('id');
		$.ajax({
			url: '/my/logs_mscan',
			data: {action: 'detail',id: id},
			dataType: 'json'
		}).done(function(data) {
			$('#log-details-modal-info tbody').html('');
			$('#log-details-modal-info-un tbody').html('');
			if(data.error){
				$('#log-details-modal-info tbody').append('<tr><td class="text-warning text-center">'+data.error+'</td></tr>');
			}else {
				if(data.failed_files){				
					jQuery.each(data.failed_files, function(i, val) {		
						$('#log-details-modal-info tbody').append('<tr><td>'+i+'</td><td>'+val[1]+'</td><td>'+val[2]+'</td><td>'+val[0]+'</td></tr>');
					});
					
				}else{
					$('#log-details-modal-info tbody').append('<tr><td class="text-warning text-center" colspan="4">'+l_log_no_data+'</td></tr>');
				}
				if(data.unknown_files){				
					jQuery.each(data.unknown_files, function(i, val) {		
						$('#log-details-modal-info-un tbody').append('<tr><td>'+i+'</td><td>'+val[1]+'</td><td>'+val[2]+'</td><td>'+val[0]+'</td></tr>');
					});
					
				}else{
					$('#log-details-modal-info-un tbody').append('<tr><td class="text-warning text-center" colspan="4">'+l_log_no_data+'</td></tr>');
				}
			}
			$('#log-details-modal-loading').addClass('hidden');
			$('#log-details-modal-info').removeClass('hidden');
			$('#log-details-modal-info-un').removeClass('hidden');
		});
	});

	function load(url){
		$('#mscan-log-table tbody').html('<tr><td colspan="5" class="text-center"><img src="/images/loading.gif"></td></tr>');		
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
            if(url=='/my/logs_mscan')url+='?'
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
				$('#mscan-log-table tbody').html('');
				data.rows.forEach(function(row){
					$('#mscan-log-table tbody').append(template
						.replace(':submited:',row.submited)
						.replace(':service_name:',row.service_name)
						.replace(':total_core_files:',row.total_core_files)
						.replace(':result:',row.result)
						.replace(':log_id:',row.log_id)
						.replace(':text-class:',row.result=='PASSED' ? 'text-success' : 'text-warning')
						.replace(':display:',row.result=='PASSED' ? 'hidden' : '')
						.replace(':hint:',row.result!='PASSED' ? ' data-toggle="tooltip" data-placement="top" title="'+l_mscan_warning_hint+'"' : '')
					);
				});
				$('[data-toggle="tooltip"]').tooltip();
			}else{
				$('#mscan-log-table tbody').html('<tr><td colspan="5" class="text-center text-danger"><i class="glyphicon glyphicon-search"></i>&nbsp;&nbsp;'+l_log_no_data+'</td></tr>');				
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
		var url = '/my/logs_mscan';
		var date = $('#customdates').val();
		var result = $('#result').val();
		var service = $('#service').val();

		if(date)    filter.push('customdates='+date);
		if(result)  filter.push('result='+result);
		if(service) filter.push('service='+service);

		if(filter.length) url = '?'+filter.join('&');
		load(url);
	});
});