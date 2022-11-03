<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Goutte\Client;
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
        // $this->truncateTables();
        // $this->insertUrls();
        $this->pokemonLinkList();
        $this->detailPokemon($this->pokemonLinkList());
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

    private function pokemonLinkList(): array
    {
        $url = 'https://yakkun.com/swsh/zukan/';
        $crawler = \Goutte::request('GET', $url);
        $targetLinks = $crawler->filter('ul.pokemon_list > li > a')->each(function ($node) {
            return $node->attr('href');
        });
        return $targetLinks;
    }

    private function detailPokemon(array $links)
    {
        for($i = 0; $i < 1; $i++) {
            $crawler = \Goutte::request('GET', $links[$i]);
            $targetLinks = $this->catchTextDate($crawler, 'tr.head > th');
            Pokemons::create([]);
        }
    }

    private function catchTextDate($crawler, $className)
    {
        $crawler->filter($className)->each(function ($node) {
            return $node->text();
        });
    }
}
