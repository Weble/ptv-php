<?php

namespace PTV\Data\Requests\MapInformation;

use DateTime;
use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * getMapInformation
 *
 * Gets information about the map. See [here](./concepts/map-information) for more information.
 */
class GetMapInformation extends Request
{
	protected Method $method = Method::GET;


	public function resolveEndpoint(): string
	{
		return "/map-information";
	}


	public function __construct()
	{
	}
}
