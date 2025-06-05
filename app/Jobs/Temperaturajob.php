<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class Temperaturajob implements ShouldQueue
{
   use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
 
     protected $filename;
    /**
     * Create a new job instance.
     */
    public function __construct($filename)
    {
        $this->filename = $filename;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if (($handle = fopen(storage_path('app/' . $this->filename), 'r')) !== false) {
        while (($data = fgetcsv($handle, 1000, ',')) !== false) {
            if ($data[0] === 'data') continue; 

            DB::table('tb_temperatura')->insert([
                'data' => $data[0],
                'temperatura' => floatval($data[1]),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        fclose($handle);
    }
    }
}
