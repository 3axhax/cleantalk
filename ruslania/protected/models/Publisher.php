<?php

class Publisher extends CMyActiveRecord
{
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'all_publishers';
    }

    public function GetABC($lang, $entity)
    {
        $sql = 'SELECT DISTINCT(first_'.$lang.') AS first_'.$lang.' '
              .'FROM `all_publishers` AS al '
              .'JOIN all_publishers_entity AS e ON al.id=e.publisher '
              .'WHERE e.entity=:entity '
              .'ORDER BY first_'.$lang;

        $rows = Yii::app()->db->createCommand($sql)->queryAll(true, array(':entity' => $entity));
        return $rows;
    }

    public function GetPublishersByFirstChar($char, $lang, $entity)
    {
        $sql = 'SELECT * FROM all_publishers AS al '
              .'JOIN all_publishers_entity AS e ON al.id=e.publisher '
              .'WHERE first_'.$lang.'=:char AND e.entity=:entity '
              .'ORDER BY title_'.$lang;
        $rows = Yii::app()->db->createCommand($sql)->queryAll(true, array(':char' => $char, ':entity' => $entity));
        return $rows;
    }

    public function GetTotalItems($entity, $pid, $avail)
    {
        $entities = Entity::GetEntitiesList();
        $data = $entities[$entity];
        $table = $data['site_table'];

        $sql = 'SELECT COUNT(*) FROM '.$table.' WHERE publisher_id=:id';
        if($avail) $sql .= ' AND avail_for_order=1';

        $cnt = Yii::app()->db->createCommand($sql)->queryScalar(array(':id' => $pid));
        return $cnt;
    }

    public function GetItems($entity, $pid, $paginator, $sort, $lang, $avail)
    {
        $dp = Entity::CreateDataProvider($entity);
        $criteria = $dp->getCriteria();
        if(!empty($pid))
        {
            $criteria->addCondition('publisher_id=:pid');
            $criteria->params[':pid'] = $pid;
        }
        if($avail)
            $criteria->addCondition('avail_for_order=1');

        $criteria->order = SortOptions::GetSQL($sort, $lang);
        $paginator->applyLimit($criteria);
        $dp->setCriteria($criteria);
        $dp->pagination = false;

        $data = $dp->getData();

        return Product::FlatResult($data);
    }

    public function GetByID($entity, $pid)
    {
        $sql = 'SELECT * FROM all_publishers WHERE id=:pid';
        $row = Yii::app()->db->createCommand($sql)->queryRow(true, array(':pid' => $pid));
        return $row;
    }

    public function GetByIDs($ids)
    {
        if(empty($ids) || !is_array($ids)) return array();
        $sql = 'SELECT * FROM all_publishers WHERE id IN ('.implode(',', $ids).')';
        $rows = Yii::app()->db->createCommand($sql)->queryAll();
        $ret = array();
        foreach($rows as $row) $ret[$row['id']] = $row;

        return $ret;
    }

    public function GetCountByIds($ids, $entity=null)
    {
        if(empty($ids) || !is_array($ids)) return array();

        $sql = 'SELECT * FROM all_publishers_entity WHERE publisher IN ('.implode(',', $ids).') ';
        $params = array();
        if(!empty($entity))
        {
            $sql .= ' AND entity=:entity';
            $params[':entity'] = $entity;
        }
        $rows = Yii::app()->db->createCommand($sql)->queryAll(true, $params);
        return $rows;
    }

    public function GetAll($entity, $lang)
    {
        $sql = 'SELECT * FROM all_publishers AS al '
            .'JOIN all_publishers_entity AS e ON al.id=e.publisher '
            .'WHERE e.entity=:entity AND qty > 0 '
            .'ORDER BY title_'.$lang;
        $rows = Yii::app()->db->createCommand($sql)->queryAll(true, array(':entity' => $entity));
        return $rows;
    }
}