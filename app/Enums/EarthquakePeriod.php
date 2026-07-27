<?php

namespace App\Enums;

use Carbon\CarbonImmutable;

enum EarthquakePeriod: string
{
    case Hour = 'hour';
    case Day = 'day';
    case Week = 'week';

    public function startTime(
        CarbonImmutable $endTime,
    ): CarbonImmutable {
        return match ($this) {
            self::Hour => $endTime->subHour(),
            self::Day => $endTime->subDay(),
            self::Week => $endTime->subWeek(),
        };
    }
}
