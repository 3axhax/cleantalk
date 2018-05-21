<?php

class MagazineType extends CMyActiveRecord
{
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'pereodics_types';
    }

    public function GetTotalItems($entity, $tid, $avail)
    {
        // periodics всегда avail_for_order
        $sql = 'SELECT COUNT(*) FROM pereodics_catalog WHERE `type`=:tid';
        $cnt = Yii::app()->db->createCommand($sql)->queryScalar(array(':tid' => $tid));
        return $cnt;
    }

    public function GetItems($entity, $tid, $paginator, $sort, $lang, $avail)
    {
        $dp = Entity::CreateDataProvider($entity);
        $criteria = $dp->getCriteria();
        $criteria->addCondition('t.type=:tid');

//      periodics всегда avail_for_order
//        if($avail)
//            $criteria->addCondition('avail_for_order=1');

        $criteria->params[':tid'] = $tid;
        $criteria->order = SortOptions::GetSQL($sort, $lang, $entity);
        $paginator->applyLimit($criteria);
        $dp->setCriteria($criteria);
        $dp->pagination = false;

        $data = $dp->getData();

        return Product::FlatResult($data);
    }


}