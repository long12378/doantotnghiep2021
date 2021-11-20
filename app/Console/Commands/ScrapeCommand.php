<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ScrapeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrape:tgdd';

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
        // $apple = new \App\Scraper\TGDD();
        // $apple->scrape();
        // $oppo = new \App\Scraper\TGDDoppo();
        // $oppo->scrape();
        // $samsung = new \App\Scraper\TGDDsamsung();
        // $samsung->scrape();
        // $vivo = new \App\Scraper\TGDDvivo();
        // $vivo->scrape();
        // $xiaomi = new \App\Scraper\TGDDxiaomi();
        // $xiaomi->scrape();
        // $realme = new \App\Scraper\TGDDrealme();
        // $realme->scrape();
        // $nokia = new \App\Scraper\TGDDnokia();
        // $nokia->scrape();
        // $mobell = new \App\Scraper\TGDDmobell();
        // $mobell->scrape();
        $mac = new \App\Scraper\TGDDgamminglaptop();
        $mac->scrape();
    }
}
