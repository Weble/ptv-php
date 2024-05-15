<?php

namespace PTV\Data\Requests\CustomRoadAttributes;

use DateTime;
use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * getRoads
 *
 * Get roads from (click) points on a map.
 */
class GetRoads extends Request
{
	protected Method $method = Method::GET;


	public function resolveEndpoint(): string
	{
		return "/roads";
	}


	/**
	 * @param null|string $polylineFormat
	 * @param string $points A point or a polyline to select roads.
	 *
	 * For a single point the road closest to this point will be returned. Several points will be considered
	 * a polyline and all roads intersected by this polyline will be returned. The polyline must not be closed,
	 * i.e. the first and the last point must be different. Ferries will not be selected.
	 *
	 * Format: `<point1_lat>,<point1_lon>,...,<pointN_lat>,<pointN_lon>`.
	 *
	 * A request will be rejected if it
	 * * does not contain an even number of coordinates,
	 * * contains a closed polyline,
	 * * contains invalid coordinates or
	 * * covers more than 5000 roads.
	 */
	public function __construct(
		protected ?string $polylineFormat = null,
		protected string $points,
	) {
	}


	public function defaultQuery(): array
	{
		return array_filter(['polylineFormat' => $this->polylineFormat, 'points' => $this->points]);
	}
}
