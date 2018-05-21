<?php

class Packet5 extends Packet
{
    public function GetShippingCost()
    {
        $ret = array();
        $ret[Delivery::TYPE_ECONOMY] = array('Price' => 18.5, 'CalcVAT' => false);
        $ret[Delivery::TYPE_PRIORITY] = array('Price' => 30, 'CalcVAT' => false);
        $ret[Delivery::TYPE_EXPRESS] = array('Price' => 40, 'CalcVAT' => false);
        return $ret;
    }
}