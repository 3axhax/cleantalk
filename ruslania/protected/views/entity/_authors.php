<?php if (!empty($item['Authors'])) : ?>
<div class="authors">
    <?=sprintf($ui->item("WRITTEN_BY"), ''); ?>
    <?php foreach ($item['Authors'] as $author)
    {
        $tmp[] = '<a href="'.Yii::app()->createUrl('entity/byauthor',
            array('entity' => Entity::GetUrlKey($entity),
                  'aid' => $author['id'],
                  'title' => ProductHelper::ToAscii(ProductHelper::GetTitle($author)))).'" class="cprop">'
               .ProductHelper::GetTitle($author).'</a>';
    } ?>

    <?=implode(', ', $tmp); ?>

</div>
<?php endif; ?>
