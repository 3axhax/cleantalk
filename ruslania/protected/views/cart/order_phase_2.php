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

            <?php $this->renderPartial('_order_where_now', array('step' => 2)); ?>
            <?php $this->renderPartial('_noneditable_cart', array('cart' => $cartItems)); ?>


        <?php if(!empty($addresses)) : ?>
        <form method="GET" action="/cart/step3" data-bind="submit: DoNextStep">
            <input type="hidden" name="did" data-bind="value: DeliveryID" />
            <input type="hidden" name="bid" data-bind="value: BillingID" />
        <table width="100%">
            <tr>
                <td width="50%" valign="top">
                    Выберите адрес доставки:
                    <table width="100%" cellspacing="0" cellpadding="5" border="0">
                        <tbody>
                        <tr>
                            <td style="font-weight: bold;" class="maintxt" colspan="2">Выберите адрес доставки Вашего заказа:
                            </td>
                        </tr>
                        <!-- ko foreach: Addresses -->
                        <tr>
                            <td width="30" valign="top"><img width="30" height="36" class="va" src="/pic1/dad_p.gif"></td>
                            <td width="100%" class="maintxt">
                                <b data-bind="text: type"></b>: ,
                                <br><span data-bind="text: country_str"></span>,
                                <span data-bind="text: postindex"></span>,
                                <span data-bind="text: city"></span>,
                                <span data-bind="text: streetaddress"></span>

                                <div class="mt5">
                                    <a href="#" data-bind="click: $root.SetDelivery">Доставить заказ на этот адрес</a>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div class="itemsep1"><img width="1" height="1" border="0" src="/pic/null.gif"></div>
                            </td>
                        </tr>
                        <!-- /ko -->
                        </tbody>
                    </table>

                </td>
                <td width="50%" valign="top">
                    <table width="100%" cellspacing="0" cellpadding="5" border="0">
                        <tbody>
                        <tr>
                            <td style="font-weight: bold;" class="maintxt" colspan="2">Выберите адрес плательщика Вашего заказа:</td>
                        </tr>
                        <!-- ko foreach: Addresses -->
                        <tr>
                            <td width="30" valign="top"><img width="30" height="36" class="va" src="/pic1/dad_p.gif"></td>
                            <td width="100%" class="maintxt">
                                <b data-bind="text: type"></b>: ,
                                <br><span data-bind="text: country_str"></span>,
                                <span data-bind="text: postindex"></span>,
                                <span data-bind="text: city"></span>,
                                <span data-bind="text: streetaddress"></span>

                                <div class="mt5">
                                    <a href="#" data-bind="click: $root.SetPayment">Счет на этот адрес</a>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div class="itemsep1"><img width="1" height="1" border="0" src="/pic/null.gif"></div>
                            </td>
                        </tr>
                        <!-- /ko -->

                        </tbody>
                    </table>
                </td>
            </tr>
        </table>

        <div data-bind="visible: NextStep">
            <input type="image" />
            <input type="checkbox" id="accept" data-bind="checked: Accept">я согласен с условиями</input>
        </div>
        <div data-bind="visible: !NextStep()">
            Выберите адрес доставки и адрес плательщика
        </div>
        </form>

            <script type="text/javascript">

                var aVM = function()
                {
                    var self = this;
                    self.Accept = ko.observable(false);
                    self.DeliveryID = ko.observable();
                    self.BillingID = ko.observable();
                    self.Addresses = ko.observableArray([]);
                    self.SetDelivery = function(data)
                    {
                        self.DeliveryID(data.id());
                        return false;
                    };
                    self.SetPayment = function(data)
                    {
                        self.BillingID(data.id());
                        return false;
                    };

                    self.NextStep = ko.computed(function()
                    {
                        var did = self.DeliveryID();
                        var pid = self.BillingID();
                        return did > 0 && pid > 0;
                    });

                    self.DoNextStep = function()
                    {
                        if(!self.Accept())
                        {
                            alert('Must accept');
                            return false;
                        }
                        return true;
                    }
                };

                var avm = new aVM();
                ko.applyBindings(avm, $('#AddrTable')[0]);

                $(document).ready(function()
                {
                    $.getJSON('/site/myaddresses', function(json)
                    {
                        ko.mapping.fromJS(json, {}, avm);
                        $.each(json.Addresses, function(idx, value)
                        {
                            if(value.if_default == 1)
                            {
                                avm.BillingID(value.id);
                                avm.DeliveryID(value.id);
                            }
                        });
                    });
                });

            </script>

        <?php else : ?>
            Или добавьте новый адрес:
            <?php
            $model = new Address;
            $model->type = Address::PRIVATE_PERSON;
            $this->renderPartial('/site/address_form', array('model' => $model)); ?>
        <?php endif ; ?>

        </td>
    </tr>
</table>
</div>