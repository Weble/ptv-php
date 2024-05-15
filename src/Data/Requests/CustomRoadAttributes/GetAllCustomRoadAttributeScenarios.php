<?php

namespace PTV\Data\Requests\CustomRoadAttributes;

use DateTime;
use Saloon\Enums\Method;
use Saloon\Http\Request;

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
	 * @param null|string $polylineFormat
	 */
	public function __construct(
		protected ?array $results = null,
		protected ?string $polylineFormat = null,
	) {
	}


	public function defaultQuery(): array
	{
		return array_filter(['results' => $this->results, 'polylineFormat' => $this->polylineFormat]);
	}
}
