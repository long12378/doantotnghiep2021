<?php

namespace App\Scraper;

use App\Models\Brand_phone;
use App\Models\Product;
use App\Models\Tgdd_phone;
use App\Models\Tgdd_phone_detail;
use Exception;
use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;

class fptapple
{
    public function scrape(){
        $url = "http://api.scraperapi.com?api_key=db2edb40264fd762f0ce7f4e082be6ae&url=https://fptshop.com.vn/dien-thoai/apple-iphone";
        $client = new Client();
        $crawler = $client->request('GET',$url);
        // $crawler->filter()
    }
}