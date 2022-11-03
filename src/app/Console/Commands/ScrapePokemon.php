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
        $this->truncateTables();
        $this->insertUrls();
        $this->pokemonLinkList();
        $this->createPokemonList($this->pokemonLinkList());
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

    const HP = 0;
    const ATTACK = 1;
    const DEFENCE = 2;
    const SPECIAL_ATTACK = 3;
    const SPECIAL_DEFENCE = 4;
    const SPEED = 5;

    private function createPokemonList(array $links)
    {
        for($i = 0; $i < 100; $i++) {
            $crawler = \Goutte::request('GET', $links[$i]);
            Pokemons::create([
                'type_id' => $i + 1,
                'url' => $links[$i],
                'name' => $this->getPokemonName($crawler),
                'hp' => $this->getPokemonStatus($crawler, self::HP),
                'attack' => $this->getPokemonStatus($crawler, self::ATTACK),
                'defence' => $this->getPokemonStatus($crawler, self::DEFENCE),
                'special_attack' => $this->getPokemonStatus($crawler, self::SPECIAL_ATTACK),
                'special_defence' => $this->getPokemonStatus($crawler, self::SPECIAL_DEFENCE),
                'speed' => $this->getPokemonStatus($crawler, self::SPEED),
            ]);
        }
    }

    private function getPokemonName($crawler)
    {
        return $crawler->filter('tr.head > th')->text();
    }

    private function getPokemonStatus($crawler, $status): int
    {
        return $this->getIntStatus($crawler
            ->filter('table.center > tr > td.left ')->eq($status)->text());
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
