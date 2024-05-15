<?php

namespace PTV\Data\Resource;

use PTV\Data\Requests\MapInformation\GetMapInformation;
use PTV\Data\Resource;
use Saloon\Http\Response;

class MapInformation extends Resource
{
	public function getMapInformation(): Response
	{
		return $this->connector->send(new GetMapInformation());
	}
}
