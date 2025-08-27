<?php

namespace App\Console\Commands\Develepment;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class IdeHelper extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dev:ide';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $this->call('ide-helper:generate');
        $this->call('ide-helper:models', ['--nowrite' => true]);
        $this->call('ide-helper:meta'); // Should be called last

        return 0;
    }
}
