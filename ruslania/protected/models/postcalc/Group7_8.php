<?php

class Group7_8 extends BasePostCalc
{
    public function GetRates()
    {
        $this->calcVAT = false;
        $ret = array();
        
        $economyPrice = array('Price' => 7, 'CalcVAT' => false);
        if($this->unitWeight <= 4 && !$this->notInEnvelope) $economyPrice = $economyPrice;
        else if(($this->unitWeight <= 4 && $this->notInEnvelope) || ($this->unitWeight > 4 && $this->unitWeight <=8  && !$this->notInEnvelope))
            $economyPrice['Price'] = 13;
        else return $this->Calc4Packet();
        $ret[Delivery::TYPE_ECONOMY] = $economyPrice;


        $priorityPrice = array('Price' => 12, 'CalcVAT' => false);
        if($this->realUnitWeight <= 4 && !$this->notInEnvelope) $priorityPrice = $priorityPrice;
        else if(($this->realUnitWeight <= 4 && $this->notInEnvelope) || ($this->realUnitWeight > 4 && $this->realUnitWeight <=8 && !$this->notInEnvelope))
            $priorityPrice['Price'] = 15;
        else return $this->Calc4Packet();
        $ret[Delivery::TYPE_PRIORITY] = $priorityPrice;

        $expressPrice = array('Price' => 30, 'CalcVAT' => false);
        if($this->realUnitWeight <= 4 && !$this->notInEnvelope) $expressPrice = $expressPrice;
        else if(($this->realUnitWeight <= 4 && $this->notInEnvelope) || ($this->realUnitWeight > 4 && $this->realUnitWeight <=8 && !$this->notInEnvelope))
            $expressPrice['Price'] = 35;
        else return $this->Calc4Packet();
        $ret[Delivery::TYPE_EXPRESS] = $expressPrice;

        return $this->Format($ret);
    }

    public function Calc4Packet()
    {
        throw new CException('Dont call directly');
    }
}