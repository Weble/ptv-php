<?php

namespace PTV\Data\DTO;


class VehicleModel
{
    public function __construct(
        public string     $id,
        public string     $predefinedProfile,
        public string     $vehicleType,
        public Commercial $commercial,
        public Engine     $engine,
        public Battery    $battery,
    )
    {
    }
}
