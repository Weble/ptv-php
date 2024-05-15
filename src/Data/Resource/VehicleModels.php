<?php

namespace PTV\Data\Resource;

use PTV\Data\Requests\VehicleModels\GetVehicleModels;
use PTV\Data\Resource;
use Saloon\Http\Response;

class VehicleModels extends Resource
{
	/**
	 * @param string $identification String which must be contained in the vehicle model or the vehicle variant. See **commercial** in the response.
	 * @param array $vehicleTypes A comma-separated list of vehicle types. See **vehicleType** in the response.
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
	public function getVehicleModels(?string $identification, ?array $vehicleTypes): Response
	{
		return $this->connector->send(new GetVehicleModels($identification, $vehicleTypes));
	}
}
