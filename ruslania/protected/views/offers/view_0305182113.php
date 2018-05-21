<?php $this->widget('TopBar', array('breadcrumbs' => $this->breadcrumbs)); ?>
     <div class="container listgoods content_books" style="margin-top: 0;">
    
    <div class="row">
        
        <?
		$serGoods = unserialize(Yii::app()->getRequest()->cookies['yourView']->value);
		
		if ($serGoods) {
			
			shuffle($serGoods);
			
		?>
		
        <div class="span2">
            <h2 class="poht" style="margin-top: 0; margin-bottom: 20px;">
				<?=$ui->item('A_NEW_VIEWD_ITEMS')?>:
			</h2>
			
			
			<div class="you_view">
			
				<ul>
				
					<?
						$i = 1;
						foreach ($serGoods as $goods) {
							
							if ($i > 5) break;
							
							$ex = explode('_', $goods);
							
							$good_id = $ex[0];
							$good_entity = $ex[1];
							
							if ($good_id == $item['id']) continue;
							
							$igoods = Product::GetBaseProductInfo($good_entity, $good_id);
							
							$price = DiscountManager::GetPrice(Yii::app()->user->id, $igoods);
							
							//var_dump($igoods);
							$i++;
							?>
							
								<li>
									<div class="span1 photo new">
										<?php $url = ProductHelper::CreateUrl($igoods); ?>

    <a href="<?=$url; ?>"><img src="<?=Picture::Get($igoods, Picture::SMALL); ?>" alt="" /></a>

									</div>
									<div class="span2 text">
										<div class="title"><a href="<?=$url; ?>"><?=ProductHelper::GetTitle($igoods, 'title', 30); ?></a></div>
										<div class="cost"><?php if (!empty($price[DiscountManager::DISCOUNT])) : ?>
            <span style="font-size: 90%; color: #ed1d24; text-decoration: line-through;">
                <?= ProductHelper::FormatPrice($price[DiscountManager::BRUTTO]); ?>
            </span>&nbsp;<span class="price" style="color: #301c53;font-size: 18px; font-weight: bold;">
                <?= ProductHelper::FormatPrice($price[DiscountManager::WITH_VAT]); ?>
                
            </span>

        <?php else : ?>

            <span class="price">
       <?= ProductHelper::FormatPrice($price[DiscountManager::WITH_VAT]); ?>
        
        </span>

        <?php endif; ?></div>
										<div class="nds"><?= ProductHelper::FormatPrice($price[DiscountManager::WITHOUT_VAT]); ?> <?=$ui->item('WITHOUT_VAT'); ?></div>                            
									</div>
									<div class="clearfix"></div>
								</li>
							
							<?
							
						}
					?>
				
                   
				</ul>
			
			</div>
			
        </div>
        <? } ?>
        
        <div class="span10">
<?php $desc = ProductHelper::GetTitle($offer, 'description'); ?>

            <?php if(!empty($desc)) : ?>
                <p class="text"><?=nl2br($desc); ?></p>
            <?php endif; ?>

            <?php foreach($groups as $group=>$data) : ?>
                <table height="30" cellspacing="0" cellpadding="0" border="0" style="vertical-align:top" class="text">
                    <tr>
                        <td colspan="3">
                            <div class="itemsep1">&nbsp;</div>
                        </td>
                    </tr>
                    <tr>
                        <td width="31" class="maintxt" style="padding-top: 2px;padding-bottom: 2px;padding-left: 2px;padding-right: 5px;"><img width="31" height="31" border="0" src="/pic1/cart_ibook.gif"></td>
                        <td width="100%" class="maintxt" style="padding: 2px;"><a href="<?=Yii::app()->createUrl('entity/list', array('entity' => Entity::GetUrlKey($data['entity']))); ?>" class="ctitle"><?=Entity::GetTitle($data['entity']); ?></a></td>
                    </tr>
                </table>

                <ul class="items">
                    <?php foreach($data['items'] as $item) : ?>
                        <?php
                            $item['entity'] = $data['entity']; 
                            $item['status'] = Product::GetStatusProduct($item['entity'], $item['id']);
                        ?>
                        <li>
                            <?php $this->renderPartial('/entity/_common_item_2',
                                array('item' => $item,
                                      'entity' => $data['entity'], 'isList' => true)); ?>
                        </li>
                    <?php endforeach; ?>
                </ul>


            <?php endforeach; ?>
        </div>
        </div>
        </div>