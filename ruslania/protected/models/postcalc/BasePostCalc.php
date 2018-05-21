<?php

abstract class BasePostCalc
{
    protected $group = 0;
    protected $unitWeight = 0;
    protected $originalUnitWeight;
    protected $realUnitWeight;
    protected $kg = 0;
    protected $realKg = 0;
    protected $notInEnvelope = false;
    protected $address = null;
    protected $calcVAT = false;
    protected $isOrkki = false; // Orkkimaat, no FreeShipping at all
    protected $currency;
    const VATPercent = 24;

    public function BasePostCalc($group, $unitWeight, $realUnitWeight, $notInEnvelope, $address, $currency)
    {
        $this->group = $group;
        $this->originalUnitWeight = $unitWeight;
        $this->unitWeight = ceil($unitWeight); // округляем вес в кг. всегда вверх до целого
        $this->kg = ceil($this->unitWeight * PostCalculator::UNIT_WEIGHT); //вес в килограммах для пакетов

        $this->realUnitWeight = $realUnitWeight; // Реальный вес без учета "без почтовых"
        $this->realKg = ceil($this->realUnitWeight * PostCalculator::UNIT_WEIGHT); // Реальный вес в кг без учета без почтовых

        $this->notInEnvelope = $notInEnvelope;
        $this->address = $address;

        $this->currency = $currency;
    }


    public function IsOrc()
    {
        return $this->isOrkki;
    }

    public function Format($ret)
    {
        $rez = array();
        $rates = Currency::GetRates();
        $useVAT = Address::UseVAT($this->address);

        foreach($ret as $idx=>$data)
        {
            $value = $data['Price'];
            $calcVAT = $data['CalcVAT'];

            $value = $value * $rates[$this->currency];
            if($useVAT && $calcVAT &&
               $this->group > 1 && $this->group <=6)
            {
                $value *= (100+self::VATPercent) / 100;
            }

            $ret[$idx] = round($value, 1);

            $rez[] = array('type' => Delivery::ToString($idx),
                           'id' => $idx,
                           'currency' => $this->currency,
                           'currencyName' => Currency::ToStr($this->currency),
                           'value' => $ret[$idx]);
        }

//        $ret['OriginalUW'] = $this->originalUnitWeight;
//        $ret['CalcUW'] = $this->unitWeight;
        return $rez;
    }

    public abstract function GetRates();
    public abstract function Calc4Packet();
}