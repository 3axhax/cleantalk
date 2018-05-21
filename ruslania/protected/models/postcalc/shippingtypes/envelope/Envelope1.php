<?php

class Envelope1 extends Envelope
{
    public function GetShippingCost()
    {
        $ret = array();
        $ret[Delivery::TYPE_ECONOMY] = array('Price' => 3.9, 'CalcVAT' => false);
        $ret[Delivery::TYPE_PRIORITY] = array('Price' => 7, 'CalcVAT' => false);
        $ret[Delivery::TYPE_EXPRESS] = array('Price' => 15, 'CalcVAT' => false);
        return $ret;
    }
}