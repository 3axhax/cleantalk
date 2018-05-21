<table width="100%" cellspacing="0" cellpadding="0" border="0" style="vertical-align:top" class="text">
    <tr>
        <td class="leftmnu" width="300" valign="top" align="center">
            <div style="padding: 0 5px 0 5px;" >
                <?php $this->renderPartial('/site/login_form', array('model' => new User, 'class' => 'login2')); ?>
                <?php $this->renderPartial('/entity/_left_text'); ?>
        </td>
        <td valign="top" style="padding: 5px;">
            <?php $this->widget('TopBar', array('breadcrumbs' => $this->breadcrumbs)); ?>

            <?php if(empty($newOid)) : ?>

            <div class="info-box warning">
                <h2><?=$ui->item('A_SAMPO_PAYMENT_DECLINED'); ?></h2>
                <p><?=$ui->item('MSG_PAYMENT_RESULTS_DECLINED_2'); ?><br/>
                   <?=$ui->item('MSG_PAYMENT_RESULTS_DECLINED_3'); ?>
                </p>
            </div>

            <p><?=$ui->item('ORDER_PAYMENT_TRY_AGAIN'); ?></p>

            <?php $this->renderPartial('/payment/_payment_choose2', array('order' => $order)); ?>

            <?php else : ?>

            <div class="info-box warning">
                <h2><?=$ui->item('A_SAMPO_PAYMENT_DECLINED'); ?></h2>
                <p>
                    <?=$ui->item('MSG_PAYMENT_RESULTS_DECLINED_LUOTTOKUNTA'); ?>
                </p>

                <p>
                    <a href="<?=Yii::app()->createUrl('client/pay', array('oid' => $newOid)); ?>">New order# <?=$newOid; ?></a>
                </p>
            </div>

            <?php endif; ?>


            <!-- /content -->
            <div class="clearBoth"></div>
        </td>
        <td width="220" valign="top" style="padding-left: 10px; padding-right: 10px; padding-top: 10px;">
&nbsp;
        </td>
    </tr>

</table>