<?php

class PeriodicCountry extends CMyActiveRecord
{
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'pereodics_countries';
    }
}