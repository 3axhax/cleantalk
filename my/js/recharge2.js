$(document).ready(function () {
    var query = {};

    function reload(key, value) {
        query[key] = value;
        window.location.replace('?' + $.param(query));
    }

    $('#license_id').change(function () {
        reload('license_id', $('#license_id').val());
    });

    $('#currency').change(function () {
        var date = new Date();
        date.setTime(date.getTime() + 24 * 60 * 60);
        var expires = "; expires=" + date.toGMTString();
        document.cookie = "currency="+ $('#currency').val() + expires + "; path=/";
        window.location.reload();
    });
});