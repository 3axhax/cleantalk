<?php

class MiniPacket extends BaseShippingType
{

    function GetShippingCost()
    {
        throw new Exception('Dont call directly');
    }

    function GetType()
    {
        return 'MiniPacket';
    }
}