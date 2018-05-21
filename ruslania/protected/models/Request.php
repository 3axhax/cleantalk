<?php

class Request extends CMyActiveRecord
{
    public $check; // проверка что корзина не изменилась за время создания заказа

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'users_requests';
    }

    public function relations()
    {
        return array(
            'items' => array(self::HAS_MANY, 'RequestItem', array('rid' => 'id')),
            'states' => array(self::HAS_MANY, 'RequestState', array('rid' => 'id')),
        );
    }

    public function GetRequests($uid)
    {
        $criteria = new CDbCriteria;
        $criteria->condition = 't.uid=:uid';
        $criteria->params = array(':uid' => $uid);
        $criteria->order = 't.id DESC';
        $list = Request::model()->with('items', 'states')->together()->findAll($criteria);

        $list = $this->FlatRequestList($list);

        return $list;
    }

    private function FlatRequestList($list)
    {
        $items = array();
        $itemsData = array();

        // Собрать все ID товаров что бы выбрать инфу по ним 1 запросом
        foreach ($list as $order)
            foreach ($order->items as $item)
            {
                $ent = Entity::ConvertToHuman($item['entity']);
                $items[$ent][] = $item['iid'];
            }

        $p = new Product;
        foreach ($items as $entity => $ids)
        {
            $result = $p->GetProducts($entity, $ids);
            foreach ($result as $item)
                $itemsData[$entity][$item['id']] = $item;
        }

        // Теперь привести список заказов в вид массива
        $ret = array();
        foreach ($list as $order)
        {
            $ord = $order->attributes;
            $states = array();
            foreach ($order->states as $state)
            {
                $states[] = $state->attributes;
            }


            $items = array();
            foreach ($order->items as $item)
            {
                $entity = Entity::ConvertToHuman($item['entity']);
                $iid = $item['iid'];
                $row = false;

                if (isset($itemsData[$entity][$iid]))
                {
                    $row = array_merge($item->attributes, $itemsData[$entity][$iid]);
                }
                else
                {
                    $row['entity'] = $entity;
                    $row['id'] = 0;
                    $row['title_ru'] = Yii::app()->ui->item('MSG_ERROR_DELETED_ITEM_RECORD');
                }

                $items[] = $row;
                $ret[] = array('RID' => $order['id'], 'Item' => $row, 'LastState' => 'XXX', 'States' => $states);
            }
        }

        return $ret;
    }

    public function CreateNewRequest($uid, $items, $notes)
    {
        $transaction = Yii::app()->db->beginTransaction();
        try
        {
            $sql = 'INSERT INTO users_requests (uid, notes) VALUES (:uid, :notes)';
            Yii::app()->db->createCommand($sql)->execute(array(':uid' => $uid, ':notes' => $notes));
            $rid = Yii::app()->db->lastInsertID;

            // Добавить статус
            $sql = 'INSERT INTO users_requests_states (rid, state) VALUES (:rid, :state)';
            Yii::app()->db->createCommand($sql)->execute(array(':rid' => $rid, ':state' => RequestState::SavedInSystem));

            // Добавить товары
            $sql = 'INSERT INTO users_requests_items (rid, entity, iid, quantity) VALUES (:rid, :entity, :iid, :qty)';
            foreach ($items as $item)
            {
                Yii::app()->db->createCommand($sql)->execute(array(':rid' => $rid,
                                                                   ':entity' => Entity::ConvertToSite($item['entity']),
                                                                   ':iid' => $item['id'],
                                                                   ':qty' => $item['quantity']
                                                             ));
            }

            $transaction->commit();
            return $rid;
        }
        catch (Exception $ex)
        {
            CommonHelper::LogException($ex, 'Failed to create request');
            $transaction->rollback();
            return false;
        }
    }

    public function GetRequest($rid)
    {
        $criteria = new CDbCriteria;
        $criteria->condition = 't.id=:rid';
        $criteria->params = array(':rid' => $rid);
        $request = Request::model()->with('items', 'states')->together()->find($criteria);
        if (empty($request)) return array();

        $request = $this->FlatRequest($request);
        return $request;
    }

    private function FlatRequest($request)
    {
        $items = array();
        $itemsData = array();

        // Собрать все ID товаров что бы выбрать инфу по ним 1 запросом
        foreach ($request->items as $item)
        {
            $e = Entity::ConvertToHuman($item['entity']);
            $items[$e][] = $item['iid'];
        }

        $p = new Product;

        foreach ($items as $entity => $ids)
        {
            $result = $p->GetProducts($entity, $ids);
            foreach ($result as $item)
                $itemsData[$entity][$item['id']] = $item;
        }

        $ord = $request->attributes;

        $items = array();
        foreach ($request->items as $item)
        {
            $entity = Entity::ConvertToHuman($item['entity']);
            $iid = $item['iid'];
            $row = false;

            if (isset($itemsData[$entity][$iid]))
            {
                $row = array_merge($item->attributes, $itemsData[$entity][$iid]);
            }
            $items[] = $row;
        }

        $ord['Items'] = $items;

        foreach ($request->states as $state)
        {
            $ord['States'][] = $state->attributes;
        }


        return $ord;
    }
}