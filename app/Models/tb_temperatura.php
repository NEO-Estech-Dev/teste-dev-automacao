<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class tb_temperatura extends Model
{
    protected $table = 'tb_temperatura';


    public static function getdados()
    {
 
      $dadosTemperatura = DB::table('tb_temperatura')
        ->select(DB::raw('DATE(data) as dia'), 'temperatura')
        ->get()
        ->groupBy('dia');

    $resultado = [];

    foreach ($dadosTemperatura as $dia => $dados) {
         //ordenar de menor para maior
        $temperaturas = $dados->pluck('temperatura')->sort()->values();

        $count = $temperaturas->count();
       
        $media = $temperaturas->avg();
        $min = $temperaturas->min();
        $max = $temperaturas->max();

       
        $mediana = $count % 2 == 0
            ? ($temperaturas[$count / 2 - 1] + $temperaturas[$count / 2]) / 2
            : $temperaturas[floor($count / 2)];

        $acima10 = $temperaturas->filter(fn($v) => $v > 10)->count() / $count * 100;
        $abaixoMenos10 = $temperaturas->filter(fn($v) => $v < -10)->count() / $count * 100;
        $entre = $temperaturas->filter(fn($v) => $v >= -10 && $v <= 10)->count() / $count * 100;

        $resultado[] = [
            'dia' => $dia,
            'media' => round($media, 2),
            'mediana' => $mediana,
            'min' => $min,
            'max' => $max,
            '% acima de 10' => round($acima10, 2),
            '% abaixo de -10' => round($abaixoMenos10, 2),
            '% entre -10 e 10' => round($entre, 2),
        ];
    }

        return $resultado;

    }
}
