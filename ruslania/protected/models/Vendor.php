<?php

class Vendor extends CMyActiveRecord
{
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'vendors';
    }

    public function relations()
    {
        return array(
            'deliveryTime' => array(self::BELONGS_TO, 'DeliveryTime', 'dtid'),
        );
    }
}
