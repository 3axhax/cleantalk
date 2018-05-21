<?php

abstract class BaseShippingType
{
    protected $uw;
    protected $realUW;
    protected $kg;
    protected $notInEnvelope;
    protected $realKg;
    protected $isOrkki = false;

    // realUW и realKg показывают настоящий вес и юнитвейт товара, если бы на него не было установлено "без почтовых"
    public function __construct($uw, $realUW, $kg, $realKg, $notInEnvelope)
    {
        $this->uw = $uw;
        $this->realUW = $realUW;
        $this->kg = $kg;
        $this->realKg = $realKg;
        $this->notInEnvelope = $notInEnvelope;
    }

    abstract function GetShippingCost();
    abstract function GetType();
    public function IsOrc()
    {
        return $this->isOrkki;
    }
}