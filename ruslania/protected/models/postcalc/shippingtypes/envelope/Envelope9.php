<?php

class Envelope9 extends Envelope
{
    public function GetShippingCost()
    {
        $ret = array();
        $ret[Delivery::TYPE_ECONOMY] = array('Price' => 20, 'CalcVAT' => false);
        $ret[Delivery::TYPE_PRIORITY] = array('Price' => 22, 'CalcVAT' => false);

        $perusExpress = 39.736;
        $kerroinExpress = 14.72;
        $ret[Delivery::TYPE_EXPRESS] = array('Price' => $perusExpress + (($this->realKg-1) * $kerroinExpress), 'CalcVAT' => true);
        return $ret;
    }
}