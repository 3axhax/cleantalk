<?php 
// session_start();
		// if(!isset($_SESSION['ert']))
		// {
			// echo 'no4';
			// $_SESSION['ert'] = '456';
		// }
		// else
			// echo 'yes4';

// echo $_SESSION['ert'];	
		
// $session = Yii::app()->session;
// echo  $session['shopcartkey'];

$url = explode('?', $_SERVER['REQUEST_URI']);
$url = trim($url[0], '/');

$entity = Entity::ParseFromString($url);

if (Yii::app()->getRequest()->cookies['showSelLang']->value != '1') { 

	switch (strtolower(geoip_country_code_by_name($_SERVER['REMOTE_ADDR']))) {
		
		case 'ru': $lang = 'ru'; break;
		case 'fi': $lang = 'fi'; break;
		case 'gb': $lang = 'en'; break;
		case 'de': $lang = 'de'; break;
		case 'fr': $lang = 'fr'; break;
		case 'es': $lang = 'es'; break;
		case 'se': $lang = 'se'; break;
		default : $lang = 'en'; break;
	}

	Yii::app()->language = $lang;

}

//echo Yii::app()->language;

$ui = Yii::app()->ui; ?><!DOCTYPE html><html>
    <head>
        <title><?= $this->pageTitle; ?></title>
        <meta name="Keywords" content="">
        <META name="verify-v1" content="eiaXbp3vim/5ltWb5FBQR1t3zz5xo7+PG7RIErXIb/M="/>
        <meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
        <link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">
        <link href="/new_style/jscrollpane.css" rel="stylesheet" type="text/css"/>
        <link href="/new_style/bootstrap.css" rel="stylesheet" type="text/css"/>
		<link rel="stylesheet" href="/css/template_styles.css" />
        <link rel="stylesheet" href="/css/jquery.bootstrap-touchspin.min.css">
        <link rel="stylesheet" href="/css/opentip.css">
		<link rel="stylesheet" type="text/css" href="/css/jquery-bubble-popup-v3.css"/>
        <link href="/new_style/style_site.css" rel="stylesheet" type="text/css"/>
		<link rel="stylesheet" type="text/css" href="/css/prettyPhoto.css"/>
        <script src="/new_js/jquery.js" type="text/javascript"></script>
        <script src="/new_js/jquery.mousewheel.min.js" type="text/javascript"></script>
        <meta name="csrf" content="<?= MyHTML::csrf(); ?>"/>
        <script src="/new_js/jScrollPane.js" type="text/javascript"></script>
        <script src="/new_js/slick.js" type="text/javascript" charset="utf-8"></script>
        <script src="/new_js/nouislider.js" type="text/javascript" charset="utf-8"></script>
        <link href="/new_js/nouislider.css" rel="stylesheet" type="text/css"/>
		<script type="text/javascript" src="/js/jquery.prettyPhoto.js"></script>
		<script src="/js/common.js"></script>
		<script src="/new_js/jquery.bootstrap-touchspin.min.js"></script>
		<script src="/js/opentip.js"></script>
		<script type="text/javascript" src="/js/marcopolo.js"></script>
		<!--[if lt IE 9]>
<script src="libs/html5shiv/es5-shim.min.js"></script>
<script src="libs/html5shiv/html5shiv.min.js"></script>
<script src="libs/html5shiv/html5shiv-printshiv.min.js"></script>
<script src="libs/respond/respond.min.js"></script>
<![endif]-->
        <script>
			
			function show_subs(uid, sid, subsid) {
				var csrf = $('meta[name=csrf]').attr('content').split('=');
				
				$.post('/site/loadhistorysubs', { uid : uid, sid : sid, YII_CSRF_TOKEN: csrf[1], subsid : subsid }, function(data) {
					
					$('.history_subs_box').css('top', $(window).scrollTop() + 50);
					
					$('.history_subs_box .table_box').html(data);
					
					$('.history_subs_box, .opacity').show();
					
				});
				
			}
			
            $(document).ready(function () {
				
				
				$('li.dd_box .click_arrow').click(function(){
					
					if ($(this).parent().hasClass('show_dd')) {
						
						$('.dd_box').removeClass('show_dd');
						
					} else {
						
						$('.dd_box').removeClass('show_dd');
						
						$(this).parent().addClass('show_dd');
						
					}
					
					return false;
				})
				
				$(document).click(function (event) {
				if ($(event.target).closest("li.dd_box").length)
				return;
				$('li.dd_box').removeClass('show_dd');
				event.stopPropagation();
				});
				
				
				$(document).ready(function()
    {
        $('.search_text').on('keydown', function(a)
        {
            if(a.keyCode == 13)
            {
                $('#srch').submit();
            }
        });
		
		function decline_days(num) {
        var count = num;

        num = num % 100;

        if (num > 19) {
            num = num % 10;
        }

        switch (num) {

            case 1: {
                    return count + ' <?=$ui->item('A_NEW_SEARCH_RES_COUNT3'); ?>';
                }

            case 2: case 3: case 4: {
                    return count + ' <?=$ui->item('A_NEW_SEARCH_RES_COUNT2'); ?>';
                }

            default: {
                    return count + ' <?=$ui->item('A_NEW_SEARCH_RES_COUNT1'); ?>';
                }
			}
		}
		
        $('#Search').marcoPolo({
            url:'/site/search',
            cache : false,
			hideOnSelect: false,
            dynamicData:{ avail: function() { return $('.checkbox_box .avail').val(); } },
            formatItem:function (data, $item, q)
            {
					var ret = '';
					
					if (data.Counts != undefined) {
						
						for (var i = 1; i < 10; i++) {
							
							if (data.Counts.enityes[i] != undefined) {
							
								ret += '<div class="row_category">'+data.Counts.enityes[i][0] + ' <span>'+ data.Counts.enityes[i][2] +'</span> <a href="'+data.Counts.enityes[i][3]+'" class="result_search_count">'+decline_days(data.Counts.enityes[i][1])+' </a></div>';
							
							}
							
						}
						
					} else {
						
						if ( data.length > 0) {
							ret += '<div class="title_goods"><?=$ui->item('A_NEW_SEARCH_GOODS_TITLE'); ?></div>';
							
							for (var i=0; i<data.length;i++)
							{
								
								if (data[i].is_product) {
								
								var img = '';
								if (data[i].picture_url != 'http://ruslania.com/pictures/small/' && data[i].picture_url != ''){
									
									var img = '<img height="86" src="'+data[i].picture_url+'" />';
									
								}
								
								ret += '<div class="row_item"><table><tr><td class="pic"><a href="'+data[i].url +'">'+img+'</a></td><td class="name"><a href="'+data[i].url+'">'+data[i].title+'</a><div style="height: 18px;"></div><span class="price">'+data[i].price+'</span></td></tr></table></div>';
								//<a class="cart-action add_cart<?if (Yii::app()->language == 'es') echo ' no_img';?>" data-action="add" style="width: 162px;font-size: 13px; margin-left: 18px; color: #fff;" data-entity="'+data[i].entity+'" data-id="'+data[i].id+'" data-quantity="1" href="javascript:;" onclick="add2Cart($(this).attr(\'data-action\'),     $(this).attr(\'data-entity\'),$(this).attr(\'data-id\'),$(this).attr(\'data-quantity\'),null,$(this));"><?=$ui->item('CART_COL_ITEM_MOVE_TO_SHOPCART');?></a>
								
								}
							}
							
							
						}
						
					}
					
					//console.log(ret);
					
					return ret;
               
            }

        });
    });
				
				
				
                $.ajax({
                    url: '/cart/getcount',
                    data: 'id=1',
                    type: 'GET',
                    success: function (data) {
                        var d = JSON.parse(data);
                        //alert(data);
                        $('div.cart_count').html(d.countcart)
                        $('div.span1.cart .cost').html(d.totalPrice)
                    }
                });
				
				 $('select.periodic').change(function ()
                {
					
					//alert(1);
					
                    var $el = $(this);
                    var cart = $el.closest('.span11, .span1.cart');

                    var worldpmonthVat0 = cart.find('input.worldmonthpricevat0').val();
                    var worldpmonthVat = cart.find('input.worldmonthpricevat').val();
                    var finpmonthVat0 = cart.find('input.finmonthpricevat0').val();
                    var finpmonthVat = cart.find('input.finmonthpricevat').val();

                    var nPriceVat = (worldpmonthVat * $el.val()).toFixed(2);
                    var nPriceVat0 = (worldpmonthVat0 * $el.val()).toFixed(2);

                    var nPriceFinVat = (finpmonthVat * $el.val()).toFixed(2);
                    var nPriceFinVat0 = (finpmonthVat0 * $el.val()).toFixed(2);

                    cart.find('.periodic_world .price').html(nPriceVat + ' <?=Currency::ToSign(); ?>');
					cart.find('.periodic_world .pwovat span').html(nPriceVat0 + ' <?=Currency::ToSign(); ?>');

					cart.find('.periodic_fin .price').html(nPriceFinVat + ' <?=Currency::ToSign(); ?>');
					cart.find('.periodic_fin .pwovat span').html(nPriceFinVat0 + ' <?=Currency::ToSign(); ?>');

                    cart.find('a.add').attr('data-quantity', $el.val());
                });


				
				
            })

            $(document).ready(function () {

                $(document).click(function (event) {
                    if ($(event.target).closest(".dd_box_select").length)
                        return;
                    $('.dd_box_select .list_dd').hide();
                    event.stopPropagation();
                })

                var blockScroll1 = false;
                var blockScroll2 = false;
                var blockScroll3 = false;
                var page_authors = 1;
                var page_izda = 1;
                var page_seria = 1;
                $('.dd_box_select .list_dd.authors_dd').scroll(function () {

                    if (($(this).height() + $(this).scrollTop()) >= $('.items', $(this)).height() && !blockScroll1) {

                        blockScroll1 = true;
                        page_authors++;
                        var tthis = $(this);
                        $('.load_items', $(this)).show();
                        $('.load_items', $(this)).html('<?=$ui->item('A_NEW_LOAD'); ?>');
                        var csrf = $('meta[name=csrf]').attr('content').split('=');

                        var url = '/site/loaditemsauthors/page/' + page_authors + '/entity/' + $('.entity_val').val() + '/cid/' + $('.cid_val').val();

                        $.post(url, {YII_CSRF_TOKEN: csrf[1]}, function (data) {
                            //alert(data);
                            $('.items .rows', tthis).append(data);
                            blockScroll1 = false;
                            $('.load_items', tthis).html('');
                            $('.load_items', tthis).hide();
                        })

                    }

                })
                $('.dd_box_select .list_dd.izda_dd').scroll(function () {

                    if (($(this).height() + $(this).scrollTop()) >= $('.items', $(this)).height() && !blockScroll2) {

                        blockScroll2 = true;
                        page_izda++;
                        var tthis = $(this);
                        $('.load_items', $(this)).show();
                        $('.load_items', $(this)).html('<?=$ui->item('A_NEW_LOAD'); ?>');
                        var csrf = $('meta[name=csrf]').attr('content').split('=');

                        var url = '/site/loaditemsizda/page/' + page_izda + '/entity/' + $('.entity_val').val() + '/cid/' + $('.cid_val').val();

                        $.post(url, {YII_CSRF_TOKEN: csrf[1]}, function (data) {
                            //alert(data);
                            $('.items .rows', tthis).append(data);
                            blockScroll2 = false;
                            $('.load_items', tthis).html('');
                            $('.load_items', tthis).hide();
                        })

                    }

                })
                $('.dd_box_select .list_dd.seria_dd').scroll(function () {

                    if (($(this).height() + $(this).scrollTop()) >= $('.items', $(this)).height() && !blockScroll3) {

                        blockScroll3 = true;
                        page_seria++;
                        var tthis = $(this);
                        $('.load_items', $(this)).show();
                        $('.load_items', $(this)).html('<?=$ui->item('A_NEW_LOAD'); ?>');
                        var csrf = $('meta[name=csrf]').attr('content').split('=');

                        var url = '/site/loaditemsseria/page/' + page_seria + '/entity/' + $('.entity_val').val() + '/cid/' + $('.cid_val').val();

                        $.post(url, {YII_CSRF_TOKEN: csrf[1]}, function (data) {
                            //alert(data);
                            $('.items .rows', tthis).append(data);
                            blockScroll3 = false;
                            $('.load_items', tthis).html('');
                            $('.load_items', tthis).hide();
                        })

                    }

                })

            })
            
            function show_items() {
                
                //$('.span10.listgoods').html('');
                
                var create_url;
                
                create_url = '/site/ggfilter/entity/'+$('form.filter input.entity_val').val()+'/cid/'+$('form.filter input.cid_val').val()+'/author/'+$('form.filter .form-row input[name=author]').val()+'/avail/'+$('form.filter .form-row input[name=avail]').val()+'/ymin/'+$('form.filter .form-row input.year_inp_mini').val()+'/ymax/'+$('form.filter .form-row input.year_inp_max').val()+'/izda/'+$('form.filter .form-row input[name=izda]').val()+'/seria/'+$('form.filter .form-row input[name=seria]').val()+'/cmin/'+$('form.filter .form-row input.cost_inp_mini').val()+'/cmax/'+$('form.filter .form-row input.cost_inp_max').val()+'/';
                
                var bindings = [];
                var i = 0;
                $('.bindings input[type=checkbox]:checked').each(function() {
                    
                    bindings[i] = $(this).val();
                    
                    i++;
                })
                
                var csrf = $('meta[name=csrf]').attr('content').split('=');
                $('.span10.listgoods').html('<?=$ui->item('A_NEW_LOAD2'); ?>');
                $.post(create_url, { YII_CSRF_TOKEN: csrf[1], 'binding_id[]' : bindings, search_name : $('form.filter .search.inp').val(), sort : $('form.filter .sort').val() }, function(data) {
                    
                    $('.span10.listgoods').html(data);
                    $('.box_select_result_count').hide(1);
                    $(window).scrollTop(0);
                })
                
            }
            
            var mini_map_isOn = 0;
            var TimerId;
            
            function mini_cart_off() {
                if (mini_map_isOn == 1)
                {
                    $('#cart_renderpartial').toggle(100);
                }
                mini_map_isOn = 0;
            }
            $(document).ready(function () {
                $('.cart_box').click(function () {
                        $('#cart_renderpartial').toggle(100);
                        mini_map_isOn = 1-mini_map_isOn;
                        if (mini_map_isOn)
                            TimerId = setTimeout(mini_cart_off, 10000);
                        else
                            clearTimeout(TimerId);
                    })
            })
            function show_result_count(cont) {

                $('.box_select_result_count').hide(1);

                var frm = $('form.filter').serialize();
                var csrf = $('meta[name=csrf]').attr('content').split('=');

                frm = frm + '&' + csrf[0] + '=' + csrf[1];

                //alert(frm);

                $.post('/site/gtfilter/', frm, function (data) {
                    //alert(data);
                    $('.box_select_result_count a', cont.parents('.form-row')).show();
                    if (data == '0') {
                        $('.box_select_result_count a', cont.parents('.form-row')).hide();
                    }

                    $('.box_select_result_count .res_count', cont.parents('.form-row')).html(data);
                    $('.box_select_result_count', cont.parents('.form-row')).show(1);
                })
            }

            function select_item(item, inp_name) {
                var id = item.attr('rel');
                //$('.list_dd', item.parent().parent().parent().parent()).scrollTop(0);

                $('div', item.parent()).removeClass('selact');

                item.addClass('selact');
                $('.text span', item.parent().parent().parent().parent()).html(item.html());
                $('.list_dd', item.parent().parent().parent().parent()).hide();
                $('input[name=' + inp_name + ']', item.parent().parent().parent().parent()).val(id);

                show_result_count(item);
            }

            function show_sc(cont, c) {
                cont.toggle(400, function () {

                    //c.toggleClass('open');

                    if (c.hasClass('open')) {

                        c.removeClass('open');


                        $('li a.open_subcat', $(cont)).removeClass('open');
                        $('ul.subcat', $(cont)).hide();

                    } else {
                        c.addClass('open');
                    }

                });
            }
			$(document).ready(function () {
				
				
				
			
			})
            function add2Cart(action, eid, iid, qty, type, $el)
            {
				
                // var $parent = $el.closest('.to_cart');
                var csrf = $('meta[name=csrf]').attr('content').split('=');
				
				//alert(opentip.currentStem);
                //$el.CreateBubblePopup();
                var post =
                        {
                            entity: eid,
                            id: iid,
                            quantity: qty,
                            type: type
                        };
                post[csrf[0]] = csrf[1];
				
				//var bubble_popup_id = $el.GetBubblePopupID();
				var seconds_to_wait = 10;
				/*$el.ShowBubblePopup({

            align: 'right',
            mouseOut: 'show',
            alwaysVisible: false,
            innerHtml: '<p><?=$ui->item('AJAX_IN_PROGRESS'); ?></p>',

            innerHtmlStyle:{
                color:'#666666',
                'text-align':'left'
            },

            themeName: 	'blue',
            themePath: 	'/css/jquerybubblepopup-themes'

			}, false); */
			
			//$el.FreezeBubblePopup();
			
			var opentip = new Opentip($el,'',{ target: true, tipJoint: "bottom", group: "group-example", showOn: "click", hideOn: 'ondblclick', background: '#fff', borderColor : '#fff' });
				
				opentip.deactivate();
				
                $.post('/cart/'+action, post, function (json)
                {
					
					var json = JSON.parse(json);
					var opentip = new Opentip($el,'<div style="padding-right: 17px;">'+json.msg +
						'</div><div style="height: 6px;"></div><span class="timer_popup"></span> <span class="countdown">00: 10</span><a href="javascript:;" class="close_popup" onclick="$(this).parent().parent().parent().remove()"><img src="/new_img/close_popup.png" alt="" /></a>',{ target: true, tipJoint: "bottom", group: "group-example", showOn: "click", hideOn: 'ondblclick', background: '#fff', borderColor : '#fff' });
					/*$el.ShowBubblePopup({

						align: 'center',
						innerHtml: json.msg +
						'<div style="height: 3px;"></div><span class="timer_popup"></span> <span class="countdown">00: 10</span>	<a href="#" class="close_popup"><img src="/new_img/close_popup.png" alt="" /></a><div class="arrow_popup"><img src="/new_img/bottom_popup.png"></div>',

						innerHtmlStyle:{
							color:'#666666',
							'text-align':'left'
						},
						mouseOut: 'show',
						alwaysVisible: false,

						themeName: 	'blue',
						themePath: 	'/css/jquerybubblepopup-themes'

					}, false);
					*/
					
					
					
					opentip.show();
					
					function doCountdown()
					{
						
						var str = '';
						
						var timer = setTimeout(function()
						{
							seconds_to_wait--;
							
							if (seconds_to_wait < 10) {
								str = '00:0'+seconds_to_wait;
							} else {
								str = '00:'+seconds_to_wait;
							}
							
							if($('#opentip-'+opentip.id+' span.countdown').length>0) $('#opentip-'+opentip.id+' span.countdown').html(str);
							if(seconds_to_wait > 0) doCountdown();
							else opentip.deactivate();
						}, 1000);
					}
					
					if (json.already)
                    {
                        $('div.already-in-cart', $el.parent()).html(json.already);
                    }

					
					
					doCountdown();
					
					update_header_cart();
					
				})
            }


            $(document).ready(function () {

                $(document).click(function (event) {
                    if ($(event.target).closest(".select_lang").length)
                        return;
                    $('.select_lang .dd_select_lang').hide();
                    $('.select_lang').removeClass('act');
                    $('.select_lang .label_lang').removeClass('act');
                    event.stopPropagation();
                });
				
				$.fn.prettyPhoto({social_tools: false});

                $('a.read_book').click(function ()
                {
					
					
                    var $this = $(this);
                    var images = [];
                    if ($this.attr('data-images') != '')
                    {
                        images = $this.attr('data-images').split('|');
                        if (images.length > 0)
                            $.prettyPhoto.open(images, [], []);
                    }

                    //            var pdf = $this.attr('data-pdf').split('|');
                    //            if(pdf.length > 0)
                    //            {
                    //                var iid = $this.attr('data-iid');
                    //                $('#staticfiles'+iid).fadeIn();
                    //            }
                });
				
				/* $('.tabs_container .tabs li').click(function() {
					
					var $clas = $(this).attr('class').split(' ')[0];
					
					//alert($clas);
					
					$('.tabs_container .tabcontent, .tabs_container .tabs li').removeClass('active');
					
					$('.tabs_container .tabcontent.'+$clas).addClass('active');
					$('.tabs_container .tabs li.'+$clas).addClass('active');
					
				}) */
				
				
				$(document).click(function (event) {
                    if ($(event.target).closest(".span1.cart, .b-basket-list").length)
                        return;
                    $('.b-basket-list').fadeOut();
                    event.stopPropagation();
                });

                $(document).click(function (event) {
                    if ($(event.target).closest(".select_valut").length)
                        return;
                    $('.select_valut .dd_select_valut').hide();
                    $('.label_valut').removeClass('act');
                    event.stopPropagation();
                });

                var elems = $('a.cart-action');

                elems.click(function ()
                {
                    //alert('1');

                    var $el = $(this);
                    var $parent = $el.closest('.to_cart');

                    var entity = $el.attr('data-entity');
					
					//alert($el.attr('data-quantity'));
					
                    add2Cart($el.attr('data-action'),
                            $el.attr('data-entity'),
                            $el.attr('data-id'),
                            $el.attr('data-quantity'),
                            null,
                            $el
                            );

                    return false;
                });

            })

            function check_search(cont) {

                if ($('.check', cont).hasClass('active')) {
                    $('.check', cont).removeClass('active');
                    $('.avail', cont).val('');
                } else {
                    $('.check', cont).addClass('active');
                    $('.avail', cont).val('1');
                }

            }

            function show_tab(cont, url) {
                
				if (cont.parent().hasClass('active')) {
					
					location.href = cont.attr('href');
					
					
				} else {
					
					$('.dd_box_bg .tabs li').removeClass('active');
					cont.parent().addClass('active');
					var csrf = $('meta[name=csrf]').attr('content').split('=');
					$('.dd_box_bg .content .list').html('');
					
					$.post('/site/mload' + cont.attr('href'), {YII_CSRF_TOKEN: csrf[1], id: 1}, function (data) {
						$('.dd_box_bg .content .list').html(data);
					})
				
				}
            }
			
			function update_header_cart(){
				$.ajax({
					url: '/cart/getcount',
					data: 'id=1',
					type: 'GET',
					success: function (data) {
						var d = JSON.parse(data);
						
						 var data = { language: '<?=Yii::app()->language; ?>', is_MiniCart: 1};
						$.getJSON('/cart/getall', data, function (json)
						{
							ko.mapping.fromJS(json, {}, cvm_1);
						   
							cvm_1.FirstLoad(false);
							
						});
						
						$('div.cart_count').html(d.countcart)
						$('div.span1.cart .cost').html(d.totalPrice)
					}
				});
			}	

			function addComment() {
				var csrf = $('meta[name=csrf]').attr('content').split('=');
				var ser = $('form.addcomment').serialize() + '&'+csrf[0]+'='+csrf[1];
				
				
				
				$.post('/site/addcomments/', ser, function (data) {
					
					if (data) {
						//$('.comments_block').html(data);
						$('form span.info').html('<?=$ui->item('A_NEW_REVIEW_SENT1');?>');
						$('form span.info').delay(1).show(0);
						
						$('form span.info').delay(2000).hide(0);
						
						$('.review form textarea').val('');
					} else {
						$('form span.info').html('<span style="color: #ff0000;"><?=$ui->item('A_NEW_REVIEW_SENT2');?></span>');
						$('form span.info').delay(1).show(0);
						
						$('form span.info').delay(2000).hide(0);
						
						$('.review form textarea').val('');
					}
				})
				
			}
			
			
        </script>

    </head>

    <body>
	
	<?
	
	if ($_GET['sel'] == '1') { 
	
		$cookie = new CHttpCookie('showSelLang', '1');

		$cookie->expire = time() + (60*60*24*20000); // 20000 days

		Yii::app()->getRequest()->cookies['showSelLang'] = $cookie;

	}
	
	if (Yii::app()->getRequest()->cookies['showSelLang']->value == '' OR Yii::app()->getRequest()->cookies['showSelLang']->value == '0') {
	
		?>
		
		<div class="opacity_box" style="display: block;"></div>
		
		<div class="lang_yesno_box">
			
			<div class="box_title box_title_ru"><?=$ui->item('A_NEW_RUS_POPUP');?></div>
			
			<? if (Yii::app()->language == 'ru') : ?>
			
				
				<div class="box_title box_title_en">Is your language russian?</div>
			
			<? endif; ?>
			
			<div class="box_btns">
				<a href="?language=ru&sel=1" class="btn_yes"><?=$ui->item('A_NEW_BTN_YES');?> <? if (Yii::app()->language == 'ru') : ?>(Yes)<? endif; ?></a>
				<a href="javascript:;" onclick="$('.lang_yesno_box').hide(); $('.lang_yesno_box.select_lang').show();" class="btn_no"><?=$ui->item('A_NEW_BTN_NO');?> <? if (Yii::app()->language == 'ru') : ?>(No)<? endif; ?></a>
			</div>
			
		</div>
		
		<div class="lang_yesno_box select_lang">
		
			<div class="box_title box_title_ru"><?=$ui->item('A_NEW_SELECT_LANG_TITLE');?>:</div>
			<div class="row">
				<ul class="list_languages">
					<li class="ru span1"><a href="<?= MyUrlManager::RewriteCurrent($this, 'ru'); ?>&sel=1"><?=$ui->item('A_LANG_RUSSIAN')?></a></li>
					<li class="fi span1"><a href="<?= MyUrlManager::RewriteCurrent($this, 'fi'); ?>&sel=1"><?=$ui->item('A_LANG_FINNISH')?></a></li>
					<li class="en span1"><a href="<?= MyUrlManager::RewriteCurrent($this, 'en'); ?>&sel=1"><?=$ui->item('A_LANG_ENGLISH')?></a></li>
					<li class="de span1"><a href="<?= MyUrlManager::RewriteCurrent($this, 'de'); ?>&sel=1"><?=$ui->item('A_LANG_GERMAN')?></a></li>
					<li class="fr span1"><a href="<?= MyUrlManager::RewriteCurrent($this, 'fr'); ?>&sel=1"><?=$ui->item('A_LANG_FRENCH')?></a></li>
					<li class="es span1"><a href="<?= MyUrlManager::RewriteCurrent($this, 'es'); ?>&sel=1"><?=$ui->item('A_LANG_ESPANIOL')?></a></li>
					<li class="se span1"><a href="<?= MyUrlManager::RewriteCurrent($this, 'se'); ?>&sel=1"><?=$ui->item('A_LANG_SWEDISH')?></a></li>
					
				</ul>
			</div>
		</div>
		
		<?
		
	}
	?>
	
	
        <div class="header_logo_search_cart">
        
		<? $mess = Yii::app()->ui->item('MSG_MAIN_WELCOME_INTERNATIONAL_ORDERS'); 
		if ($mess) {
		?>
		
		<div class="alert_bg" style="display: block">
            <div class="container">
                <span class="text"><?=$mess?></span>
                <span class="close_alert" onclick="$(this).parent().parent().remove()"><img src="/new_img/close_alert.png" /></span>
            </div>
        </div><?}?>

        <div class="light_gray_menu">
            <div class="container">
                <ul>
				<!--<li style="padding-right: 0px;"><img src="/new_img/flag.png" /></li>-->
                    <li style="border: 0;padding-left: 10px;"><a href="<?= Yii::app()->createUrl('site/static', array('page' => 'aboutus')); ?>" class=""><?=$ui->item('A_NEW_ABOUTUS');?></a></li>
                    <li><a href="<?= Yii::app()->createUrl('site/static', array('page' => 'contact')); ?>"><?=$ui->item('YM_CONTEXT_CONTACTUS')?></a></li>
                    <li><span class="telephone">+358 92727070</span></li>
                    <li><span class="adrs">Bulevardi 7, FI-00120 Helsinki, Finland</span></li>
                    <li><?=$ui->item('A_NEW_TITLE_TOP');?></li>
					
					<?php if (Yii::app()->user->isGuest) : ?>
					
                    <li class="menu_right none_right_padding"><a href="<?= Yii::app()->createUrl('site/login'); ?>"><?=$ui->item('A_SIGNIN')?></a></li>
                    <li class="menu_right none_border"><a href="<?= Yii::app()->createUrl('site/register'); ?>"><?=$ui->item('A_REGISTER')?></a></li>
					<? else :?>
					<li class="menu_right none_right_padding"><a href="<?= Yii::app()->createUrl('site/logout'); ?>"><?= $ui->item('YM_CONTEXT_PERSONAL_LOGOUT'); ?></a></li>
					<li class="menu_right none_border "><a href="<?=Yii::app()->createUrl('client/me'); ?>"><?= $ui->item('YM_CONTEXT_PERSONAL_MAIN'); ?></a></li>
                    
					<?endif;?>
                </ul>
            </div>
        </div>

			<div class="container">
                <div class="row">
                    <div class="span1 logo">
                        <a href="/"><img src="/new_img/logo.png" alt=""/></a>
                    </div>
                    <div class="span10">
                        <form method="get" action="/site/search" id="srch">
                            <div class="search_box">
								<div class="loading"><?=$ui->item('A_NEW_SEARCHING_RUR');?></div>
                                <input type="text" name="q" class="search_text" placeholder="<?=$ui->item('A_NEW_INPUT_SEARCH');?>" id="Search" value="<?=$_GET['q']?>"/>
                                <img src="/new_img/btn_search.png" class="search_run" alt="" onclick="$('#srch').submit()"/>
                            </div>

                            <div class="pult">

                                <ul>
                                    <li class="sm"><a href="/advsearch<? if ($entity) { echo '?e='.$entity; } elseif ($_GET['e']) { echo '?e='.$_GET['e']; }?>" class="search_more"> <?=$ui->item('Advanced search')?></a></li>
                                    <li class="chb">
                                        <div class="checkbox_box" onclick="check_search($(this))">
											<?
											
												$act = array();
												
												$act = array(1, ' active');
												
												if (isset($_GET['avail'])) {
													
													if ($_GET['avail'] == '1') {
														$act = array(1, ' active');
													} else {
														$act = array('', '');
													}
													
												}
											?>
										
                                            <span class="checkbox">
                                                <span class="check<?=$act[1]?>"></span>
                                            </span> <input type="hidden" name="avail" value="<?=$act[0]?>" class="avail"><?= $ui->item('A_NEW_SEARCH_AVAIL'); ?>
                                        </div>
                                    </li>
                                    <li class="langs">
                                        <div class="select_lang">
											<?
											$arrLangsTitle = array(
												'ru' => $ui->item('A_LANG_RUSSIAN'),
												'rut' => $ui->item('A_LANG_TRANSLIT'),
												'fi' => $ui->item('A_LANG_FINNISH'),
												'en' => $ui->item('A_LANG_ENGLISH'),
												'de' => $ui->item('A_LANG_GERMAN'),
												'fr' => $ui->item('A_LANG_FRENCH'),
												'es' => $ui->item('A_LANG_ESPANIOL'),
												'se' => $ui->item('A_LANG_SWEDISH')
											);
											?>
                                            <div class="label_lang" onclick="$('.dd_select_lang').toggle(); $(this).toggleClass('act'); $(this).parent().toggleClass('act')">
                                                <span class="lang <?=Yii::app()->language;?>"><a href="javascript:;"><?=$arrLangsTitle[Yii::app()->language]; ?></a> <span class="dd"></span></span>
                                            </div>

                                            <div class="dd_select_lang">

                                                <div class="label_lang">
                                                    <span class="lang ru"><a href="<?= MyUrlManager::RewriteCurrent($this, 'ru'); ?>"><?=$ui->item('A_LANG_RUSSIAN')?></a></span>
                                                </div>
                                                <div class="label_lang">
                                                    <span class="lang ru"><a href="<?= MyUrlManager::RewriteCurrent($this, 'rut'); ?>"><?=$ui->item('A_LANG_TRANSLIT')?></a></span>
                                                </div>
                                                <div class="label_lang">
                                                    <span class="lang fi"><a href="<?= MyUrlManager::RewriteCurrent($this, 'fi'); ?>"><?=$ui->item('A_LANG_FINNISH')?></a></span>
                                                </div>
                                                <div class="label_lang">
                                                    <span class="lang en"><a href="<?= MyUrlManager::RewriteCurrent($this, 'en'); ?>"><?=$ui->item('A_LANG_ENGLISH')?></a></span>
                                                </div>
                                                <div class="label_lang">
                                                    <span class="lang de"><a href="<?= MyUrlManager::RewriteCurrent($this, 'de'); ?>"><?=$ui->item('A_LANG_GERMAN')?></a></span>
                                                </div>
                                                <div class="label_lang">
                                                    <span class="lang fr"><a href="<?= MyUrlManager::RewriteCurrent($this, 'fr'); ?>"><?=$ui->item('A_LANG_FRENCH')?></a></span>
                                                </div>
                                                <div class="label_lang">
                                                    <span class="lang es"><a href="<?= MyUrlManager::RewriteCurrent($this, 'es'); ?>"><?=$ui->item('A_LANG_ESPANIOL')?></a></span>
                                                </div>
                                                <div class="label_lang">
                                                    <span class="lang se"><a href="<?= MyUrlManager::RewriteCurrent($this, 'se'); ?>"><?=$ui->item('A_LANG_SWEDISH')?></a></span>
                                                </div>

                                            </div>

                                        </div>
                                    </li>
                                    <li class="valuts">

                                        <div class="select_valut">
											<? $arrVCalut = array(
												
												'1' => array('euro','Euro'),
												'2' => array('usd','USD'),
												'3' => array('gbp','GBP'),
												
											); ?>
                                            <div class="label_valut select" onclick="$('.dd_select_valut').toggle(); $(this).toggleClass('act')">
                                                <a href="javascript:;"><span class="valut <?=$arrVCalut[(string)Yii::app()->currency][0]?>"><?=$arrVCalut[(string)Yii::app()->currency][1]?><span class="dd"></span></span></a>
                                            </div>

                                            <div class="dd_select_valut">
							
                                                <div class="label_valut">
                                                    <a href="<?= MyUrlManager::RewriteCurrency($this, Currency::EUR); ?>">
													<span style="width: 17px; display: inline-block; text-align: center">&euro;</span><span class="valut" style="margin-left: 10px;">Euro</span></a>
                                                </div>
                                                <div class="label_valut">
                                                    <a href="<?= MyUrlManager::RewriteCurrency($this, Currency::USD); ?>"><span style="width: 17px; display: inline-block; text-align: center">$</span><span class="valut" style="margin-left: 10px;">USD</span></a>
                                                </div>
                                                <div class="label_valut">
                                                    <a href="<?= MyUrlManager::RewriteCurrency($this, Currency::GBP); ?>"><span style="width: 17px; display: inline-block; text-align: center">£</span><span class="valut" style="margin-left: 10px;">GBP</span></a>
                                                </div>
                                            </div>

                                        </div>

                                    </li>
                                </ul>

                            </div>
                        </form>
                    </div>
                    <div class="span1 cart" >
                        
                        
                        <div class="span1">

                            <?= $ui->item('A_NEW_CART'); ?>:
                            <div class="cost"></div>

                        </div>
                        <div class="span2 js-slide-toggle" data-slidetoggle=".b-basket-list" data-slideeffect="fade" data-slidecontext=".span1.cart" >

                            <div class="cart_box" ><img src="/new_img/cart.png" alt=""/></div>
                            <div class="cart_count"></div>

                        </div>
                        
                           
								<?php  $this->renderPartial('/cart/header_cart'); ?>   
                          
                   
                       

                    </div>
                </div>



            </div>
			<div style="height: 10px;"></div>
        <script>
            $(document).ready(function () {
                $('a', $('.dd_box .tabs li')[0]).click();				
                // $('li.dd_box .content').jScrollPane({scrollbarWidth:18, showArrows:true});
				
				
				$('.dd_box').removeClass('show_dd');

            })
        </script>
        <div class="index_menu">

            <div class="container">
                <ul>
                    <li class="dd_box">
					<div class="click_arrow"></div>
						<a class="dd" href="<?= Yii::app()->createUrl('entity/list', array('entity' => Entity::GetUrlKey(Entity::BOOKS))); ?>"><?= $ui->item("A_GOTOBOOKS"); ?></a>
						 <div class="dd_box_bg list_subcategs" style="left: 0;">
							
							<div class="span10">
								
								<ul>
									<? $i = 1; $rows = Category::GetCategoryList(10, 0);
									
									foreach ($rows as $row) {
									
									?>
									<li> 
										<a href="<?=Yii::app()->createUrl('entity/list', array('entity' => Entity::GetUrlKey(10), 'cid' => $row['id'], 'title' => ProductHelper::ToAscii($row['title_en'])))?>"><?=ProductHelper::GetTitle($row)?></a>
									</li>
									<? 
									if ($i % 2 == 0) {
										
										echo '<div class="clearfix"></div>';
										
									}
									?>
									<? $i++; } ?>
									
								</ul>
								
							</div>
							
							<div class="span2">
								
								<img src="/new_img/banner.png" />
								
							</div>
							
						 </div>
					</li>
                    
					<li class="dd_box"><div class="click_arrow"></div><a class="dd"  href="<?= Yii::app()->createUrl('entity/list', array('entity' => Entity::GetUrlKey(Entity::SHEETMUSIC))); ?>"><?= $ui->item("A_GOTOMUSICSHEETS"); ?></a>
					<div class="dd_box_bg list_subcategs" style="left: -80px;">
							
							<div class="span10">
								
								<ul>
									<? $i = 1; $rows = Category::GetCategoryList(Entity::SHEETMUSIC, 0);
									
									foreach ($rows as $row) {
									
									?>
									<li> 
										<a href="<?=Yii::app()->createUrl('entity/list', array('entity' => Entity::GetUrlKey(Entity::SHEETMUSIC), 'cid' => $row['id'], 'title' => ProductHelper::ToAscii($row['title_en'])))?>"><?=ProductHelper::GetTitle($row)?></a>
									</li>
									<? 
									if ($i % 2 == 0) {
										
										echo '<div class="clearfix"></div>';
										
									}
									?>
									<? $i++; } ?>
									
								</ul>
								
							</div>
							
							<div class="span2">
								
								<img src="/new_img/banner.png" />
								
							</div>
							
						 </div>
					</li>
                    <li class="dd_box"><div class="click_arrow"></div><a class="dd"  href="<?= Yii::app()->createUrl('entity/list', array('entity' => Entity::GetUrlKey(Entity::MUSIC))); ?>"><?= $ui->item("Music catalog"); ?></a>
					
					<div class="dd_box_bg list_subcategs" style="left: -310px;">
							
							<div class="span10">
								
								<ul>
									<? $i = 1; $rows = Category::GetCategoryList(Entity::MUSIC, 0);
									
									foreach ($rows as $row) {
									
									?>
									<li> 
										<a href="<?=Yii::app()->createUrl('entity/list', array('entity' => Entity::GetUrlKey(Entity::MUSIC), 'cid' => $row['id'], 'title' => ProductHelper::ToAscii($row['title_en'])))?>"><?=ProductHelper::GetTitle($row)?></a>
									</li>
									<? 
									if ($i % 2 == 0) {
										
										echo '<div class="clearfix"></div>';
										
									}
									?>
									<? $i++; } ?>
									
								</ul>
								
							</div>
							
							<div class="span2">
								
								<img src="/new_img/banner.png" />
								
							</div>
							
						 </div>
					
					</li>
                    <li class="dd_box"><div class="click_arrow"></div><a class="dd"  href="<?= Yii::app()->createUrl('entity/list', array('entity' => Entity::GetUrlKey(Entity::PERIODIC))); ?>"><?= $ui->item("A_GOTOPEREODICALS"); ?></a>
					
					<div class="dd_box_bg list_subcategs" style="left: -420px;">
							
							<div class="span10">
								
								<ul>
									<? $i = 1; $rows = Category::GetCategoryList(30, 0);
									
									foreach ($rows as $row) {
									
									?>
									<li> 
										<a href="<?=Yii::app()->createUrl('entity/list', array('entity' => Entity::GetUrlKey(30), 'cid' => $row['id'], 'title' => ProductHelper::ToAscii($row['title_en'])))?>"><?=ProductHelper::GetTitle($row)?></a>
									</li>
									<? 
									if ($i % 2 == 0) {
										
										echo '<div class="clearfix"></div>';
										
									}
									?>
									<? $i++; } ?>
									
								</ul>
								
							</div>
							
							<div class="span2">
								
								<img src="/new_img/banner.png" />
								
							</div>
							
						 </div>
					
					</li>

                    <li class="dd_box"><div class="click_arrow"></div>
                        <a href="javascript:;" class="dd"><?= $ui->item('A_NEW_MORE'); ?></a>
                        <div class="dd_box_bg"><div class="shadow"><img src="/new_img/shadow.png" /></div>

                            <div class="tabs">
                                <ul>
                                    <!--<li class="active"><a href="<?= Yii::app()->createUrl('entity/list', array('entity' => Entity::GetUrlKey(Entity::VIDEO))); ?>" onclick="show_tab($(this), '<?= Entity::GetUrlKey(Entity::VIDEO) ?>'); return false;"><?= $ui->item("A_GOTOVIDEO"); ?></a></li>-->
                                    <li><a href="<?= Yii::app()->createUrl('entity/list', array('entity' => Entity::GetUrlKey(Entity::SOFT))); ?>" ><?= $ui->item("A_GOTOSOFT"); ?></a></li>
                                    <li><a href="<?= Yii::app()->createUrl('entity/list', array('entity' => Entity::GetUrlKey(Entity::MAPS))); ?>"><?= $ui->item("A_GOTOMAPS"); ?></a></li>
                                    <li><a href="<?= Yii::app()->createUrl('entity/list', array('entity' => Entity::GetUrlKey(Entity::PRINTED))); ?>"><?= $ui->item('A_NEW_PRINT_PRODUCTS'); ?></a></li>
                                    <li><?=$ui->item("A_GOTOPRINTED_6_V2");
                                        ?></li>
										
										<li><a href="<?=Yii::app()->createUrl('entity/authorlist', array('entity' => Entity::GetUrlKey(Entity::BOOKS))); ?>">
            <?=$ui->item('A_LEFT_AUDIO_AZ_PROPERTYLIST_AUTHORS'); ?>
        </a></li>
										
                                </ul>
                                <div style="clear: both"></div>
                            </div>
                            <div class="content">
                                <div class="list">
                                    <ul>
                                        <li><a href=""><img src="/new_img/splin.png" alt=""/></a></li>
                                        <li><a href=""><img src="/new_img/splin.png" alt=""/></a></li>
                                        <li><a href=""><img src="/new_img/splin.png" alt=""/></a></li>
                                        <li><a href=""><img src="/new_img/splin.png" alt=""/></a></li>
                                        <li><a href=""><img src="/new_img/splin.png" alt=""/></a></li>
                                    </ul>
                                    <ul>
                                        <li><a href=""><img src="/new_img/splin.png" alt=""/></a></li>
                                        <li><a href=""><img src="/new_img/splin.png" alt=""/></a></li>
                                        <li><a href=""><img src="/new_img/splin.png" alt=""/></a></li>
                                        <li><a href=""><img src="/new_img/splin.png" alt=""/></a></li>
                                        <li><a href=""><img src="/new_img/splin.png" alt=""/></a></li>
                                    </ul>
                                    <div style="clear: both"></div>
                                </div>
                            </div>

                        </div>
                    </li>
                    <li class="yellow_item"><a href="<?= Yii::app()->createUrl('site/static', array('page' => 'for-alle2')); ?>"><?=$ui->item('A_NEW_GOODS_2'); ?></a></li>
                    <li class="red_item"><a href="<?= Yii::app()->createUrl('offers/list'); ?>"><?=$ui->item('A_NEW_ITEMS_NEW'); ?></a></li>
                    <li class="red_item"><a href="<?= Yii::app()->createUrl('site/sale'); ?>"><?=$ui->item('A_NEW_DISCONT'); ?></a></li>
                    <li><a href="<?= Yii::app()->createUrl('ourstore'); ?>" class="home"><?=$ui->item('A_NEW_OURSTORE'); ?></a></li>
                </ul>
            </div>

        </div>

        </div>
		
		
		
        <?= $content; ?>


        <div class="footer">

            <div class="container">

                <div class="row">
                    <div class="span1">
                        <a href=""><img src="/new_img/logo_footer.png" alt="" /></a>
                        <div class="text">
                            <?=$ui->item('A_NEW_DESC_FOOTER'); ?>
                            <a href="<?= Yii::app()->createUrl('site/static', array('page' => 'aboutus')); ?>"><?=$ui->item('A_NEW_MORE_ABOUTUS'); ?></a>
                        </div>
                        <div class="contacts">

                            <div class="maps_ico">Ruslania Books Corp. Bulevardi 7, FI-00120 Helsinki, Finland </div>
                            <div class="phone_ico">+358 9 2727070</div>
                            <div class="mail_ico">generalsupports@ruslania.com</div>

                        </div>
                        <div class="social_icons">

                            <a href="https://vk.com/ruslaniabooks"><img src="/new_img/vk.png" alt="" /></a>
                            <a href="https://www.facebook.com/RuslaniaBooks/"><img src="/new_img/fb.png" alt="" /></a>
                            <a href="https://twitter.com/RuslaniaKnigi"><img src="/new_img/tw.png" alt="" /></a>
                            <!--<a href=""><img src="/new_img/gp.png" alt="" /></a>-->

                        </div>
                    </div>
                    <div class="span2">
                        <div class="span1">
                            <ul>
                                <li class="title"><?=$ui->item('A_NEW_ABOUTUS'); ?></li>

                                <li><a href="<?= Yii::app()->createUrl('site/static', array('page' => 'aboutus')); ?>"><?= $ui->item("A_ABOUTUS"); ?></a></li>
                                <li><a href="<?= Yii::app()->createUrl('site/static', array('page' => 'csr')); ?>"><?= $ui->item("A_CSR"); ?></a></li>
                                <li><a href="<?= Yii::app()->createUrl('site/static', array('page' => 'conditions')); ?>"><?= $ui->item("MSG_CONDITIONS_OF_USE"); ?></a></li>
                                <li><a href="<?= Yii::app()->createUrl('site/static', array('page' => 'conditions_order')); ?>"><?= $ui->item("YM_CONTEXT_CONDITIONS_ORDER_ALL"); ?></a></li>
                                <li><a href="<?= Yii::app()->createUrl('site/static', array('page' => 'conditions_subscription')); ?>"><?= $ui->item("YM_CONTEXT_CONDITIONS_ORDER_PRD"); ?></a></li>
                                <li><a href="<?= Yii::app()->createUrl('site/static', array('page' => 'contact')); ?>"><?= $ui->item("YM_CONTEXT_CONTACTUS"); ?></a></li>
                                <li><a href="<?= Yii::app()->createUrl('site/static', array('page' => 'legal_notice')); ?>"><?= $ui->item("YM_CONTEXT_LEGAL_NOTICE"); ?></a></li>
                                <li><a href="<?= Yii::app()->createUrl('site/static', array('page' => 'faq')); ?>"><?= $ui->item("A_FAQ"); ?></a></li>
                            </ul>
                        </div><div class="span1">
                            <ul>
                                <li class="title"><?=$ui->item('A_NEW_OURPREDL'); ?></li>

                                <li><a href="<?= Yii::app()->createUrl('site/sale'); ?>"><?= $ui->item("MENU_SALE"); ?></a></li>
                                <li><a href="<?= Yii::app()->createUrl('offers/list'); ?>"><?= $ui->item("RUSLANIA_RECOMMENDS"); ?></a></li>
                                <li><a href="<?= Yii::app()->createUrl('site/static', array('page' => 'offers_partners')); ?>"><?= $ui->item("A_OFFERS"); ?></a></li>
                                <li><a href="<?= Yii::app()->createUrl('site/static', array('page' => 'offers_partners')); ?>">– <?= $ui->item("A_OFFERS_PARTNERS"); ?></a></li>
                                <li><a href="<?= Yii::app()->createUrl('offers/special', array('mode' => 'uni')); ?>">– <?= $ui->item("A_OFFERS_UNIVERCITY"); ?></a></li>
                            </ul>
                        </div><div class="span1">
                            <ul>
                                <li class="title"><?=$ui->item('A_NEW_USERS'); ?></li>
								
								<?php if (Yii::app()->user->isGuest) : ?>
								
                                <li><a href="<?= Yii::app()->createUrl('site/register'); ?>"><?= $ui->item('A_REGISTER'); ?></a></li>
                                <li><a href="<?= Yii::app()->createUrl('site/login'); ?>"><?= $ui->item('A_SIGNIN'); ?></a></li>
                                <li><a href="<?= Yii::app()->createUrl('cart/view'); ?>"><?= $ui->item('A_SHOPCART'); ?></a></li>
                                
                                <!-- <li><a href="">Выход</a></li>-->
								<?php else : ?>
									<li><a href="<?=Yii::app()->createUrl('client/me'); ?>"><?= $ui->item('YM_CONTEXT_PERSONAL_MAIN'); ?></a></li>
									<li><a href="<?= Yii::app()->createUrl('cart/view'); ?>"><?= $ui->item('A_SHOPCART'); ?></a></li>
									<li><a href="/my/memo"><?=$ui->item('A_NEW_MY_FAVORITE'); ?></a></li>
									<li><a href="<?= Yii::app()->createUrl('site/logout'); ?>"><?= $ui->item('YM_CONTEXT_PERSONAL_LOGOUT'); ?></a></li>
								<?endif;?>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="row payment">

                    <div class="span1">
                        <img src="https://img.paytrail.com/?id=34135&type=horizontal&cols=18&text=0&auth=b6c2c7566147a60e" width="770" alt="" />
                    </div>
                    <div class="span2">

                        <img src="/new_img/payment2.png" alt="" />
						<!-- <img src="https://seal.thawte.com/getthawteseal?at=0&sealid=1&dn=RUSLANIA.COM&lang=en&gmtoff=-180" alt="" /> -->
                        <img src="/new_img/payment3.png" alt="" />
                        <img src="/new_img/buyer_protection.jpg" alt="" />
                        <img src="/new_img/payment4.png" alt="" />

                    </div>

                </div>

                <div class="copyright">

                    2017 © <b>Ruslania</b> - All rights Reserved

                </div>

            </div>

        </div>


    </body>
</html>
