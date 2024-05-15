<?php

namespace PTV\Routing\Requests\ReachableLocations;

use DateTime;
use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * deleteReachableLocations
 *
 * Cancels a reachable locations calculation and deletes the calculated results specified by its ID.
 * Results already calculated cannot be requested by its ID, anymore.
 */
class DeleteReachableLocations extends Request
{
	protected Method $method = Method::DELETE;


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
