<?php

class VideoSubtitle extends CMyActiveRecord
{
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'video_creditslist';
    }

    public function GetTotalItems($entity, $sid, $avail)
    {
        if($avail)
        {
            $sql = 'SELECT COUNT(*) FROM video_credits AS a '
                  .'JOIN video_catalog AS b ON a.video_id=b.id '
                  .'WHERE credits_id=:sid AND b.avail_for_order=1';
        }
        else
        {
            $sql = 'SELECT COUNT(*) FROM video_credits WHERE credits_id=:sid';
        }
        $cnt = Yii::app()->db->createCommand($sql)->queryScalar(array(':sid' => $sid));
        return $cnt;
    }

    public function GetItems($entity, $sid, $paginator, $sort, $lang, $avail)
    {
        $dp = Entity::CreateDataProvider($entity);
        $criteria = $dp->getCriteria();
        $criteria->join = 'JOIN video_credits AS j ON j.video_id=t.id ';
        $criteria->addCondition('j.credits_id=:sid');
        if($avail)
            $criteria->addCondition('avail_for_order=1');
        $criteria->params[':sid'] = $sid;
        $criteria->order = SortOptions::GetSQL($sort, $lang);
        $paginator->applyLimit($criteria);
        $dp->setCriteria($criteria);
        $dp->pagination = false;

        $data = $dp->getData();

        return Product::FlatResult($data);
    }
}