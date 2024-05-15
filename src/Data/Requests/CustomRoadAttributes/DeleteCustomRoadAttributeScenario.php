<?php

namespace PTV\Data\Requests\CustomRoadAttributes;

use DateTime;
use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * deleteCustomRoadAttributeScenario
 *
 * Delete a custom road attribute scenario.
 */
class DeleteCustomRoadAttributeScenario extends Request
{
	protected Method $method = Method::DELETE;


	public function resolveEndpoint(): string
	{
		return "/road-attributes/{$this->scenarioId}";
	}


	/**
	 * @param string $scenarioId The ID of the custom road attribute scenario.
	 */
	public function __construct(
		protected string $scenarioId,
	) {
	}
}
