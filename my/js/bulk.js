window.addEventListener('load', function () {
	$(".bulk-check").change(function() {
	    if($(this).prop("checked")){
	        $(".r-check").prop("checked", 1);
	    }else{
	        $(".r-check").prop("checked", 0);
	    }
	});
	$(document).on('change','.bulk-check, .r-check',function() {
        if($('.bulk-check, .r-check').is(":checked")) {
            $('.bulk-row').css('opacity',1);
            $('.bulk-btn').removeClass('disabled');
        }else{
            $('.bulk-row').css('opacity',0.5);
            $('.bulk-btn').addClass('disabled');
        }
    });
    $('#bulk-action-bottom').change(function() {
	    var option = $(this).find("option:selected").val();
    	$("#bulk-action-top").selectpicker("val", option);
    	if($('.r-check').is(":checked")){
	    	$('.bulk-btn').removeClass('disabled');
	    	$('#log-table .bulk-row').css('opacity',1);
	    }
	});

	$('#bulk-action-top').change(function() {
	    var option = $(this).find("option:selected").val();
    	$("#bulk-action-bottom").selectpicker("val", option);
    	if($('.r-check').is(":checked")){
	    	$('.bulk-btn').removeClass('disabled');
	    	$('#log-table .bulk-row').css('opacity',1);
	    }
	});
	 $(".bulk-btn").click(function() {
	    var baction = $("#bulk-action-top").val();	    
	    if( baction=='deny' || baction=='allow' ){
	        if($(this).hasClass("disabled")){
	            return;
	        }
	        $('.bulk-btn').addClass("disabled");
	        var add_record = [];
	        $('.r-check:checked').each(function(){
	            add_record.push($(this).val());
	        });
	        if(add_record.length){
	            
	            $("#log-bulk-modal-error").addClass("hidden");
	            $("#log-bulk-modal-result").addClass("hidden");
	            $("#log-bulk-modal-loading").removeClass("hidden");
	            $("#log-bulk-modal-close").removeClass("hidden");

	            $('#log-bulk-modal').modal('show');
	            $.ajax({
	                method: "POST",
	                url: "/my/show_private?service_type="+service_type,
	                data: {add_record: add_record.join(','), service_id: "all", action: "add_record", add_status: baction, ajax: 1},
	                dataType: "json"
	            }).done(function(data) {
	                $("#log-bulk-modal-loading").addClass("hidden");
	                if(data){
	                    var msg = Object.values(data);
	                    $("#log-bulk-modal-result").html(msg.join('<br>') + "<br><br>Your Personal lists is <a href='/my/show_private?service_type="+service_type+"'>here</a>.").removeClass("hidden");
	                }else{
	                    $("#log-bulk-modal-error").removeClass("hidden");
	                }
	            }).error(function() {
	                $("#log-bulk-modal-loading").addClass("hidden");
	                $("#log-bulk-modal-error").removeClass("hidden");
	            });
	        }
	    }
	});
	$(document).on('change','.r-check',function() {
        if($(this).prop('checked')){
            $(this).parents('.log-row').addClass('selected');
        }else{
            $(this).parents('.log-row').removeClass('selected');
        }
    });
    $(document).on('change','.bulk-check',function() {
        if($(this).prop('checked')){
            $('.log-row').addClass('selected');
        }else{
            $('.log-row').removeClass('selected');
        }
    });
});