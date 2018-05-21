<?php KnockoutForm::RegisterScripts(); ?>

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

                <?php $this->renderPartial('_order_where_now', array('step' => 3)); ?>
                <?php $this->renderPartial('_noneditable_cart', array('cart' => $cartItems, 'deliveryAddress' => $deliveryAddress)); ?>

                <?=CHtml::beginForm('/cart/confirmorder', 'POST'); ?>
                    <input type="hidden" name="delivery_address_id" value="<?=$deliveryAddress['address_id']; ?>"/>
                    <input type="hidden" name="billing_address_id" value="<?=$billingAddress['address_id']; ?>"/>
                    <input type="hidden" name="mandate" value="<?=md5(uniqid(rand(), true)); ?>" />
                    <input type="hidden" name="check" value="<?=$cartCheckNumber; ?>" />
                    <table width="100%" cellspacing="0" cellpadding="7" border="0">
                        <tbody>
                        <tr>
                            <td style="font-weight: bold; background-color: #F6F6F6; vertical-align: middle"
                                class="maintxt"><img width="57" height="38" alt="3.1" src="/pic1/oph2_31.gif"></td>
                            <td width="100%"
                                style="font-weight: bold; background-color: #F6F6F6; vertical-align: middle"
                                class="maintxt">Выберите способ доставки Вашего заказа:
                            </td>
                        </tr>
                        <tr>
                            <td width="100%" colspan="2">
                                <table width="100%" cellspacing="0" cellpadding="5" border="0">
                                    <tbody>
                                    <?php foreach ($deliveryClasses as $cls) : ?>
                                    <tr>
                                        <td class="maintxt">

                                            <?=CHtml::radioButton('delivery_type_id', $dtid == $cls['id'], array('class' => 'delivery',
                                                                                                          'value' => $cls['id'],
                                                                                                          'data-price' => $cls['DeliveryPrice'],
                                                                                                          'id' => 'idt'.$cls['id'])); ?>
                                            <label for="idt<?=$cls['id']; ?>"><b><?=CHtml::encode($cls['Title']); ?></b></label>
                                            (<?=$ui->item("ORDER_DELIVERY_CLASS_TIME"); ?>: <?=$cls['delivery_time']; ?>
                                            дня)
                                        </td>
                                    </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        </tbody>
                    </table>

                    <table width="100%" cellspacing="0" cellpadding="7" border="0">
                        <tbody>
                        <tr>
                            <td style="font-weight: bold; background-color: #F6F6F6; vertical-align: middle"
                                class="maintxt"><img width="74" height="38" alt="3.1" src="/pic1/oph2_32.gif"></td>
                            <td width="100%"
                                style="font-weight: bold; background-color: #F6F6F6; vertical-align: middle"
                                class="maintxt"><?=$ui->item("MSG_CHOOSE_PAYMENT_TYPE"); ?>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <table width="100%" cellspacing="0" cellpadding="5" border="0">
                                    <tbody>
                                    <?php foreach ($paymentList as $p) : ?>
                                    <tr>
                                        <td nowrap="" class="maintxt">

                                            <?=CHtml::radioButton('payment_type_id', ($pid == $p['id']), array('class' => 'payment',
                                                                                                    'value' => $p['id'],
                                                                                                    'id' => 'ipt'.$p['id'])); ?>
                                            <label for="ipt<?=$p['id']; ?>"
                                                   style="position: relative; top: -3px;"><b><?=$p['Title']; ?></b></label>
                                        </td>
                                        <td width="100%" class="maintxt">
                                            <?=$p['Comment']; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <div class="itemsep1"><img width="1" height="1" border="0"
                                                                       src="/pic/null.gif"></div>
                                        </td>
                                    </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        </tbody>
                    </table>


                    <table width="100%" cellspacing="0" cellpadding="7" border="0">
                        <tbody>
                        <tr>
                            <td style="font-weight: bold; background-color: #F6F6F6; vertical-align: middle"
                                class="maintxt"><img width="73" height="38" alt="3.3" src="/pic1/oph2_33.gif"></td>
                            <td width="100%"
                                style="font-weight: bold; background-color: #F6F6F6; vertical-align: middle"
                                class="maintxt">Внимание!
                            </td>
                        </tr>
                        <tr>
                            <td style="padding: 15px" class="maintxt" colspan="2"><input type="checkbox"
                                                                                         id="inshop" value="1"
                                                                                         name="is_reserved" />
                                <label style="cursor: hand" for="inshop">Выберите этот пункт, если хотите зарезервировать
                                    заказ и выкупить его за наличный расчет в магазине "Руслания" в Хельсинки в течение
                                    7 дней. Наш адрес можно посмотреть в разделе <a target="new"
                                                                                    href="http://www.ruslania.com/context-584.html">Контакты</a></label>
                            </td>
                        </tr>
                        </tbody>
                    </table>


                    <table width="100%" cellspacing="0" cellpadding="7" border="0">
                        <tbody>
                        <tr>
                            <td style="font-weight: bold; background-color: #F6F6F6; vertical-align: middle"
                                class="maintxt"><img width="75" height="38" alt="3.4" src="/pic1/oph2_34.gif"></td>
                            <td width="100%"
                                style="font-weight: bold; background-color: #F6F6F6; vertical-align: middle"
                                class="maintxt">Пожелания в связи с заказом:
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-left: 15px; padding-top: 15px;" class="maintxt" colspan="2"><textarea
                                    name="notes"
                                    style="width: 40%; font-family: tahoma, verdana, sans-serif, arial; font-size: 95%"
                                    cols="55" rows="10"></textarea>
                            </td>
                        </tr>
                        </tbody>
                    </table>

                    <input type="image" />
                </form>
            </td>
        </tr>
    </table>
</div>
<script type="text/javascript">
    $(document).ready(function ()
    {
        $('input:radio.delivery').change(function ()
        {
            var val = parseFloat($(this).attr('data-price'));
            $('#DeliveryPrice').html(val.toFixed(2));
            var total = parseFloat($('#total').attr('data-total')) + val;
            $('#TotalPrice').html(total.toFixed(2));
        }).last().change();

        var payments = $('input:radio.payment');

        $('#inshop').change(function()
        {
            var val = $(this).is(':checked');
            //console.log('VAL = '  + val);
            if(val)
                payments.attr('disabled', 'disabled');
            else
                payments.removeAttr('disabled');

        })
    });
</script>
