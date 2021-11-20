<?php

namespace App\Scraper;

use App\Models\Brand_phone;
use App\Models\Product;
use App\Models\Tgdd_phone;
use App\Models\Tgdd_phone_detail;
use Exception;
use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;

class TGDDoppo
{

    public function scrape()
    {
        $url = "http://api.scraperapi.com/?api_key=6a620ba9d3253bed8983b4c0a2a1c332&url=https://www.thegioididong.com/dtdd-oppo";

        $client = new Client();

        $crawler = $client->request('GET', $url);
        if (Brand_phone::where('name', '=', 'Oppo')->count() == 0) {
            $newbrandphone = new Brand_phone;
            $newbrandphone->name = 'Oppo';
            $newbrandphone->save();
        }
        $brand = Brand_phone::where('name', 'Oppo')->first();
        // $crawler->filter('#categoryPage > div.container-productbox > div.view-more')->attr('style');
        $crawler->filter('ul.listproduct li.item')->each(
            function (Crawler $node) use ($brand) {
                try {
                    $image = $node->filter('a.main-contain > div.item-img.item-img_42 > img')->attr('data-src');
                    $dataurl = $node->filter('a.main-contain')->attr('href');
                    $urlphone = "http://api.scraperapi.com?api_key=6a620ba9d3253bed8983b4c0a2a1c332&url=https://www.thegioididong.com" . $dataurl;
                    $clientphone = new Client();
                    $crawlerphone = $clientphone->request('GET', $urlphone);
                    //cao detail
                    $crawlerphone->filter('body > section.detail')->reduce(
                        function (Crawler $node) use ($image, $brand) {
                            //lay tung link cua tung type
                            $name = $node->filter('h1')->text();
                            if ($node->filter('div.box_main > div.box_right')->children('div.scrolling_inner')->count() > 1) {
                                //co nhieu bo nho khac nhau
                                print($name . " ");
                                $countbn = $node->filter('div.box_main > div.box_right > div:nth-child(1) > div')->children('a')->count();
                                for ($i = 0; $i < $countbn; $i++) {
                                    $bnlink = $node->filter('div.box_main > div.box_right > div:nth-child(1) > div > a')->eq($i)->attr('href');
                                    $urlbn = "http://api.scraperapi.com?api_key=6a620ba9d3253bed8983b4c0a2a1c332&url=https://www.thegioididong.com" . $bnlink;
                                    $bnclient = new Client();
                                    $crawlerbn = $bnclient->request('GET', $urlbn);
                                    $crawlerbn->filter('body > section.detail')->reduce(
                                        function (Crawler $node) use ($image, $brand) {
                                            $namepre = $node->filter('h1')->text();
                                            $name = substr($namepre, 16);
                                            $pricepre = $node->filter('div.box_main > div.box_right > div.box04.box_normal > div.price-one > div > p.box-price-present')->text();
                                            $pricepre = preg_replace('/\₫/', '', $pricepre);
                                            $pricepre = preg_replace('/\./', '', $pricepre);
                                            $pricepre = preg_replace('/\ */', '', $pricepre);
                                            $price = (int)$pricepre;

                                            if (Tgdd_phone::where('name', '=', $name)->count() == 0) {
                                                $newtgddphone = new Tgdd_phone;
                                                $newtgddphone->name = $name;
                                                $newtgddphone->price = $price;
                                                $newtgddphone->image = $image;
                                                $newtgddphone->save();
                                                $newtgddphonedetail = new Tgdd_phone_detail;
                                                $newtgddphonedetail->phone_id = $newtgddphone->id;
                                                $newtgddphonedetail->id_hang = $brand->id;
                                                $node->filter('div.box_main > div.box_right > div.parameter > ul > li')->each(
                                                    function (Crawler $node) use ($newtgddphonedetail) {
                                                        $detailelement = $node->filter('p')->text();
                                                        switch ($detailelement) {
                                                            case "Màn hình:":
                                                                $screen = "";
                                                                $count = $node->filter('div')->children('span')->count();
                                                                for ($i = 0; $i < $count; $i++) {
                                                                    if ($screen != "") {
                                                                        $screen = $screen . ", " . $node->filter('div span')->eq($i)->text();
                                                                    } else {
                                                                        $screen = $screen . $node->filter('div span')->eq($i)->text();
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
                                                                for ($i = 0; $i < $count; $i++) {
                                                                    if ($sim != "") {
                                                                        $sim = $sim . ", " . $node->filter('div span')->eq($i)->text();
                                                                    } else {
                                                                        $sim = $sim . $node->filter('div span')->eq($i)->text();
                                                                    }
                                                                }
                                                                $newtgddphonedetail->sim = $sim;
                                                                break;
                                                            case "Pin, Sạc:":
                                                                $pin = "";
                                                                $count = $node->filter('div')->children('span')->count();
                                                                for ($i = 0; $i < $count; $i++) {
                                                                    if ($pin != "") {
                                                                        $pin = $pin . ", " . $node->filter('div span')->eq($i)->text();
                                                                    } else {
                                                                        $pin = $pin . $node->filter('div span')->eq($i)->text();
                                                                    }
                                                                }
                                                                $newtgddphonedetail->pin = $pin;
                                                                break;
                                                            default:
                                                                break;
                                                        }
                                                    }
                                                );

                                                $newtgddphonedetail->save();
                                            }
                                        }
                                    );
                                }
                            } else {
                                $namepre = $node->filter('h1')->text();
                                $name = substr($namepre, 16);
                                $pricepre = $node->filter('div.box_main > div.box_right > div.box04.box_normal > div.price-one > div > p.box-price-present')->text();
                                $pricepre = preg_replace('/\₫/', '', $pricepre);
                                $pricepre = preg_replace('/\./', '', $pricepre);
                                $pricepre = preg_replace('/\ */', '', $pricepre);
                                $price = (int)$pricepre;

                                if (Tgdd_phone::where('name', '=', $name)->count() == 0) {
                                    $newtgddphone = new Tgdd_phone;
                                    $newtgddphone->name = $name;
                                    $newtgddphone->price = $price;
                                    $newtgddphone->image = $image;
                                    $newtgddphone->save();
                                    $newtgddphonedetail = new Tgdd_phone_detail;
                                    $newtgddphonedetail->phone_id = $newtgddphone->id;
                                    $newtgddphonedetail->id_hang = $brand->id;
                                    $node->filter('div.box_main > div.box_right > div.parameter > ul > li')->each(
                                        function (Crawler $node) use ($newtgddphonedetail) {
                                            $detailelement = $node->filter('p')->text();
                                            switch ($detailelement) {
                                                case "Màn hình:":
                                                    $screen = "";
                                                    $count = $node->filter('div')->children('span')->count();
                                                    for ($i = 0; $i < $count; $i++) {
                                                        if ($screen != "") {
                                                            $screen = $screen . ", " . $node->filter('div span')->eq($i)->text();
                                                        } else {
                                                            $screen = $screen . $node->filter('div span')->eq($i)->text();
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
                                                    for ($i = 0; $i < $count; $i++) {
                                                        if ($sim != "") {
                                                            $sim = $sim . ", " . $node->filter('div span')->eq($i)->text();
                                                        } else {
                                                            $sim = $sim . $node->filter('div span')->eq($i)->text();
                                                        }
                                                    }
                                                    $newtgddphonedetail->sim = $sim;
                                                    break;
                                                case "Pin, Sạc:":
                                                    $pin = "";
                                                    $count = $node->filter('div')->children('span')->count();
                                                    for ($i = 0; $i < $count; $i++) {
                                                        if ($pin != "") {
                                                            $pin = $pin . ", " . $node->filter('div span')->eq($i)->text();
                                                        } else {
                                                            $pin = $pin . $node->filter('div span')->eq($i)->text();
                                                        }
                                                    }
                                                    $newtgddphonedetail->pin = $pin;
                                                    break;
                                                default:
                                                    break;
                                            }
                                        }
                                    );

                                    $newtgddphonedetail->save();
                                }
                            }
                        }
                    );
                } catch (\InvalidArgumentException $e) {
                }
            }
        );
    }
}
