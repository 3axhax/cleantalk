<?php

class Group6PostCalc extends Group2_6
{
    public function Calc4Packet($returnFormatted=true)
    {
        $this->calcVAT = true;

        $ret = array();

        $perusEconomy = 16;
        $kerroinEconomy = 1.18;
        $ret[Delivery::TYPE_ECONOMY] = array('Price' => $perusEconomy + ($this->kg * $kerroinEconomy), 'CalcVAT' => true);

        $ret[Delivery::TYPE_PRIORITY] = array('Price' => ($perusEconomy + ($this->realKg * $kerroinEconomy)) * 1.5, 'CalcVAT' => true);

        $perusExpress = 27.2816;
        $kerroinExpress = 3.74;

        $ret[Delivery::TYPE_EXPRESS] = array('Price' => $perusExpress + ($this->realKg * $kerroinExpress), 'CalcVAT' => true);

        if($returnFormatted) return $this->Format($ret);
        return $ret;
    }
}