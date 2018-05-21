<?
$entity = 10;
$db = new mysqli('localhost', 'ruslania', 'K7h9E6r2', 'ruslania');

echo $db->error;

$sql = 'SELECT id, title_ru, description_ru, image, brutto, isbn FROM books_catalog ORDER BY add_date DESC LIMIT 0, 40';
// $q = $db->query('SELECT id, title_ru, description_ru, image, brutto, isbn FROM books_catalog ORDER BY add_date DESC LIMIT 0, 40');
    
$items = Yii::app()->db->createCommand($sql)->queryAll();

	// foreach ($items as $row)
	// {

        // // $row = $q->fetch_assoc();
		
		// // echo $row['isbn'].'<br>';
		// $product = Product::GetProduct(10, $row['id']);
		
		// echo ProductHelper::GetTitle($product, 'title', 18).'<br>';
		
	// }
	?>
            <ul class="items">
                <?php foreach ($items as $row) : ?>
                    <?php
					
					$item = Product::GetProduct(10, $row['id']);
                    $item['entity'] = $entity;
                    $key = 'itemlist_' . $entity . '_' . $item['id'];
                    ?>
                    <li>
                        <?php $this->renderPartial('test123_item', array('item' => $item, 'entity' => $entity, 'isList' => true)); ?>
                    </li>
                <?php endforeach; ?>
            </ul>