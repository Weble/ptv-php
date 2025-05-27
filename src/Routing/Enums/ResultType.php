<?php

namespace PTV\Routing\Enums;

enum ResultType: string
{
    /** Main results */
    /** Response includes the route ID. See [here](./concepts/route-ids) for more information. */
    case ROUTE_ID = 'ROUTE_ID';
    /** Response includes the complete **polyline** of the entire route in the format specified by **options[polylineFormat]**. */
    case POLYLINE = 'POLYLINE';
    /** Response includes information about the route **legs** defined as the parts of the route between two consecutive waypoints. */
    case LEGS = 'LEGS';
    /** Response includes the **polyline** of each of the **legs** in the format specified by **options[polylineFormat]**. _LEGS_ will automatically be included. */
    case LEGS_POLYLINE = 'LEGS_POLYLINE';
    /** Response includes up to three alternatives in addition to the optimal route.
     * Not supported with more than two waypoints or with **options[routingMode]=MONETARY**.
     * Additional calculations will degrade performance. */
    case ALTERNATIVE_ROUTES = 'ALTERNATIVE_ROUTES';
    /** Response includes the guided navigation information for the [PTV Navigator](https://www.myptv.com/en/logistics-software/ptv-navigator).
     * See [here](./concepts/guided-navigation) for more information. */
    case GUIDED_NAVIGATION = 'GUIDED_NAVIGATION';
    /** Response includes a report with monetary costs for the route. See [here](./concepts/monetary-costs) for more information. */
    case MONETARY_COSTS = 'MONETARY_COSTS';

    /** Toll-related results */
    /** Response includes the toll **costs** of the route. */
    case TOLL_COSTS = 'TOLL_COSTS';
    /** Response includes the list of toll **sections** defined by the toll operators. */
    case TOLL_SECTIONS = 'TOLL_SECTIONS';
    /** Response includes the list of toll **systems** defined by the toll operators. */
    case TOLL_SYSTEMS = 'TOLL_SYSTEMS';
    /** Response includes **events** when a toll road is entered, exited or a toll booth is passed. */
    case TOLL_EVENTS = 'TOLL_EVENTS';

    /** Events */
    /** Response includes **events** for a **maneuver** when the driver has to take an action, e.g. turn left or right. */
    case MANEUVER_EVENTS = 'MANEUVER_EVENTS';
    /** Response includes **events** when a **border** of a country or subdivision is crossed by the route. */
    case BORDER_EVENTS = 'BORDER_EVENTS';
    /** Response includes **events** when the route contains a **violation**, e.g. entering or exiting an area where passing with the current vehicle is prohibited. */
    case VIOLATION_EVENTS = 'VIOLATION_EVENTS';
    /** The response contains the **polyline** of each route violation. _VIOLATION_EVENTS_ will automatically be included. */
    case VIOLATION_EVENTS_POLYLINE = 'VIOLATION_EVENTS_POLYLINE';
    /** Response includes **events** when a **waypoint** is reached by the route. */
    case WAYPOINT_EVENTS = 'WAYPOINT_EVENTS';
    /** Response includes **events** when the offset to UTC changes (**utcOffsetChange**). */
    case UTC_OFFSET_CHANGE_EVENTS = 'UTC_OFFSET_CHANGE_EVENTS';
    /** Response includes **events** when a combined transport is entered or exited. */
    case COMBINED_TRANSPORT_EVENTS = 'COMBINED_TRANSPORT_EVENTS';
    /** Response includes **events** when a traffic incident such as a traffic jam is reached by the route. */
    case TRAFFIC_EVENTS = 'TRAFFIC_EVENTS';
    /** The response contains the **polyline** of each traffic event. _TRAFFIC_EVENTS_ will automatically be included. */
    case TRAFFIC_EVENTS_POLYLINE = 'TRAFFIC_EVENTS_POLYLINE';
    /** Response includes **events** when a low-emission zone is entered or exited by the route. */
    case LOW_EMISSION_ZONE_EVENTS = 'LOW_EMISSION_ZONE_EVENTS';
    /** Response includes **events** when a road prohibited except for delivery vehicles is entered or exited by the route. */
    case DELIVERY_ONLY_EVENTS = 'DELIVERY_ONLY_EVENTS';
    /** The response contains the **polyline** of each delivery-only event. _DELIVERY_ONLY_EVENTS_ will automatically be included. */
    case DELIVERY_ONLY_EVENTS_POLYLINE = 'DELIVERY_ONLY_EVENTS_POLYLINE';

    /** Emission-related results */
    /** Emissions (EN16258_2012) calculated per EN16258:2012 based on fuel consumption.
     * For combustion engines with specific fuel types. Supports bioFuelRatio.
     * European profiles only. Mutually exclusive with EMISSIONS_EN16258_2012_HBEFA. */
    case EMISSIONS_EN16258_2012 = 'EMISSIONS_EN16258_2012';
    /** Emissions (EN16258_2012) calculated per EN16258:2012 with HBEFA 4.2 auto-calculation.
     * Ignores averageFuelConsumption. European profiles only.
     * Same vehicle support as EMISSIONS_EN16258_2012. Mutually exclusive with it. */
    case EMISSIONS_EN16258_2012_HBEFA = 'EMISSIONS_EN16258_2012_HBEFA';
    /** Draft version of ISO 14083:2023. See **EMISSIONS_ISO14083_2023** for more information. */
    case EMISSIONS_ISO14083_2022 = 'EMISSIONS_ISO14083_2022';
    /** Draft version of ISO 14083:2023. See **EMISSIONS_ISO14083_2023_DEFAULT_CONSUMPTION** for more information. */
    case EMISSIONS_ISO14083_2022_DEFAULT_CONSUMPTION = 'EMISSIONS_ISO14083_2022_DEFAULT_CONSUMPTION';
    /** Emissions (ISO14083_2023) calculated per ISO 14083:2023 based on fuel and electricity consumption.
     * For European/American profiles. Supports all fuel/electricity types, bioFuelRatio, and hybridRatio.
     * Mutually exclusive with other ISO14083 calculations. */
    case EMISSIONS_ISO14083_2023 = 'EMISSIONS_ISO14083_2023';
    /** Emissions (ISO14083_2023) calculated per ISO 14083:2023 with HBEFA 4.2 auto-calculation.
     * Ignores averageFuelConsumption and averageElectricityConsumption. European profiles only.
     * Same vehicle support as EMISSIONS_ISO14083_2023. Mutually exclusive with other ISO14083 calculations. */
    case EMISSIONS_ISO14083_2023_DEFAULT_CONSUMPTION = 'EMISSIONS_ISO14083_2023_DEFAULT_CONSUMPTION';
    /** Emissions (French_CO2e_Decree_2017_639) calculated per French CO2E decree (2017).
     * For combustion engines with specific fuel types and bioFuelRatio values.
     * European profiles only. */
    case EMISSIONS_FRENCH_CO2E_DECREE_2017_639 = 'EMISSIONS_FRENCH_CO2E_DECREE_2017_639';

    /** Results available only in the POST operation */
    /** Schedule events for breaks, rests, service, and waiting.
     * Recommend using with WAYPOINT_EVENTS to assign events to specific waypoints. */
    case SCHEDULE_EVENTS = 'SCHEDULE_EVENTS';
    /** Schedule events including driving, breaks, rests, service, and waiting.
     * Includes SCHEDULE_EVENTS automatically. Recommend using with WAYPOINT_EVENTS. */
    case SCHEDULE_EVENTS_WITH_DRIVING = 'SCHEDULE_EVENTS_WITH_DRIVING';
    /** Schedule report overview with times including breaks and rests. */
    case SCHEDULE_REPORT = 'SCHEDULE_REPORT';
    /** Detailed electricity consumption report for electric vehicles.
     * Only for specific EV models, not general profiles. Preview feature. */
    case EV_REPORT = 'EV_REPORT';
    /** Events reporting detailed electricity consumption along the route.
     * Only for specific EV models, not general profiles. Preview feature. */
    case EV_STATUS_EVENTS = 'EV_STATUS_EVENTS';
    /** Polyline for each evStatus-event since the previous one.
     * Includes EV_STATUS_EVENTS automatically. Only for specific EV models. Preview feature. */
    case EV_STATUS_EVENTS_POLYLINE = 'EV_STATUS_EVENTS_POLYLINE';
    /** Events proposing EV battery charging locations.
     * Charging time is informational only, not included in travel time. Only for specific EV models. Preview feature. */
    case EV_CHARGE_EVENTS = 'EV_CHARGE_EVENTS';
}
