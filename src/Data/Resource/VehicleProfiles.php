<?php

namespace PTV\Data\Resource;

use PTV\Data\DTO\VehicleProfile;
use PTV\Data\Requests\VehicleProfiles\GetPredefinedVehicleProfiles;
use PTV\Data\Resource;
use Saloon\Http\Response;

class VehicleProfiles extends Resource
{
    /** @return VehicleProfile[] */
	public function all(): array
	{
		return $this->connector->send(new GetPredefinedVehicleProfiles())->dto();
	}
}
