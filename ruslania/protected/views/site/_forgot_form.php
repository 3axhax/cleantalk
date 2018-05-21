<?php $form = $this->beginWidget('CActiveForm', array(
                                                     'id' => 'remind-form',                                                     'enableAjaxValidation' => true,
                                                     'clientOptions' => array(
                                                         'validateOnChange' => false,
                                                         'validateOnSubmit' => true,
                                                     )
                                                )); ?>

<p><?=$form->error($model, 'login'); ?></p>
<?=$form->textField($model, 'login', array('placeholder'=>$ui->item('A_LOGINFORM_EMAIL'))); ?> <input type="submit" class="sort" value="<?=$ui->item('A_TITLE_REMIND_PASS'); ?>" />

<?php $this->endWidget(); ?>