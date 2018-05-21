<?php

class RequestItem extends CMyActiveRecord
{
    public $check; // проверка что корзина не изменилась за время создания заказа

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'users_requests_items';
    }
}