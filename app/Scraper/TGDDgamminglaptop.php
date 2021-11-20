<?php

namespace App\Scraper;

use App\Models\Brand_phone;
use App\Models\Category_laptop;
use App\Models\Product;
use App\Models\Tgdd_phone;
use App\Models\Tgdd_phone_detail;
use Composer\DependencyResolver\Request;
use Exception;
use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;

class TGDDgamminglaptop
{

    public function scrape()
    {
        $url = "https://www.thegioididong.com/laptop?g=laptop-gaming#c=44&p=37699&o=9&pi=3";
        $client = new Client();
        $crawler = $client->Request('GET',$url);
        if(Category_laptop::where('name','=','Laptop Gamming'))
        $crawler->filter('ul.listproduct li.item')->each(
            function(Crawler $node){
                try{

                }
                catch(Exception $e){

                }
            }
        );
        // $count = $crawler->filter('ul')->children('li')->count();
        // print($count);
    }
}
