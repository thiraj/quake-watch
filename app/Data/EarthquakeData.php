<?php

namespace App\Data;

use Carbon\CarbonImmutable;

final readonly class EarthquakeData
{
    public function __construct(
        public string $id,
        public string $location,
        public float $magnitude,
        public CarbonImmutable $occurredAt,
        public float $longitude,
        public float $latitude,
        public float $depth,
        public string $url,
    ) {}
}
