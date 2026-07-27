<?php

namespace App\Data;

use Carbon\CarbonImmutable;

final readonly class EarthquakeSearchData
{
    public function __construct(
        public CarbonImmutable $startTime,
        public CarbonImmutable $endTime,
        public float $minimumMagnitude = 1.0,
        public int $limit = 100,
    ) {}
}
