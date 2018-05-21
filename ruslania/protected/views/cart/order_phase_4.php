<div id="AddrTable">
    <table width="100%" cellspacing="0" cellpadding="0" border="0" style="vertical-align:top">
        <tr>
            <td class="leftmnu" width="20%" valign="top">
                <div style="padding: 0 5px 0 5px;">
                    <?php $this->widget('MyPersonalMenu'); ?>
                    <?php $this->renderPartial('/site/login_form', array('model' => new User)); ?>
                </div>
            </td>
            <td valign="top" style="padding: 5px;">

                <?php $this->renderPartial('_order_where_now', array('step' => 4)); ?>
                <?php $this->renderPartial('_noneditable_cart', array('cart' => $cartItems, 'deliveryAddress' => $deliveryAddress)); ?>


                <table width="100%">
                    <tr>
                        <td width="50%" valign="top">
                            <?=$ui->item("ORDER_MSG_TOTAL_DATA"); ?>
                            <table width="100%" cellspacing="0" cellpadding="5" border="0">
                                <tbody>
                                <tr>
                                    <td class="maintxt"><b>Адрес доставки</b>:<br>Omelianenko Maxim, Vetelintie 5D 69, 00450,
                                        Helsinki, Finland
                                    </td>
                                </tr>
                                <tr>
                                    <td class="maintxt"><b>Способ доставки</b>:<br>Economy (3-10 дней)</td>
                                </tr>
                                <tr>
                                    <td class="maintxt"><b>Адрес плательщика</b>:<br>Vetelintie 5D 69, 00450, Helsinki, Finland</td>
                                </tr>
                                <tr>
                                    <td class="maintxt"><b>Заказчик</b>:<br>Omelianenko Maxim</td>
                                </tr>
                                <tr>
                                    <td class="maintxt"><b>Способ оплаты</b>:<br>Оплата кредитной или банковской карточкой Visa,
                                        MasterCard Debit/Credit или American Express (только в евро!)<br>
                                        Внимание! Если Ваш заказ в USD, то сумма будет пересчитана в EUR автоматически.
                                    </td>
                                </tr>
                                </tbody>
                            </table>

                            <?=CHtml::beginForm('/confirmorder', 'POST'); ?>
                            <form method="POST" action="/confirmorder">
                                <?=CHtml::hiddenField('did', $did); ?>
                                <?=CHtml::hiddenField('bid', $bid); ?>
                                <?=CHtml::hiddenField('dtid', $dtid); ?>
                                <?=CHtml::hiddenField('pid', $pid); ?>
                                <?=CHtml::hiddenField('ordnt', $ordnt); ?>
                                <?=CHtml::hiddenField('inshop', $inshop); ?>
                                <?=CHtml::hiddenField('ochk', $orderCheckNumber); ?>
                                <?=CHtml::hiddenField('mandate', $mandate); ?>

                                <input type="submit" value="Оформить заказ" />
                            </form>

                        </td>
                        <td width="50%" valign="top">
                            <div class=notes1header>
                                <?=$ui->item("A_LEFT_PAY_ATTENTION"); ?>
                            </div>
                            <div class=notes1body>
                                <?=$ui->item("ORDER_MSG_SAVE_PAY_ATTENTION"); ?>
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</div>
