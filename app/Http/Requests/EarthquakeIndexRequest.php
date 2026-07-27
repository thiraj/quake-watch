<?php

namespace App\Http\Requests;

use App\Data\EarthquakeSearchData;
use App\Enums\EarthquakePeriod;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class EarthquakeIndexRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<int, string|Enum>>
     */
    public function rules(): array
    {
        return [
            'period' => ['sometimes', Rule::enum(EarthquakePeriod::class)],
            'minimum_magnitude' => ['sometimes', 'numeric', 'min:0', 'max:10'],
        ];
    }

    public function toSearchData(): EarthquakeSearchData
    {
        $period = EarthquakePeriod::from(
            $this->validated('period', EarthquakePeriod::Day->value),
        );

        $endTime = CarbonImmutable::now('UTC');

        return new EarthquakeSearchData(
            startTime: $period->startTime($endTime),
            endTime: $endTime,
            minimumMagnitude: (float) $this->validated('minimum_magnitude', 1.0),
        );
    }
}
