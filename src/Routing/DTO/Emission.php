<?php

namespace PTV\Routing\DTO;

use PTV\Routing\Enums\EmissionType;

class Emission
{
    public function __construct(
        public readonly EmissionType $type,
        public readonly ?float $fuelConsumption = null,
        public readonly ?float $electricityConsumption = null,
        public readonly ?float $co2eTankToWheel = null,
        public readonly ?float $co2eWellToWheel = null,
        public readonly ?float $energyUseTankToWheel = null,
        public readonly ?float $energyUseWellToWheel = null,
    )
    {
    }
}
