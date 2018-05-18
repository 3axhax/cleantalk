window.AJAX = {
    get: function (uri, params, success, failure) {
        var q = params ? this.prepare(params) : '';
        var xhr = new XMLHttpRequest();

        xhr.open('GET', uri + (q ? '?' + q : ''), true);
        xhr.onreadystatechange = function () {
            if (xhr.readyState != 4) return;
            if (xhr.status != 200) {
                failure();
            } else {
                success(xhr.response);
            }
        };
        xhr.send();
    },

    prepare: function (obj) {
        var str = [];
        for (var p in obj) {
            if (obj.hasOwnProperty(p)) {
                str.push(encodeURIComponent(p) + '=' + encodeURIComponent(obj[p]));
            }
        }
        return str.join('&');
    }
};
;(function () {
    function News() {
        this.elements = {
            button: $('#news-notification'),
            block: $('#news-block')
        };
        this.visible = false;
        this.init();
    }

    News.prototype.init = function () {
        var self = this;
        function hide() {
            self.elements['block'].removeClass('show');
            self.visible = false;
            document.removeEventListener('click', hide);
        }

        this.elements['block'].click(function (e) {
            e.stopPropagation();
        });

        this.elements['block'].find('.dismiss').each(function () {
            $(this).click(function (e) {
                var news_id = $(this).data('id');
                AJAX.get('/my/ajax', {
                    'action': 'news_dismiss',
                    'news_id': news_id
                }, function () {
                    $('#news_' + news_id).remove();
                    self.refresh();
                });
                e.preventDefault();
            });
        });

        this.elements['button'].click(function (e) {
            e.preventDefault();
            e.stopPropagation();
            if (!self.visible) {
                self.elements['block'].addClass('show');
                self.visible = true;
                self.refresh();
                document.addEventListener('click', hide);
            } else {
                hide();
            }
            $(this).blur();
        });
    };

    News.prototype.refresh = function () {
        var height = 100;
        var count = 0;
        this.elements['block'].find('.news-item').each(function (i) {
            if (i < 2 && height < 400) {
                height += $(this).height();
            }
            count++;
        });
        if (!count) {
            $('#news-link').remove();
        } else {
            if (height < 400) height += 40;
            this.elements['block'].css('height', height + 'px');
            $('#news-link .label').html(count);
        }
    };

    window.addEventListener('load', function () {
        if (document.getElementById('news-link')) {
            new News();
        }
    });
}());
