<?php

class VideoDirector
{
    public function GetTotalItems($entity, $did, $avail)
    {
        if ($entity != Entity::VIDEO) return 0;
        $entities = Entity::GetEntitiesList();
        $data = $entities[$entity];

        if($avail)
        {
            $sql = 'SELECT COUNT(*) FROM video_directors AS a '
                  .'JOIN video_catalog AS b ON a.video_id=b.id '
                  .'WHERE person_id=:did AND b.avail_for_order=1';
        }
        else
        {
            $sql = 'SELECT COUNT(*) FROM video_directors WHERE person_id=:did';
        }
        $cnt = Yii::app()->db->createCommand($sql)->queryScalar(array(':did' => $did));
        return $cnt;
    }

    public function GetItems($entity, $did, $paginator, $sort, $lang, $avail)
    {
        $dp = Entity::CreateDataProvider($entity);
        $criteria = $dp->getCriteria();
        $criteria->join = 'JOIN video_directors AS j ON j.video_id=t.id ';
        $criteria->addCondition('j.person_id=:did');
        if($avail)
            $criteria->addCondition('avail_for_order=1');
        $criteria->params[':did'] = $did;
        $criteria->order = SortOptions::GetSQL($sort, $lang);
        $paginator->applyLimit($criteria);
        $dp->setCriteria($criteria);
        $dp->pagination = false;

        $data = $dp->getData();
        return Product::FlatResult($data);
    }

    public function GetDirectorList($entity, $lang)
    {
        if($entity != Entity::VIDEO) return array();
        $sql = 'SELECT * FROM video_directorslist ORDER BY title_'.$lang;
        $rows = Yii::app()->db->createCommand($sql)->queryAll();
        return $rows;
    }
}