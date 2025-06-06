<?php

namespace App\Services;

use App\Models\Job;
use App\Models\Temperature;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class TemperatureService
{
    const MAX_DIFF_DAYS = 30;

    public function analisy(array $filters = []): array
    {
        $startDate = $filters['startDate'] ?? now()->subDays(30)->toDateString();
        $endDate = $filters['endDate'] ?? now()->toDateString();

        $diffInDays = now()->parse($startDate)->diffInDays(now()->parse($endDate));
        if ($diffInDays > self::MAX_DIFF_DAYS) {
            throw new \InvalidArgumentException('Date range cannot exceed '.self::MAX_DIFF_DAYS.' days.');
        }

        $cacheKey = 'temperature_analysis_'.md5(json_encode($filters));
        $resume = Cache::remember($cacheKey, now()->addMinutes(60), function () use ($filters, $startDate, $endDate) {
            $data = Temperature::query()
            ->whereBetween('date', [$startDate, $endDate])
            ->get()
            ->groupBy(fn($item) => $item->date->format('Y-m-d'));
            
            
            $resume = [];
            foreach ($data as $date => $temperatures) {
                $values = $temperatures->pluck('temperature');
                $average = $values->avg();
                $qtd = $values->count();
                $median = $qtd % 2 === 0
                    ? ($values[$qtd / 2 - 1] + $values[$qtd / 2]) / 2
                    : $values[$qtd / 2];
    
                $resume[$date] = [
                    'average' => $average,
                    'median' => $median,
                    'max' => $values->max(),
                    'min' => $values->min(),
                    'percentageMoreThan10' => $values->filter(fn($temp) => $temp > 10)->count() / $qtd * 100,
                    'percentageLessThan10' => $values->filter(fn($temp) => $temp < 10)->count() / $qtd * 100,
                    'percentageBetween-10And10' => $values->filter(fn($temp) => $temp >= -10 && $temp <= 10)->count() / $qtd * 100,
                ];
            }
    
            return $resume;
        });

        return $resume;
    }
}

