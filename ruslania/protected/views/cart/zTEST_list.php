<?php KnockoutForm::RegisterScripts(); ?>
<div id="cart_renderpartial">
                <div data-bind="visible: FirstLoad" class="center cartload">
                    <?=$ui->item('CART_IS_LOADING'); ?><br/><br/>
                    <img src="/pic1/loader.gif"/>
                </div>
                
                <!-- triangle -->
                <div class="mini_cart_triangle"></div>
                <!-- /triangle -->
                
                <!-- content -->
                <div data-bind="visible:  CartItems().length > 0">
                    <table width="35%" cellspacing="1" cellpadding="5" border="0" class="cart1 items_tbl"
                           style="margin-bottom: 10px; margin-top: 15px;">
                        <thead>
                        <tr>
                        </tr>
                        </thead>
                        <tbody class="items" data-bind="foreach: CartItems">
                        <!-- ko if: $index() < 3-->
                        <tr>
                            <td valign="middle" class="cart1contents1">
                                <table>
								<tr>
								<td>
								<img width="31" height="31" align="middle"
                                     alt="" style="vertical-align: middle"
                                     src="/pic1/cart_ibook.gif">
								</td><td style="padding-left: 20px;" class="maintxt1_1">
                                <span
                                    data-bind="text: Title"
                                    class="maintxt1">
                                </span>
                                <p class="maintxt_price">
                                <span data-bind="text: $root.ReadyPriceStr($data), visible: DiscountPercent() == 0" ></span>
                                <div data-bind="visible: DiscountPercent() > 0">
                                    <s data-bind="text: PriceOriginal"></s>
                                    <span data-bind="text: $root.ReadyPriceStr($data)"></span>
                                </div>
                                </p>
        
								</td>
								</tr>
								</table>
                            </td>
                            <td valign="middle" align="center" class="cart1contents1">
                                <a href="javascript:;" data-bind="click: function(data, event) { cvm_1.RemoveFromCart(data, <?=Cart::TYPE_ORDER; ?>); }"><img src="/new_img/del_cart.png" width="18px" height="18px" /></a>
                            </td>
                        </tr>
                        <!--/ko-->
                        </tbody>
                        
                        <tr>
                            <td colspan="8" class="order_start_box" class="cart1header2" align="right">
                            <a href="<?=Yii::app()->createUrl('cart/view'); ?>" class="count_mini_cart" data-bind="text: 'И еще '+(CartItems().length-3)+' товаров', visible: CartItems().length > 3">test</a>

                                <a href="/cart/doorder" class="order_start_mini_cart" data-bind="visible: CartItems().length > 0 && !AjaxCall() && TotalVAT() > 0">
                                    Оформить заказ
                                </a>

                                

                            </td>
                        </tr>
                    </table>


                </div>


            </div>

<script type="text/javascript">

    var csrf_1 = $('meta[name=csrf]').attr('content').split('=');

    var cartVM_1 = function ()
    {
        var self = this;
        self.FirstLoad = ko.observable(true);
        self.CartItems = ko.observableArray([]);
        self.EndedItems = ko.observableArray([]);
        self.RequestItems = ko.observableArray([]);
        self.AjaxCall = ko.observable(false);

        self.IsVATInPrice = function()
        {
            var usingVAT = true;
            var items = self.CartItems();
            $.each(items, function (idx, item)
            {
                usingVAT = item.UseVAT();
            });

            return usingVAT
                ? '<?=$ui->item('WITH_VAT'); ?>'
                : '<?=$ui->item('WITHOUT_VAT'); ?>';
        };

        self.ReadyPrice = function(item)
        {
            if(item.Entity() != <?=Entity::PERIODIC; ?>)
                return item.UseVAT() ? item.PriceVAT() : item.PriceVAT0();
            else
            {
                if(item.Price2Use() == <?=Cart::FIN_PRICE; ?>)
                    return item.UseVAT() ? item.PriceVATFin() : item.PriceVAT0Fin();
                else
                    return item.UseVAT() ? item.PriceVATWorld() : item.PriceVAT0World();
            }
        };

        self.ReadyPriceStr = function(item)
        {
            if(item.Entity() != <?=Entity::PERIODIC; ?>)
                return item.UseVAT() ? item.PriceVATStr() : item.PriceVAT0Str();
            else
            {
                if(item.Price2Use() == <?=Cart::FIN_PRICE; ?>)
                    return item.UseVAT() ? item.PriceVATFinStr() : item.PriceVAT0FinStr();
                else
                    return item.UseVAT() ? item.PriceVATWorldStr() : item.PriceVAT0WorldStr();
            }
        };

        self.LineTotalVAT = function (item)
        {
            return Math.abs(parseInt(item.Quantity()) * self.ReadyPrice(item)).toFixed(2);
        };

        self.RemoveFromCart = function(item, type)
        {
            if(confirm('<?=$ui->item('ARE_YOU_SURE'); ?>'))
            {
                var obj =
                {
                    entity : item.Entity(),
                    iid : item.ID(),
                    type : type
                };
                obj[csrf_1[0]] = csrf_1[1];
                $.when
                    (
                        $.ajax({
                            type: "POST",
                            url: '/cart/remove',
                            data: obj,
                            dataType: 'json'
                        })
                    ).then(function(json)
                    {
                        if(!json.hasError)
                        {
                            if(type == <?=Cart::TYPE_ORDER; ?>)  self.CartItems.remove(item);
                            else self.RequestItems.remove(item);
                            //update_cart();
                        }
                    });
                   $.ajax({
                        url: '/cart/getcount',
                        data: 'id=1',
                        type: 'GET',
                        success: function (data) {
                            var d = JSON.parse(data);
                            $('div.cart_count').html(d.countcart)
                            $('div.span1.cart .cost').html(d.totalPrice)
                        }
                    })
                
            }
        };

        self.UsingMinPrice = ko.observable(false);

        self.TotalVAT = ko.computed(function ()
        {
            var ret = 0;
            var items = self.CartItems();

            var sumEur = 0;
            var rate = 1;
            $.each(items, function (idx, item)
            {
                sumEur += Math.abs(parseInt(item.Quantity()) * self.ReadyPrice(item));
                ret +=  Math.abs(parseInt(item.Quantity()) * self.ReadyPrice(item));
                rate = parseFloat(item.Rate());
            });

            if(sumEur < <?=Yii::app()->params['OrderMinPrice']; ?>)
            {
                ret = <?=Yii::app()->params['OrderMinPrice']; ?> * rate;
                self.UsingMinPrice(true);
            }
            else self.UsingMinPrice(false);

            return ret.toFixed(2);
        });

        self.QuantityChanged = function (data, event)
        {
            var post =
            {
                entity: data.Entity(),
                id: data.ID(),
                quantity: data.Quantity(),
                type : data.Price2Use()
            };
            post[csrf_1[0]] = csrf_1[1];

            $.post('/cart/changequantity', post, function (json)
            {
                if(json.changed)
                    data.InfoField(json.changedStr);
                else
                    data.InfoField('');
//                console.log(json);
                data.Quantity(json.quantity);
            }, 'json');
        };

        self.RequestQuantityChanged = function (data, event)
        {
            var post =
            {
                entity: data.Entity(),
                id: data.ID(),
                quantity: data.Quantity()
            };
            post[csrf_1[0]] = csrf_1[1];

            $.post('/cart/changequantity', post, function (json)
            {
//                console.log(json);
                data.Quantity(json.quantity);
            }, 'json');
        };
    };
    
    
    var cvm_1 = new cartVM_1();
    
    var first_cvm = cvm_1;

    firstState = $('#cart_renderpartial').clone();        
    
    ko.applyBindings(cvm_1, $('#cart_renderpartial')[0]);
    
    
    $(document).ready(function ()
    {
        
        var data = { language: '<?=Yii::app()->language; ?>', is_MiniCart: 1};
        $.getJSON('/cart/getall', data, function (json)
        {
            ko.mapping.fromJS(json, {}, cvm_1);
           
            cvm_1.FirstLoad(false);
            
        });
    });

    $(document).ajaxStart(function ()
    {
        cvm_1.AjaxCall(true)
    }).ajaxComplete(function ()
        {
            cvm_1.AjaxCall(false);
        });
        
        
    function update_mini_cart() {
        ko.applyBindings(cvm_1, firstState[0]);
        
         var data = { language: '<?=Yii::app()->language; ?>', is_MiniCart: 1}; 
        $.getJSON('/cart/getall', data, function (json)
            {
                ko.mapping.fromJS(json, {}, cvm_1);
    
            });
 
    }
    

</script>