<?php

namespace PTV\Data\DTO;

use PTV\Data\Enums\Region;

class VehicleProfile
{
    public function __construct(
        public readonly string $name,
        public readonly string $description,
        public readonly Region $region,
        public readonly ?Vehicle $vehicle,
        public readonly string $currency,
        public readonly float  $costPerKilometer,
        public readonly float  $workingCostPerHour
    )
    {

    }

    public function enum(): ?\PTV\Data\Enums\VehicleProfile
    {
        return \PTV\Data\Enums\VehicleProfile::tryFrom($this->name);
    }
}
