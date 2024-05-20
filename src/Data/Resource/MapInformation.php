<?php

namespace PTV\Data\Resource;

use PTV\Data\Requests\MapInformation\GetMapInformation;
use PTV\Data\Resource;

class MapInformation extends Resource
{
    public function all(): array
    {
        return $this->connector->send(new GetMapInformation())->dto();
    }
}
