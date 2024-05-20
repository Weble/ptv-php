<?php

namespace PTV\Data\Resource;

use PTV\Data\Enums\PolylineFormat;
use PTV\Data\Requests\CustomRoadAttributes\CreateCustomRoadAttributeScenario;
use PTV\Data\Requests\CustomRoadAttributes\DeleteCustomRoadAttributeScenario;
use PTV\Data\Requests\CustomRoadAttributes\GetAllCustomRoadAttributeScenarios;
use PTV\Data\Requests\CustomRoadAttributes\GetCustomRoadAttributeScenario;
use PTV\Data\Requests\CustomRoadAttributes\GetRoads;
use PTV\Data\Requests\CustomRoadAttributes\UpdateCustomRoadAttributeScenario;
use PTV\Data\Resource;
use Saloon\Http\Response;

class CustomRoadAttributes extends Resource
{
	/**
	 * @param array $results Defines which results will be returned.
	 * @param PolylineFormat|null $polylineFormat
	 */
	public function customRoadAttributeScenarios(?array $results = null, ?PolylineFormat $polylineFormat = null)
	{
        // TODO
		return $this->connector->send(new GetAllCustomRoadAttributeScenarios($results, $polylineFormat))->dto();
	}


	/**
	 * @param array $results Defines which results will be returned.
	 * @param string $polylineFormat
	 */
	public function createCustomRoadAttributeScenario(?array $results, ?string $polylineFormat): Response
	{
        // TODO
		return $this->connector->send(new CreateCustomRoadAttributeScenario($results, $polylineFormat));
	}


	/**
	 * @param string $scenarioId The ID of the custom road attribute scenario.
	 * @param array $results Defines which results will be returned.
	 * @param string $polylineFormat
	 */
	public function getCustomRoadAttributeScenario(
		string $scenarioId,
		?array $results,
		?string $polylineFormat,
	): Response
	{
        // TODO
		return $this->connector->send(new GetCustomRoadAttributeScenario($scenarioId, $results, $polylineFormat));
	}


	/**
	 * @param string $scenarioId The ID of the custom road attribute scenario.
	 * @param array $results Defines which results will be returned.
	 * @param string $polylineFormat
	 */
	public function updateCustomRoadAttributeScenario(
		string $scenarioId,
		?array $results,
		?string $polylineFormat,
	): Response
	{
        // TODO
		return $this->connector->send(new UpdateCustomRoadAttributeScenario($scenarioId, $results, $polylineFormat));
	}


	/**
	 * @param string $scenarioId The ID of the custom road attribute scenario.
	 */
	public function deleteCustomRoadAttributeScenario(string $scenarioId): Response
	{
        // TODO
		return $this->connector->send(new DeleteCustomRoadAttributeScenario($scenarioId));
	}


	/**
	 * @param string $points A point or a polyline to select roads.
     * @param string $polylineFormat
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
	public function roads(string $points, ?PolylineFormat $polylineFormat = null): string
	{
		return $this->connector->send(new GetRoads($points, $polylineFormat))->dto();
	}
}
