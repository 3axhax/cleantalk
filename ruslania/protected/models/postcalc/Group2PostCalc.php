<?php

class Group2PostCalc extends Group2_6
{
    public function Calc4Packet($returnFormatted=true)
    {
        $ret = array();
        $this->calcVAT = false;
        $perusEconomy = 11;
        $kerroinEconomy = 0.1;

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
}