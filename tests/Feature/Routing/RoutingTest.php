<?php

use PTV\Routing\DTO\MonetaryCosts;
use PTV\Routing\DTO\Route;
use PTV\Routing\Enums\ResultType;
use PTV\Routing\PTVRouting;
use PTV\Routing\Requests\Routing\CalculateRoute;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

test('Routing Test', function (array $waypoints) {
    $mockClient = new MockClient([
        CalculateRoute::class => MockResponse::fixture('calculateRoute-'. sha1(json_encode($waypoints)))
    ]);

    $connector = new PTVRouting($_ENV['PTV_API_KEY']);
    $connector->withMockClient($mockClient);

    $route = $connector
        ->route()
        ->with([
            ResultType::MONETARY_COSTS,
            ResultType::POLYLINE,
            ResultType::LEGS,
            ResultType::LEGS_POLYLINE,
            ResultType::ROUTE_ID,
            ResultType::TOLL_COSTS,
            ResultType::TOLL_SYSTEMS,
            ResultType::TOLL_SECTIONS,
        ])
        ->calculateRoute(
        $waypoints
        );

    expect($route)->toBeInstanceOf(Route::class);
    expect($route->monetaryCosts)->toBeInstanceOf(MonetaryCosts::class);
    expect($route->monetaryCosts->totalCost)->not->toBeNull();
})->with([
    'Weble to YOOTheme' => [
        ["45.5422993,11.5220921", "53.5418064,9.9991367"]
    ],
]);
