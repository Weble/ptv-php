<?php

use PTV\Routing\DTO\Route;
use PTV\Routing\PTVRouting;
use PTV\Routing\Requests\Routing\CalculateRoute;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

test('Routing Test', function () {
    $mockClient = new MockClient([
        CalculateRoute::class => MockResponse::fixture('calculateRoute')
    ]);

    $connector = new PTVRouting($_ENV['PTV_API_KEY']);
    $connector->withMockClient($mockClient);

    $route = $connector->route()->calculateRoute(
        ["49.01318,8.4279", "49.01835,8.36881"]
    );

    expect($route)->toBeInstanceOf(Route::class);
    expect($route->distance)->toBe(8138);
    expect($route->travelTime)->toBe(1729);
});
