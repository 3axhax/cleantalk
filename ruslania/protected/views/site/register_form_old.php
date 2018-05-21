<?php $refresh = isset($refresh) && $refresh; ?>

<?php if (!Yii::app()->user->isGuest): ?>

    Already registered

<?php else : ?>

    <?php $form = $this->beginWidget('KnockoutForm', array(
                                                          'model' => $model,
'class' =>'registr',                                                          'action' => '/site/register',
                                                          'id' => 'user-register',
                                                          'viewModel' => 'registerVM',
                                                          'beforeAjaxSubmit' => 'beforeRegister',
                                                          'afterAjaxSubmit' => 'doRegister',
                                                     )); ?>
    <ul data-bind="foreach: errorStr">
        <li><span data-bind="text: $data"></span></li>
    </ul>
    <table width="100%" border="0" cellspacing="5" cellpadding="0" class="login">
        <tr>
            <td class=maintxt><?= $ui->item("regform_email"); ?>:</td>
            <td><?= $form->textField('login'); ?></td>
        </tr>
        <tr>
            <td class=maintxt>
                <?= $ui->item("regform_password"); ?>:
            </td>
            <td><?= $form->passwordField('pwd'); ?>
            </td>
        </tr>
        <tr>
            <td class=maintxt>
                <?= $ui->item("regform_repeat_password"); ?>:
            </td>
            <td><?= $form->passwordField('pwd2'); ?>
            </td>
        </tr>

        <tr>
            <td class=maintxt>
                <?= $ui->item("regform_firstname"); ?>:</td>
                <td><?= $form->textField('first_name'); ?>
            </td>
        </tr>

        <tr>
            <td class=maintxt>
                <?= $ui->item("regform_lastname"); ?>:</td>
                <td><?= $form->textField('last_name'); ?>
            </td>
        </tr>


        <tr>
            <td></td>
            <td class=maintxt>
                <?= $form->submitButton($ui->item('A_REGISTER'), array('class' => 'sort')); ?>
            </td>
        </tr>
    </table>
    <script>

        function beforeRegister(vm)
        {
            $('#user-register input').change();
            return true;
        }

        function doRegister(json)
        {
            <?php if($refresh) : ?>
            window.location.reload();
            <?php else : ?>
            window.location.href = '/me';
            <?php endif; ?>
        }
    </script>
    <?php $this->endWidget(); ?>

<?php endif; ?>






