<?php

class Group5PostCalc extends BasePostCalc
{
    public function GetRates()
    {
        $this->calcVAT = false;
        $ret = array();

        $economyPrice = 5;

        $needToAssign = true;
        if($this->unitWeight <= 4 && !$this->notInEnvelope) $economyPrice = 5;
        else if(!$this->notInEnvelope && $this->unitWeight > 4 && $this->unitWeight <=8) $economyPrice = 9;
        else if($this->notInEnvelope && $this->unitWeight <= 8) $economyPrice = 9;
        else
        {
            $packetPrices = $this->Calc4Packet(false);
            $ret[Delivery::TYPE_ECONOMY] = $packetPrices[Delivery::TYPE_ECONOMY];
            $needToAssign = false;
        }

        if($needToAssign)
            $ret[Delivery::TYPE_ECONOMY] = array('Price' => $economyPrice, 'CalcVAT' => false);

        $priorityPrice = 7;
        $needToAssign = true;
        if($this->realUnitWeight <= 4 && !$this->notInEnvelope) $priorityPrice = 7;
        else if(!$this->notInEnvelope && $this->realUnitWeight > 4 && $this->realUnitWeight <=8) $priorityPrice = 12;
        else
        {
            $packetPrices = $this->Calc4Packet(false);
            $ret[Delivery::TYPE_PRIORITY] = $packetPrices[Delivery::TYPE_PRIORITY];
            $needToAssign = false;
        }

        if($needToAssign)
            $ret[Delivery::TYPE_PRIORITY] = array('Price' => $priorityPrice, 'CalcVAT' => false);

        $expressPrice = 20;
        $needToAssign = true;
        if($this->realUnitWeight <= 4 && !$this->notInEnvelope) $expressPrice = 20;
        else if(!$this->notInEnvelope && $this->realUnitWeight > 4 && $this->realUnitWeight <=8)
            $expressPrice = 25;
        else
        {
            $packetPrices = $this->Calc4Packet(false);
            $ret[Delivery::TYPE_EXPRESS] = $packetPrices[Delivery::TYPE_EXPRESS];
            $needToAssign = false;
        }

        if($needToAssign)
            $ret[Delivery::TYPE_EXPRESS] = array('Price' => $expressPrice, 'CalcVAT' => false);

        return $this->Format($ret);
    }


    public function Calc4Packet($returnFormatted=true)
    {
        $ret = array();
        $this->calcVAT = false;

        $economyPrice = array('Price' => 5, 'CalcVAT' => false);
        if($this->unitWeight <= 4 && !$this->notInEnvelope) $economyPrice['Price'] = 5;
        else if(!$this->notInEnvelope && $this->unitWeight > 4 && $this->unitWeight <=8) $economyPrice['Price'] = 9;
        else
        {
            $economyPrice['Price'] = 18.5;
            $economyPrice['CalcVAT'] = false;
        }

        $ret[Delivery::TYPE_ECONOMY] = $economyPrice;

        // PRIORITY
        $priorityPrice = array('Price' => 7, 'CalcVAT' => false);
        if($this->realUnitWeight <= 4 && !$this->notInEnvelope) $priorityPrice['Price'] = 7;
        else if(!$this->notInEnvelope && $this->realUnitWeight > 4 && $this->realUnitWeight <=8) $priorityPrice['Price'] = 12;
        else if($this->notInEnvelope && $this->realUnitWeight <= 8) $priorityPrice['Price'] = 20;
        else
        {
            $priorityPrice['CalcVAT'] = false;
            $priorityPrice['Price'] = 30;
        }
        $ret[Delivery::TYPE_PRIORITY] = $priorityPrice;


        $expressPrice = array('Price' => 20, 'CalcVAT' => false);
        if($this->realUnitWeight <= 4 && !$this->notInEnvelope) $expressPrice['Price'] = 20;
        else if(!$this->notInEnvelope && $this->realUnitWeight > 4 && $this->realUnitWeight <=8) $expressPrice['Price'] = 30;
        else if($this->notInEnvelope && $this->realUnitWeight <= 8) $expressPrice['Price'] = 25;
        else
        {
            $expressPrice['CalcVAT'] = false;
            $expressPrice['Price'] = 40;
        }
        $ret[Delivery::TYPE_EXPRESS] = $expressPrice;

        if($returnFormatted) return $this->Format($ret);
        return $ret;
    }



}