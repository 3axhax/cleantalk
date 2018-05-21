<!--http://hpneo.github.io/gmaps/examples.html; -->

<?php Yii::app()->clientScript->registerScriptFile('http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.js'); ?>
<?php Yii::app()->clientScript->registerScriptFile('http://maps.google.com/maps/api/js?sensor=true'); ?>
<?php Yii::app()->clientScript->registerScriptFile('/js/gmaps.js'); ?>
<?php KnockoutForm::RegisterScripts(); ?>
<?php Yii::app()->clientScript->registerScriptFile('/js/knockout-file.js'); ?>
<?php Yii::app()->clientScript->registerScriptFile('/js/knockout.sortable.js'); ?>

<script id="taskTmpl" type="text/html">
    <div style="width: 100px; height: 100px; border: 1px black solid; float: left; margin-right: 2px;">
        <img data-bind="attr: { src : Urlsq, 'data-id': ID }" />
    </div>
</script>

<table width="100%" id="mmm">
    <tr>
        <td width="70%" valign="top">
            <textarea style="width:98%; height: 700px; border: 1px black solid;"></textarea>
        </td>
        <td width="30%" valign="top">
            <h3>File upload</h3>
            <input type="file" multiple="multiple"
                   data-bind="fileUpload: { property: 'Images', url: '/api/upload',
                    params: 'DynParams',
                   autoupload: true, done: 'AfterDone' }" />
            <hr/>
            <div data-bind="sortable: { template: 'taskTmpl', data: UploadedImages, afterMove: $root.ItemMoved }"
                 style="min-width: 100px; min-height: 100px; background-color: yellow"></div>
            <div class="clearBoth"></div>
            <button data-bind="click: AddNewSlider">New slider</button>
            <ul data-bind="foreach: Sliders">
                <li>
                    <input data-bind="value: Name" />
                    <div style="min-width: 100px; min-height: 100px; background-color: yellow"
                        data-bind="sortable: { template: 'taskTmpl', data: Images, afterMove: $root.ItemMoved }" ></div>
                    <div class="clearBoth"></div>
                </li>
            </ul>

            <h3>Google Maps</h3>
            <div id="map" style='width:500px; height: 350px; border: 1px black solid;'></div>
            <ul data-bind="foreach: Markers">
                <li><span data-bind="text: title"></span> <span data-bind="text: position.lat()"></span>
                    <span data-bind="text: position.lng()"></span></li>
            </ul>
        </td>
    </tr>
</table>

<script type="text/javascript">

    var Slider = function(id, name)
    {
        var self = this;
        self.ID = id;
        if(name == '') name = 'NewGroup';
        self.Name = ko.observable(name);
        self.Images = ko.observableArray([]);

        ko.computed(function()
        {
            return;
            var images = self.Images();
            var obj = $.extend({ 'Images' : images, 'UGID' : self.ID }, mmm.DynParams());
            $.post('/api/updategroup', obj, function(json)
            {
            }, 'json');
        });
    };

    var MMM = function()
    {
        var self = this;
        self.Markers = ko.observableArray([]);
        self.Images = ko.observableArray([]);
        self.Sliders = ko.observableArray([]);
        self.UploadedImages = ko.observableArray([]);
        self.ItemMoved = function(arg)
        {
            var obj = $.extend({}, self.DynParams());
            var items = [];
            $.each(self.Sliders(), function(idx, slider)
            {
                items.push({ UGID: slider.ID, 'Images': slider.Images()});
            });
            obj['Data'] = items;

            $.post('/api/updategroup', obj, function(json)
            {
            }, 'json');
        };

        self.DynParams = function()
        {
            var csrf = $('meta[name=csrf]').attr('content').split('=');
            var obj = {};
            obj[csrf[0]] = csrf[1];
            return obj;
        };
        self.AfterDone = function(json)
        {
            ko.mapping.fromJS(json, {}, self.UploadedImages);
        };
        self.AddNewSlider = function()
        {
            var obj = $.extend({}, self.DynParams());
            $.post('/api/newgroup', obj, function(json)
            {
                var sld = new Slider(json.id);
                self.Sliders.push(sld);
            }, 'json');
        };

        self.Update = function()
        {
            $.getJSON('/api/getimages', function(json)
            {
                ko.mapping.fromJS(json, {}, self);
            });

            $.getJSON('/api/getimagegroups', function(json)
            {
                $.each(json, function(idx, value)
                {
                    var sld = new Slider(value.UGID, value.UGName);
                    self.Sliders.push(sld);
                });
            });
        }
    };

    var mmm = new MMM();
    ko.applyBindings(mmm, $('#mmm')[0]);

    $(document).ready(function()
    {
        mmm.Update();
    });






    var map = new GMaps({
        div: '#map',
        lat: 60.1709,
        lng: 24.9375
    });

    GMaps.on('click', map.map, function(event) {
        var index = map.markers.length;
        var lat = event.latLng.lat();
        var lng = event.latLng.lng();

//        var template = $('#edit_marker_template').text();
//
//        var content = template.replace(/{{index}}/g, index).replace(/{{lat}}/g, lat).replace(/{{lng}}/g, lng);

        var mk = map.addMarker({
            lat: lat,
            lng: lng,
            title: 'Marker #' + index,
            infoWindow: {
                content : 'Some place'
            }
        });

        mmm.Markers.push(mk);
    });


</script>