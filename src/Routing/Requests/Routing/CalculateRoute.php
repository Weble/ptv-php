<?php

namespace PTV\Routing\Requests\Routing;

use DateTimeImmutable;
use Money\Currency;
use Money\Money;
use PTV\Data\DTO\TariffVersion;
use PTV\Data\DTO\TollSystem;
use PTV\Data\Enums\VehicleProfile;
use PTV\Routing\DTO\Currencies;
use PTV\Routing\DTO\ExchangeRate;
use PTV\Routing\DTO\Leg;
use PTV\Routing\DTO\MonetaryCostOptions;
use PTV\Routing\DTO\MonetaryCosts;
use PTV\Routing\DTO\Options;
use PTV\Routing\DTO\Route;
use PTV\Routing\DTO\Toll\Cost;
use PTV\Routing\DTO\Toll\CountryPrice;
use PTV\Routing\DTO\Toll\Event;
use PTV\Routing\DTO\Toll\EventToll;
use PTV\Routing\DTO\Toll\RoadType;
use PTV\Routing\DTO\Toll\Section;
use PTV\Routing\DTO\Toll\SectionCost;
use PTV\Routing\DTO\Toll\Toll;
use PTV\Routing\DTO\Vehicle;
use PTV\Routing\Enums\EtcSubscriptionType;
use PTV\Routing\Enums\HazardousMaterial;
use PTV\Routing\Enums\LowEmissionZoneApproval;
use PTV\Routing\Enums\PaymentMethod;
use PTV\Routing\Enums\ResultType;
use PTV\Routing\Enums\TruckRoute;
use RuntimeException;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

/**
 * calculateRoute
 *
 * Calculates a route by specifying a list of waypoints.
 * @param null|array $waypoints The list of waypoints the route will be calculated for. At least two waypoints are necessary, a maximum number may apply according to your subscription.
 * The first waypoint is the start and the last is the destination of the route.
 * Additional intermediate waypoints are possible.
 * The format of each waypoint is `<lat>,<lon>[;<attribute>;<attribute>;...]`
 * representing a point with the latitude value in degrees from south to north
 * and the longitude value in degrees (WGS84/EPSG:4326) from west to east.
 * This point will be matched to the nearest possible road.
 * By default the air-line connection between given and matched coordinates is not included in the route polyline, distance and duration.
 * We will refer to this type of waypoint as an _on-road waypoint_.
 *
 * The behaviour of a waypoint can be changed by appending the following attributes:
 *  * `includeLastMeters` to include the air-line connection between given and matched coordinates in the route polyline, distance and duration.
 *  We will refer to this type of waypoint as an _off-road waypoint_.
 *  * `roadAccess=<lat>,<lon>`, to use these coordinates for matching to the nearest road. Implies **includeLastMeters**, i.e.
 *  the air-line connection between the waypoint coordinates and the matched coordinates
 *  is included in the route polyline, distance and duration. This is useful if the waypoint should not be matched to the nearest possible road but to some road further away,
 *  e.g. garage exit at a different road.
 *  * `matchSideOfStreet`, specifies that this waypoint will be reached at the side of street on which it is located.
 *  This is useful to prevent the driver from crossing the street to actually reach the location represented by this waypoint.
 *  * `radius=<distance>`, influences the route course, so that the route passes an area defined by the given radius [m] (integer value).
 *  This waypoint will not appear as a waypoint event in the response and may not be used as start and destination.
 *  `radius` must be > 0 and is not compatible with any other attribute on the same waypoint except for `name`.
 *  We will refer to this type of waypoint as a _route-manipulation waypoint_.
 *  * `name=<name of waypoint>`, is an identifier to reference this waypoint in the response.
 *  * In order to influence the route course so that the route uses a specific ferry or railway connection between two locations the waypoint is formatted as follows:
 *  `combinedTransport=<lat>,<lon>,<lat>,<lon>`. Both locations will be matched to the nearest ports looking for a direct connection.
 *  If no connection can be found, this waypoint will be ignored, and the warning _ROUTING_COMBINED_TRANSPORT_WAYPOINT_IGNORED_ will be returned.
 *  If more than one connection is found, the best one will be used,
 *  and the alternative connections will be returned in the response in a warning _ROUTING_COMBINED_TRANSPORT_WAYPOINT_AMBIGUOUS_.
 *  This waypoint will not appear as a waypoint event in the response and may not be used as start or destination.
 *  We will refer to this type of waypoint as a _combined-transport waypoint_.
 *
 *  See [here](./concepts/waypoints) for more information.
 * @param null|string $routeId Instead of the waypoint mentioned above, a **routeId** from a previously calculated route or a matched track can be entered.
 * More information and applying restrictions can be found [here](./concepts/waypoints).
 * @param null|VehicleProfile $profile A profile defines a vehicle by a set of attributes, matching typical transport situations.
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
 * @param null|array $vehicle Physical and legal properties of the vehicle such as its dimensions to override the values of the selected **profile**.
 *
 * These parameters will be ignored for non-motorized profiles such as _BICYCLE_ or _PEDESTRIAN_.
 * Unsupported parameters such as **electricityType** for combustion vehicles should not be specified in the request.
 *
 * Use array notation like `vehicle[emissionStandard]=EURO_5` to set vehicle attributes.
 * @param null|array $options Routing-relevant options like date of travel or the use of additional data.
 * Use array notation like `options[trafficMode]=AVERAGE` to set options.
 * @param null|MonetaryCostOptions $monetaryCostOptions Relevant options to report the monetary costs of a route when _MONETARY_COSTS_ are requested in the **results**.
 * Used for monetary cost routing when **options[routingMode]=MONETARY** is set.
 * The costs have to be specified in the currency that is set in **options[currency]**.
 * @param null|array<ResultType> $results Comma-separated list that defines which results will be returned.
 * _TOLL_COSTS_, _TOLL_SECTIONS_, _TOLL_SYSTEMS_ and _TOLL_EVENTS_ will be ignored for non-motorized profiles such as _BICYCLE_ or _PEDESTRIAN_.
 * For electric vehicles and non-motorized profiles such as _BICYCLE_ or _PEDESTRIAN_ all emission values will be 0.
 *
 * **Main results:**
 *  * `ROUTE_ID`
 *     Response includes the route ID. See [here](./concepts/waypoints) for more information.
 *  * `POLYLINE`
 *     Response includes the complete **polyline** of the entire route in the format specified by **options[polylineFormat]**.
 *  * `LEGS`
 *     Response includes information about the route **legs** defined as the parts of the route between two consecutive waypoints.
 *  * `LEGS_POLYLINE`
 *     Response includes the **polyline** of each of the **legs** in the format specified by **options[polylineFormat]**. _LEGS_ will automatically be included.
 *  * `ALTERNATIVE_ROUTES`
 *     Response includes up to three alternatives in addition to the optimal route. Only supported when exactly two on-road or off-road waypoints are specified. Please note that the additional calculations will degrade the performance.
 *     Cannot be used with **options[routingMode]=MONETARY**.
 *  * `GUIDED_NAVIGATION`
 *     Response includes the guided navigation information for the [PTV Navigator](https://www.myptv.com/en/logistics-software/ptv-navigator).
 *     See [here](./concepts/guided-navigation) for more information.
 *  * `MONETARY_COSTS`
 *     Response includes a report with monetary costs for the route. See [here](./concepts/monetary-costs) for more information.
 *
 * **Toll-related results:**
 *  * `TOLL_COSTS`
 *     Response includes the toll **costs** of the route.
 *  * `TOLL_SECTIONS`
 *     Response includes the list of toll **sections** defined by the toll operators.
 *  * `TOLL_SYSTEMS`
 *     Response includes the list of toll **systems** defined by the toll operators.
 *  * `TOLL_EVENTS`
 *     Response includes **events** when a toll road is entered, exited or a toll booth is passed.
 *
 * **Events:**
 *  * `MANEUVER_EVENTS`
 *     Response includes **events** for a **maneuver** when the driver has to take an action, e.g. turn left or right.
 *  * `BORDER_EVENTS`
 *     Response includes **events** when a **border** of a country or subdivision is crossed by the route.
 *  * `VIOLATION_EVENTS`
 *     Response includes **events** when the route contains a **violation**, e.g. entering or exiting an area where passing with the current vehicle is prohibited.
 *  * `VIOLATION_EVENTS_POLYLINE`
 *     The response contains the **polyline** of each route violation. _VIOLATION_EVENTS_ will automatically be included.
 *  * `WAYPOINT_EVENTS`
 *     Response includes **events** when a **waypoint** is reached by the route.
 *  * `UTC_OFFSET_CHANGE_EVENTS`
 *     Response includes **events** when the offset to UTC changes (**utcOffsetChange**).
 *  * `COMBINED_TRANSPORT_EVENTS`
 *     Response includes **events** when a combined transport is entered or exited.
 *  * `TRAFFIC_EVENTS`
 *     Response includes **events** when a traffic incident such as a traffic jam is reached by the route.
 *  * `TRAFFIC_EVENTS_POLYLINE`
 *     The response contains the **polyline** of each traffic events. _TRAFFIC_EVENTS_ will automatically be included.
 *  * `LOW_EMISSION_ZONE_EVENTS`
 *     Response includes **events** when a low-emission zone is entered or exited by the route.
 *
 * **Emission-related results:**
 *  * `EMISSIONS_EN16258_2012`
 *     Response includes information on **emissions** (**EN16258_2012**) calculated according to EN16258 from 2012 (a.k.a. CEN) based on the total fuel consumption for this route.
 *     Only vehicles with **engineType** _COMBUSTION_ and **fuelType** _GASOLINE_, _DIESEL_, _COMPRESSED_NATURAL_GAS_ or _LIQUEFIED_PETROLEUM_GAS_ are supported.
 *     For _GASOLINE_ and _DIESEL_, an arbitrary **bioFuelRatio** is supported.
 *     This is a European emission standard which should only be used with [European profiles](../data-api/concepts/profiles).
 *     It is mutually exclusive with **EMISSIONS_EN16258_2012_HBEFA**.
 *  * `EMISSIONS_EN16258_2012_HBEFA`
 *     Response includes information on **emissions** (**EN16258_2012**) calculated according to EN 16258 from 2012 (a.k.a. CEN) based on the total fuel consumption for this route
 *     which is automatically calculated through HBEFA 4.2. The **averageFuelConsumption** will be ignored.
 *     This is a European emission standard which should only be used with [European profiles](../data-api/concepts/profiles).
 *     Supported vehicles are the same as those of _EMISSIONS_EN16258_2012_.
 *     It is mutually exclusive with **EMISSIONS_EN16258_2012**.
 *  * `EMISSIONS_ISO14083_2022`
 *     Draft version of ISO 14083:2023. See **EMISSIONS_ISO14083_2023** for more information.
 *  * `EMISSIONS_ISO14083_2022_DEFAULT_CONSUMPTION`
 *     Draft version of ISO 14083:2023. See **EMISSIONS_ISO14083_2023_DEFAULT_CONSUMPTION** for more information.
 *  * `EMISSIONS_ISO14083_2023`
 *     Response includes information on **emissions** (**ISO14083_2023**) calculated according to ISO 14083:2023 (a.k.a. ISO) based on the total fuel and electricity consumption for this route.
 *     Only supported for [European and American profiles](../data-api/concepts/profiles). Emissions are calculated using the respective factors.
 *     All fuel and electricity types are supported, for _GASOLINE_ and _DIESEL_ an arbitrary **bioFuelRatio** is supported.
 *     For **engineType** _HYBRID_ or **engineType** _COMBUSTION_ with **fuelType** _CNG_GASOLINE_ or _LPG_GASOLINE_, an arbitrary **hybridRatio** is supported.
 *     It is mutually exclusive with all other ISO14083 calculations.
 *  * `EMISSIONS_ISO14083_2023_DEFAULT_CONSUMPTION`
 *     Response includes information on **emissions** (**ISO14083_2023**) calculated according to ISO 14083:2023 (a.k.a. ISO) for based on the default fuel and electricity consumption for this route
 *     which is automatically calculated through HBEFA 4.2. The **averageFuelConsumption** and **averageElectricityConsumption** will be ignored.
 *     Only supported for [European profiles](../data-api/concepts/profiles). Emissions are calculated using the European factors.
 *     Supported vehicles are the same as those of _EMISSIONS_ISO14083_2023_.
 *     It is mutually exclusive with all other ISO14083 calculations.
 *  * `EMISSIONS_FRENCH_CO2E_DECREE_2017_639`
 *     Response includes information on **emissions** (**French_CO2e_Decree_2017_639**) calculated according to the French CO2E decree from 2017 based on the total fuel consumption for this route.
 *     Only vehicles with **engineType** _COMBUSTION_ and **fuelType** _GASOLINE_, _DIESEL_, _COMPRESSED_NATURAL_GAS_ or _LIQUEFIED_PETROLEUM_GAS_ are supported.
 *     For _GASOLINE_ a **bioFuelRatio** of _0_, _10_ and _85_ is supported, for _DIESEL_ _0_ and _30_.
 *     This is a European emission standard which should only be used with [European profiles](../data-api/concepts/profiles).
 *
 * **Results available only in the POST operation:**
 *  * `SCHEDULE_EVENTS`
 *     Response includes **events** when the driver has to take a break or a rest, perform service or wait for a waypoint to open (**schedule**).
 *  * `SCHEDULE_REPORT`
 *     Response includes the **scheduleReport** which provides an overview of the times of the schedule of this route including break and rest times.
 *  * `EV_REPORT`
 *     Response includes a report with detailed electricity consumption for electric vehicles for the route and, if they are requested, for legs. This is only available for concrete models of electric vehicles but not for general routing profiles (see documentation of **profile**). This result is in a preview state, the API is stable, feature changes could be introduced in future.
 *  * `EV_STATUS_EVENTS`
 *     Response includes events reporting the electricity consumption along the route in more detail. This is only available for concrete models of electric vehicles but not for general routing profiles (see documentation of **profile**). This result is in a preview state, the API is stable, feature changes could be introduced in future.
 *  * `EV_STATUS_EVENTS_POLYLINE`
 *     Response includes the polyline for each **evStatus**-event since the previous **evStatus**-event. _EV_STATUS_EVENTS_ will automatically be included. This is only available for concrete models of electric vehicles but not for general routing profiles (see documentation of **profile**). This result is in a preview state, the API is stable, feature changes could be introduced in future.
 *  * `EV_CHARGE_EVENTS`
 *     Response includes events proposing where the battery of the electric vehicle should be charged. The charging time is a proposal, currently for information only. It is not included in the travel time of the route and the start time of subsequent events is not offset by it. This is only available for concrete models of electric vehicles but not for general routing profiles (see documentation of **profile**). This result is in a preview state, the API is stable, feature changes could be introduced in future.
 */
class CalculateRoute extends Request
{
    use CreateRouteFromResponse;

    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return "/routes";
    }


    public function __construct(
        /** @var array<string>|null */
        protected ?array               $waypoints = null,
        protected ?string              $routeId = null,
        protected ?VehicleProfile      $profile = null,
        protected ?Vehicle             $vehicle = null,
        protected ?Options             $options = null,
        protected ?MonetaryCostOptions $monetaryCostOptions = null,
        /** @var array<ResultType>|null */
        protected ?array               $results = null,
    )
    {
    }


    public function defaultQuery(): array
    {
        return array_filter([
            'waypoints' => $this->waypoints,
            'routeId' => $this->routeId,
            'profile' => $this->profile?->value,
            'vehicle' => $this->vehicle ? array_filter([
                'engineType' => $this->vehicle->engineType?->value,
                'fuelType' => $this->vehicle->fuelType?->value,
                'electricityType' => $this->vehicle->electricityType?->value,
                'averageFuelConsumption' => $this->vehicle->averageFuelConsumption,
                'averageElectricityConsumption' => $this->vehicle->averageElectricityConsumption,
                'bioFuelRatio' => $this->vehicle->bioFuelRatio,
                'hybridRatio' => $this->vehicle->hybridRatio,
                'dualFuelRatio' => $this->vehicle->dualFuelRatio,
                'cylinderCapacity' => $this->vehicle->cylinderCapacity,
                'emissionStandard' => $this->vehicle->emissionStandard?->value,
                'co2EmissionClass' => $this->vehicle->co2EmissionClass,
                'lowEmissionZoneApprovals' => implode(",", array_map(fn(LowEmissionZoneApproval $item) => $item->value, $this->vehicle->lowEmissionZoneApprovals ?? [])),
                'particleReductionClass' => $this->vehicle->particleReductionClass?->value,
                'emptyWeight' => $this->vehicle->emptyWeight,
                'loadWeight' => $this->vehicle->loadWeight,
                'totalPermittedWeight' => $this->vehicle->totalPermittedWeight,
                'totalTechnicallyPermittedWeight' => $this->vehicle->totalTechnicallyPermittedWeight,
                'axleWeight' => $this->vehicle->axleWeight,
                'numberOfAxles' => $this->vehicle->numberOfAxles,
                'numberOfTires' => $this->vehicle->numberOfTires,
                'height' => $this->vehicle->height,
                'heightAboveFrontAxle' => $this->vehicle->heightAboveFrontAxle,
                'length' => $this->vehicle->length,
                'width' => $this->vehicle->width,
                'hazardousMaterials' =>   implode(",", array_map(fn(HazardousMaterial $item) => $item->value, $this->vehicle->hazardousMaterials ?? [])),
                'tunnelRestrictionCode' => $this->vehicle->tunnelRestrictionCode?->value,
                'truckRoutes' => implode(",", array_map(fn(TruckRoute $item) => $item->value, $this->vehicle->truckRoutes ?? [])),
                'commercial' => $this->vehicle->commercial,
                'etcSubscriptions' =>  implode(",", array_map(fn(EtcSubscriptionType $item) => $item->value, $this->vehicle->etcSubscriptions ?? [])),
            ]) ?: null : null,
            'options' => $this->options ? array_filter([
                'startTime' => $this->options->startTime?->format(DATE_ATOM),
                'arrivalTime' => $this->options->arrivalTime?->format(DATE_ATOM),
                'tollTime' => $this->options->tollTime?->format(DATE_ATOM),
                'trafficMode' => $this->options->trafficMode?->value,
                'language' => $this->options->language,
                'polylineFormat' => $this->options->polylineFormat?->value,
                'allowedCountries' => implode(",", $this->options->allowedCountries ?? []),
                'prohibitedCountries' => implode(",", $this->options->prohibitedCountries ?? []),
                'currency' => $this->options->currency?->getCode(),
                'preferTurnsOnPassengerSide' => $this->options->preferTurnsOnPassengerSide,
                'avoid' => implode(",", $this->options->avoid ?? []),
                'blockIntersectingRoads' => implode("|", $this->options->blockIntersectingRoads ?? []),
                'customRoadAttributeScenarios' => implode(",", $this->options->customRoadAttributeScenarios ?? []),
                'routingMode' => $this->options->routingMode?->value,
            ]) ?: null : null,
            'monetaryCostOptions' => $this->monetaryCostOptions ? array_filter((array)$this->monetaryCostOptions) ?: null : null,
            'results' => implode(",", array_map(fn(ResultType $resultType) => $resultType->value, $this->results ?? []))
        ]);
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
