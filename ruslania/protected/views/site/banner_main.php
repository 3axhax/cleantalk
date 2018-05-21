<script type="text/javascript">
    $(document).ready(function() {
        $('.container_slides ul').slick({
            lazyLoad: 'ondemand',
            slidesToShow: 1,
            slidesToScroll: 1
        });
    });
</script>
	
<div class="slider_bg">                        
	<div class="container slider_container">
		<div class="overflow_box">
			<div class="container_slides" style="width: 1170px;">
				<ul>
				
						<?
							$bannersNew = new BannersNew();
							$bannersNew->getMainBanner();
						?>
								
				</ul>                
			</div>
		</div>            
	</div>						        
</div>