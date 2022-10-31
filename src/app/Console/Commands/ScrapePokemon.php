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
        // $crawler = \Goutte::request('GET', 'https://duckduckgo.com/html/?q=Laravel');
        // $crawler->filter('.result__title .result__a')->each(function ($node) {
        //   dump($node->text());
        // });
        // return view('welcome');

        $url = 'https://yakkun.com/swsh/zukan/';
        $crawler = \Goutte::request('GET', $url);
        $crawler->filter('ul.pokemon_list > li > a')->each(function ($node) {
            dump($node->attr('href'));
        });
    }
}
