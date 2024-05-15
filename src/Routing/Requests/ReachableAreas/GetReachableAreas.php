<?php

namespace PTV\Routing\Requests\ReachableAreas;

use DateTime;
use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * getReachableAreas
 *
 * Gets the results of a reachable areas calculation specified by its ID.
 */
class GetReachableAreas extends Request
{
	protected Method $method = Method::GET;


	public function resolveEndpoint(): string
	{
		return "/reachable-areas/{$this->id}";
	}


	/**
	 * @param string $id The ID of the calculated reachable areas.
	 */
	public function __construct(
		protected string $id,
	) {
	}
}
