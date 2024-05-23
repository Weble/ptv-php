<?php

use PTV\Routing\DTO\Leg;
use PTV\Routing\DTO\MonetaryCosts;
use PTV\Routing\DTO\Route;
use PTV\Routing\Enums\ResultType;
use PTV\Routing\PTVRouting;
use PTV\Routing\Requests\Routing\CalculateRoute;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

test('Routing Test', function (array $waypoints, array $resultTypes) {
    $cacheKey = implode(",", $waypoints) . '-' . implode(",", array_map(fn(ResultType $type) => $type->value, $resultTypes));
    $mockClient = new MockClient([
        CalculateRoute::class => MockResponse::fixture('calculateRoute-'. $cacheKey)
    ]);

    $connector = new PTVRouting($_ENV['PTV_API_KEY']);
    //$connector->withMockClient($mockClient);

    $route = $connector
        ->route()
        ->return($resultTypes)
        ->calculate($waypoints);

    expect($route)->toBeInstanceOf(Route::class);
    expect($route)->not->toBeNull();
    expect($route->monetaryCosts)->toBeInstanceOf(MonetaryCosts::class);
    expect($route->monetaryCosts->totalCost)->not->toBeNull();
    expect($route->leg)->toBeInstanceOf(Leg::class);

    $alternativeRoute = $connector
        ->route()
        ->return([
            ResultType::MONETARY_COSTS
        ])
        ->recalculate($route->alternativeRoutes[0]->routeId);

    expect($alternativeRoute)->toBeInstanceOf(Route::class);

    $retrievedRoute = $connector
        ->route()
        ->get($route);

    expect($retrievedRoute)->toBeInstanceOf(Route::class);
    expect($retrievedRoute->routeId)->toBe($route->routeId);

})->with([
    'Weble to YOOTheme' => [
        [
            "45.5422993,11.5220921",
            "53.5418064,9.9991367"
        ],
        [
            ResultType::MONETARY_COSTS,
            ResultType::POLYLINE,
            ResultType::LEGS,
            ResultType::LEGS_POLYLINE,
            ResultType::ROUTE_ID,
            ResultType::TOLL_COSTS,
            ResultType::TOLL_SYSTEMS,
            ResultType::TOLL_SECTIONS,
            ResultType::TOLL_EVENTS,
            ResultType::ALTERNATIVE_ROUTES,
            // ResultType::GUIDED_NAVIGATION,
        ],
    ],
]);
