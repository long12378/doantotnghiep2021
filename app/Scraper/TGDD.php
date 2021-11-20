<?php

namespace App\Scraper;

use App\Models\Brand_phone;
use App\Models\Product;
use App\Models\Tgdd_phone;
use App\Models\Tgdd_phone_detail;
use Exception;
use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;

class TGDD
{

    public function scrape()
    {
        // curl -x "http://scraperapi:6a620ba9d3253bed8983b4c0a2a1c332@proxy-server.scraperapi.com:8001" -k "https://www.thegioididong.com/dtdd"
        //cao iphone
        $url = "http://api.scraperapi.com?api_key=6a620ba9d3253bed8983b4c0a2a1c332&url=https://www.thegioididong.com/dtdd-apple-iphone";

        $client = new Client();

        $crawler = $client->request('GET', $url);
        if(Brand_phone::where('name','=','Apple')->count() == 0){
            $newbrandphone = new Brand_phone;
            $newbrandphone->name = 'Apple';
            $newbrandphone->save();
        }
        $brand = Brand_phone::where('name','Apple')->first();
        // $crawler->filter('#categoryPage > div.container-productbox > div.view-more')->attr('style');
        $crawler->filter('ul.listproduct li.item')->each(
            function (Crawler $node) use($brand){
                try {
                    // $name = $node->filter('h3')->text();
                    $test = $node->filter('ul')->children('li')->count();
                    $image = $node->filter('a.main-contain > div.item-img.item-img_42 > img')->attr('src');
                    for($i = 0; $i < $test; $i++){
                        $dataurl = $node->filter('ul li')->eq($i)->attr('data-url');
                        $urlphone = "http://api.scraperapi.com?api_key=6a620ba9d3253bed8983b4c0a2a1c332&url=https://www.thegioididong.com".$dataurl;
                        $clientphone = new Client();
                        $crawlerphone = $clientphone->request('GET',$urlphone);
                        $crawlerphone->filter('body > section.detail')->reduce(
                            function(Crawler $node) use($image, $brand){
                                $namepre = $node->filter('h1')->text();
                                $name = substr($namepre,16);
                                $pricepre = $node->filter('div.box_main > div.box_right > div.box04.box_normal > div.price-one > div > p.box-price-present')->text();
                                $pricepre = preg_replace('/\₫/', '', $pricepre);
                                $pricepre = preg_replace('/\./', '', $pricepre);
                                $pricepre = preg_replace('/\ */', '', $pricepre);
                                $price = (int)$pricepre;
                                $newtgddphone = new Tgdd_phone;
                                $newtgddphone->name = $name;
                                $newtgddphone->price = $price;
                                $newtgddphone->image = $image;
                                if(Tgdd_phone::where('name','=',$name)->count() == 0){
                                    $newtgddphone->save();
                                }
                                $newtgddphonedetail = new Tgdd_phone_detail;
                                $newtgddphonedetail->phone_id = $newtgddphone->id;
                                $newtgddphonedetail->id_hang = $brand->id;
                                $node->filter('div.box_main > div.box_right > div.parameter > ul > li')->each(
                                    function(Crawler $node) use($newtgddphonedetail){
                                        $detailelement = $node->filter('p')->text();
                                        switch($detailelement){
                                            case "Màn hình:":
                                                $screen = "";
                                                $count = $node->filter('div')->children('span')->count();
                                                for($i=0; $i<$count; $i++){
                                                    if($screen!=""){
                                                        $screen = $screen.", ".$node->filter('div span')->eq($i)->text();
                                                    }
                                                    else{
                                                        $screen = $screen.$node->filter('div span')->eq($i)->text();
                                                    }    
                                                }
                                                $newtgddphonedetail->screen = $screen;
                                                break;
                                            case "Hệ điều hành:":
                                                $os = $node->filter('div > span')->text();
                                                $newtgddphonedetail->OS = $os;
                                                break;
                                            case "Camera sau:":
                                                $backcamera = $node->filter('div > span')->text();
                                                $newtgddphonedetail->back_camera = $backcamera;
                                                break;
                                            case "Camera trước:":
                                                $frontcamera = $node->filter('div > span')->text();
                                                $newtgddphonedetail->front_camera = $frontcamera;
                                                break;
                                            case "Chip:":
                                                $chip = $node->filter('div > span')->text();
                                                $newtgddphonedetail->chip = $chip;
                                                break;
                                            case "RAM:":
                                                $ram = $node->filter('div > span')->text();
                                                $newtgddphonedetail->RAM = $ram;
                                                break;
                                            case "Bộ nhớ trong:":
                                                $rom = $node->filter('div > span')->text();
                                                $newtgddphonedetail->ROM = $rom;
                                                break;
                                            case "SIM:":
                                                $sim = "";
                                                $count = $node->filter('div')->children('span')->count();
                                                for($i=0; $i<$count; $i++){
                                                    if($sim != ""){
                                                        $sim = $sim.", ".$node->filter('div span')->eq($i)->text();
                                                    }
                                                    else{
                                                        $sim = $sim.$node->filter('div span')->eq($i)->text();
                                                    }
                                                }
                                                $newtgddphonedetail->sim = $sim;
                                                break;
                                            case "Pin, Sạc:":
                                                $pin = "";
                                                $count = $node->filter('div')->children('span')->count();
                                                for($i=0; $i<$count; $i++){
                                                    if($pin != ""){
                                                        $pin = $pin.", ".$node->filter('div span')->eq($i)->text();
                                                    }
                                                    else{
                                                        $pin = $pin.$node->filter('div span')->eq($i)->text();
                                                    }
                                                }
                                                $newtgddphonedetail->pin = $pin;
                                                break;
                                        }
                                    }
                                );
                                if(Tgdd_phone::where('name','=',$name)->count() == 0){
                                    $newtgddphonedetail->save();
                                }
                                
                            }
                        );
                    }
                } catch (\InvalidArgumentException $e) {
                }
            }
        );
    }
}
