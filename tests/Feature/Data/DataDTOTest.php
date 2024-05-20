<?php

use PTV\Data\DTO\MapInformation;
use PTV\Data\DTO\VehicleModel;
use PTV\Data\DTO\VehicleProfile;
use PTV\Data\Enums\VehicleType;
use PTV\Data\PTVData;
use PTV\Data\Requests\CustomRoadAttributes\GetCustomRoadAttributeScenario;
use PTV\Data\Requests\CustomRoadAttributes\GetRoads;
use PTV\Data\Requests\MapInformation\GetMapInformation;
use PTV\Data\Requests\VehicleModels\GetVehicleModels;
use PTV\Data\Requests\VehicleProfiles\GetPredefinedVehicleProfiles;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

test('get vehicle profiles as DTO', function () {
    $mockClient = new MockClient([
        GetPredefinedVehicleProfiles::class => MockResponse::fixture('vehicleProfiles')
    ]);

    $connector = new PTVData($_ENV['PTV_API_KEY']);
    $connector->withMockClient($mockClient);

    $profiles = $connector->vehicleProfiles()->all();

    expect($profiles)->toHaveCount(22);
    expect($profiles)->each->toBeInstanceOf(VehicleProfile::class);
});

test('get vehicle models as DTO', function () {
    $mockClient = new MockClient([
        GetVehicleModels::class => MockResponse::fixture('vehicleModels')
    ]);

    $connector = new PTVData($_ENV['PTV_API_KEY']);
    $connector->withMockClient($mockClient);

    $models = $connector->vehicleModels()->all();

    expect($models)->toHaveCount(272);
    expect($models)->each->toBeInstanceOf(VehicleModel::class);
});

test('get filtered vehicle models as DTO', function (VehicleType $type, int $total) {
    $mockClient = new MockClient([
        GetVehicleModels::class => MockResponse::fixture('vehicleModels-' . $type->value)
    ]);

    $connector = new PTVData($_ENV['PTV_API_KEY']);
    $connector->withMockClient($mockClient);

    $models = $connector->vehicleModels()->all([
        $type,
    ]);

    expect($models)->toHaveCount($total);
    expect($models)->each->toBeInstanceOf(VehicleModel::class);
})->with([
    VehicleType::TRUCK->value => [
        VehicleType::TRUCK, 206
    ],
    VehicleType::BODY->value => [
        VehicleType::BODY, 0
    ],
    VehicleType::LCV->value => [
        VehicleType::LCV, 66
    ],
    VehicleType::SCV->value => [
        VehicleType::SCV, 0
    ],
    VehicleType::SEMI_TRAILER->value => [
        VehicleType::SEMI_TRAILER, 0
    ],
    VehicleType::TRAILER->value => [
        VehicleType::TRAILER, 0
    ],
]);


test('get map information as DTO', function () {
    $mockClient = new MockClient([
        GetMapInformation::class => MockResponse::fixture('mapInformation')
    ]);
    $connector = new PTVData($_ENV['PTV_API_KEY']);
    $connector->withMockClient($mockClient);

    $models = $connector->mapInformation()->all();

    expect($models)->toHaveCount(342);
    expect($models)->each->toBeInstanceOf(MapInformation::class);
});

test('get roads information as DTO', function () {
    $mockClient = new MockClient([
        GetRoads::class => MockResponse::fixture('roads')
    ]);
    $connector = new PTVData($_ENV['PTV_API_KEY']);
    $connector->withMockClient($mockClient);

    $points = $connector->customRoadAttributes()->roads('6.626621368537682,35.493691935511414,18.520381599098922,47.09178374646217');

    expect($points)->toBeString();
});
