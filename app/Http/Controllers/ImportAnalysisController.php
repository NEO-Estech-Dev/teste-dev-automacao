<?php

namespace App\Http\Controllers;

use App\Models\Import;
use Illuminate\Support\Facades\DB;

class ImportAnalysisController extends Controller
{
    public function analysis()
    {
        $data = Import::select(DB::raw('DATE(date) as day'), 'temperature')
            ->get()
            ->groupBy('day');

        $result = [];

        foreach ($data as $day => $group) {
            $values = $group->pluck('temperature')->sort()->values()->all();
            $total = count($values);
            $sum = array_sum($values);
            $average = $sum / $total;

            $median = $total % 2 === 0
                ? ($values[$total / 2 - 1] + $values[$total / 2]) / 2
                : $values[floor($total / 2)];

            $min = min($values);
            $max = max($values);
            $above10 = count(array_filter($values, fn($v) => $v > 10));
            $below10 = count(array_filter($values, fn($v) => $v < -10));
            $between = count(array_filter($values, fn($v) => $v >= -10 && $v <= 10));

            $result["Dia de registro: " . $day] = [
                'Média' => round($average, 2),
                'Mediana' => $median,
                'Mínimo' => $min,
                'Máximo' => $max,
                'Acima de 10' => round(($above10 / $total) * 100, 2),
                'Abaixo de -10' => round(($below10 / $total) * 100, 2),
                'Entre -10 e 10' => round(($between / $total) * 100, 2),
            ];
        }

        return response()->json($result);
    }
}
