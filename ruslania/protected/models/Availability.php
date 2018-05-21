<?php

class Availability
{
    const MIN_ITEMS = 5;

    const AVAIL_IN_SHOP = 1;
    const ENDING_IN_SHOP = 2;
    const TO_ORDER_FAST = 3; // Надо заказывать у поставщиков быстрые поставщики
    const TO_ORDER_SLOW = 4; // медленные поставщики
    const TEMPORARY_OUT = 5; //
    const NOT_AVAIL_AT_ALL = 6;

    public static function GetStatus($item)
    {
        if ($item['in_shop'] > 0)
        {
            if ($item['in_shop'] <= self::MIN_ITEMS &&
                (!empty($item['entity']) || $item['entity'] != Entity::PERIODIC)
            )
            {
                return self::ENDING_IN_SHOP;
            }
            else
            {
                return self::AVAIL_IN_SHOP;
            }
        }
        else if ($item['econet_skip'] > 0) // in_stock
        {
            $ret = self::TO_ORDER_SLOW;

            if (isset($item['Publisher']))
            {
                $fastCountries = array(68, 62);
                if (!empty($item['Publisher']['country_id']))
                {
                    if (in_array($item['Publisher']['country_id'], $fastCountries)) $ret = self::TO_ORDER_FAST;
                }
            }

            $fastVendors = array(5, 7, 431);
            if (!empty($item['vendor']) && in_array($item['vendor'], $fastVendors)) $ret = self::TO_ORDER_FAST;

            return $ret;
        }
        else
        {
            return self::NOT_AVAIL_AT_ALL;
//            if (empty($item['stock_id']))
//                return self::NOT_AVAIL_AT_ALL;
//            else
//                return self::TEMPORARY_OUT;
        }
    }

    public static function ToStr($item)
    {
        $code = self::GetStatus($item);
        switch ($code)
        {
            case self::AVAIL_IN_SHOP :
                return Yii::app()->ui->item("ITEM_AVAIBLE_STATUS_AVAIBLE_SHOP"); //В магазине
            case self::ENDING_IN_SHOP :
                return Yii::app()->ui->item("ITEM_AVAIBLE_STATUS_AVAIBLE_LESS_5"); //заканчивается в магазине
            case self::TEMPORARY_OUT :
                return Yii::app()->ui->item("ITEM_AVAIBLE_STATUS_NOT_AVAIBLE"); //временно отсутствует
            case self::NOT_AVAIL_AT_ALL :
                return Yii::app()->ui->item("ITEM_AVAIBLE_STATUS_NOT_AVAILABLE_AT_ALL"); // Нет в нашем ассортименте.
            case self::TO_ORDER_FAST : // return Yii::app()->ui->item('ITEM_FAST_DELIVERY');
            case self::TO_ORDER_SLOW : //return Yii::app()->ui->item("ITEM_SLOW_DELIVERY"); // поступление от 2 до 15 дней
            {
                if(!isset($item['DeliveryTime']) || $item['DeliveryTime'] === false) return Yii::app()->ui->item("ITEM_SLOW_DELIVERY"); // поступление от 2 до 15 дней

                $unit = $item['DeliveryTime']['delivery_unit'];
                $name = $item['DeliveryTime']['delivery_type_name'];
                $key = 'SHIPPING_WEEKS';
                if($unit == 1)
                {
                    if($name == 1) $key = 'SHIPPING_DAY';
                    else $key = 'SHIPPING_DAYS';
                }
                else if($unit == 2)
                {
                    if(strpos($name, '-') !== false) $key = 'SHIPPING_WEEKS';
                    else $key = 'SHIPPING_WEEK';
                }

                return Yii::app()->ui->item('SHIPPING').' '.$name.' '.Yii::app()->ui->item($key).' '.Yii::app()->ui->item('SHIPPING_AFTER');
                break;
            }

        }
    }
}