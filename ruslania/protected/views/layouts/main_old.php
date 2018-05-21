<?php $ui = Yii::app()->ui; ?><!DOCTYPE html>
<html>
<head>
    <title><?=$this->pageTitle; ?></title>
    <meta name="Keywords" content="">
    <META name="verify-v1" content="eiaXbp3vim/5ltWb5FBQR1t3zz5xo7+PG7RIErXIb/M="/>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
    <!--    <script language="JavaScript" type="text/javascript" src="/js/base.js"></script>-->
    <link rel="stylesheet" type="text/css" href="/css/ruslania.css"/>
    <link rel="stylesheet" type="text/css" href="/css/jquery-bubble-popup-v3.css"/>
    <link rel="stylesheet" type="text/css" href="/css/autocomplete.css"/>
    <link rel="stylesheet" type="text/css" href="/css/prettyPhoto.css"/>
    <link rel="stylesheet" type="text/css" href="/css/magnific-popup.css"/>
    <link href="/css/font-awesome/css/font-awesome.css " rel="stylesheet">
    <?php Yii::app()->clientScript->registerCoreScript('jquery'); ?>
    <?php Yii::app()->clientScript->registerScriptFile('/js/jquery-bubble-popup-v3.min.js'); ?>
    <?php Yii::app()->clientScript->registerScriptFile('/js/marcopolo.js'); ?>
    <?php Yii::app()->clientScript->registerScriptFile('/js/jquery.prettyPhoto.js'); ?>
    <?php Yii::app()->clientScript->registerScriptFile('/js/magnific-popup.js'); ?>
    <link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">
    <meta name="csrf" content="<?= MyHTML::csrf(); ?>"/>
    <base href="//<?=Yii::app()->params['Base']; ?>/">
</head>
<body bgcolor="#FFFFFF" topmargin="0" leftmargin="0" rightmargin="0" marginwidth="0" marginheight="0">

<table width="100%" border="0" cellpadding="0" cellspacing="0" class="text">
<tr>
<!-- logo part (sun) -->
<td class="topbg" width="165" valign="top">
    <a href="/"><img
            border="0" src="/pic1/toplogo3.gif" width="165" height="118" alt="Ruslania.com"></a>
</td>
<!-- main menus part -->
<td class="topbg" style="align: left;width: 100%;" valign="top">
    <table cellpadding="0" cellspacing="0" border="0" width="100%">
        <!-- main entities menu part -->
        <tr>
            <td background="/pic1/menu_bg_1.gif" valign="middle">
                <table height="31" border="0" cellspacing="0" cellpadding="0" width="100%">
                    <tr>
                        <td style="padding-left: 10px;" nowrap>
                            <a class="topenttxt"
                               href="<?= Yii::app()->createUrl('entity/list', array('entity' => Entity::GetUrlKey(Entity::BOOKS))); ?>"
                               title="<?= $ui->item("A_TITLE_GOTOBOOKS"); ?>"><?= $ui->item("A_GOTOBOOKS"); ?></a>
                        </td>
                        <td width="13%"></td>
                        <td align="left" nowrap>
                            <a class="topenttxt"
                               href="<?= Yii::app()->createUrl('entity/list', array('entity' => Entity::GetUrlKey(Entity::SHEETMUSIC))); ?>"
                               title="<?= $ui->item("A_TITLE_GOTOMUSICSHEETS"); ?>"><?= $ui->item("A_GOTOMUSICSHEETS"); ?></a>
                        </td>
                        <td width="13%"></td>
                        <td align="left" nowrap>
                            <a class="topenttxt"
                               href="<?= Yii::app()->createUrl('entity/list', array('entity' => Entity::GetUrlKey(Entity::PERIODIC))); ?>"
                               title="<?= $ui->item("A_TITLE_GOTOPEREODICALS"); ?>"><?= $ui->item("A_GOTOPEREODICALS"); ?></a>
                        </td>
                        <td width="13%"></td>
                        <td align="left" nowrap>
                            <a class="topenttxt"
                               href="<?= Yii::app()->createUrl('entity/list', array('entity' => Entity::GetUrlKey(Entity::MUSIC))); ?>"
                               title="<?= $ui->item("A_TITLE_GOTOMUSIC"); ?>"><?= $ui->item("Music catalog"); ?></a>
                        </td>
<!--                        <td width="13%"></td>-->
<!--                        <td align="left" nowrap>-->
<!--                            <a class="topenttxt"-->
<!--                               href="--><?//= Yii::app()->createUrl('entity/list', array('entity' => Entity::GetUrlKey(Entity::AUDIO))); ?><!--"-->
<!--                               title="--><?//= $ui->item("A_TITLE_GOTOAUDIO"); ?><!--">--><?//= $ui->item("A_GOTOAUDIO"); ?><!--</a>-->
<!--                        </td>-->
                        <td width="13%"></td>
                        <td align="left" nowrap>
                            <a class="topenttxt"
                               href="<?= Yii::app()->createUrl('entity/list', array('entity' => Entity::GetUrlKey(Entity::VIDEO))); ?>"
                               title="<?= $ui->item("A_TITLE_GOTOVIDEO"); ?>"><?= $ui->item("A_GOTOVIDEO"); ?></a>
                        </td>
                        <td width="13%"></td>
                        <td align="left" nowrap>
                            <a class="topenttxt"
                               href="<?= Yii::app()->createUrl('entity/list', array('entity' => Entity::GetUrlKey(Entity::SOFT))); ?>"
                               title="<?= $ui->item("A_TITLE_GOTOSOFT"); ?>"><?= $ui->item("A_GOTOSOFT"); ?></a>
                        </td>
                        <td width="13%"></td>
                        <td align="left" style="padding-right: 10px;" nowrap>
                            <a class="topenttxt"
                               href="<?= Yii::app()->createUrl('entity/list', array('entity' => Entity::GetUrlKey(Entity::MAPS))); ?>"
                               title="<?= $ui->item("A_TITLE_GOTOMAPS"); ?>"><?= $ui->item("A_GOTOMAPS"); ?></a>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <!-- submenu part -->
        <tr>
            <td height="57">
                <table border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td class="topmnutxt">
                            <!--
                            <a href="" class="topmnutxt">
                                                                </a><br>
                            -->
                            <a href="<?= Yii::app()->createUrl('cart/view'); ?>"
                               class="topmnutxt"><?= $ui->item('A_SHOPCART'); ?></a><br>
                            <?php if (Yii::app()->user->isGuest) : ?>
                                <a href="<?= Yii::app()->createUrl('site/login'); ?>"
                                   class="topmnutxt"><?= $ui->item('A_SIGNIN'); ?></a><br>
                                <a href="<?=Yii::app()->createUrl('site/register'); ?>" class="topmnutxt"><?= $ui->item('A_REGISTER'); ?></a>
                            <?php else : ?>
                                <a href="<?=Yii::app()->createUrl('client/me'); ?>" class="topmnutxt"><?= $ui->item('YM_CONTEXT_PERSONAL_MAIN'); ?></a><br>
                                <a href="<?= Yii::app()->createUrl('site/logout'); ?>"
                                   class="topmnutxt"><?= $ui->item('YM_CONTEXT_PERSONAL_LOGOUT'); ?></a>
                            <?php endif; ?>
                        </td>
                        <td class="topmnutxt">
                            <a href="<?= Yii::app()->createUrl('site/static', array('page' => 'faq')); ?>"
                               class="topmnutxt"><?= $ui->item("A_FAQ"); ?></a><br>
                            <a href="<?= Yii::app()->createUrl('site/static', array('page' => 'aboutus')); ?>"
                               class="topmnutxt"><?= $ui->item("A_ABOUTUS"); ?></a><br>
                            <a href="<?= Yii::app()->createUrl('site/static', array('page' => 'contact')); ?>"
                               class="topmnutxt"><?= $ui->item("YM_CONTEXT_CONTACTUS"); ?></a>
                        </td>
                        <td class="topmnutxt">
                            <a href="<?=Yii::app()->createUrl('site/sale'); ?>"
                               class="topmnutxt"><?= $ui->item("MENU_SALE"); ?></a><br>
                            <a href="<?= Yii::app()->createUrl('offers/list'); ?>"
                               class="topmnutxt"><?= $ui->item("RUSLANIA_RECOMMENDS"); ?></a><br>
                            <a href="<?= Yii::app()->createUrl('offers/special', array('mode' => 'fs')); ?>"
                               class="topmnutxt"><?= $ui->item("FREE_SHIPPING_OFFER"); ?></a>
                        </td>
                        <td class="topmnutxt" align="center" valign="middle" width="60">
                            <a href="<?= Yii::app()->createUrl('offers/special', array('mode' => 'alle2')); ?>">
                            <img src="/pic1/2e.png" width="50" height="50"/>
                            </a>
                        </td>
                        <td class="topmnutxt" align="center">

                        <a href="<?=Yii::app()->createUrl('site/static', array('page' => 'offers_partners')); ?>"
                           class="topmnutxt">
                            <?=$ui->item("A_OFFERS"); ?>&nbsp;<br/><?= $ui->item("A_OFFERS_PARTNERS"); ?></a>
                            <br/>
                            <a href="<?=Yii::app()->createUrl('offers/special', array('mode' => 'uni')); ?>" class="topmnutxt">
                                <?= $ui->item("A_OFFERS_UNIVERCITY"); ?></a>


<!--                            --><?//= $ui->item("A_OFFERS"); ?>
<!--                            <table cellpadding="0" cellspacing="0" border="0">-->
<!--                                <tr>-->
<!--                                    <td class="subtopmnutxt">-->
<!--                                        <a href="--><?//=Yii::app()->createUrl('offers/special', array('mode' => 'firms')); ?><!--" class="topmnutxt">-->
<!--                                            &ndash;&nbsp;--><?//= $ui->item("A_OFFERS_FRMS"); ?><!--</a><br>-->
<!--                                        <a href="--><?//=Yii::app()->createUrl('offers/special', array('mode' => 'lib')); ?><!--" class="topmnutxt">-->
<!--                                            &ndash;&nbsp;--><?//= $ui->item("A_OFFERS_LIBS"); ?><!--</a>-->
<!--                                    </td>-->
<!--                                    <td class="subtopmnutxt">-->
<!--                                        <a href="--><?//=Yii::app()->createUrl('offers/special', array('mode' => 'uni')); ?><!--" class="topmnutxt">-->
<!--                                            &ndash;&nbsp;--><?//= $ui->item("A_OFFERS_UNIVERCITY"); ?><!--</a><br>-->
<!--                                        <a href="--><?//=Yii::app()->createUrl('site/static', array('page' => 'offers_partners')); ?><!--" class="topmnutxt">-->
<!--                                            &ndash;&nbsp;--><?//= $ui->item("A_OFFERS_PARTNERS"); ?><!--</a><br>-->
<!--                                    </td>-->
<!--                                </tr>-->
<!--                            </table>-->
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <!-- language and currency select area -->
        <tr>

            <td background="/pic1/menu_bg_2.gif">
                <table cellspacing="0" cellpadding="0" border="0">
                    <tr>
                        <td height="30" style="padding-left: 5px; padding-right: 5px;" nowrap>
                            <img src="/pic/null.gif" width="1" height="1">
                            <a class="topmnu1txt" href="<?= MyUrlManager::RewriteCurrent($this, 'ru'); ?>">
                                <img src="/pic1/ru.gif" width="18" height="12" border="0" alt="ru"
                                     style="vertical-align: text-bottom;">
                                <?= $ui->item("A_LANG_RUSSIAN"); ?></a>
                            <img src="/pic/null.gif" width="3" height="1">
                            <a class="topmnu1txt" href="<?= MyUrlManager::RewriteCurrent($this, 'rut'); ?>">
                                <img src="/pic1/ru.gif" width="18" height="12" border="0" alt="ru"
                                     style="vertical-align: text-bottom;">
                                <?= $ui->item("A_LANG_TRANSLIT"); ?></a>
                            <img src="/pic/null.gif" width="3" height="1">
                            <a class="topmnu1txt" href="<?= MyUrlManager::RewriteCurrent($this, 'fi'); ?>">
                                <img src="/pic1/fi.gif" width="18" height="12" border="0" alt="fi"
                                     style="vertical-align: text-bottom;">
                                <?= $ui->item("A_LANG_FINNISH"); ?></a>
                            <img src="/pic/null.gif" width="3" height="1">
                            <a class="topmnu1txt" href="<?= MyUrlManager::RewriteCurrent($this, 'en'); ?>">
                                <img src="/pic1/us.gif" width="18" height="12" border="0" alt="us"
                                     style="vertical-align: text-bottom;">
                                <img src="/pic1/uk.gif" width="18" height="12" border="0" alt="uk"
                                     style="vertical-align: text-bottom;">
                                <?= $ui->item("A_LANG_ENGLISH"); ?></a>
                            <img src="/pic/null.gif" width="3" height="1">
                            <a class="topmnu1txt" href="<?= MyUrlManager::RewriteCurrent($this, 'de'); ?>">
                                <img src="/pic1/de.gif" width="18" height="12" border="0" alt="de"
                                     style="vertical-align: text-bottom;">
                                <?= $ui->item("A_LANG_GERMAN"); ?></a>
                            <img src="/pic/null.gif" width="3" height="1">
                            <a class="topmnu1txt" href="<?= MyUrlManager::RewriteCurrent($this, 'fr'); ?>">
                                <img src="/pic1/fr.gif" width="18" height="12" border="0" alt="fr"
                                     style="vertical-align: text-bottom;">
                                <?= $ui->item("A_LANG_FRENCH"); ?></a>
                            <img src="/pic/null.gif" width="3" height="1">
                            <a class="topmnu1txt" href="<?= MyUrlManager::RewriteCurrent($this, 'es'); ?>">
                                <img src="/pic1/es.gif" width="18" height="12" border="0" alt="es"
                                     style="vertical-align: text-bottom;">
                                <?= $ui->item("A_LANG_ESPANIOL"); ?></a>
                            <img src="/pic/null.gif" width="3" height="1">
                            <a class="topmnu1txt" href="<?= MyUrlManager::RewriteCurrent($this, 'se'); ?>">
                                <img src="/pic1/se.gif" width="18" height="12" border="0" alt="se"
                                     style="vertical-align: text-bottom;">
                                <?= $ui->item("A_LANG_SWEDISH"); ?></a>
                        </td>
                        <td style="padding-right: 10px;" nowrap>
                            <img src="/pic/null.gif" width="1" height="12">
                            <a class="topmnu1txt" style="font-weight: bold;">
                                <?= $ui->item("MSG_TOP_CHOOSE_CURRENCY"); ?></a>
                            <img src="/pic/null.gif" width="1" height="12">
                            <a class="topmnu1txt" href="<?= MyUrlManager::RewriteCurrency($this, Currency::EUR); ?>">
                                <?= $ui->item("MSG_TOP_CHOOSE_CURRENCY_CHANGE_TO_EUR"); ?></a>
                            <img src="/pic/null.gif" width="1" height="12">
                            <a class="topmnu1txt" href="<?= MyUrlManager::RewriteCurrency($this, Currency::USD); ?>">
                                <?= $ui->item("MSG_TOP_CHOOSE_CURRENCY_CHANGE_TO_USD"); ?></a>
                            <img src="/pic/null.gif" width="1" height="12">
                            <a class="topmnu1txt" href="<?= MyUrlManager::RewriteCurrency($this, Currency::GBP); ?>">
                                <?= $ui->item("MSG_TOP_CHOOSE_CURRENCY_CHANGE_TO_GBP"); ?></a>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</td>
<!-- "new" separator-->
<td width="9">
<!--    bgcolor="#AC0000" valign="bottom">-->
<!--    <img src="/pic1/new.gif" width="9" height="27" alt="new">-->
</td>
<!-- "new" menu -->
<td bgcolor="#9BA6B3" style="padding-left: 10px; vertical-align: top">
    <table cellpadding="0" cellspacing="0" border="0" height="100%">
        <tr>
            <td class="newmnutxt" height="31">
                <a class="topenttxt"
                   href="<?= Yii::app()->createUrl('entity/list', array('entity' => Entity::GetUrlKey(Entity::PRINTED))); ?>"
                   title="<?= $ui->item("A_TITLE_GOTOPRINTED"); ?>"><?= $ui->item("A_GOTOPRINTED"); ?></a>
            </td>
        </tr>
        <tr>
            <td>
                <table cellpadding="0" cellspacing="0" border="0">
                    <td class="newmnutxt">
                        <a
                            href="<?= Yii::app()->createUrl('entity/list', array('entity' => Entity::GetUrlKey(Entity::PRINTED))); ?>"
                            title="<?= $ui->item("A_TITLE_GOTOPRINTED"); ?>"><img
                                src="/pic1/gif_place.jpg" width="59" height="81" border="0"></a>
                    </td>
                    <td class="newmnutxt" class="txtsmall" nowrap align="left"
                        style="padding-left: 10px; text-decoration: none; color: white;">
                        <ul class="printed_list txtsmall">
                            <li>&mdash; <?= $ui->item("A_GOTOPRINTED_1_V2"); ?></li>
                            <li>&mdash; <?= $ui->item("A_GOTOPRINTED_2_V2"); ?></li>
                            <li>&mdash; <?= $ui->item("CANDIES"); ?></li>
                            <li>&mdash; <?= $ui->item("A_GOTOPRINTED_4_V2"); ?></li>
                            <li>&mdash; <?= $ui->item("A_GOTOPRINTED_5_V2"); ?></li>
                            <li>&mdash; <?= $ui->item("A_GOTOPRINTED_6_V2"); ?></li>
                        </ul>
                    </td>
                </table>
            </td>
        </tr>
    </table>
</td>
</tr>
<!-- top menu wnd !-->

<tr>
    <td colspan="4" valign="top" style="width: 100%;">
    <?= $content; ?>
    </td>
</tr>
</table>


<div id="footer" class="text">
    <table cellspacing=10 cellpadding=0 border=0>
        <tr>
            <td width=100% class=maintxt>
                <a class=maintxt
                   href="<?= Yii::app()->createUrl('site/static', array('page' => 'conditions')); ?>"><?= $ui->item("MSG_CONDITIONS_OF_USE"); ?></a>
                &nbsp;&nbsp;|
                <a class=maintxt
                   href="<?= Yii::app()->createUrl('site/static', array('page' => 'conditions_order')); ?>"><?= $ui->item("YM_CONTEXT_CONDITIONS_ORDER_ALL"); ?></a>
                &nbsp;&nbsp;|
                <a class=maintxt
                   href="<?= Yii::app()->createUrl('site/static', array('page' => 'conditions_subscription')); ?>"><?= $ui->item("YM_CONTEXT_CONDITIONS_ORDER_PRD"); ?></a>
                &nbsp;&nbsp;|
                <a class=maintxt
                   href="<?= Yii::app()->createUrl('site/static', array('page' => 'contact')); ?>"><?= $ui->item("YM_CONTEXT_CONTACTUS"); ?></a>
                &nbsp;&nbsp;|
                <a class=maintxt
                   href="<?= Yii::app()->createUrl('site/static', array('page' => 'legal_notice')); ?>"><?= $ui->item("YM_CONTEXT_LEGAL_NOTICE"); ?></a>
                &nbsp;&nbsp;|
                <a href="<?= Yii::app()->createUrl('site/static', array('page' => 'csr')); ?>" class="maintxt"><?= $ui->item("A_CSR"); ?></a>

                &nbsp;&nbsp;&copy; <?= date('Y'); ?>, Ruslania Books OY&nbsp;&nbsp;&nbsp;&nbsp;<a class=maintxt
                                                                                                  href="mailto:generalsupports@ruslania.com"
                                                                                                  title="mail to Ruslania Books"><?= $ui->item("A_MAIL_TO_RUSLANIA"); ?></a>
                &nbsp; <?=$ui->item('FOOTER_CONTACTS'); ?>
            </td>
            <td width=100% align=right class=maintxt>
                <a href="/#top" class=maintxt><img align=center border=0
                                                   src="/pic1/movetop.gif"><?= $ui->item("MSG_MOVE_TOP"); ?></a>
            </td>
        </tr>
    </table>
</div>

<div id="periodic-price-form" class="white-popup-block mfp-hide white-popup">
    <h2><?=$ui->item('PERIODIC_POPUP_HEADER'); ?></h2>
    <h4 id="formTitle"></h4>
    <h5 id="formMonths"></h5>
    <input type="hidden" name="eid" value=""/>
    <input type="hidden" name="iid" value=""/>
    <input type="hidden" name="qty" value=""/>

    <div class="periodic_choice">
        <div id="finPrice"></div>
        <button id="finSubscription"><?=$ui->item('PERIODIC_POPUP_FINLAND_BUTTON'); ?></button>
    </div>
    <div class="periodic_choice">
        <div id="worldPrice"></div>
        <button id="worldSubscription"><?=$ui->item('PERIODIC_POPUP_WORLD_BUTTON'); ?></button>
    </div>
    <div class="clearfix"></div>
</div>

<script type="text/javascript">

    function add2Cart(action, eid, iid, qty, type, $el)
    {
        var $parent = $el.closest('.to_cart');
        var csrf = $('meta[name=csrf]').attr('content').split('=');

        $el.CreateBubblePopup();
        var post =
        {
            entity: eid,
            id: iid,
            quantity: qty,
            type: type
        };
        post[csrf[0]] = csrf[1];
        var bubble_popup_id = $el.GetBubblePopupID();
        var seconds_to_wait = 10;

        $el.ShowBubblePopup({

            align: 'center',
            mouseOut: 'show',
            alwaysVisible: false,
            innerHtml: '<p><?=$ui->item('AJAX_IN_PROGRESS'); ?></p>',

            innerHtmlStyle:{
                color:'#666666',
                'text-align':'left'
            },

            themeName: 	'black',
            themePath: 	'/css/jquerybubblepopup-themes'

        }, false);
        //save_options = false; it will use new options only on click event, it does not overwrite old options.

        $el.FreezeBubblePopup();

        $.post('/cart/' + action, post, function (json)
        {
            $el.ShowBubblePopup({

                align: 'center',
                innerHtml: json.msg +
                '<p><a href="#"><?=$ui->item('MSG_PAYMENT_RESULTS_CLOSE_WIN'); ?></a> (<span class="countdown">'+seconds_to_wait+'</span>)</p>',

                innerHtmlStyle:{
                    color:'#666666',
                    'text-align':'left'
                },
                mouseOut: 'show',
                alwaysVisible: false,

                themeName: 	'blue',
                themePath: 	'/css/jquerybubblepopup-themes'

            }, false); //save_options = false; it will use new options only on click event, it does not overwrite old options.

            if(json.already)
            {
                $parent.find('span.already-in-cart').html(json.already + '<br/>');
            }

            $('#'+bubble_popup_id+' a:last').click(function()
            {
                $el.HideBubblePopup();
                return false;
            });

            function doCountdown()
            {
                var timer = setTimeout(function()
                {
                    seconds_to_wait--;
                    if($('#'+bubble_popup_id+' span.countdown').length>0) $('#'+bubble_popup_id+' span.countdown').html(seconds_to_wait);
                    if(seconds_to_wait > 0) doCountdown();
                    else $el.HideBubblePopup();
                }, 1000);
            }
            doCountdown();
        }, 'json');
    }

    function padZero(s, len, c)
    {
        var c= c || '0';
        var s = s.toString();
        while(s.length< len) s= c+ s;
        return s;
    }

    var nowPlaying = null;
    var nowInterval = null;
    $(document).ready(function ()
    {
        var $finSubButton = $('#finSubscription');
        var $worldSubButton = $('#worldSubscription');
        var $formDiv = $('#periodic-price-form');
        var $formEid = $formDiv.find('input[name="eid"]');
        var $formIid = $formDiv.find('input[name="iid"]');
        var $formQty = $formDiv.find('input[name="qty"]');

        $finSubButton.click(function()
        {
           $.magnificPopup.close();
           add2Cart('add', $formEid.val(), $formIid.val(), $formQty.val(), <?=Cart::FIN_PRICE; ?>, $finSubButton.data());
        });

        $worldSubButton.click(function()
        {
            $.magnificPopup.close();
            add2Cart('add', $formEid.val(), $formIid.val(), $formQty.val(), <?=Cart::WORLD_PRICE; ?>, $worldSubButton.data());
        });

        var elems = $('a.cart-action');

        $('select.periodic').change(function ()
        {
            var $el = $(this);
            var cart = $el.closest('.to_cart');

            var worldpmonthVat0 = cart.find('input.worldmonthpricevat0').val();
            var worldpmonthVat = cart.find('input.worldmonthpricevat').val();
            var finpmonthVat0 = cart.find('input.finmonthpricevat0').val();
            var finpmonthVat = cart.find('input.finmonthpricevat').val();

            var nPriceVat = (worldpmonthVat * $el.val()).toFixed(2);
            var nPriceVat0 = (worldpmonthVat0 * $el.val()).toFixed(2);

            var nPriceFinVat = (finpmonthVat * $el.val()).toFixed(2);
            var nPriceFinVat0 = (finpmonthVat0 * $el.val()).toFixed(2);

            cart.find('.periodic_world .pwvat').html(nPriceVat + ' <?=Currency::ToSign(); ?>');
            cart.find('.periodic_world .pwovat').html(nPriceVat0 + ' <?=Currency::ToSign(); ?>');

            cart.find('.periodic_fin .pwvat').html(nPriceFinVat + ' <?=Currency::ToSign(); ?>');
            cart.find('.periodic_fin .pwovat').html(nPriceFinVat0 + ' <?=Currency::ToSign(); ?>');

            cart.find('a.add').attr('data-quantity', $el.val());
        });

        $('select.selquantity').change(function ()
        {
            var $el = $(this);
            var cart = $el.closest('.to_cart');
            var desiredQuantity = parseInt($el.val()) || 1;
            cart.find('a.add').attr('data-quantity', desiredQuantity);
        });

        elems.click(function()
        {
            var $el = $(this);
            var $parent = $el.closest('.to_cart');

            var entity = $el.attr('data-entity');

            if(entity == <?=Entity::PERIODIC; ?>)
            {
                var $finPrice = $('#finPrice');
                var $worldPrice = $('#worldPrice');

                var $itemFinBlock = $parent.find('.periodic_fin');
                var $itemWorldBlock = $parent.find('.periodic_world');

                if($itemWorldBlock.length && $itemFinBlock.length)
                {
                    $formEid.val($el.attr('data-entity'));
                    $formIid.val($el.attr('data-id'));
                    $formQty.val($el.attr('data-quantity'));
                    $finSubButton.data($el);
                    $worldSubButton.data($el);

                    // show dialog only if we have different prices
                    var $formTitle = $('#formTitle');
                    var $formMonths = $('#formMonths');
                    var $title = $parent.closest('li').find('a.title');
                    $formTitle.html($title.html());

                    var $select = $parent.find('select.periodic');
                    $formMonths.html($(':selected', $select).text());

                    var finHtml = $itemFinBlock.html();
                    $finPrice.html(finHtml);
                    var worldHtml = $itemWorldBlock.html();
                    $worldPrice.html(worldHtml);
                    $.magnificPopup.open({
                        items: {
                            src: '#periodic-price-form', // can be a HTML string, jQuery object, or CSS selector
                            type: 'inline'
                        }
                    });
                    return false;
                }
            }

            add2Cart($el.attr('data-action'),
                $el.attr('data-entity'),
                $el.attr('data-id'),
                $el.attr('data-quantity'),
                null,
                $el
            );

            return false;
        });

        $.fn.prettyPhoto({social_tools: false});

        $('img.lookinside.liimg').click(function()
        {
            var $this = $(this);
            var images = [];
            if($this.attr('data-images') != '')
            {
                images = $this.attr('data-images').split('|');
                if(images.length > 0) $.prettyPhoto.open(images, [], []);
            }

//            var pdf = $this.attr('data-pdf').split('|');
//            if(pdf.length > 0)
//            {
//                var iid = $this.attr('data-iid');
//                $('#staticfiles'+iid).fadeIn();
//            }
        });

        $('img.lookinside.audiostop').click(function()
        {
            if(nowPlaying != null) nowPlaying.pause();
        });

        $('img.lookinside.liaudio').click(function()
        {
            var el = this;
            if (el.mp3)
            {
                if(el.mp3.paused) el.mp3.play();
                else el.mp3.pause();
            }
            else
            {
                var audios = $(el).attr('data-audio').split('|');
                var id = $(el).attr('data-iid');
                if(audios.length > 0)
                {
                    el.mp3 = new Audio(audios[0]);
                    if(nowPlaying != el && nowPlaying != null) nowPlaying.pause();
                    el.mp3.play();
                    nowPlaying = el.mp3;

                    setInterval(function()
                    {
                        if(el.mp3)
                        {
                            var curMinutes = padZero(Math.floor(el.mp3.currentTime / 60), 2);
                            var curSeconds = padZero(Math.ceil(el.mp3.currentTime - curMinutes * 60), 2);
                            $('#audionow'+id).html(curMinutes+':'+curSeconds);

                            var totMinutes = padZero(Math.floor(el.mp3.duration / 60), 2);
                            var totSeconds = padZero(Math.ceil(el.mp3.duration - totMinutes * 60), 2);

                            $('#audiototal' + id).html(totMinutes + ':' + totSeconds);
                        }

                    }, 1000);

                    $('#audioprog'+id).fadeIn();
                }
            }
        });
    });
</script>
<?php

    $this->widget('GoogleAnalytics', array('account' => 'UA-27359361-1', 'domain' => 'ruslania.com'));
?>
</body>
</html>
