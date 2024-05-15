<?php

namespace PTV\Routing\Requests\ReachableAreas;

use DateTime;
use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * deleteReachableAreas
 *
 * Cancels a reachable areas calculation and deletes the calculated results specified by its ID.
 * Results already calculated cannot be requested by its ID, anymore.
 */
class DeleteReachableAreas extends Request
{
	protected Method $method = Method::DELETE;


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
