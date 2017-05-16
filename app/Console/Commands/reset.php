<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class reset extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description.';

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
     * @return mixed
     */
    public function handle()
    {
        // \Artisan::call('migrate:reset');
        
        //\Artisan::call('migrate');
        \Artisan::call('create:admin');
        \Artisan::call('db:seed');
    }
}
