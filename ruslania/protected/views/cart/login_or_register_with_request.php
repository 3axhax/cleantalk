<?php $this->widget('TopBar', array('breadcrumbs' => $this->breadcrumbs)); ?>
<div class="container listgoods">
            <?php if(!empty($product)) : ?>
            <div class="information info-box">
                <?=$ui->item('MSG_LOGIN_OR_REGISTER_TO_CREATE_REQUEST'); ?>
                <br/>
                <?=$ui->item('MSG_REQUEST_AFTER_SAVE_ATTENTION'); ?>
            </div>
            <ul class="items">
                <li>
                    <?php $this->renderPartial('/entity/_common_item_2', array('item' => $product,
                                                                             'hideButtons' => true,
                                                                             'entity' => $product['entity'])); ?>
                </li>
            </ul>
            <?php else : ?>

                <div class="information info-box">
                    <?=$ui->item('MSG_SEARCH_ERROR_NOTHING_FOUND'); ?>
                </div>

            <?php endif; ?>
		
            <table width="100%" style="margin-top: 25px;">
                <tr>
                    <td width="50%" valign="top">
                        <?php $this->renderPartial('/site/login_form2', array('model' => new User,
                                                                             'uiKey' => 'A_LEFT_PERSONAL_LOGIN',
                                                                             'class' => '',
                                                                             'refresh' => true)); ?>
                    </td>
                    <td width="50%" valign="top">
                        <table cellspacing="0" cellpadding="0" border="0" width="100%" class="myheader divider">
                            <tbody>
                            <tr>
                                <td class="leftmnutitle1-table-txt" align="center">
                                    <?= Yii::app()->ui->item('A_LEFT_PERSONAL_REGISTRATION'); ?>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <?php $this->renderPartial('/site/register_form2', array('model' => new User, 'refresh' => true)); ?>

                    </td>
                </tr>
            </table>



        </div>

