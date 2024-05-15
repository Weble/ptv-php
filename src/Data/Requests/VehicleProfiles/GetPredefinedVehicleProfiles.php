<?php

namespace PTV\Data\Requests\VehicleProfiles;

use DateTime;
use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * getPredefinedVehicleProfiles
 *
 * Returns the predefined vehicle profiles for routing.
 */
class GetPredefinedVehicleProfiles extends Request
{
	protected Method $method = Method::GET;


	public function resolveEndpoint(): string
	{
		return "/vehicle-profiles/predefined";
	}


	public function __construct()
	{
	}
}
