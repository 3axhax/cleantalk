<?

	class Comments extends CActiveRecord
	{
		
		public static function model($className = __CLASS__)
		{
			return parent::model($className);
		}

		public function tableName()
		{
			return 'product_reviews';
		}
		
		public function get_list($entity, $id) {
			
			$sql = 'SELECT * FROM product_reviews '
            . 'WHERE product_entity=:entity AND product_id=:product_id AND moder=1 ORDER BY `id` DESC';

			return Yii::app()->db->createCommand($sql)->query(array('entity'=>$entity, 'product_id'=>$id)); 
			
		}
		
	}

?>