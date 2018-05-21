<?php

class Group3PostCalc extends Group2_6
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
            $economyPrice['Price'] = 12;
            $economyPrice['CalcVAT'] = false;
        }

        $ret[Delivery::TYPE_ECONOMY] = $economyPrice;

        // PRIORITY
        $priorityPrice = array('Price' => 7, 'CalcVAT' => false);
        if($this->realUnitWeight <= 4 && !$this->notInEnvelope) $priorityPrice['Price'] = 7;
        else if(!$this->notInEnvelope && $this->realUnitWeight > 4 && $this->realUnitWeight <=8) $priorityPrice['Price'] = 12;
        else
        {
            $priorityPrice['CalcVAT'] = false;
            $priorityPrice['Price'] = 18;
        }
        $ret[Delivery::TYPE_PRIORITY] = $priorityPrice;


        $expressPrice = array('Price' => 20, 'CalcVAT' => false);
        if($this->realUnitWeight <= 4 && !$this->notInEnvelope) $expressPrice['Price'] = 20;
        else if(!$this->notInEnvelope && $this->realUnitWeight > 4 && $this->realUnitWeight <=8)
            $expressPrice['Price'] = 25;
        else
        {
            $expressPrice['CalcVAT'] = false;
            $expressPrice['Price'] = 30;
        }
        $ret[Delivery::TYPE_EXPRESS] = $expressPrice;

        if($returnFormatted) return $this->Format($ret);
        return $ret;
    }


//    public function Calc4Packet()
//    {
//        $this->calcVAT = true;
//
//        $ret = array();
//        $perusEconomy = 13.5;
//        $kerroinEconomy = 0.3;
//
//        $economyPrice = array('Price' => $perusEconomy + ($this->kg * $kerroinEconomy), 'CalcVAT' => true);
//
//        $ret[Delivery::TYPE_ECONOMY] = $economyPrice;
//        $ret[Delivery::TYPE_PRIORITY] = array('Price' => ($perusEconomy + ($this->realKg * $kerroinEconomy))*1.5, 'CalcVAT' => true);
//        $ret[Delivery::TYPE_EXPRESS] = array('Price' => ($perusEconomy + ($this->realKg * $kerroinEconomy))*2, 'CalcVAT' => true);
//
////        $ret[Delivery::TYPE_PRIORITY] = 1.5 * $ret[Delivery::TYPE_ECONOMY];
////        $ret[Delivery::TYPE_EXPRESS] = 2 * $ret[Delivery::TYPE_ECONOMY];
//        return $this->Format($ret);
//    }
}