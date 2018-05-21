<ul>
    <?php $i = 1; foreach($items as $item) : ?>
	
	<? $item['entity'] = $entity; ?>
	
    <?php $url = ProductHelper::CreateUrl($item);?>
    <li style="background: url('<?= Picture::Get($item, Picture::BIG); ?>'); background-size: auto 100%; background-position: center center;">
        <a href="<?=$url?>">
            <div style="width: 150px; height: 208px;"></div>
        </a>
    </li>
    
    <?php if ($i % 5 == 0) { echo "</ul><ul>"; } ?>
    
    <?php $i++; endforeach; ?>
</ul>