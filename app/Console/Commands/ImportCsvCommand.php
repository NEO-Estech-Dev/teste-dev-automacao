<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\ImportCsvJob;

class ImportCsvCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:csv {path}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import temperature CSV asynchronously using queue';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $path = $this->argument('path');
        $fullPath = storage_path('app/' . $path);

        if (!file_exists($fullPath)) {
            $this->error("File $path not found.");
            return 1;
        }

        ImportCsvJob::dispatch($path);

        $this->info("Import job dispatched for $path.");

        return 0;
    }
}
