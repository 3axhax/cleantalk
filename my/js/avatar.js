$(function() {
    var cropper;
    // Clear event
    $('.image-preview-clear').click(function(){       
        $('.image-preview-filename').val("");
        $('.image-preview-clear').hide();
        $('.crop-box').hide();
        $('.image-preview-input input:file').val("");
        $('#crop_it').attr('src', '');
        $(".image-preview-input-title").text($(".image-preview-input-title").data('browse'));
        if(cropper)cropper.destroy();
    }); 
    // Create the preview image
    $(".image-preview-input input:file").change(function (){     
        var file = this.files[0];
        var reader = new FileReader();
        reader.onload = function (e) {
            $(".image-preview-input-title").text($(".image-preview-input-title").data('change'));
            $(".image-preview-clear").show();
            $(".image-preview-filename").val(file.name);     
            $('.crop-box').show();
            $('#crop_it').attr('src', e.target.result).width('100%');
            var image = document.querySelector('#crop_it');
            if(cropper)cropper.destroy();
            cropper = new Cropper(image, {
                movable: false,
                zoomable: false,
                rotatable: false,
                scalable: false,
                aspectRatio: 1,
                crop: function(e) {
                    $('input[name="crop-x"]').val(e.detail.x);
                    $('input[name="crop-y"]').val(e.detail.y);
                    $('input[name="crop-w"]').val(e.detail.width);
                    $('input[name="crop-h"]').val(e.detail.height);
                }
            });

        }        
        reader.readAsDataURL(file);
    });
    // init state
    if($('#avatar_filename').val()){ 
        $(".image-preview-input-title").text($(".image-preview-input-title").data('change'));
        $(".image-preview-clear").show();
    }else{
        $('.crop-box').hide();
    } 
});