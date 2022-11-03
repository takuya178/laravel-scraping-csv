<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Goutte\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Pokemons;

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
        for($i = 0; $i < 3; $i++) {
            $crawler = \Goutte::request('GET', $links[$i]);
            dump($this->getPokemonHp($crawler));
            // Pokemons::create([
            //     'type_id' => $i + 1,
            //     'url' => $links[$i],
            //     'name' => getPokemonName($crawler),
            //     'hp' => 
            // ]);
        }
    }

    private function getPokemonName($crawler)
    {
        return $crawler->filter('tr.head > th')->text();
    }

    private function getPokemonHp($crawler): int
    {
        $hpText = $crawler->filter('table.center > tr > td.left ')->text();
        return $this->getIntStatus($hpText);
    }

    private function getIntStatus($text): int
    {
        return $this->changeInt($this->trimSpace($text));
    }

    private function trimSpace($text): array
    {
        $textList = str_split(strstr($text, "(", true));
        return array_splice($textList, 2, 3);
    }

    private function changeInt(array $trimSpace): int
    {
        return (int) implode("", $trimSpace);
    }
}
