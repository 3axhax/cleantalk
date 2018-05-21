<?php

class VideoActor
{
    public function GetByIds($ids)
    {
        $sql = 'SELECT * FROM video_actorslist WHERE id IN ('.implode(',', $ids).')';
        $rows = Yii::app()->db->createCommand($sql)->queryAll();
        return $rows;
    }

    public function GetTotalItems($entity, $aid, $avail)
    {
        if($avail)
        {
            $sql = 'SELECT COUNT(*) FROM video_actors AS a '
                  .'JOIN video_catalog AS b ON a.video_id=b.id '
                  .'WHERE person_id=:id AND b.avail_for_order=1';
        }
        else
        {
            $sql = 'SELECT COUNT(*) FROM video_actors WHERE person_id=:id';
        }
        $cnt = Yii::app()->db->createCommand($sql)->queryScalar(array(':id' => $aid));
        return $cnt;
    }

    public function GetItems($entity, $aid, $paginator, $sort, $lang, $avail)
    {
        $dp = Entity::CreateDataProvider($entity);
        $criteria = $dp->getCriteria();
        $criteria->join = 'JOIN video_actors AS j ON j.video_id=t.id ';
        $criteria->addCondition('j.person_id=:aid');
        if($avail)
            $criteria->addCondition('avail_for_order=1');
        $criteria->params[':aid'] = $aid;
        $criteria->order = SortOptions::GetSQL($sort, $lang);
        $paginator->applyLimit($criteria);
        $dp->setCriteria($criteria);
        $dp->pagination = false;

        $data = $dp->getData();

        return Product::FlatResult($data);
    }

    public function GetActorList($entity, $lang)
    {
        if($entity != Entity::VIDEO) return array();
        $sql = 'SELECT * FROM video_actorslist ORDER BY title_'.$lang;
        $rows = Yii::app()->db->createCommand($sql)->queryAll();
        return $rows;
    }
}