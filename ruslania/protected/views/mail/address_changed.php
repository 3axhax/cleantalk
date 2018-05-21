<p>Пользователь сайта с ID: <?=$uid; ?> сменил адрес.</p>
<p>Старый адрес:</p>
<p>
<?=$old['receiver_first_name']; ?> <?=$old['receiver_last_name']; ?><br/>
<?=$old['streetaddress']; ?><br/>
<?=$old['postindex']; ?> <?=$old['city']; ?> <br/>
<?=$old['country_name']; ?>
</p>

<p>Новый адрес:</p>
<p>
<?=$new['receiver_first_name']; ?> <?=$new['receiver_last_name']; ?><br/>
<?=$new['streetaddress']; ?><br/>
<?=$new['postindex']; ?> <?=$new['city'];?><br/>
<?=$new['country_name']; ?>
</p>

<?php if(count($all) > 1) : ?>
<p>У клиента есть еще адреса доставки: </p>
<p>
    <ul>
    <?php foreach($all as $addr) : ?>
    <li>
        <?=$addr['receiver_first_name']; ?> <?=$addr['receiver_last_name']; ?><br/>
        <?=$addr['streetaddress']; ?><br/>
        <?=$addr['postindex']; ?> <?=$addr['city']; ?><br/>
        <?=$addr['country_name']; ?>
    </li>
    <?php endforeach; ?>
    </ul>
</p>

<?php endif; ?>