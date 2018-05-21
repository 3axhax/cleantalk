<?php

class Envelope extends BaseShippingType
{
    function GetShippingCost()
    {
        throw new Exception('Dont call directly');
    }

    function GetType()
    {
        return 'Envelope';
    }
}