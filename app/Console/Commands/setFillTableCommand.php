<?php

namespace App\Console\Commands;

use App\Jobs\getTemperatureDataJob;
use Illuminate\Console\Command;

class setFillTableCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sendJob:temp';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envia para a fila uma job para preencher a tabela de temperatura';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        getTemperatureDataJob::dispatch();
        return 0;
    }
}
