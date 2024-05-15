<?php

namespace PTV\Data\Requests\VehicleModels;

use DateTime;
use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * getVehicleModels
 *
 * A list of **VehicleModel** objects. Only vehicle models matching all filters are returned.
 * In case
 * that no vehicle model is found an empty list is returned. In case no filters are applied, all
 * available vehicle models are returned.
 *
 * This method is in a preview state, the API is stable,
 * feature changes could be introduced in future.
 */
class GetVehicleModels extends Request
{
	protected Method $method = Method::GET;


	public function resolveEndpoint(): string
	{
		return "/vehicle-models";
	}


	/**
	 * @param null|string $identification String which must be contained in the vehicle model or the vehicle variant. See **commercial** in the response.
	 * @param null|array $vehicleTypes A comma-separated list of vehicle types. See **vehicleType** in the response.
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
	public function __construct(
		protected ?string $identification = null,
		protected ?array $vehicleTypes = null,
	) {
	}


	public function defaultQuery(): array
	{
		return array_filter(['identification' => $this->identification, 'vehicleTypes' => $this->vehicleTypes]);
	}
}
