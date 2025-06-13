<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class getTemperatureDataJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $filePath = base_path('example.csv');

        if(File::exists($filePath)) {
            DB::beginTransaction();
            if (($handle = fopen($filePath, 'r')) !== false) {
                while ($data = fgetcsv($handle)) {
                    DB::table('tbl_temperature')->insert([
                        'collect_date' => date("Y-m-d H:i:s",strtotime($data[0])),
                        'temp' => floatval($data[1])
                    ]);
                }

                fclose($handle);
            }
            DB::commit();
        }
        echo "Dados Foram Importados com sucesso! \n";
    }
}
