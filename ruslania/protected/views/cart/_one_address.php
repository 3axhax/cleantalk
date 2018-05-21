<tr>
    <?php $id = 'r'.uniqid(); ?>

    <td width="30" valign="top"><img width="30" height="36" class="va" src="/pic1/dad_p.gif"></td>
    <td width="100%" class="maintxt">
        <?php $type = $addr['type'] == Address::ORGANIZATION ? 'Организация' : 'Частное лицо'; ?>
        <b><?=$type; ?></b>: ,
        <br><?=$addr['country_str']; ?>, <?=$addr['postindex']; ?>, <?=$addr['city']; ?>, <?=$addr['streetaddress']; ?>
        <div class="mt5"><input id="<?=$id; ?>" type="radio" name="<?=$mode; ?>" class="<?=$mode; ?>Radio" style="vertical-align: middle;" value="<?=$addr['id']; ?>" name="">
        <label for="<?=$id; ?>" style="position: relative; top: -5px;" >Доставить заказ на этот адрес</label></div>
    </td>
</tr>
