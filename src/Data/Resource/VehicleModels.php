<?php

namespace PTV\Data\Resource;

use PTV\Data\DTO\VehicleModel;
use PTV\Data\Enums\VehicleType;
use PTV\Data\Requests\VehicleModels\GetVehicleModels;
use PTV\Data\Resource;

class VehicleModels extends Resource
{
    /**
     * @param string|null $identification String which must be contained in the vehicle model or the vehicle variant. See **commercial** in the response.
     * @param VehicleType[]|null $vehicleTypes A comma-separated list of vehicle types. See **vehicleType** in the response.
     * @return VehicleModel[]
     *
     * The following vehicle types are supported.
     *
     * Tractor-like vehicle types:
     * * `TRUCK` - Truck. Total permitted weight > 7.5t.
     * * `LCV` - Light Commercial Vehicle. Total permitted weight < 7.5t.
     * * `SCV` - Small Commercial Vehicle. Total permitted weight < 3.5t.
     *
     * Trailer-like vehicle types:
     * * `TRAILER` - Trailer
     * * `SEMI_TRAILER` - Semi-trailer
     * * `BODY` - Body
     *
     * This list can be extended at any time.
     */
    public function all(?array $vehicleTypes = null, ?string $identification = null): array
    {
        return $this->connector->send(new GetVehicleModels($vehicleTypes, $identification))->dto();
    }
}
