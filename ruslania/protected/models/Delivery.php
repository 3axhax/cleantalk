<?php

class Delivery
{
    const TYPE_ECONOMY = 3;
    const TYPE_PRIORITY = 2;
    const TYPE_EXPRESS = 1;
    const TYPE_FREE = 4;

    public static function ToString($idx)
    {
        switch($idx)
        {
            case self::TYPE_ECONOMY : return 'Economy';
            case self::TYPE_EXPRESS : return 'Express';
            case self::TYPE_PRIORITY: return 'Priority';
            case self::TYPE_FREE : return Yii::app()->ui->item('MSG_DELIVERY_TYPE_4');
        }
    }
}