<?php

class Group2_6 extends BasePostCalc
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

    public function Calc4Packet()
    {
        throw new CException('Dont call Calc4Packet directly');
    }
}