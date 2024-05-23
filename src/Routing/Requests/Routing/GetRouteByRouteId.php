<?php

namespace PTV\Routing\Requests\Routing;

use DateTime;
use PTV\Routing\DTO\Route;
use RuntimeException;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

/**
 * getRouteByRouteId
 *
 * Returns the route of a previously calculated route or an alternative route. The response will use
 * the same parameters and contain all results of the previously calculated route. Although the route
 * itself will be the same, other results might be slightly different such as the travel time or  toll
 * costs. See [here](./concepts/waypoints) for more information.
 */
class GetRouteByRouteId extends Request
{
    use CreateRouteFromResponse;

	protected Method $method = Method::GET;

	public function resolveEndpoint(): string
	{
		return "/routes/{$this->routeId}";
	}

	/**
	 * @param string $routeId The route ID returned from a previous route calculation or alternative route.
	 */
	public function __construct(
		protected string $routeId,
	) {
	}

    public function createDtoFromResponse(Response $response): Route
    {
        if (!$response->successful()) {
            $errors = $response->json();

            throw new RuntimeException(json_encode($errors), $response->status());
        }

        return $this->parseRoute($response->json());
    }
}
