<table width="100%" cellspacing="0" cellpadding="0" border="0" style="vertical-align:top" class="text">
    <tr>
        <td class="leftmnu" width="20%" valign="top">
            <?php $this->renderPartial('/entity/_left_text'); ?>
        </td>
        <td valign="top" style="padding: 5px;">
            <?php $this->widget('TopBar', array('breadcrumbs' => $this->breadcrumbs)); ?>
            <?php $this->renderPartial('/site/login_form', array('model' => new User,
                'uiKey' => 'A_LEFT_PERSONAL_LOGIN',
                'class' => '',
                'refresh' => true)); ?>
        </td>
    </tr>
</table>

