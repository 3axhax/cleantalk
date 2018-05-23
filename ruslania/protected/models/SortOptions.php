<?php

class SortOptions
{
    const PriceLH  = 3;
    const PriceHL = 4;
    const TimeLH = 7;
    const TimeHL = 8;
    const NewLH = 11;
    const NewHL = 12;
    const DeliveryTimeLH = 15;
    const DeliveryTimeHL = 16;

    public static function GetSortData()
    {
        return array(
            self::PriceLH => Yii::app()->ui->item('SORTBY_ALL_PRICE_ASC'),
            self::PriceHL => Yii::app()->ui->item('SORTBY_ALL_PRICE_DESC'),
            self::TimeLH => Yii::app()->ui->item('SORTBY_ALL_DATE_ASC'),
            self::TimeHL => Yii::app()->ui->item('SORTBY_ALL_DATE_DESC'),
            self::NewLH => Yii::app()->ui->item('SORTBY_ALL_ADD_DATE_ASC'),
            self::NewHL => Yii::app()->ui->item('SORTBY_ALL_ADD_DATE_DESC'),
        );
    }

    public static function GetDefaultSort($sort)
    {
        $data = self::GetSortData();
        if(array_key_exists($sort, $data)) return $sort;
        return self::NewHL;
    }

    public static function GetSQL($sort, $lang, $entity=null)
    {
        switch($sort)
        {
            case self::NewHL : return ' t.add_date DESC ';//' t.in_stock DESC, t.add_date DESC ';
            case self::NewLH : return ' t.add_date ASC ';
            case self::TimeLH :
                if($entity == Entity::PERIODIC) return ' t.id ASC';
                else return ' t.year ASC ';
            case self::TimeHL :
                if($entity == Entity::PERIODIC) return ' t.id DESC';
                else return ' t.year DESC ';
            case self::PriceLH :
                if($entity == Entity::PERIODIC) return ' t.sub_world_year ASC';
                else return ' t.brutto ASC ';
            case self::PriceHL :
                if($entity == Entity::PERIODIC) return ' t.sub_world_year DESC';
                else return ' t.brutto DESC ';
            case self::DeliveryTimeLH : return ' t.in_shop DESC, deliveryTime.delivery_unit, deliveryTime.delivery_type_name DESC ';
            case self::DeliveryTimeHL : return ' t.in_shop DESC, deliveryTime.delivery_unit, deliveryTime.delivery_type_name ASC ';
            default : throw new CException('Sort not implemented '.$sort);
        }
    }
}