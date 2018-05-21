<?php

class Bookshelf extends CActiveRecord
{
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'bookshelf';
    }

    public function getDbConnection()
    {
        return Yii::app()->db;
    }

}