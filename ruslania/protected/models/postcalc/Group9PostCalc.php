<?php

class Group9PostCalc extends BasePostCalc
{
    public function GetRates()
    {
        $this->calcVAT = false;
        $ret = array();

        $economyPrice = array('Price' => 20, 'CalcVAT' => false);
        if($this->unitWeight <= 4 && !$this->notInEnvelope) $economyPrice = $economyPrice;
        else if(($this->unitWeight <= 4 && $this->notInEnvelope) || ($this->unitWeight > 4 && $this->unitWeight <=8 && !$this->notInEnvelope))
            $economyPrice['Price'] = 24;
        else return $this->Calc4Packet();
        $ret[Delivery::TYPE_ECONOMY] = $economyPrice;

        $priorityPrice = array('Price' => 22, 'CalcVAT' => false);
        if($this->realUnitWeight <= 4 && !$this->notInEnvelope) $priorityPrice = $priorityPrice;
        else if(($this->realUnitWeight <= 4 && $this->notInEnvelope) || ($this->realUnitWeight > 4 && $this->realUnitWeight <=8 && !$this->notInEnvelope))
            $priorityPrice['Price'] = 27;
        else return $this->Calc4Packet();
        $ret[Delivery::TYPE_PRIORITY] = $priorityPrice;

        $perusExpress = 39.736;
        $kerroinExpress = 14.72;
        $ret[Delivery::TYPE_EXPRESS] = array('Price' => $perusExpress + (($this->realKg-1) * $kerroinExpress), 'CalcVAT' => true);

        /*
                if($this->unitWeight <= 4 && !$this->notInEnvelope)
                {
                    $ret[Delivery::TYPE_ECONOMY] = 5 + 15;
                    $ret[Delivery::TYPE_PRIORITY] = 7 + 15;
                    $ret[Delivery::TYPE_EXPRESS] = 12 + 15;
                }
                else if(($this->unitWeight <= 4 && $this->notInEnvelope) || ($this->unitWeight > 4 && $this->unitWeight <=8))
                {
                    $ret[Delivery::TYPE_ECONOMY] = 9+15;
                    $ret[Delivery::TYPE_PRIORITY] = 12+15;
                    $ret[Delivery::TYPE_EXPRESS] = 15+15;
                }
                else
                {
                    return $this->Calc4Packet();
                }
        */
        return $this->Format($ret);
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

//public function Calc4Packet()
//{
//    $this->calcVAT = true;
//
//    $perus = 39.736;
//    $kerroin = 14.72;
//    $price = $perus + ($this->kg * $kerroin);
//
//    return $this->Format(array(Delivery::TYPE_EXPRESS => $price));
//
//}
