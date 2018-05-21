<div style="padding: 0 5px 0 5px;">
    <div class="center" style="color: #333333; font-weight: bold;">
        <div id="fb-root"></div>
        <div class="fb-like-box" data-href="http://www.facebook.com/pages/Ruslania/243348345708329" data-width="150" data-show-faces="false" data-stream="false" data-header="true"></div>


        <?php $lang = Yii::app()->language; ?>

        <div class="center">

        <?php if($lang == 'ru' || $lang == 'rut') : ?>

            <script type="text/javascript" src="//vk.com/js/api/openapi.js?116"></script>

            <!-- VK Widget -->
            <div id="vk_groups" style="margin: 0 auto;"></div>
            <script type="text/javascript">
                VK.Widgets.Group("vk_groups", {mode: 1, width: "220", height: "200", color1: 'FFFFFF', color2: '2B587A', color3: '5B7FA6'}, 93895665);
            </script>

            <div style="margin-top: 15px;">
            <a href="https://twitter.com/RuslaniaKnigi" class="twitter-follow-button" data-size="large" data-show-count="false" data-lang="ru">Читать @RuslaniaKnigi</a>
            <a href="https://twitter.com/RuslaniaMusic" class="twitter-follow-button" data-size="large" data-show-count="false" data-lang="ru">Читать @RuslaniaMusic</a>
            <a href="https://twitter.com/RuslaniaMovies" class="twitter-follow-button" data-size="large" data-show-count="false" data-lang="ru">Читать @RuslaniaMovies</a>
            </div>

        <?php elseif($lang == 'fi') : ?>

            <a href="https://twitter.com/RuslaniaKirjat" class="twitter-follow-button" data-size="large" data-show-count="false" data-lang="fi">@RuslaniaKirjat</a>
            <a href="https://twitter.com/RuslaniaMusic" class="twitter-follow-button" data-size="large" data-show-count="false" data-lang="fi">@RuslaniaMusic</a>
            <a href="https://twitter.com/RuslaniaMovies" class="twitter-follow-button" data-size="large" data-show-count="false" data-lang="fi">@RuslaniaMovies</a>

        <?php else : ?>

            <a href="https://twitter.com/RuslaniaMusic" class="twitter-follow-button" data-size="large" data-show-count="false" data-lang="en">Follow @RuslaniaMusic</a>
            <a href="https://twitter.com/RuslaniaMovies" class="twitter-follow-button" data-size="large" data-show-count="false" data-lang="en">Follow @RuslaniaMovies</a>

        <?php endif; ?>
        </div>

        <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>


        <a href="/offers">
        <div class="infobox-container">
            <div class="triangle-l"></div>
            <div class="triangle-r"></div>
            <div class="infobox">
                <h3><span><?=$ui->item('WE_RECOMMEND'); ?></span></h3>
                <p><?=$ui->item('WE_RECOMMEND_TEXT'); ?><p/>
            </div>
        </div>
        </a>

        <div style="padding-bottom: 15px; padding-top: 10px; padding-left: 10px; text-align: left">
            <?=sprintf($ui->item('MSG_OUR_SITE_IS_CERTIFIED'), Yii::app()->createUrl('site/static', array('page' => 'thawte'))); ?>
        </div>

        <div id="thawteseal" style="text-align:center;" title="Click to Verify - This site chose Thawte SSL for secure e-commerce and confidential communications.">
            <div><script type="text/javascript" src="https://seal.thawte.com/getthawteseal?host_name=ruslania.com&amp;size=L&amp;lang=en"></script></div>
        </div>


        <div style="padding-bottom: 12px; padding-left: 10px; font-weight: bold; text-align: left; width: 173px;">
            <a href="<?=Yii::app()->createUrl('site/static', array('page' => 'safety')); ?>" class="maintxt1">
                <?=$ui->item('MSG_PAYMENTS_ARE_SECURE'); ?></a>
        </div>


        <div style="padding-bottom: 5px"><a
                href="<?=Yii::app()->createUrl('site/static', array('page' => 'paypal')); ?>"><img
                    src="/pic1/verification_seal.gif" border="0"><br/>
            <?=$ui->item('MSG_WHAT_IS_PAYPAL'); ?></a></div>
<!--        <div style="padding-bottom: 3px"><img-->
<!--                    src="/pic1/sclogo_104x56.gif" width="104" height="56" alt="MasterCard SecureCode" border="0" />-->
<!--        </div>-->
<!---->
<!---->
<!--        <div style="padding-bottom: 8px"><img-->
<!--                    src="/pic1/visa_verified_logo_3.gif" width="115" height="66" alt="Verified by VISA"-->
<!--                    border="0"></div>-->

<!--        <div style="padding-bottom: 8px"><img-->
<!--                src="/pic1/moneygram.jpg" alt="Moneygram"-->
<!--                border="0"></div>-->

<!--        <div style="padding-bottom: 15px"><img src="/pic1/amex_logo_100x86.gif"-->
<!--                                                                             width="100" height="86"-->
<!--                                                                             alt="American Express"-->
<!--                                                                             border="0">-->
<!--        </div>-->
        <div style="margin-top:10px; margin-bottom:15px;">
            <img src="/pic1/paytrail4.jpg"/></div>

    </div>
</div>


<script>(function(d)
{var js, id = 'facebook-jssdk'; if (d.getElementById(id)) {return;}
js = d.createElement('script'); js.id = id; js.async = true;
js.src = '//connect.facebook.net/en_US/all.js#xfbml=1';
d.getElementsByTagName('head')[0].appendChild(js);
}(document));</script>


