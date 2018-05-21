<?php

class Group4PostCalc extends Group2_6
{

    public function Calc4Packet($returnFormatted=true)
    {
        $ret = array();
        $this->calcVAT = false;

        $economyPrice = array('Price' => 5, 'CalcVAT' => false);
        if($this->unitWeight <= 4 && !$this->notInEnvelope) $economyPrice['Price'] = 5;
        else if(!$this->notInEnvelope && $this->unitWeight > 4 && $this->unitWeight <=8) $economyPrice['Price'] = 9;
        else
        {
            $economyPrice['Price'] = 15;
            $economyPrice['CalcVAT'] = false;
        }

        $ret[Delivery::TYPE_ECONOMY] = $economyPrice;

        // PRIORITY
        $priorityPrice = array('Price' => 7, 'CalcVAT' => false);
        if($this->realUnitWeight <= 4 && !$this->notInEnvelope) $priorityPrice['Price'] = 7;
        else if(!$this->notInEnvelope && $this->realUnitWeight > 4 && $this->realUnitWeight <=8) $priorityPrice['Price'] = 15;
        else
        {
            $priorityPrice['CalcVAT'] = false;
            $priorityPrice['Price'] = 25;
        }
        $ret[Delivery::TYPE_PRIORITY] = $priorityPrice;


        $expressPrice = array('Price' => 20, 'CalcVAT' => false);
        if($this->realUnitWeight <= 4 && !$this->notInEnvelope) $expressPrice['Price'] = 20;
        else if(!$this->notInEnvelope && $this->realUnitWeight > 4 && $this->realUnitWeight <=8)
            $expressPrice['Price'] = 25;
        else
        {
            $expressPrice['CalcVAT'] = false;
            $expressPrice['Price'] = 35;
        }
        $ret[Delivery::TYPE_EXPRESS] = $expressPrice;

        if($returnFormatted) return $this->Format($ret);
        return $ret;
    }

//    public function Calc4Packet()
//    {
//        $this->calcVAT = true;
//        $ret = array();
//
//        $perusEconomy = 18;
//        $kerroinEconomy = 0.5;
//        $ret[Delivery::TYPE_ECONOMY] = array('Price' => $perusEconomy + ($this->kg * $kerroinEconomy), 'CalcVAT' => true);
//        $ret[Delivery::TYPE_PRIORITY] = array('Price' => ($perusEconomy + ($this->realKg * $kerroinEconomy)) *1.5, 'CalcVAT' => true);
//        $ret[Delivery::TYPE_EXPRESS] = array('Price' => ($perusEconomy + ($this->realKg * $kerroinEconomy)) *2, 'CalcVAT' => true);
//        return $this->Format($ret);
//    }
}