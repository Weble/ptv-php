<?php

namespace PTV\Data\Requests\CustomRoadAttributes;

use DateTime;
use PTV\Data\Enums\PolylineFormat;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

/**
 * getAllCustomRoadAttributeScenarios
 *
 * Get a list of all custom road attribute scenarios of the current user.
 */
class GetAllCustomRoadAttributeScenarios extends Request
{
	protected Method $method = Method::GET;


	public function resolveEndpoint(): string
	{
		return "/road-attributes";
	}


	/**
	 * @param null|array $results Defines which results will be returned.
	 * @param null|PolylineFormat $polylineFormat
	 */
	public function __construct(
		protected ?array $results = null,
		protected ?PolylineFormat $polylineFormat = null,
	) {
	}


	public function defaultQuery(): array
	{
		return array_filter(['results' => $this->results, 'polylineFormat' => $this->polylineFormat]);
	}

    public function createDtoFromResponse(Response $response): mixed
    {
        dd($response->json());
    }
}
