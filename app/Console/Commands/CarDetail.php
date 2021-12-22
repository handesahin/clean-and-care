<?php

namespace App\Console\Commands;

use App\Http\Controllers\CarController;
use Illuminate\Console\Command;

class CarDetail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cars:upsert';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Upsert Car Details';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }


    public function handle()
    {
        app(CarController::class)->upsertCarDetails();
    }
}
