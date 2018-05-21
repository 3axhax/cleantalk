<?php

class Binding
{
    public function GetBinding($entity, $bid)
    {
        $entities = Entity::GetEntitiesList();
        $data = $entities[$entity];
        if(!array_key_exists('binding_table', $data)) return false;
        $table = $data['binding_table'];

        $sql = 'SELECT * FROM '.$table.' WHERE id=:id';
        $row = Yii::app()->db->createCommand($sql)->queryRow(true, array(':id' => $bid));
        return $row;
    }

    public function GetTotalItems($entity, $bid, $avail)
    {
        $entities = Entity::GetEntitiesList();
        $data = $entities[$entity];
        if(!array_key_exists('binding_table', $data)) return 0;

        $sql = 'SELECT COUNT(*) FROM '.$data['site_table'].' WHERE binding_id=:bid';
        if($avail)
            $sql .= ' AND avail_for_order=1';

        $cnt = Yii::app()->db->createCommand($sql)->queryScalar(array(':bid' => $bid));
        return $cnt;
    }

    public function GetItems($entity, $bid, $paginator, $sort, $lang, $avail)
    {
        $entities = Entity::GetEntitiesList();
        $data = $entities[$entity];
        if(!array_key_exists('binding_table', $data)) return array();

        $dp = Entity::CreateDataProvider($entity);
        $criteria = $dp->getCriteria();

        if(!empty($bid))
        {
            $criteria->addCondition('t.binding_id=:bid');
            $criteria->params[':bid'] = $bid;
        }

        if(!empty($avail))
        {
            $criteria->addCondition('t.avail_for_order=1');
        }

        $criteria->order = SortOptions::GetSQL($sort, $lang);
        $paginator->applyLimit($criteria);
        $dp->setCriteria($criteria);
        $dp->pagination = false;

        $data = $dp->getData();

        return Product::FlatResult($data);
    }

}