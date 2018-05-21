<table width="100%" cellspacing="0" cellpadding="0" border="0" style="vertical-align:top">
    <tr>
        <td class="leftmnu" width="20%" valign="top">
            <?php $this->renderPartial('/entity/_left_text'); ?>
            <?php $this->renderPartial('/site/login_form', array('model' => new User)); ?>
        </td>
        <td valign="top" style="padding: 5px;">
            <!-- content -->
            <?php $this->widget('TopBar', array('breadcrumbs' => $this->breadcrumbs)); ?>
            <!-- /content -->


            <?php
            $lang = 'en';
            switch(Yii::app()->language)
            {
                case 'ru':
                case 'rut' : $lang = 'ru'; break;
                case 'fi' : $lang = 'fi'; break;
            }
            ?>

            <div class="sale_header">
                Ruslania - 30 vuotta alansa huipulla!
            </div>

            <div class="sale_text">
                Tilaa netistä tai tule kirjakauppaan. Olemme Helsingissä, Bulevardi 7. Puh 09 2727070<br/>
                Klikkaa sinua kiinnostavan osaston kohdalla ja pääset suoraan selaamaan suosittuja tuotteita.
            </div>
            
            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td width="50%" class="landing_right"><a href="/printed/bycategory/43/matreshki?avail=1&language=fi"><img src="/pic1/landing/Maatuskat.jpg" alt=""/></a></td>
                    <td width="50%" class="landing_left"><a href="/printed/bycategory/2/postersl?avail=1&language=fi"><img src="/pic1/landing/Julisteet.jpg " alt=""/></a></td>
                </tr>
                <tr>
                    <td width="50%" class="landing_right"><a href="/printed/bypublisher/12300/studiya-upakovki?avail=1&language=fi"><img src="/pic1/landing/Puuvillakassit.jpg" alt=""/></a></td>
                    <td width="50%" class="landing_left"><a href="/books/bycategory/230/russian-literature-in-finnish?avail=1&language=fi"><img src="/pic1/landing/Venäläinen_kirjallisuus.jpg" alt=""/></a></td>
                </tr>
                <tr>
                    <td width="50%" class="landing_right"><a href="/books/bycategory/68/venajan-kielen-oppikirjoja?avail=1&language=fi"><img src="/pic1/landing/Venäjän_kielen_oppikirjoja.jpg" alt=""/></a></td>
                    <td width="50%" class="landing_left"><a href="/printed/bycategory/3/postcard?avail=1&language=fi"><img src="/pic1/landing/Postikortit.jpg" alt=""/></a></td>
                </tr>
            </table>

            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td width="50%" class="landing_right"><a href="/for-alle2?language=fi"><img src="/pic1/landing/Tuotteet_enintään_2_euroa.jpg" alt=""/></a></td>
                    <td width="50%" class="landing_left"><a href="/for-fs?language=fi"><img src="/pic1/landing/Tuotteet_postikuluitta.jpg" alt=""/></a></td>
                </tr>
            </table>


            <div class="sale_text">
                Musiikkia:
            </div>

            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td width="50%" class="landing_right"><a href="/music/byperformer/96301/hvorostovski-dmitri?avail=1&language=fi"><img src="/pic1/landing/Hvorostovsky2.jpg" alt=""/></a></td>
                    <td width="50%" class="landing_left"><a href="/music/byperformer/96398/okean-elzi?avail=1&language=fi"><img src="/pic1/landing/Okean_Elzi.jpg" alt=""/></a></td>
                </tr>
                <tr>
                    <td width="50%" class="landing_right"><a href="/music/byperformer/96142/vysotski-vladimir?avail=1&language=fi"><img src="/pic1/landing/Vysotsky.jpg" alt=""/></a></td>
                    <td width="50%" class="landing_left"><a href="/music/byperformer/96158/pugacheva-alla?avail=1&language=fi"><img src="/pic1/landing/Pugacheva.jpg" alt=""/></a></td>
                </tr>

                <tr>
                    <td width="50%" class="landing_right">
                        <ul class="landing">
                        <li><a href="/music/bycategory/73/cd-russian-rock-alternative-music?avail=1&language=fi">Rockmusiikkia</a></li>
                        <li><a href="/music/bycategory/6/classical-music?avail=1&language=fi">Klassista musiikkia</a></li>
                        <li><a href="/music/bycategory/2/russian-pop-music?avail=1&language=fi">Popmusiikkia</a></li>
                        <li><a href="/music/bycategory/4/jazz?avail=1&language=fi">Jazz</a></li>
                        </ul>
                    </td>
                    <td width="50%" class="landing_left">
                        <ul class="landing">
                            <li><a href="/music/bycategory/28/russian-music-military-historical-songs?avail=1&language=fi">Military</a></li>
                            <li><a href="/music/bycategory/78/heavy-metal?avail=1&language=fi">Heavy metal</a></li>
                            <li><a href="/music/bycategory/71/electronic-dance-music-trance-house-soul-techno-chill-out?avail=1&language=fi">Disco</a></li>
                            <li><a href="/music/bycategory/24/russian-music-hits-of-the-old-days?avail=1&language=fi">Nostagia</a></li>
                        </ul>

                    </td>
                </tr>
            </table>


            <div class="sale_text">
                Elokuvat:
            </div>

            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td width="50%" class="landing_right"><a href="/video/bysubtitle/2/anglijskij?sort=12&avail=1"><img src="/pic1/landing/Elokuvat%20Englanninkielinen%20tekstitys.jpg" alt=""/></a></td>
                    <td width="50%" class="landing_left"><a href="/video/bysubtitle/8/finskij?sort=12&avail=1"><img src="/pic1/landing/Elokuvat%20Suomenkielinen%20tekstitys.jpg" alt=""/></a></td>
                </tr>
            </table>
            
            <div class="sale_text">
                <img src="/pic1/landing/bandana.jpg" alt="" width="400"/><br/><br/>
                Jokaiselle tilaajalle ilmainen Ruslanian 30-vuotistuubihuivi!<br/> 
                Netissä tilatessasi mainitse "tilausta koskevissa toivomuksissa" 
                "Ruslanian kesä 2016".
                <br/>
                Myymälässä riittää, kun mainitset kampanjasta kassalla tai tulostat
                nettisivun mukaasi.
            </div>


                <div class="sale_text">
                Kirjakauppa Ruslania, Bulevardi 7, 00120 Helsinki<br/>
                Sähköposti asiakaspalvelu@ruslania.com<br/>
                Nettikauppa Ruslania.com<br/>
                Myymälä on avoinna arkisin 9-18 ja lauantaisin 10-16.<br/>
                Sunnuntaisin suljettu.<br/>

                Yhteystiedot: <a href="http://ruslania.com/contact?language=fi">http://ruslania.com/contact?language=fi</a>

            </div>

        </td>
        <td width="220" valign="top" style="padding-left: 10px; padding-right: 10px; padding-top: 10px;">

        </td>
    </tr>

</table>