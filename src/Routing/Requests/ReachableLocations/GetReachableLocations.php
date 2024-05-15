<?php

namespace PTV\Routing\Requests\ReachableLocations;

use DateTime;
use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * getReachableLocations
 *
 * Gets the results of a reachable locations calculation specified by its ID.
 */
class GetReachableLocations extends Request
{
	protected Method $method = Method::GET;


	public function resolveEndpoint(): string
	{
		return "/reachable-locations/{$this->id}";
	}


	/**
	 * @param string $id The ID of the calculated reachable locations.
	 */
	public function __construct(
		protected string $id,
	) {
	}
}
