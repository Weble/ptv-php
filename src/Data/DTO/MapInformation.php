<?php

namespace PTV\Data\DTO;

class MapInformation
{
    public function __construct(

        public string   $code,
        public string   $country,
        public string   $continent,
        public Features $features,
    )
    {
    }
}
