<table width="100%" cellspacing="0" cellpadding="0" border="0" style="vertical-align:top">
    <tr>
        <td class="leftmnu" width="20%" valign="top">

            <?php $this->renderPartial('/site/_me_left'); ?>

        </td>
        <td valign="top" style="padding: 5px;" class="text">
            <!-- content -->
            <?php $this->widget('TopBar', array('breadcrumbs' => $this->breadcrumbs)); ?>

            <?php if(Yii::app()->user->hasFlash('order')) : ?>
                <div class="info-box information">
                    <?=Yii::app()->user->getFlash('order'); ?>
                </div>
            <?php endif; ?>

            <?php if(!$order['is_reserved']) : ?>
                <?php $this->renderPartial('/payment/_payment_choose2', array('order' => $order, 'isPaid' => $isPaid)); ?>
            <?php else : ?>
                <div class="info-box information">
                    <?=$ui->item('IN_SHOP_NOT_READY'); ?>
                </div>
                <?php $this->renderPartial('/client/_one_order', array('order' => $order, 'onlyContent' => true,
                                                                       'class' => 'bordered',
                                                                       'enableSlide' => false)); ?>
            <?php endif; ?>
            <!-- /content -->
        </td>
        <td width="220" valign="top" style="padding-left: 10px; padding-right: 10px; padding-top: 10px;">
            <?php $this->widget('Banners', array('entity' => 'index')); ?>
        </td>
    </tr>

</table>

