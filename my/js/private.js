(function () {
    var records = [];
    var progress = 0;
    var part_size = 100;

    PrivateLists.prototype.add_records = function (start,count,add_status) {
        var self = this;
        $.ajax({
            url: '/my/show_private',
            type: "POST",
            data: ({
                ajax: 'progress',
                add_record_type: $('#add_record_type').val(),
                add_record: records.slice(start,start+count).join(','),
                note: $('input[name=note]').val(),
                service_id: $('#add_record_service_id').val(),
                action: 'add_record',
                add_status: add_status
            }),
            success: function(response) {
                part_count = Math.ceil(records.length/part_size);
                progress = parseInt(100 / part_count * (start/count+1));
                $('.progress-bar').css('width', progress+'%').attr('aria-valuenow', progress).text(progress+'%');
                if(start+1 < records.length){
                    self.add_records(start+count,count,add_status);
                }else{
                    location.reload();
                }
            }
        });
    }

    $('#progress_modal').on('hidden.bs.modal', function () {
        if($('#progress_modal').data('import')){
            $.ajax({
                url: '/my/show_private',
                type: "POST",
                data: ({action: 'upload_cancel',service_id: $('#csv-form select[name=service_id]').val()})
            });
        }else{
            location.reload();
        }
    })
    function PrivateLists(licenses) {
        this.licenses = licenses;
        this.init();
        this.typeUpdate();
        if ($('#information_message .alert').html() != '') {
            this.information($('#information_message .alert').html());
        }
    }

    PrivateLists.prototype.init = function () {
        var self = this;
        
        $('#csv-form').submit(function(e){
            if($('#csv-form button[type=submit]').hasClass('disabled')){
                return false;
            }        
            var form = $(this)[0];
            $('.progress-bar').css('width', '0%').attr('aria-valuenow', 0).text('0%');
            $('#progress_modal').data('import',1);
            $('#progress_modal').modal('show');
            self.import_csv(0,form);

            return false;
        });

        $(document).on('click', ".add_records", function() {
            if ($('#add_record').val() != '' && !$(this).attr('disabled')) {
                $('#add_status').val(this.id);
                $('#add_record_form').attr('action', '/my/show_private' + self.prepareHref());
                $('input[name=add_record_type]').val($('#add_record_type').val());
                records = $('#add_record').val().split(',');
                if(records.length>part_size){
                    $('.progress-bar').css('width', progress+'%').attr('aria-valuenow', progress).text(progress+'%');
                    $('#progress_modal').modal('show');
                    self.add_records(0,part_size,this.id);
                }else{
                    $('#add_record_form').submit();
                }
            }
        });

        $('#service_type').change(function () {
            this.typeUpdate();
        }.bind(this));

        $('#button-filter').click(function () {
            window.location.replace(this.prepareHref());
            return true;
        }.bind(this));

        $(document).on('click', '.delete_record', function() {
            if(!confirm(delete_confirm)){
                return false;
            }
            var arr1 = (this.id).split('_');
            var service_id = arr1[1];
            var record_id = arr1[2];

            $.ajax({
                url: '/my/show_private',
                type: "POST",
                data: ({
                    record_id: record_id,
                    service_id: service_id,
                    action: 'delete_record',
                    service_type: $('#service_type').val()
                }),
                success: function(response) {
                    self.information($('#onlyrec_' + record_id).text() + $('#deleted_recs_text').val());
                    $('#record_' + record_id).remove();
                    if(!$('.recordcb').length>0){
                        location.reload();
                    }else{
                        $('#records_found').text(parseInt($('#records_found').text())-1);
                    }
                }
            });
        });

        // Change record status

        $(document).on('click', '.chrecstatus', function() {
            var arr2 = (this.id).split('_');
            var service_id = arr2[1];
            var record_id = arr2[2];
            var rec_status = arr2[3];

            $.ajax({
                url: '/my/show_private',
                type: "POST",
                data: ({
                    record_id: record_id,
                    service_id: service_id,
                    action: 'change_status',
                    status: rec_status
                }),
                success: function(response) {
                    if (rec_status == 0) {
                        $('#allow_' + record_id).hide();
                        $('#deny_' + record_id).show();
                    }
                    if (rec_status == 1) {
                        $('#allow_' + record_id).show();
                        $('#deny_' + record_id).hide();
                    }
                }
            });
        });

        // Check/uncheck all records

        $(document).on('click', '#checkall', function() {
            if ($(this).is(':checked'))
                $('.recordcb').prop('checked', true);
            else
                $('.recordcb').prop('checked', false);

        });
        $(document).on('change','.recordcb, #checkall', function(){
            if($('.recordcb').is(':checked')){
                $('#delbulk').removeClass('disabled');
            }else{
                $('#delbulk').addClass('disabled');
            }
        });

        // Delete checked records

        $(document).on('click', '#delbulk', function() {
            if($(this).hasClass('disabled')){
                return false;
            }
            if(!confirm(delete_confirm)){
                return false;
            }
            var bunch_deleted_records = 0;
            var records = [];            
            $('.loading').show();
            $('.recordcb:checked').each( function(index, value) {
                records.push(this.value);
                bunch_deleted_records++;
            });
            $.ajax({
                url: '/my/show_private',
                type: "POST",
                dataType: "json",
                data: ({
                    records: records,
                    action: 'delete_record'
                }),

                success: function(response) {
                    if(Array.isArray(response.records))
                        response.records.forEach(function(id){
                            $('#record_' + id).remove();
                        });

                    $('.loading').hide();
                    self.information(bunch_deleted_records + $('#deleted_recs_text').val());
                    $('#delbulk').addClass('disabled');
                    if(!$('.recordcb').length>0){
                        location.reload();
                    }else{
                        $('#records_found').text(parseInt($('#records_found').text())-response.records.length);
                    }
                }

            });

            
        });

        // Add countries
        
        $(document).on('click', '.countries', function() {
            if ($(this).attr('disabled')) return;
            var chckd = 0;
            var status = 'allow';

            $('.addctr:checked').each(function() {
                chckd = 1;
            });

            if ($(this).hasClass('allow_countries')) status = 'allow'; else status = 'deny';
            if (chckd == 1) {
                $('#countries_status').val(status);
                $('#countries_form').attr('action', '/my/show_private' + self.prepareHref());
                $('#countries_form').submit();
            }
        });
        
        
        $(document).on('click', '#add-country', function() {
            if ($(this).attr('disabled')) return;
            var chckd = 1;
            var status = 'allow';

            

            if ($(this).hasClass('allow_countries')) status = 'allow'; else status = 'deny';
            if (chckd == 1) {
                $('#countries_form_add').submit();
            }
        });

        // Check/uncheck all countries

        $(document).on('click', '#allcountries', function() {
            if ($(this).is(':checked'))
                $('.addctr').prop('checked', true);
            else
                $('.addctr').prop('checked', false);
        });
    };

    PrivateLists.prototype.prepareHref = function () {
        var new_href = '';
        var is_filter = false;

        if ($('#service_id').val() != '') {
            new_href = new_href + (new_href == '' ? '?' : '&');
            new_href = new_href + 'service_id=' + $('#service_id').val();
        }

        if ($('#service_type').val() != '') {
            new_href = new_href + (new_href == '' ? '?' : '&');
            new_href = new_href + 'service_type=' + $('#service_type').val();
        }

        if ($('#record_type').val() != '') {
            new_href = new_href + (new_href == '' ? '?' : '&');
            new_href = new_href + 'record_type=' + $('#record_type').val();
        }

        if ($('#status').val() != '') {
            new_href = new_href + (new_href == '' ? '?' : '&');
            new_href = new_href + 'status=' + $('#status').val();
        }
        if ($('#search_record').val() != '') {
            new_href = new_href + (new_href == '' ? '?' : '&');
            new_href = new_href + 'record=' + $('#search_record').val();
        }
        if ($('#customdates').val() != '') {
            var datesarr = $('#customdates').val().split('-');
            new_href = new_href + (new_href == '' ? '?' : '&');

            start_from = new Date(Date.parse(datesarr[0])).getTime() / 1000;
            new_href = new_href + 'start_from=' + start_from;

            end_to = new Date(Date.parse(datesarr[1])).getTime() / 1000;
            new_href = new_href + '&end_to=' + end_to;
        }
        return new_href;
    };

    PrivateLists.prototype.typeUpdate = function () {
        var serviceType = $('#service_type').val();
    };

    PrivateLists.prototype.information = function (message) {
        $('#information_message .alert').html(message);
        $('#information_message').modal('show');
        setTimeout(function(){ $('#information_message').modal('hide'); }, 10000);
    };
    
    PrivateLists.prototype.import_csv = function (from,form=false){
        var self = this;
        if(!form){
            var data = new FormData();
            data.append('action', 'upload_csv');
            data.append('service_id', $('#csv-form select[name=service_id]').val());
        }else{
            var data = new FormData(form);    
        }
        data.append('from', from);
        $.ajax({
            type: "POST",
            enctype: 'multipart/form-data',
            url: '/my/show_private' + self.prepareHref(),
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                if(data != false && !data.end){
                    var progress = parseInt(100 * data.from / data.totalsize);
                    $('.progress-bar').css('width', progress+'%').attr('aria-valuenow', progress).text(progress+'%');
                    self.import_csv(data.from);
                }else{
                    $('#progress_modal').data('import',0);
                    location.reload();
                }
            },
            error: function (e) {
                location.reload();
            }
        });

    };

    window.PrivateLists = PrivateLists;
}());

$( document ).ready(function(){
    $('#show-geo-list').click(function(){
        $('#geo-list').show();
        $(this).attr('disabled',true);
        $('#new-record').attr('disabled',true);
        $('#show-csv').attr('disabled',true);
        $('html, body').animate({scrollTop: $("#geo-list").offset().top-100}, 1000);
        $("#countries-select").selectpicker().data("selectpicker").$button.focus();
    });
    $('#new-record').click(function(){
        $('#close-add-form').css('margin-top','0');
        $('#add-form').show();
        $(this).attr('disabled',true);
        $('#show-geo-list').attr('disabled',true);
        $('#show-csv').attr('disabled',true);
        $('html, body').animate({scrollTop: $("#add-form").offset().top-100}, 1000);
        $('#add_record_type').val('1').trigger('change');
        $('#add_record').focus();
    });
    $('#show-csv').click(function(){
        $('#import-csv').show();
        $(this).attr('disabled',true);
        $('#show-geo-list').attr('disabled',true);
        $('#new-record').attr('disabled',true);
        $('html, body').animate({scrollTop: $("#import-csv").offset().top-100}, 1000);
    });
    $('#close-csv-form').click(function(){
        $('#import-csv').hide();
        $('#new-record').attr('disabled',false);
        $('#show-geo-list').attr('disabled',false);
        $('#show-csv').attr('disabled',false);
    });
    $('#close-add-form').click(function(){
        $('#add-form').hide();
        $('#new-record').attr('disabled',false);
        $('#show-geo-list').attr('disabled',false);
        $('#show-csv').attr('disabled',false);
    });
    $('#close-geo-list').click(function(){
        $('#geo-list').hide();
        $('#show-geo-list').attr('disabled',false);
        $('#new-record').attr('disabled',false);
        $('#show-csv').attr('disabled',false);
    });
    $('#top-btn').click(function(){
        $('html, body').animate({scrollTop: 0}, 1000);
    });
    $('.clear').click(function(){       
        $('.filename').val("");
        $('.clear').hide();
        
        $('.input input:file').val("");        
        $(".image-preview-input-title").text($(".image-preview-input-title").data('browse'));
        $('#csv-form button[type=submit]').addClass('disabled');
        
    }); 
    $(".input input:file").change(function (){     
        var file = this.files[0];
        $(".input-title").text($(".input-title").data('change'));
        $(".clear").show();
        $(".filename").val(file.name);
        $('#csv-form button[type=submit]').removeClass('disabled');
    });
    $('#add_record_type').change(function(){
        var record_type = $(this).val();
        $("#add_record").attr('placeholder', l_add + ' ' + $('#add_record_type option:selected').text().toLowerCase());
        $('#close-add-form').css('margin-top','-49px');
        $(".add-form").addClass("hidden");
        $('#stop_word_hint').addClass("hidden");
        $("#list_btn").addClass("hidden");
        $("#list_box").addClass("hidden");
        $("#allow").removeClass("hidden");
        if(record_type=='3'){ // Geo form
            $("#countries_form_add").removeClass("hidden");
        }
        else if (record_type == '9') {
            $('#language_form_add').removeClass('hidden');
        }
        else if(record_type=='8'){ // Stop-word form
            if(stop_word_enabled){
                $("#allow").addClass("hidden");
                $("#add_record_form").removeClass("hidden");
                $("#list_btn").removeClass("hidden");
            }else{
                $('#stop_word_hint').removeClass("hidden");
            }
        }else if(record_type==''){ // Hide form
            $('#close-add-form').css('margin-top','-34px');
        }else{ // Default form
            $("#add_record_form").removeClass("hidden");
        }
    });
    $('#list_btn').click(function(){
        $("#list_box").removeClass("hidden");
        $(this).addClass("hidden");
        return false;
    })
    $('#add_list').click(function(){
        var words = [];
        $('#words option:selected').each(function(i){
            words.push($(this).val());
        });
        if(words.length){
            if($('#add_record').val()==''){
                $('#add_record').val(words.join(', '))
            }else{
                $('#add_record').val($('#add_record').val()+', '+words.join(', '));
            }
            if($('input[name=note').val()==''){
                $('input[name=note').val(l_list);
            }
        }
    });
});