<?php

namespace PTV\Routing\Requests\ReachableLocations;

use DateTime;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * startAndCreateReachableLocations
 *
 * Starts the calculation of the sets of reachable and unreachable locations from the given ones and
 * creates them as the result.
 */
class StartAndCreateReachableLocations extends Request implements HasBody
{
	use HasJsonBody;

	protected Method $method = Method::POST;


	public function resolveEndpoint(): string
	{
		return "/reachable-locations";
	}


	/**
	 * @param null|string $waypoint The start or destination waypoint.
	 * The format of the waypoint is `<lat>,<lon>[;<attribute>;<attribute>;...]`
	 * representing a point with the latitude value in degrees from south to north
	 * and the longitude value in degrees (WGS84/EPSG:4326) from west to east.
	 * This point will be matched to the nearest possible road.
	 * By default the air-line connection between given and matched coordinates is not included in the distance or duration.
	 * We will refer to this type of waypoint as an _on-road waypoint_.
	 *
	 * The behaviour of a waypoint can be changed by appending the following attributes:
	 *   * `includeLastMeters` to include the air-line connection between given and matched coordinates in the distance or duration.
	 *   We will refer to this type of waypoint as an _off-road waypoint_.
	 *   * `roadAccess=<lat>,<lon>`, to use these coordinates for matching to the nearest road. Implies **includeLastMeters**, i.e.
	 *   the air-line connection between the waypoint coordinates and the matched coordinates
	 *   is included in the distance or duration. This is useful if the waypoint should not be matched to the nearest possible road but to some road further away,
	 *   e.g. garage exit at a different road.
	 *
	 * See [here](./concepts/waypoints) for more information.
	 * @param null|string $routeId Instead of the waypoint mentioned above, a **routeId** from a previously calculated route or a matched track can be entered.
	 * More information and applying restrictions can be found [here](./concepts/waypoints).
	 * @param null|string $profile A profile defines a vehicle by a set of attributes, matching typical transport situations.
	 * It must be the name of one of the [predefined profiles](../data-api/concepts/profiles) such as _EUR_TRAILER_TRUCK_.
	 *
	 * If this parameter is not specified and the first waypoint or the routeId is located in the Americas,
	 * _USA_8_SEMITRAILER_5AXLE_ is used as the default instead of _EUR_TRAILER_TRUCK_.
	 *
	 * If the first waypoint or the routeId is located in the Americas but a non-American profile is specified or vice-versa, a warning is returned (routing only).
	 * Always use a profile which matches the region of the waypoints to obtain best results.
	 *
	 * If the attributes of the profile do not fit to your vehicle, the values can be changed by the corresponding attributes in the **vehicle** parameter (routing only).
	 * The values of the predefined profiles may be adapted to reflect current vehicle standards. To obtain the same results when values change, it is recommended to
	 * always send with the request the **vehicle** parameters that are important for your use case.
	 * @param int $horizon The distance [m] or travel time [s] of the horizons, depending of the **horizonType** (limited to 100 km or 1 hours).
	 * @param null|string $horizonType
	 * @param null|array $options Routing-relevant options like driving direction or the use of additional data.
	 * Use array notation like `options[trafficMode]=AVERAGE` to set options.
	 */
	public function __construct(
		protected ?string $waypoint = null,
		protected ?string $routeId = null,
		protected ?string $profile = null,
		protected int $horizon,
		protected ?string $horizonType = null,
		protected ?array $options = null,
	) {
	}


	public function defaultQuery(): array
	{
		return array_filter([
			'waypoint' => $this->waypoint,
			'routeId' => $this->routeId,
			'profile' => $this->profile,
			'horizon' => $this->horizon,
			'horizonType' => $this->horizonType,
			'options' => $this->options,
		]);
	}
}
