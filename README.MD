# PHP SDK for the PTV APIs

[![Latest Version](https://img.shields.io/github/release/weble/ptv-php.svg?style=flat-square)](https://github.com/weble/ptv-php/releases)
[![MIT Licensed](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![run-tests](https://img.shields.io/github/actions/workflow/status/weble/ptv-php/run-tests.yml?label=tests&style=flat-square)](https://github.com/weble/ptv-php/actions)
[![Total Downloads](https://img.shields.io/packagist/dt/weble/ptv-php.svg?style=flat-square)](https://packagist.org/packages/weble/ptv-php)

This is an SDK for the [PTV API](https://developer.myptv.com/en).

It currently implements partially the [Data API](https://developer.myptv.com/en/documentation/data-api/data-api-reference) and the [Routing API](https://developer.myptv.com/en/documentation/routing-api/routing-api-reference).

Under the hood it uses [Saloon](https://docs.saloon.dev/) to handle the requests.

It features only 2 dependencies:
- `saloonphp/saloon` to handle the requests and the SDK building in general
- `moneyphp/money` to represent the prices and currencies

Every parameter and response object is carefully mapped with a dedicated **DTO** class and **Enum**

## Installation

`composer require weble/ptv-php`

## Basic Usage

Just create the client and interact with each of the apis.

```php
use PTV\PTV;

$ptv = new PTV('[YOUR-API-KEY]');

$profiles = $ptv->data()->vehicleProfiles()->all();
$route $ptv->routing()->route()->calculate(['lat,lng', 'lat2,lng2']);
```

### Setting a language

```php
use PTV\PTV;

$ptv = new PTV('[YOUR-API-KEY]', 'it');
```

## Data Api

Only 3 endpoints are implemented as of today:

### 1. `vehicleProfiles`

```php
use PTV\PTV;

$ptv = new PTV('[YOUR-API-KEY]');
$profiles = $ptv->data()->vehicleProfiles()->all();
```
### 2. `vehicleModels`

```php
use PTV\Data\Enums\VehicleType;
use PTV\PTV;

$ptv = new PTV('[YOUR-API-KEY]');

$profiles = $ptv->data()->vehicleModels()->all();
$profiles = $ptv->data()->vehicleModels()->all([
    VehicleType::TRAILER,
    VehicleType::SEMI_TRAILER,
]);
```

### 3. `mapInformation`

```php
use PTV\Data\Enums\VehicleType;
use PTV\PTV;

$ptv = new PTV('[YOUR-API-KEY]');

$mapInfo = $ptv->data()->mapInformation()->all();
```

## Routing Api

Currently only 3 endpoints are supported:

### 1. `calculate`

This is by far the most complete one and the most likely used.
You can calculate a route by chaining parameters within the call.

Each parameter is typed for full IDE autocompletion and ease of use.

```php
use Money\Currency;
use PTV\Data\Enums\EngineType;
use PTV\Data\Enums\FuelType;
use PTV\PTV;
use PTV\Routing\DTO\MonetaryCostOptions;
use PTV\Routing\DTO\Options;
use PTV\Routing\DTO\Vehicle;
use PTV\Routing\Enums\ResultType;
use PTV\Routing\Enums\TrafficMode;

$ptv = new PTV('[YOUR-API-KEY]');
$route = $ptv
    ->routing()
    ->route()
    ->return([
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
            ResultType::GUIDED_NAVIGATION,
    ])
    ->forVehicle(
         new Vehicle(
            engineType: EngineType::COMBUSTION,
            fuelType: FuelType::DIESEL,
            numberOfAxles: 2,
            totalPermittedWeight: 75000,
         )
    )
    ->withCostOptions(
        new MonetaryCostOptions(
             costPerKilometer: 1.2
        )
    )
    ->withOptions(
        new Options(
            startTime: DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2025-01-01 09:00:00'),
            trafficMode: TrafficMode::AVERAGE,
            currency: new Currency('EUR'),
        )
    )
    ->calculate([
        "45.5422993,11.5220921",
        "53.5418064,9.9991367"
])
```
### 2. `recalculate`

Uses a previously returned Route Id to recalculate parts of the route results

```php
use Money\Currency;
use PTV\Data\Enums\EngineType;
use PTV\Data\Enums\FuelType;
use PTV\PTV;
use PTV\Routing\DTO\MonetaryCostOptions;
use PTV\Routing\DTO\Options;
use PTV\Routing\DTO\Vehicle;
use PTV\Routing\Enums\ResultType;
use PTV\Routing\Enums\TrafficMode;

$ptv = new PTV('[YOUR-API-KEY]');
$route = $ptv
    ->routing()
    ->route()
    ->return([
        ResultType::MONETARY_COSTS,
    ])
    ->withCostOptions(
        new MonetaryCostOptions(
             costPerKilometer: 1.2
        )
    )
    ->withOptions(
        new Options(
            startTime: DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2025-01-01 09:00:00'),
            trafficMode: TrafficMode::AVERAGE,
            currency: new Currency('EUR'),
        )
    )
    ->recalculate('[your-route-id]');
```

### Get Route

Get previously calculated route details, or even an alternative route detail.

```php
use Money\Currency;
use PTV\Data\Enums\EngineType;
use PTV\Data\Enums\FuelType;
use PTV\PTV;
use PTV\Routing\DTO\MonetaryCostOptions;
use PTV\Routing\DTO\Options;
use PTV\Routing\DTO\Vehicle;
use PTV\Routing\Enums\ResultType;
use PTV\Routing\Enums\TrafficMode;

$ptv = new PTV('[YOUR-API-KEY]');
$route = $ptv
    ->routing()
    ->route()
    ->get('[your-route-id]');
```

### The route object

The `Route` object is a fully typed DTO to ease reading the results of the APIs

```php
use PTV\Routing\DTO\Route;
/** @var Route  $route **/

$route->alternativeRoutes;
$route->monetaryCosts->distanceCost;
$route->toll->costs;

// ...
```
## Testing

To test you can just run 

`composer test`

It will use fixture json to test the SDK.

If you want you can also set a `.env` file with a dedicated PTV key and do some real testing with the API.

