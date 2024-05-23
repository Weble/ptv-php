<?php

namespace PTV\Routing\Enums;

enum ResultType: string
{
    // Response includes the route ID. See [here](./concepts/waypoints) for more information.
    case ROUTE_ID = 'ROUTE_ID';
    // Response includes the complete **polyline** of the entire route in the format specified by **options[polylineFormat]**.
    case POLYLINE = 'POLYLINE';
    // Response includes information about the route **legs** defined as the parts of the route between two consecutive waypoints;
    case LEGS = 'LEGS';
    // Response includes the **polyline** of each of the **legs** in the format specified by **options[polylineFormat]**. _LEGS_ will automatically be included.
    case LEGS_POLYLINE = 'LEGS_POLYLINE';
    // Response includes up to three alternatives in addition to the optimal route. Only supported when exactly two on-road or off-road waypoints are specified. Please note that the additional calculations will degrade the performance.
    // Cannot be used with **options[routingMode]=MONETARY**.
    case ALTERNATIVE_ROUTES = 'ALTERNATIVE_ROUTES';
    // Response includes the guided navigation information for the [PTV Navigator](https://www.myptv.com/en/logistics-software/ptv-navigator).de]=MONETARY**.
    case GUIDED_NAVIGATION = 'GUIDED_NAVIGATION';
    // Response includes a report with monetary costs for the route. See [here](./concepts/monetary-costs) for more information.gation) for more information.
    case MONETARY_COSTS = 'MONETARY_COSTS';

    // Response includes the toll **costs** of the route.
    case TOLL_COSTS = 'TOLL_COSTS';
    // Response includes the list of toll **sections** defined by the toll operators.
    case TOLL_SECTIONS = 'TOLL_SECTIONS';
    // Response includes the list of toll **systems** defined by the toll operators.';
    case TOLL_SYSTEMS = 'TOLL_SYSTEMS';
    // Response includes **events** when a toll road is entered, exited or a toll booth is passed.';
    case TOLL_EVENTS = 'TOLL_EVENTS';

    // TODO: events, emissions, post operation related result types.
}
