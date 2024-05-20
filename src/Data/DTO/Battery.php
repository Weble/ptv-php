<?php

namespace PTV\Data\DTO;


class Battery
{
    public function __construct(

        public int   $totalCapacity,
        public int   $acChargingPower,
        public int   $dcChargingPower,

        /** @var string[] $plugs */
        public array $plugs,
    )
    {

    }
}
