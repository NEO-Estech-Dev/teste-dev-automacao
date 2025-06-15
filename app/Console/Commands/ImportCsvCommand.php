<?php

namespace App\Console\Commands;

use App\Jobs\ImportCsvJob;
use Illuminate\Console\Command;

class ImportCsvCommand extends Command
{
    protected $signature = 'import:csv {path}';

    protected $description = 'Importa um CSV de temperatura de forma assíncrona';

    public function handle(): void
    {
        $path = $this->argument('path');

        if(!file_exists($path)){
            $this->error("Arquivo CSV não encontrado: {$path}");
            return;
        }

        ImportCsvJob::dispatch($path);

        $this->info("Importação do CSV iniciada: {$path}");
    }
}
