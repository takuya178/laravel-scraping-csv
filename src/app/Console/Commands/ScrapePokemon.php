<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

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
        $this->truncateTables();
        $this->insertUrls();
    }

    private function truncateTables()
    {
        DB::table('pokemon_urls');
    }
    
    private function insertUrls()
    {
        $url = 'https://yakkun.com/swsh/zukan/';
        $crawler = \Goutte::request('GET', $url);
        $urls = $crawler->filter('ul.pokemon_list > li > a')->each(function ($node) {
            return [
                'url' => $node->attr('href'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        });
        DB::table('pokemon_urls')->insert($urls);
    }

    private function insertPokemons()
    {
        
    }
}
