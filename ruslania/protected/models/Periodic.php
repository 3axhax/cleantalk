<?php

class Periodic extends CMyActiveRecord
{
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'pereodics_catalog';
    }

    public function relations()
    {
        return array(
            'category' => array(self::BELONGS_TO, 'PeriodicCategory', 'code'),
            'subcategory' => array(self::BELONGS_TO, 'PeriodicCategory', 'subcode'),
            'lookinside' => array(self::HAS_MANY, 'Lookinside', 'item_id', 'on' => 'lookinside.entity='.Entity::PERIODIC ),
            'magazinetype' => array(self::BELONGS_TO, 'MagazineType', 'type'),
            'periodicCountry' => array(self::BELONGS_TO, 'PeriodicCountry', 'country'),
            'offers' => array(self::MANY_MANY, 'Offer', 'offer_items(item_id, offer_id)', 'on' => 'offers_offers.entity_id='.Entity::PERIODIC),
            'vendorData' => array(self::BELONGS_TO, 'Vendor', 'vendor'),
        );
    }
}