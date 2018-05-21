<?php
function printTree($tree, $entity, $parent = false, $lvl = 1)

{
	
    if (!is_null($tree) && count($tree) > 0)
    {
        
		if (!$parent) {
				
			echo ' <div class="b-category-list__topic">'.Yii::app()->ui->item('A_NEW_CATEGORYES').'</div><ul class="b-category-list__item-outer">';
		
		} else {
			
			
			echo '
            <ul class="b-category-list__inner-list js-slide-content-inner-list tglvl'.$lvl.'">';
		}
		
		
        
		foreach ($tree as $node)
        {
			$cross = '';
			if (!$node['children']) {
				$cross = ' cross3 ';
			}
			
			if ($parent) {

				echo '<li class="b-category-list__item-inner '.$cross.' lvl'.$lvl.'"">';
			
			} else {
				echo '<li class="b-category-list__item '.$cross.' lvl'.$lvl.'">';
			}
            echo '<a class="b-category-list__link" href="'.Yii::app()->createUrl('entity/list',
                array('entity' => Entity::GetUrlKey($entity),
                      'cid' => $node['payload']['id'],
                      'title' => ProductHelper::ToAscii(ProductHelper::GetTitle($node['payload'])))).'">'.ProductHelper::GetTitle($node['payload']).'</a>';
					  
					  if ($node['children']) {
					  
					  if ($lvl > 1) {
					  
						  echo '
							<div class="b-category-list__cross cross2 js-slide-toggle" data-slidecontext=".lvl'.$lvl.'" data-slideclasstoggle=".lvl'.$lvl.'" data-slidetoggle=".tglvl'.($lvl+1).'"></div>';
							
						 } else {
							 echo '
							<div class="b-category-list__cross cross1 js-slide-toggle" data-slidecontext=".lvl'.$lvl.'" data-slideclasstoggle=".lvl'.$lvl.'" data-slidetoggle=".tglvl'.($lvl+1).'"></div>';
						 }
					  
					  }
            printTree($node['children'], $entity, true, ($lvl+1));
            echo '</li>';
        }
        	echo '</ul>';
		}
}
?>
<?php $this->widget('TopBar', array('breadcrumbs' => $this->breadcrumbs)); ?>
<section class="b-all-category">
	<div class="container b-all-category__wrapper">
	
		<div class="b-user-seen">
        <div class="b-user-seen__topic"><?=$ui->item('A_NEW_VIEWD_ITEMS')?>:</div>
		
		<?
		$serGoods = unserialize(Yii::app()->getRequest()->cookies['yourView']->value);
		
		if ($serGoods) {
			
			shuffle($serGoods);
			
		?>
		
       
				
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
							
							<div class="b-user-seen__book">
          <div class="b-user-seen__img"><?php $url = ProductHelper::CreateUrl($igoods); ?>

    <a href="<?=$url; ?>"><img src="<?=Picture::Get($igoods, Picture::SMALL); ?>" alt="" /></a></div>
          <div class="b-user-seen__info">
            <div class="b-user-seen__name"><a href="<?=$url; ?>"><?=ProductHelper::GetTitle($igoods, 'title', 30); ?></a></div>
            <div class="b-user-seen__price"><?php if (!empty($price[DiscountManager::DISCOUNT])) : ?>
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
		<div class="nds" style="font-size: 11px; color: rgb(116, 116, 116);"><?= ProductHelper::FormatPrice($price[DiscountManager::WITHOUT_VAT]); ?> <?=$ui->item('WITHOUT_VAT'); ?></div> 
          </div>
        </div>
							
								
							
							<?
							
						}
					?>
				
                   
				
        <? } ?>
		
		
        
      </div>
		
		<div class="b-category-list">
		
	
		<?php printTree($tree, $entity, false); ?>
	</div>
	
</section>