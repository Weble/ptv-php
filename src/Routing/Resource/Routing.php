<?php

namespace PTV\Routing\Resource;

use PTV\Data\Enums\VehicleProfile;
use PTV\Routing\DTO\MonetaryCostOptions;
use PTV\Routing\DTO\Options;
use PTV\Routing\DTO\Route;
use PTV\Routing\DTO\Vehicle;
use PTV\Routing\Enums\ResultType;
use PTV\Routing\Requests\Routing\CalculateRoute;
use PTV\Routing\Requests\Routing\CalculateRoutePost;
use PTV\Routing\Requests\Routing\GetEstimatedTimeOfArrival;
use PTV\Routing\Requests\Routing\GetRouteByRouteId;
use PTV\Routing\Resource;
use Saloon\Http\Response;
use TypeError;

class Routing extends Resource
{
    private ?array $results = null;
    private ?MonetaryCostOptions $costOptions = null;
    private ?Options $options = null;
    private ?Vehicle $vehicle = null;
    private ?VehicleProfile $profile = null;

    public function withOptions(Options $options): self
    {
        $this->options = $options;
        return $this;
    }

    public function withProfile(VehicleProfile $profile): self
    {
        $this->profile = $profile;
        return $this;
    }

    public function forVehicle(Vehicle $vehicle): self
    {
        $this->vehicle = $vehicle;
        return $this;
    }

    public function withCostOptions(MonetaryCostOptions $options): self
    {
        $this->costOptions = $options;
        return $this;
    }

    /**
     * @param array<ResultType> $results
     * @return $this
     */
    public function return(array|ResultType $results): self
    {
        $this->results = $results instanceof ResultType ? [$results] : $results;
        return $this;
    }

    public function returnAlso(ResultType $type): self
    {
        $this->results = array_merge($this->results ?? [], [$type]);
        return $this;
    }

    /**
     * @param array<string> $waypoints
     * @return Route
     */
    public function calculate(array $waypoints): Route
    {
        if (count($waypoints) < 2) {
            throw new TypeError("You need to specify at least two waypoints");
        }

        return $this->sendCalculateRouteRequest(
            waypoints: $waypoints
        );
    }

    public function recalculate(Route|string $route): Route
    {
        if ($route instanceof Route) {
            $route = $route->routeId;
        }

        return $this->sendCalculateRouteRequest(
            routeId: $route
        );
    }

    /**
     * @param array<string>|null $waypoints
     * @see calculate
     */
    private function sendCalculateRouteRequest(
        ?array               $waypoints = null,
        ?string              $routeId = null,
    ): Route
    {
        return $this->connector->send(
            new CalculateRoute(
                waypoints: $waypoints,
                routeId: $routeId,
                profile: $this->profile,
                vehicle: $this->vehicle,
                options: $this->options,
                monetaryCostOptions: $this->costOptions,
                results: $this->results,
            )
        )->dtoOrFail();
    }

    /**
     * @param string $profile A profile defines a vehicle by a set of attributes,
     * matching typical transport situations.
     * It must either be the name of one of the [predefined profiles](../data-api/concepts/profiles) such as _EUR_TRAILER_TRUCK_ or a UUID of a predefined [vehicle model](../data-api/code-samples/vehicle-models) from the Data API.
     *
     * If this parameter is not specified and the first waypoint or the routeId is located in the Americas,
     * _USA_8_SEMITRAILER_5AXLE_ is used as the default instead of _EUR_TRAILER_TRUCK_.
     *
     * If the first waypoint or the routeId is located in the Americas but a non-American profile is specified or vice-versa, a warning is returned (routing only).
     * Always use a profile which matches the region of the waypoints to obtain best results.
     *
     * If a model of an electric vehicle is used, the electricity consumption of the concrete vehicle model can be calculated.
     * Some parameters like **vehicle[engineType]** cannot be used with a model of an electric vehicle.
     * Those parameters are automatically filled as applicable from the selected model.
     * Please refer to the [concept](./concepts/model-based-ev-consumption-calculation) to see specifically which parameters are not compatible.
     * These vehicle model profiles are in a preview state, the API is stable, feature changes could be introduced in future.
     *
     * If the attributes of the profile do not fit to your vehicle, the values can be changed by the corresponding attributes in the **vehicle** parameter (routing only).
     * The values of the predefined profiles may be adapted to reflect current vehicle standards. To obtain the same results when values change, it is recommended to
     * always send with the request the **vehicle** parameters that are important for your use case.
     * @param array $vehicle Physical and legal properties of the vehicle such as its dimensions to override the values of the selected **profile**.
     *
     * These parameters will be ignored for non-motorized profiles such as _BICYCLE_ or _PEDESTRIAN_.
     * Unsupported parameters such as **electricityType** for combustion vehicles should not be specified in the request.
     *
     * Use array notation like `vehicle[emissionStandard]=EURO_5` to set vehicle attributes.
     * @param array $driver Options regarding the driver's working hours.
     * @param array $options Routing-relevant options like date of travel or the use of additional data.
     * Use array notation like `options[trafficMode]=AVERAGE` to set options.
     * @param array $monetaryCostOptions Relevant options to report the monetary costs of a route when _MONETARY_COSTS_ are requested in the **results**.
     * Used for monetary cost routing when **options[routingMode]=MONETARY** is set.
     * The costs have to be specified in the currency that is set in **options[currency]**.
     * @param array $evOptions Relevant options to report the electricity consumption of an electric vehicle along a route when _EV_REPORT_, _EV_STATUS_EVENTS_, _EV_STATUS_EVENTS_POLYLINE_ or _EV_CHARGE_EVENTS_ are requested in the **results**.
     * Use array notation like `evOptions[initialStateOfCharge]=100` to set options.
     *
     * This parameter is in a preview state, the API is stable, feature changes could be introduced in future.
     * @param array $results Comma-separated list that defines which results will be returned.
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
    public function calculateRoutePost(
        ?string $profile,
        ?array  $vehicle,
        ?array  $driver,
        ?array  $options,
        ?array  $monetaryCostOptions,
        ?array  $evOptions,
        ?array  $results,
    ): Response
    {
        return $this->connector->send(new CalculateRoutePost($profile, $vehicle, $driver, $options, $monetaryCostOptions, $evOptions, $results));
    }


    public function get(Route|string $route): Route
    {
        if ($route instanceof Route) {
            $route = $route->routeId;
        }
        return $this->connector->send(new GetRouteByRouteId($route))->dtoOrFail();
    }


    /**
     * @param string $routeId The route ID returned from a previous route calculation. See [here](./concepts/waypoints) for more information.
     *
     * Make sure to assign unique names to all off-road and on-road waypoints in the request to obtain the route ID.
     * Otherwise, the route ID cannot be used for ETA calculation because the waypoints cannot be identified. Furthermore,
     * the route ID must not contain route-manipulation waypoints, combined-transport waypoints or vehicle parameters at waypoints.
     * @param array $waypoint
     * @param array $position
     * @param array $workLogbook
     */
    public function getEstimatedTimeOfArrival(
        string $routeId,
        ?array $waypoint,
        ?array $position,
        ?array $workLogbook,
    ): Response
    {
        return $this->connector->send(new GetEstimatedTimeOfArrival($routeId, $waypoint, $position, $workLogbook));
    }
}
