<?php

namespace PTV\Data;

use PTV\Data\Resource\CustomRoadAttributes;
use PTV\Data\Resource\MapInformation;
use PTV\Data\Resource\VehicleModels;
use PTV\Data\Resource\VehicleProfiles;
use PTV\PTV;
use Saloon\Http\Connector;

/**
 * Data
 *
 * With the Data service you can obtain additional data such as vehicle profiles.
 */
class PTVData extends PTV
{
	public function resolveBaseUrl(): string
	{
		return parent::resolveBaseUrl() . '/data/v1';
	}

	public function customRoadAttributes(): CustomRoadAttributes
	{
		return new CustomRoadAttributes($this);
	}

	public function mapInformation(): MapInformation
	{
		return new MapInformation($this);
	}

	public function vehicleModels(): VehicleModels
	{
		return new VehicleModels($this);
	}

	public function vehicleProfiles(): VehicleProfiles
	{
		return new VehicleProfiles($this);
	}
}
