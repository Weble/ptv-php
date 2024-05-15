<?php

namespace PTV\Routing\Requests\Routing;

use DateTime;
use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * getEstimatedTimeOfArrival
 *
 * Calculates the estimated time of arrival (ETA) based on a previously calculated route and the
 * current position of the vehicle.
 */
class GetEstimatedTimeOfArrival extends Request
{
	protected Method $method = Method::GET;


	public function resolveEndpoint(): string
	{
		return "/eta/{$this->routeId}";
	}


	/**
	 * @param string $routeId The route ID returned from a previous route calculation. See [here](./concepts/waypoints) for more information.
	 *
	 * Make sure to assign unique names to all off-road and on-road waypoints in the request to obtain the route ID.
	 * Otherwise, the route ID cannot be used for ETA calculation because the waypoints cannot be identified. Furthermore,
	 * the route ID must not contain route-manipulation waypoints, combined-transport waypoints or vehicle parameters at waypoints.
	 * @param null|array $waypoint
	 * @param null|array $position
	 * @param null|array $workLogbook
	 */
	public function __construct(
		protected string $routeId,
		protected ?array $waypoint = null,
		protected ?array $position = null,
		protected ?array $workLogbook = null,
	) {
	}


	public function defaultQuery(): array
	{
		return array_filter(['waypoint' => $this->waypoint, 'position' => $this->position, 'workLogbook' => $this->workLogbook]);
	}
}
