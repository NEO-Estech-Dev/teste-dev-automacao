<?php

namespace App\Jobs;

use App\Models\Temperature;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProcessCSVFileJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    public string $path;
    public function __construct(string $path)
    {
        $this->path = $path;
    }

    public function handle(): void
    {
        if (!Storage::exists($this->path)) {
            return;
        }

        try {
            $file = Storage::path($this->path);
            $handle = fopen($file, 'r');

            fgetcsv($handle);
            while ($row = fgetcsv($handle)) {
                Temperature::create([
                    'date' => $row[0],
                    'temperature' => (float) $row[1],
                ]);
            }

            fclose($handle);
        } catch (\Exception $e) {
            Log::error('Error processing CSV file: ' . $e->getMessage(), [
                'path' => $this->path,
                'exception' => $e,
            ]);
        }
    }
}
