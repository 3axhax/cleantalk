<?php $this->widget('TopBar', array('breadcrumbs' => $this->breadcrumbs)); ?>

<div class="container cabinet">

<div class="row">
        <div class="span10">

            <?php if (count($list) == 0) : ?>

                <div class="info-box information">
                    <?=$ui->item("MSG_ADDRESS_ERROR_EMPTY"); ?>
                </div>

            <?php else : ?>


                <?=CHtml::beginForm(); ?>
                <?php foreach ($list as $address) : ?>

                    <?php

                    $isDefault = $address['if_default'];

                    switch ($address['type'])
                    {
                        case Address::ORGANIZATION:
                            $type = $ui->item("USER_ADDRESS_TYPE_BUSINESS");
                            $pic = "dad_b.gif";
                            break;
                        case Address::PRIVATE_PERSON:
                            $type = $ui->item("USER_ADDRESS_TYPE_PERSONAL");
                            $pic = "dad_p.gif";
                            break;
                    }

                    ?>


                    <table cellspacing="0" cellpadding="5" border="0" >
                        <tbody>
                        <tr>
                            <td valign="top"><img width="30" height="36" class="va" src="/pic1/<?=$pic; ?>"></td>
                            <td class="maintxt"><b><?=$type; ?></b>: <?=CommonHelper::FormatAddress($address); ?>
                                <div class="mb6t10l5"><img width="8" height="7" border="0" src="/pic1/arr4.gif">&nbsp;<a
                                        href="<?=Yii::app()->createUrl('client/editaddress', array('aid' => $address['address_id'])); ?>" class="maintxt1"><?=$ui->item("ADDRESS_EDIT"); ?></a>&nbsp;&nbsp;&nbsp;&nbsp;<img
                                        width="8" height="7" border="0" src="/pic1/del1.gif">&nbsp;<a
                                        href="<?=Yii::app()->createUrl('client/deleteaddress', array('aid' => $address['address_id'])); ?>" class="maintxt1"><?=$ui->item("ADDRESS_DELETE"); ?></a>
                                </div>
                                <div><input type="radio" id="ir<?=$address['address_id']; ?>" style="vertical-align: middle;" value="<?=$address['address_id']; ?>"
                                            name="aid" <?=($isDefault) ? 'checked' : ''; ?>>
                                    <label for="ir<?=$address['address_id']; ?>" style="vertical-align: middle;"><?=$ui->item("ADDRESS_USE_AS_DEFAULT"); ?></label></div>
                            </td>
                        </tr>
                        </tbody>
                    </table>


                <?php endforeach; ?>

                <input type="image" style="border:none; padding:0" title="<?=$ui->item("SAVE_ALT"); ?>" src="/pic1/<?=$ui->item("SAVE_PICTURE"); ?>">&nbsp;&nbsp;

                </form>

            <?php endif; ?>

            <a href="<?=Yii::app()->createUrl('my/newaddress'); ?>"><img src="/pic1/<?=$ui->item("ADD_ADDRESS_PICTURE"); ?>" alt="<?=$ui->item("ADD_ADDRESS_ALT"); ?>"></a>
            <!-- /content -->
        </div>
    <div class="span2">

                <?php $this->renderPartial('/site/_me_left'); ?>

            </div>
        </div>
        </div>
