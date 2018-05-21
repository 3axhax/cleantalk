<?php

class Packet7 extends Packet
{
    public function GetShippingCost()
    {
        $ret = array();

        $perusEconomy = 17.0392;
        $kerroinEconomy = 1.19;
        $ret[Delivery::TYPE_ECONOMY] = array('Price' => $perusEconomy + ($this->kg * $kerroinEconomy), 'CalcVAT' => true);

        $ret[Delivery::TYPE_PRIORITY] = array('Price' => 1.5 * $perusEconomy + ($this->realKg * $kerroinEconomy), 'CalcVAT' => true);

        $perusExpress = 29.736;
        $kerroinExpress = 6.8;

        $ret[Delivery::TYPE_EXPRESS] = array('Price' => $perusExpress + ($this->realKg * $kerroinExpress), 'CalcVAT' => true);
        return $ret;
    }
}