<?php 
        use App\Models\Product;
        use Goutte\Client;
        use Symfony\Component\DomCrawler\Crawler;
        $url = "http://api.scraperapi.com?api_key=6a620ba9d3253bed8983b4c0a2a1c332&url=https://www.thegioididong.com/dtdd#c=42&o=9&pi=6";

        $client = new Client();

        $crawler = $client->request('GET', $url);
        $crawler->filter('#categoryPage > div.container-productbox')->reduce(
            function(Crawler $node){
                $test = $node->filter('div.view-more')->attr('style');
                while($test == null){
                    // here I want to click on the <a> element
                    $node->filter('div.view-more > a')->nextAll();
                }
                echo $test;
            }
        );
        echo var_dump($crawler);
        $crawler->filter('ul.listproduct li.item')->each(
            function (Crawler $node) {
                try {
                    // $name = $node->filter('h3')->text();

                    // $price = $node->filter('.price')->text();

                    // $wholeStar = $node->filter('.icon-star')->count();
                    // $halfStar = $node->filter('.icon-star-half')->count();
                    // $rate = $wholeStar + 0.5 * $halfStar;
                    $link = $node->filter('a.main-contain')->attr('href');
                    $clientdetail = new Client();
                    $crawlerdetail = $clientdetail->request('GET', 'http://api.scraperapi.com?api_key=6a620ba9d3253bed8983b4c0a2a1c332&url='.$link.'');
                    $crawlerdetail->filter('body > section.detail')->reduce(
                        function(Crawler $nodedetail){
                            $productname = $nodedetail->filter('h1')->text();
                            echo $productname;
                        }
                    );

                } catch (\InvalidArgumentException $e) {
                    print("loi roi");
                }
            }
        );