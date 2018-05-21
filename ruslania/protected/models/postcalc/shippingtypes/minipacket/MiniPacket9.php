<?php

class MiniPacket9 extends MiniPacket
{
    public function GetShippingCost()
    {
        $ret = array();
        $ret[Delivery::TYPE_ECONOMY] = array('Price' => 24, 'CalcVAT' => false);
        $ret[Delivery::TYPE_PRIORITY] = array('Price' => 27, 'CalcVAT' => false);

        $perusExpress = 39.736;
        $kerroinExpress = 14.72;
        $ret[Delivery::TYPE_EXPRESS] = array('Price' => $perusExpress + (($this->realKg-1) * $kerroinExpress), 'CalcVAT' => true);

        return $ret;
    }
}