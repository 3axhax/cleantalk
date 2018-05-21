<?php

class Packet extends BaseShippingType
{
    function GetShippingCost()
    {
        throw new Exception('Dont call directly');
    }

    function GetType()
    {
        return 'Packet';
    }
}