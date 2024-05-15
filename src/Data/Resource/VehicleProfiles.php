<?php

namespace PTV\Data\Resource;

use PTV\Data\Requests\VehicleProfiles\GetPredefinedVehicleProfiles;
use PTV\Data\Resource;
use Saloon\Http\Response;

class VehicleProfiles extends Resource
{
	public function all(): Response
	{
		return $this->connector->send(new GetPredefinedVehicleProfiles());
	}
}
