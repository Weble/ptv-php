<?php

namespace PTV\Routing\DTO;

use PTV\Data\Enums\ElectricityType;
use PTV\Data\Enums\EngineType;
use PTV\Data\Enums\FuelType;
use PTV\Routing\Enums\EmissionStandard;
use PTV\Routing\Enums\EtcSubscriptionType;
use PTV\Routing\Enums\HazardousMaterial;
use PTV\Routing\Enums\LowEmissionZoneApproval;
use PTV\Routing\Enums\ParticleReductionClass;
use PTV\Routing\Enums\TruckRoute;
use PTV\Routing\Enums\TunnelRestrictionCode;

class Vehicle
{
    public function __construct(
        public readonly ?EngineType $engineType = null,
        public readonly ?FuelType $fuelType = null,
        public readonly ?ElectricityType $electricityType = null,
        // [l/100km] or [kg/100km]
        public readonly ?float $averageFuelConsumption = null,
        //  [kWh/100km]
        public readonly ?float $averageElectricityConsumption = null,
        public readonly ?int $bioFuelRatio = null,
        public readonly ?int $hybridRatio = null,
        public readonly ?int $dualFuelRatio = null,
        // cm^3
        public readonly ?int $cylinderCapacity = null,
        public readonly ?EmissionStandard $emissionStandard = null,
        public readonly ?int $co2EmissionClass = null,
        /** @var array<LowEmissionZoneApproval>|null */
        public readonly ?array $lowEmissionZoneApprovals = null,
        public readonly ?ParticleReductionClass $particleReductionClass = null,
        // [kg]
        public readonly ?int $emptyWeight = null,
        // [kg]
        public readonly ?int $loadWeight = null,
        // [kg]
        public readonly ?int $totalPermittedWeight = null,
        // [kg]
        public readonly ?int $totalTechnicallyPermittedWeight = null,
        // [kg]
        public readonly ?int $axleWeight = null,
        public readonly ?int $numberOfAxles = null,
        public readonly ?int $numberOfTires = null,
        public readonly ?int $height = null,
        public readonly ?int $heightAboveFrontAxle = null,
        public readonly ?int $length = null,
        public readonly ?int $width = null,
        /** @var array<HazardousMaterial>|null */
        public readonly ?array $hazardousMaterials = null,
        public readonly ?TunnelRestrictionCode $tunnelRestrictionCode = null,
        /** @var array<TruckRoute>|null */
        public readonly ?array $truckRoutes = null,
        public readonly ?bool $commercial = null,
        /** @var array<EtcSubscriptionType>|null */
        public readonly ?array $etcSubscriptions = null,
    )
    {
    }
}
