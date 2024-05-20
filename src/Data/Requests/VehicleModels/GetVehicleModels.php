<?php

namespace PTV\Data\Requests\VehicleModels;

use PTV\Data\DTO\Battery;
use PTV\Data\DTO\Commercial;
use PTV\Data\DTO\Engine;
use PTV\Data\DTO\VehicleModel;
use PTV\Data\Enums\VehicleType;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

/**
 * getVehicleModels
 *
 * A list of **VehicleModel** objects. Only vehicle models matching all filters are returned.
 * In case
 * that no vehicle model is found an empty list is returned. In case no filters are applied, all
 * available vehicle models are returned.
 *
 * This method is in a preview state, the API is stable,
 * feature changes could be introduced in future.
 */
class GetVehicleModels extends Request
{
    protected Method $method = Method::GET;

    /**
     * @param null|string $identification String which must be contained in the vehicle model or the vehicle variant. See **commercial** in the response.
     * @param null|VehicleType[] $vehicleTypes A comma-separated list of vehicle types. See **vehicleType** in the response.
     * The following vehicle types are supported.
     *
     * Tractor-like vehicle types:
     * * `TRUCK` - Truck. Total permitted weight > 7.5t.
     * * `LCV` - Light Commercial Vehicle. Total permitted weight < 7.5t.
     * * `SCV` - Small Commercial Vehicle. Total permitted weight < 3.5t.
     *
     * Trailer-like vehicle types:
     * * `TRAILER` - Trailer
     * * `SEMI_TRAILER` - Semi-trailer
     * * `BODY` - Body
     *
     * This list can be extended at any time.
     */
    public function __construct(
        protected ?array  $vehicleTypes = null,
        protected ?string $identification = null,
    )
    {
    }

    public function resolveEndpoint(): string
    {
        return "/vehicle-models";
    }

    /** @return VehicleModel[] */
    public function createDtoFromResponse(Response $response): array
    {
        $data = $response->json('vehicleModels');

        return array_map(function (array $model) {
            return new VehicleModel(
                id: $model['id'],
                predefinedProfile: $model['predefinedProfile'],
                vehicleType: $model['vehicleType'],
                commercial: new Commercial(...$model['commercial']),
                engine: new Engine(...$model['engine']),
                battery: new Battery(...$model['battery']),
            );
        }, $data);
    }


    public function defaultQuery(): array
    {
        return array_filter(['identification' => $this->identification, 'vehicleTypes' => $this->vehicleTypes ? array_map(fn(VehicleType $type) => $type->value, $this->vehicleTypes) : null]);
    }

}
