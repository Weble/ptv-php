<?php

namespace PTV\Data\DTO;

use PTV\Data\Enums\ElectricityType;
use PTV\Data\Enums\EngineType;
use PTV\Data\Enums\FuelType;

class Vehicle
{
    public function __construct(
        public readonly ?float              $bioFuelRatio,
        public readonly string           $emissionStandard,
        public readonly int              $co2EmissionClass,

        /** @var string[] $lowEmissionZoneTypes */
        public readonly array            $lowEmissionZoneTypes,

        /** @var string[] $lowEmissionZoneApprovals */
        public readonly array            $lowEmissionZoneApprovals,
        public readonly string           $particleReductionClass,
        public readonly int              $emptyWeight,
        public readonly int              $loadWeight,
        public readonly int              $totalPermittedWeight,
        public readonly int              $totalTechnicallyPermittedWeight,
        public readonly int              $axleWeight,
        public readonly int              $numberOfAxles,
        public readonly int              $numberOfTires,
        public readonly int              $height,
        public readonly int              $heightAboveFrontAxle,
        public readonly int              $length,
        public readonly int              $width,

        /** @var string[] $hazardousMaterials */
        public readonly array            $hazardousMaterials,
        public readonly string           $tunnelRestrictionCode,

        /** @var string[] $truckRoutes */
        public readonly array            $truckRoutes,
        public readonly bool             $commercial,

        /** @var string[] $etcSubscriptions */
        public readonly array            $etcSubscriptions,

        public readonly ?FuelType        $fuelType = null,
        public readonly ?ElectricityType $electricityType = null,
        public readonly ?float              $averageFuelConsumption = null,
        public readonly ?float              $averageElectricityConsumption = null,
        public readonly ?int              $hybridRatio = null,
        public readonly ?int              $dualFuelRatio = null,
        public readonly ?int              $cylinderCapacity = null,
        public readonly ?EngineType       $engineType = null,
    )
    {
    }
}
