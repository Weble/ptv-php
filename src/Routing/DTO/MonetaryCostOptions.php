<?php

namespace PTV\Routing\DTO;

class MonetaryCostOptions
{
    public function __construct(
        public readonly ?float $costPerKilometer = null,
        public readonly ?float $workingCostPerHour = null,
        public readonly ?float $costPerKwh = null,
        public readonly ?float $costPerFuelUnit = null,
    )
    {
    }
}
