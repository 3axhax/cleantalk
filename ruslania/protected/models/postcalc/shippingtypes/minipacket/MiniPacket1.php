<?php

class MiniPacket1 extends MiniPacket
{
    public function GetShippingCost()
    {
        $ret = array();
        $ret[Delivery::TYPE_ECONOMY] = array('Price' => 7, 'CalcVAT' => false);
        $ret[Delivery::TYPE_PRIORITY] = array('Price' => 10, 'CalcVAT' => false);
        $ret[Delivery::TYPE_EXPRESS] = array('Price' => 20, 'CalcVAT' => false);
        return $ret;
    }
}