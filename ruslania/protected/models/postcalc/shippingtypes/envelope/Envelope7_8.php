<?php

class Envelope7_8 extends Envelope
{
    public function GetShippingCost()
    {
        $ret = array();
        $ret[Delivery::TYPE_ECONOMY] = array('Price' => 7, 'CalcVAT' => false);
        $ret[Delivery::TYPE_PRIORITY] = array('Price' => 12, 'CalcVAT' => false);
        $ret[Delivery::TYPE_EXPRESS] = array('Price' => 30, 'CalcVAT' => false);
        return $ret;
    }
}