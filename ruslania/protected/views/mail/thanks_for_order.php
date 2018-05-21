<p><?=Yii::app()->ui->item('MSG_PAYMENT_RESULTS_1'); ?></p>

<p><?=Yii::app()->ui->item('THANKS'); ?><br/>
<?=sprintf(Yii::app()->ui->item('ORDER_MSG_NUMBER'), $order['id']);?></p>

<p><?=Yii::app()->ui->item('ORDER_MSG_CONTENTS'); ?>:</p>

<p><?php foreach($items as $item): ?>
<?=$item['Title']; ?> (<?=Yii::app()->ui->item('CART_COL_QUANTITY'); ?>: <?=$item['Quantity']; ?>)<br/>
<?php endforeach; ?></p>

<p><?=Yii::app()->ui->item('CART_COL_TOTAL_FULL_PRICE'); ?>: <?=$order['full_price']; ?> <?=Currency::ToStr($order['currency_id']); ?></p>

<p><?=Yii::app()->ui->item('ORDER_MSG_DONE_EMAIL'); ?><br/>
<a href="<?=Yii::app()->createAbsoluteUrl('client/pay', array('oid' => $order['id'])); ?>"><?=Yii::app()->createAbsoluteUrl('client/pay', array('oid' => $order['id'])); ?></a>
</p>

<p>
Ruslania Books Oy<br/>
Bulevardi 7, 00120 HELSINKI, FINLAND<br/>
Tel. +358-9-272-70722<br/>
Fax +358-9-272-70720<br/>
ruslania@ruslania.com<br/>
Webstore <a href="ruslania.com">ruslania.com</a><br/>
<a href="http://www.facebook.com/RuslaniaBooks">www.facebook.com/RuslaniaBooks</a>
</p>