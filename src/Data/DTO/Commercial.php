<?php

namespace PTV\Data\DTO;


class Commercial
{
    public function __construct(
        public string $manufacturer,
        public string $model,
        public ?string $variant = null,
        public ?int    $launchYear = null,
    )
    {
    }
}
