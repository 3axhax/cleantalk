<?php

class Group10PostCalc extends BasePostCalc
{
    protected $isOrkki = true; // Yes, ORCS!!! No Free shipping at all

    public function GetRates()
    {
        return $this->Calc4Packet();
    }

    public function Calc4Packet()
    {
        $this->calcVAT = true;

        $ret = array();

        $perusEconomy = 26.11;
        $kerroinEconomy = 3;
        $ret[Delivery::TYPE_ECONOMY] = array('Price' => $perusEconomy + (($this->kg-1) * $kerroinEconomy), 'CalcVAT' => true);

        $ret[Delivery::TYPE_PRIORITY] = array('Price' => 1.5 * ($perusEconomy + (($this->realKg-1) * $kerroinEconomy)), 'CalcVAT' => true);

        $ret[Delivery::TYPE_EXPRESS] = array('Price' => 2 * ($perusEconomy + (($this->realKg-1) * $kerroinEconomy)), 'CalcVAT' => true);

        return $this->Format($ret);
    }
}


