;(function () {
    function addon_check(name, properties) {
        if (!document.getElementById(name + '_enable')) return;

        var notice = $('#' + name + '_notice');

        $('#' + name + '_enable').click(function () {
            if (properties['enabled'] || properties['trial']) {
                if ($(this).prop('checked')) {
                    $('#' + name + '_additional').removeClass('hidden');
                } else {
                    $('#' + name + '_additional').addClass('hidden');
                }
                return true;
            }

            notice.removeClass('hidden');
            notice.html(properties['notice']);

            if (!properties['trial']) $(this).prop('checked', false);

            return false;
        });
    }

    function property_block(block) {
        var id = block.data('id');
        var checkbox = $('#' + id + '_enable');
        var checkbox_all = $('#' + id + '_enable_all');
        var checked = checkbox.prop('checked');

        checkbox.change(function () {
            if ($(this).prop('checked') != checked) {
                block.addClass('changed');
            } else {
                block.removeClass('changed');
                checkbox_all.prop('checked', false);
            }
        });
    }

    window.AntispamService = function(addons) {
        if (addons) {
            for (var a in addons) {
                if (addons.hasOwnProperty(a)) {
                    addon_check(a, addons[a]);
                }
            }

            $('.property-all').each(function () {
                property_block($(this));
            });


            $('#service_change').change(function () {
                window.location.replace('/my/service?action=edit&service_id=' + $(this).val());
            });

            $('#antispam-service-auth-key-button').click(function () {
                var api_key = $(this).attr('data-key');
                $('#antispam-service-auth-key').html('<code style="font-size:1.2em;display:block;text-align:center">' + api_key + '</code>');
            });
        }
        $('.auth-key-button').click(function () {
            var api_key = $(this).attr('data-key');
            $(this).parent().html('<code style="font-size:1.2em;display:block;text-align:center">' + api_key + '</code>');
        });
    };
}());