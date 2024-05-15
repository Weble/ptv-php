<?php

use PTV\Data\DTO\VehicleProfile;
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

    $profiles = $connector->vehicleProfiles()->all();

    expect($profiles)->toHaveCount(22);
    expect($profiles)->each->toBeInstanceOf(VehicleProfile::class);
});
