<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TemperatureController extends Controller
{
    public function mean() {
        return number_format(DB::table('tbl_temperature')->avg('temp'), 2);
    }

    public function median() {
        $total_registries = DB::table('tbl_temperature')->count('temp');
        $half_registries = $total_registries / 2;

        if($half_registries % 2 == 1) {

            return DB::table('tbl_temperature')->where('id', '=', $half_registries)->select('temp')->first()->temp;
        }else{
            $firstValue = DB::table('tbl_temperature')->where('id', '=', $half_registries)->select('temp')->first();
            $secondValue = DB::table('tbl_temperature')->where('id', '=', $half_registries+1)->select('temp')->first();

            $mean = ($firstValue + $secondValue) / 2;
            return $mean;
        }
    }

    public function lower() {
        return DB::table('tbl_temperature')->whereRaw('LOWER(temp)')->first()->temp;
    }

    public function higher() {
        return DB::table('tbl_temperature')->where('temp', \DB::raw('(select MAX(temp) FROM tbl_temperature)'))->first()->temp;
    }

    public function greater_than_ten() {
        $greather_than_ten = DB::table('tbl_temperature')->where('temp', '>', '10')->count();
        $total_registries =  DB::table('tbl_temperature')->count('temp');

        if($greather_than_ten > 0) {
            $percentage = ($greather_than_ten / $total_registries) * 100;

            return number_format($percentage, 2);
        }
    }

    public function lower_than_minus_ten() {
        $lower_than_minus_ten = DB::table('tbl_temperature')->where('temp', '<', '-10')->count();
        $total_registries =  DB::table('tbl_temperature')->count('temp');

        if($lower_than_minus_ten > 0) {
            $percentage = ($lower_than_minus_ten / $total_registries) * 100;

            return number_format($percentage, 2);
        }
    }

    public function between_both_limits() {
        $between_both_values = DB::table('tbl_temperature')->whereBetween('temp', [-10,10])->count();
        $total_registries =  DB::table('tbl_temperature')->count('temp');

        if($between_both_values > 0) {
            $percentage = ($between_both_values / $total_registries) * 100;

            return number_format($percentage, 2);
        }
    }


}
