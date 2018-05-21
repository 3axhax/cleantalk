<script type="text/javascript">
    $(document).ready(function() {
        $('.container_slides ul').slick({
            lazyLoad: 'ondemand',
            slidesToShow: 3,
            slidesToScroll: 1
        });
    });
</script>
	
<div class="slider_bg">                        
	<div class="container slider_container">
		<div class="btn_left"><img src="/new_img/btn_left.png" /></div>
		<div class="btn_right"><img src="/new_img/btn_right.png" /></div>
		<div class="overflow_box">
			<div class="container_slides" style="width: 1170px;">
				<ul>
				<?
				foreach ($actionItems as $actionItem)
				{
					$product = Product::GetProduct($actionItem['entity'], $actionItem['item_id']);
					
					$url = ProductHelper::CreateUrl($product);						
					$productTitle = ProductHelper::GetTitle($product, 'title', 18);
					$productPicture = Picture::Get($product, Picture::SMALL);
					$price = DiscountManager::GetPrice(Yii::app()->user->id, $product);
					if (!empty($price[DiscountManager::DISCOUNT]))
					{
						$priceTitle = '<span style="font-size: 90%; color: #ed1d24; text-decoration: line-through;">'.ProductHelper::FormatPrice($price[DiscountManager::BRUTTO]).'
						</span>&nbsp;<span class="price" style="color: #301c53;font-size: 18px; font-weight: bold;">
							'.ProductHelper::FormatPrice($price[DiscountManager::WITH_VAT]).'</span>';
					}
					else
						$priceTitle = ProductHelper::FormatPrice($price[DiscountManager::WITH_VAT]);
					
					$priceVatTitle = ProductHelper::FormatPrice($price[DiscountManager::WITHOUT_VAT]).' '.$ui->item('WITHOUT_VAT');
					
					$actionTitle = '';
					if($product['status'] == 'new')
					{
						$actionTitle = '<div class="new_block">Новинка!</div>';
						$actionTitleClass = ' new';
					}
					elseif($product['status'] == 'sale')
					{
						$actionTitle = '<div class="new_block">Акция</div>';
						$actionTitleClass = ' akciya';
					}
					elseif($product['status'] == 'recommend')
					{
						$actionTitle = '<div class="new_block">В подборке</div>';
						$actionTitleClass = ' rec';
					}
					?>
						<li>
							<div class="span1 photo<?=$actionTitleClass;?>">
								<?=$actionTitle;?>
								<a href="<?=$url;?>"><img src="<?=$productPicture;?>" alt=""  style="max-height: 130px;"/></a>
							</div>
							<div class="span2 text">
								
								<div class="title"><a href="<?=$url;?>"><?=$productTitle;?></a></div>
								<div class="cost"><?=$priceTitle;?></div>
								<div class="nds"><?=$priceVatTitle;?></div>
								<a href="<?=$url;?>" class="btn_yellow">Подробнее</a>
								
							</div>
						</li>						
					<?
				}
				?>
			</ul>                
		</div>
		</div>            
	</div>						        
</div>