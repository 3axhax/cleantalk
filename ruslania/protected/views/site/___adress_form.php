<?php $form = $this->beginWidget('CActiveForm', array(
                                                     'id' => 'address-form',
                                                     'enableAjaxValidation' => true,
                                                     'clientOptions' => array(
                                                         'validateOnChange' => false,
                                                         'validateOnSubmit' => true,
                                                     )
                                                )); ?>

<?php echo $form->errorSummary($model); ?>
<?php if (empty($model->type)) $model->type = Address::PRIVATE_PERSON; ?>

<table width="100%" class="address">
    <thead>
    <th width="200px"></th>
    <th width="265px"></th>
    <th></th>
    </thead>
    <tr>
        <td><?= $ui->item("address_type"); ?></td>
        <td><?= $form->radioButton($model, 'type',
                array('value' => Address::ORGANIZATION,
                      'uncheckValue' => null,
                      'data-bind' => 'checked: Type')); ?> <?= $ui->item("MSG_PERSONAL_ADDRESS_COMPANY"); ?>
            <?= $form->radioButton($model, 'type',
                array('value' => Address::PRIVATE_PERSON,
                      'uncheckValue' => null,
                      'data-bind' => 'checked: Type')); ?> <?= $ui->item("MSG_PERSONAL_ADDRESS_PERSON"); ?>
        </td>
        <td></td>
    </tr>
    <tr>
        <td><?= $ui->item("regform_titlename"); ?>:</td>
        <td><?= $form->textField($model, 'receiver_title_name'); ?></td>
        <td><span class="smalltxt1"><?= $ui->item("MSG_REGFORM_TITLENAME_TIP_1"); ?></span></td>
    </tr>
    <tr>
        <td><?= $ui->item("regform_lastname"); ?></td>
        <td>
            <?= $form->error($model, 'receiver_last_name'); ?>
            <?= $form->textField($model, 'receiver_last_name'); ?></td>
        <td>
            <span class="smalltxt1"><?= $ui->item("MSG_REGFORM_NAME_TIP_2"); ?></span>
        </td>
    </tr>
    <tr>
        <td><?= $ui->item("regform_firstname"); ?></td>
        <td><?= $form->error($model, 'receiver_first_name'); ?>
            <?= $form->textField($model, 'receiver_first_name'); ?></td>
        <td></td>
    </tr>
    <tr>
        <td><?= $ui->item("regform_middlename"); ?></td>
        <td><?= $form->textField($model, 'receiver_middle_name'); ?></td>
        <td></td>
    </tr>
    <tr>
        <td><?= $ui->item("address_country"); ?></td>
        <td><?= $form->error($model, 'country'); ?>
            <?= $form->dropDownList($model, 'country', CHtml::listData(Country::GetCountryList(), 'id', 'title_en'),
                array('empty' => '---', 'data-bind' =>'value: CountryID')); ?></td>
        <td></td>
    </tr>
    <tr data-bind="visible: CountryID()== 225">
        <td><?= $ui->item("address_state"); ?></td>
        <td><?= $form->error($model, 'state_id'); ?>
            <?= $form->dropDownList($model, 'state_id', CHtml::listData(Country::GetStatesList(), 'id', 'title_long'), array('empty' => '---')); ?></td>
        <td></td>
    </tr>
    <tr>
        <td><?= $ui->item("address_city"); ?></td>
        <td><?= $form->error($model, 'city'); ?>
            <?= $form->textField($model, 'city'); ?></td>
        <td></td>
    </tr>
    <tr>
        <td><?= $ui->item("address_postindex"); ?></td>
        <td><?= $form->error($model, 'postindex'); ?>
            <?= $form->textField($model, 'postindex'); ?></td>
        <td></td>
    </tr>
    <tr>
        <td><?= $ui->item("address_streetaddress"); ?></td>
        <td><?= $form->error($model, 'streetaddress'); ?>
            <?= $form->textField($model, 'streetaddress'); ?></td>
        <td><span class="smalltxt1"><?= $ui->item("MSG_PERSONAL_ADDRESS_COMMENT_2"); ?></span></td>
    </tr>
    <tr>
        <td><?= $ui->item("address_contact_email"); ?></td>
        <td><?= $form->textField($model, 'contact_email'); ?></td>
        <td></td>
    </tr>
    <tr>
        <td><?= $ui->item("address_contact_phone"); ?></td>
        <td><?= $form->textField($model, 'contact_phone'); ?></td>
        <td><span class="smalltxt1"><?= $ui->item('MSG_PERSONAL_ADDRESS_COMMENT_PHONE'); ?></span></td>
    </tr>
    <tr>
        <td><?= $ui->item("address_contact_notes"); ?></td>
        <td><?= $form->textArea($model, 'notes'); ?></td>
        <td></td>
    </tr>
    <tr>
        <td><?= $ui->item("address_business_title"); ?></td>
        <td><?=$form->error($model, 'business_title'); ?>
            <?= $form->textField($model, 'business_title', array('data-bind' => 'enable: Type()==1')); ?></td>
        <td></td>
    </tr>
    <tr>
        <td><?= $ui->item("address_business_number1"); ?></td>
        <td><?= $form->textField($model, 'business_number1', array('data-bind' => 'enable: Type()==1')); ?></td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td><input type="image" src="/pic1/<?= $ui->item("SAVE_PICTURE"); ?>"/>
        </td>
        <td></td>
    </tr>
</table>

<?php $this->endWidget(); ?>

<script type="text/javascript">

    var AVM = function()
    {
        var self = this;
        self.Type = ko.observable(2);
        self.CountryID = ko.observable(0);
    };

    var avm = new AVM();
    ko.applyBindings(avm, $('#address-form')[0]);

</script>
