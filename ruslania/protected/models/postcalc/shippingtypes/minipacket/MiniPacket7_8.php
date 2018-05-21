<?php

class MiniPacket7_8 extends MiniPacket
{
    public function GetShippingCost()
    {
        $ret = array();
        $ret[Delivery::TYPE_ECONOMY] = array('Price' => 13, 'CalcVAT' => false);
        $ret[Delivery::TYPE_PRIORITY] = array('Price' => 15, 'CalcVAT' => false);
        $ret[Delivery::TYPE_EXPRESS] = array('Price' => 35, 'CalcVAT' => false);
        return $ret;
    }
}