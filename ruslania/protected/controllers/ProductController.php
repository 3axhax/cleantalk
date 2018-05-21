<?php

class ProductController extends MyController
{
    public function actionView($entity, $id)
    {
        
		$entity = Entity::ParseFromString($entity);
        if($entity === false) throw new CHttpException(404);

        $product = new Product();
        $data = $product->GetProduct($entity, $id);

        $c = new Cart;
        $cart = $c->GetCart($this->uid, $this->sid);
        foreach($cart as $item)
        {
            if($entity == $item['entity'] && $item['id'] == $data['id'])
            {
                $data['AlreadyInCart'] = $item['quantity'];
            }
        }
		
		switch ($entity) {
			case 10:
				if ($data['isbn']) {
					$this->pageTitle = ProductHelper::GetTitle($data) . ' - ' . $data['isbn'];
				} else {
					$this->pageTitle = ProductHelper::GetTitle($data);
				}
			break;
			case 30:
				
				if ($data['MagazineType']['id']) {
					$this->pageTitle = ProductHelper::GetTitle($data['MagazineType']) . ' - ' .ProductHelper::GetTitle($data) . " - Подписка";
				} else {
					$this->pageTitle = ProductHelper::GetTitle($data);
				}
			
			break;
			case 22:
			
				//var_dump($data['Media']);
			
				if ($data['Media']) {
					$this->pageTitle = ProductHelper::GetTitle($data) . ' - ' . $data['Media']['title'] . " - купить онлайн";
				} else {
					$this->pageTitle = ProductHelper::GetTitle($data);
				}
			break;
			
			case 40:
			
				//var_dump($data['Media']);
			
				if ($data['Media']) {
					$this->pageTitle = ProductHelper::GetTitle($data) . ' - ' . $data['Media']['title'] . " - купить онлайн";
				} else {
					$this->pageTitle = ProductHelper::GetTitle($data);
				}
			break;
			
			case 50:
			case 60:
			case 24:
			
			
					$this->pageTitle = ProductHelper::GetTitle($data) . " - купить онлайн";
				
			break;
		}
		
		
		
		
		

        $title = Entity::GetTitle($entity, Yii::app()->language);
        $this->breadcrumbs[$title] = Yii::app()->createUrl('entity/list', array('entity' => Entity::GetUrlKey($entity)));

        $keys = array(
            Entity::AUDIO => 'audio',
            Entity::BOOKS => 'book',
            Entity::MAPS => 'map',
            Entity::MUSIC => 'music',
            Entity::PERIODIC => 'periodicals',
            Entity::PRINTED => 'printed',
            Entity::SOFT => 'soft',
            Entity::VIDEO => 'video',
            Entity::SHEETMUSIC => 'book',
        );
		
		$get_cats = Category::get_count_categories_bread($id, $entity);
		
		if (count($get_cats) == 0) {
			
			$entities = Entity::GetEntitiesList();
			$tbl = $entities[$entity]['site_table'];
			
			$sql = 'SELECT `code`, `subcode` FROM ' . $tbl . ' WHERE id='.$id;
			
			$rows = Yii::app()->db->createCommand($sql)->queryAll();
			
			$code = (int) $rows[0]['code'];
			
			if (!$rows[0]['code']) {
				$code = $rows[0]['subcode'];
			}
			
			$arr = Category::getCatsBreadcrumbs($entity, $code);
			
			foreach($arr as $a) {
				
				$title2 = ProductHelper::GetTitle($a);
				
				$this->breadcrumbs[$title2] = Yii::app()->createUrl('entity/list', array('entity' => Entity::GetUrlKey($entity), 'cid' => $a['id']));
				
			}
			
			$this->breadcrumbs[] = ProductHelper::GetTitle($data);
			
		} else {
			$this->breadcrumbs[] = ProductHelper::GetTitle($data);
		}
		
        
        //

        if(empty($data)) throw new CHttpException(404);

        $this->render('view', array('item' => $data, 'entity' => $entity));
    }
}