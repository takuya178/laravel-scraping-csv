<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ScrapePokemon extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrape:pokemon';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrape Pokemon';

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
        echo 10 .PHP_EOL;
        return 0;
    }
}
