<?php

namespace App\Services\Temperature;

use App\Models\Temperature;
use Illuminate\Support\Facades\Cache;

class TemperatureAnalysisService
{
    public function run()
    {
        $dates = Temperature::selectRaw('DATE(registered_at) as day')
            ->distinct()
            ->pluck('day');

        $results = [];

        foreach ($dates as $day) {
            $cacheKey = "temperature_analysis_{$day}";

            $summary = Cache::remember($cacheKey, now()->addMinutes(60), function () use ($day) {
                $temps = Temperature::whereDate('registered_at', $day)->pluck('temperature');

                if ($temps->isEmpty()) {
                    return null;
                }

                $sorted = $temps->sort()->values();
                $count = $temps->count();
                $middle = (int) floor($count / 2);

                $median = $count % 2 === 0
                    ? ($sorted[$middle - 1] + $sorted[$middle]) / 2
                    : $sorted[$middle];

                return [
                    'day' => $day,
                    'average' => round($temps->avg(), 2),
                    'median' => $median,
                    'min' => $temps->min(),
                    'max' => $temps->max(),
                    'percent_above_10' => round(($temps->filter(fn($t) => $t > 10)->count() / $count) * 100, 2),
                    'percent_below_minus_10' => round(($temps->filter(fn($t) => $t < -10)->count() / $count) * 100, 2),
                    'percent_between' => round(($temps->filter(fn($t) => $t >= -10 && $t <= 10)->count() / $count) * 100, 2),
                ];
            });

            if ($summary) {
                $results[] = $summary;
            }
        }

        return $results;
    }
}