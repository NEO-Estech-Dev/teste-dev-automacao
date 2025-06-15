<?php

namespace App\Jobs;

use App\Models\Temperature;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ImportCsvJob implements ShouldQueue
{
    use Queueable, InteractsWithQueue, SerializesModels;
    public function __construct(public string $path){}

    public function handle(): void
    {
        $fullPath = base_path($this->path);

        if (!file_exists($fullPath)) {
            logger()->error("Arquivo CSV não encontrado: {$fullPath}");
            return;
        }
        $handle = fopen($this->path, 'r');
        $header = fgetcsv($handle, 0 , ',');
        while(($data = fgetcsv($handle,0,',')) !== false){
            $row = array_combine($header, $data);

            Temperature::create([
                'temperature' => (float) $row['temperatura'],
                'registered_at' => $row['data'],
            ]);
        }

        fclose($handle);
    }
}
