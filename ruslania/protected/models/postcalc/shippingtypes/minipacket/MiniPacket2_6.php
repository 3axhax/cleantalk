<?php

class MiniPacket2_6 extends MiniPacket
{
    public function GetShippingCost()
    {
        $ret = array();
        $ret[Delivery::TYPE_ECONOMY] = array('Price' => 9, 'CalcVAT' => false);
        $ret[Delivery::TYPE_PRIORITY] = array('Price' => 12, 'CalcVAT' => false);
        $ret[Delivery::TYPE_EXPRESS] = array('Price' => 25, 'CalcVAT' => false);
        return $ret;
    }
}