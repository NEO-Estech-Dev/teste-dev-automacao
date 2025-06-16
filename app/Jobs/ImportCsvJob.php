<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ImportCsvJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var int
     */
    public $timeout = 3600;

    /**
     * @var int
     */
    public $tries = 3;

    /**
     * Create a new job instance.
     */
    protected $filePath;

    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $file = storage_path('app/' . $this->filePath);
        $handle = fopen($file, 'r');

        fgetcsv($handle);

        $records = [];
        $chunkSize = 1000;

        while (($line = fgetcsv($handle)) !== false) {
            $records[] = [
                'date' => $line[0],
                'temperature' => $line[1],
                'created_at' => now(),
                'updated_at' => now(),
            ];

            if (count($records) >= $chunkSize) {
                DB::table('imports')->insert($records);
                $records = [];
            }
        }

        if (!empty($records)) {
            DB::table('imports')->insert($records);
        }

        fclose($handle);
    }
}
