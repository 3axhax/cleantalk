<div>
    <table cellspacing="2" cellpadding="5" brequest="0" width="100%">
        <tbody>
        <tr>
            <td valign="top" rowspan="5" class="maintxt"><img width="27" height="29" src="/pic1/order_ico.gif"></td>
        </tr>
        <tr>
            <td width="100%" valign="top" class="maintxt">

                <b><?=sprintf($ui->item("REQUEST_MSG_NUMBER"), $request['id']);; ?></b>
                
                    <?php if(!empty($request['notes'])) : ?>
                        <div class="mbt10">
                            <?=$ui->item('MSG_PERSONAL_REQUEST_USER_COMMENTS'); ?>: <?=nl2br(CHtml::encode($request['notes'])); ?>
                        </div>
                    <?php endif; ?>

                <br/>
                <br/>
                <table cellspacing="1" cellpadding="5" brequest="0" width="100%" class="cart1">
                    <tbody>
                    <tr>
                        <td width="70%" class="cart1header1"><?=$ui->item("CART_COL_TITLE"); ?></td>
                        <td width="10%" class="cart1header1 center"><?=$ui->item("CART_COL_QUANTITY"); ?></td>
                    </tr>

                    <?php foreach($request['Items'] as $item) : ?>

                        <tr>
                            <td class="cart1contents1"><a class="maintxt"
                                                          href="<?=ProductHelper::CreateUrl($item); ?>"><?=ProductHelper::GetTitle($item); ?></a></td>
                            <td class="cart1contents1 center"><?=$item['quantity']; ?></td>
                        </tr>

                    <?php endforeach; ?>
                    </tbody>
                </table>


                <table cellspacing="1" cellpadding="5" style="margin-top: 10px" class="cart1">
                    <tbody>
                    <tr>
                        <td class="cart1contents1b" colspan="2"><?=$ui->item("REQUEST_MSG_HISTORY"); ?></td>
                    </tr>
                    <tr>
                        <td class="cart1header1"><?=$ui->item("REQUEST_MSG_HISTORY_DATE"); ?></td>
                        <td class="cart1header1"><?=$ui->item("REQUEST_MSG_HISTORY_ACTION"); ?></td>
                    </tr>
                    <?php foreach($request['States'] as $state) : ?>
                        <tr>
                            <td class="cart1contents1"><?=$state['timestamp']; ?></td>
                            <td class="cart1contents1"><?=$ui->item("REQUEST_MSG_STATE_".$state['state']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </td>
        </tr>
        </tbody>
    </table>
</div>