<?php

namespace PTV\Data\Requests\CustomRoadAttributes;

use DateTime;
use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * updateCustomRoadAttributeScenario
 *
 * Perform a full update of an existing custom road attribute scenario.
 */
class UpdateCustomRoadAttributeScenario extends Request
{
	protected Method $method = Method::PUT;


	public function resolveEndpoint(): string
	{
		return "/road-attributes/{$this->scenarioId}";
	}


	/**
	 * @param string $scenarioId The ID of the custom road attribute scenario.
	 * @param null|array $results Defines which results will be returned.
	 * @param null|string $polylineFormat
	 */
	public function __construct(
		protected string $scenarioId,
		protected ?array $results = null,
		protected ?string $polylineFormat = null,
	) {
	}


	public function defaultQuery(): array
	{
		return array_filter(['results' => $this->results, 'polylineFormat' => $this->polylineFormat]);
	}
}
