<?php

use PTV\Data\PTVData;
use PTV\Data\Requests\VehicleProfiles\GetPredefinedVehicleProfiles;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

test('my test', function () {
    $mockClient = new MockClient([
        GetPredefinedVehicleProfiles::class => MockResponse::fixture('vehicleProfiles')
    ]);

    $connector = new PTVData($_ENV['PTV_API_KEY']);
    $connector->withMockClient($mockClient);

    dd($connector->vehicleProfiles()->all());
});
