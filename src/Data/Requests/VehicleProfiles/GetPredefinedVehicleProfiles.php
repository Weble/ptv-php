<?php

namespace PTV\Data\Requests\VehicleProfiles;

use PTV\Data\DTO\Vehicle;
use PTV\Data\DTO\VehicleProfile;
use PTV\Data\Enums\ElectricityType;
use PTV\Data\Enums\EngineType;
use PTV\Data\Enums\FuelType;
use PTV\Data\Enums\Region;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use ValueError;

/**
 * getPredefinedVehicleProfiles
 *
 * Returns the predefined vehicle profiles for routing.
 */
class GetPredefinedVehicleProfiles extends Request
{
    protected Method $method = Method::GET;


    public function resolveEndpoint(): string
    {
        return "/vehicle-profiles/predefined";
    }

    /** @return VehicleProfile[] */
    public function createDtoFromResponse(Response $response): array
    {
        $data = $response->json('profiles');

        return array_map(function (array $profile) {
                return new VehicleProfile(
                    name: $profile['name'],
                    description: $profile['description'],
                    region: Region::from($profile['region']),
                    vehicle: isset($profile['vehicle']) ? new Vehicle(
                        ...array_merge($profile['vehicle'], [
                            'engineType' => isset($profile['vehicle']['engineType']) ? EngineType::from($profile['vehicle']['engineType']) : null,
                            'fuelType' => isset($profile['vehicle']['fuelType']) ? FuelType::from($profile['vehicle']['fuelType']) : null,
                            'electricityType' => isset($profile['vehicle']['electricityType']) ? ElectricityType::from($profile['vehicle']['electricityType']) : null,
                        ])
                    ) : null,
                    currency: $profile['currency'],
                    costPerKilometer: $profile['monetaryCostOptions']['costPerKilometer'],
                    workingCostPerHour: $profile['monetaryCostOptions']['workingCostPerHour'],
                );
        }, $data);
    }
}
