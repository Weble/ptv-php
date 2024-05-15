<?php

namespace PTV\Data\Requests\CustomRoadAttributes;

use DateTime;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * createCustomRoadAttributeScenario
 *
 * Create a custom road attribute scenario.
 */
class CreateCustomRoadAttributeScenario extends Request implements HasBody
{
	use HasJsonBody;

	protected Method $method = Method::POST;


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
