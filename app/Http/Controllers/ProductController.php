<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Result;
use App\Models\URL;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use League\CommonMark\Extension\CommonMark\Node\Inline\Link;

class ProductController extends Controller
{
    public function fetchDataAmazon(Result $result)
    {
        $products = Product::with('links')->whereHas('links', function ($query) {
            $query->where('platform', 'amazon');
        })->get();

        foreach ($products as $product) {
            $identifier = $product->links->pluck('identifier')->first(); // Assuming a product has only one identifier
            $url = $product->links->pluck('url')->first();
            $filteredProducts[] = [
                'asin' => $identifier,
                'url' => $url,
            ];
        }
        $client = new Client();
        $response = $client->post('http://127.0.0.1:5000/amazon', [
            'json' => $filteredProducts
        ]);

        if ($response->getStatusCode() === 200) {
            $scrapedData = json_decode($response->getBody(), true);

            // Assuming you have a model named ScrapeData
            foreach ($scrapedData['scraped_data'] as $data) {
                $url = $data['url'];
                $link = URL::where('url', $url)->first();
                $prod = Product::where('id', $link->prod_id)->first();
                $linkSeller = Result::where('url', $link->url)->latest()->first();
                log::info($link);

                // Define last seller and last price variables based on $linkSeller existence
                $lastSeller = $linkSeller ? $linkSeller->current_seller : null;
                $lastPrice = $linkSeller ? $linkSeller->current_price : null;

                // Create the result array with all necessary values
                $resultArray = [
                    'platform' => 'amazon',
                    'date' => $data['date'],
                    'time' => $data['time'],
                    'brand' => $prod->brand,
                    'category' => $prod->category,
                    'identifier' => $link->identifier,
                    'sku' => $prod->sku,
                    'title' => $data['prod_title'],
                    'url' => $data['url'],
                    'current_seller' => $data['seller'],
                    'last_seller' => $lastSeller,
                    'current_price' => $data['price'],
                    'last_price' => $lastPrice
                ];

                // Create the result entry
                Result::create($resultArray);
            }
            return "Data sent to Flask server successfully and saved in the database";
        } else {
            return "Failed to send data to Flask server";
        }

    }




    public function fetchDataNoon(Result $result)
    {
        $products = Product::with('links')->whereHas('links', function ($query) {
            $query->where('platform', 'noon');
        })->get();
        $filteredProducts = [];

        foreach ($products as $product) {
                $identifier = $product->links->pluck('identifier')->first(); // Assuming a product has only one identifier
                $url = $product->links->pluck('url')->first();
                $filteredProducts[] = [
                    'asin' => $identifier,
                    'url' => $url
                ];
            }
        $client = new Client();
        $response = $client->post('http://127.0.0.1:5000/noon', [
            'json' => $filteredProducts
        ]);
        if ($response->getStatusCode() === 200) {
            $scrapedData = json_decode($response->getBody(), true);
            // Assuming you have a model named ScrapeData
            foreach ($scrapedData['scraped_data'] as $data) {
                $url = $data['url'];
                $link = URL::where('url', $url)->first();
                $prod = Product::where('id',$link->prod_id)->first();
                $linkSeller = Result::where('sku', $prod->sku)->latest()->first();
                $lastSeller = $linkSeller ? $linkSeller->current_seller : null;
                $lastPrice = $linkSeller ? $linkSeller->current_price : null;

                // Create the result array with all necessary values
                $resultArray = [
                    'platform' => 'noon',
                    'date' => $data['date'],
                    'time' => $data['time'],
                    'brand' => $prod->brand,
                    'category' => $prod->category,
                    'identifier' => $link->identifier,
                    'sku' => $prod->sku,
                    'title' => $data['prod_title'],
                    'url' => $data['url'],
                    'current_seller' => $data['seller'],
                    'last_seller' => $lastSeller,
                    'current_price' => $data['price'],
                    'last_price' => $lastPrice
                ];

                // Create the result entry
                Result::create($resultArray);
            }
            return "Data sent to Flask server successfully and saved in the database";
        } else {
            return "Failed to send data to Flask server";
        }
    }

    public function fetchDataJumia(Result $result)
    {
        $products = Product::with('links')->whereHas('links', function ($query) {
            $query->where('platform', 'jumia');
        })->get();

        $filteredProducts = [];

        foreach ($products as $product) {
                $url = $product->links->pluck('url')->first();
                $filteredProducts[] = [
                    'query' => $url
                ];
            }
        $client = new Client();
        $response = $client->post('http://127.0.0.1:5000/jumia', [
            'json' => $filteredProducts
        ]);
        if ($response->getStatusCode() === 200) {
            $scrapedData = json_decode($response->getBody(), true);
            // Assuming you have a model named ScrapeData
            foreach ($scrapedData['scraped_data'] as $data) {
                $url = $data['query'];
                $link = URL::where('url', $url)->first();
//                log::info($url);
                $prod = Product::where('id',$link->prod_id)->first();
                $linkSeller = Result::where('sku', $prod->sku)->latest()->first();
                $lastSeller = $linkSeller ? $linkSeller->current_seller : null;
                $lastPrice = $linkSeller ? $linkSeller->current_price : null;

                // Create the result array with all necessary values
                $resultArray = [
                    'platform' => 'jumia',
                    'date' => $data['date'],
                    'time' => $data['time'],
                    'brand' => $prod->brand,
                    'category' => $prod->category,
                    'identifier' => $link->identifier,
                    'sku' => $prod->sku,
                    'title' => $data['prod_title'],
                    'url' => $data['url'],
                    'current_seller' => $data['seller'],
                    'last_seller' => $lastSeller,
                    'current_price' => $data['price'],
                    'last_price' => $lastPrice
                ];

                // Create the result entry
                Result::create($resultArray);
            }
            return "Data sent to Flask server successfully and saved in the database";
        } else {
            return "Failed to send data to Flask server";
        }
    }

    public function fetchDataBtech(Result $result)
    {
        $products = Product::with('links')->whereHas('links', function ($query) {
            $query->where('platform', 'btech');
        })->get();

        $filteredProducts = [];

        foreach ($products as $product) {
                $url = $product->links->pluck('url')->first();
                $filteredProducts[] = [
                    'sku' => $product->sku,
                    'url' => $url
                ];
            }
        $client = new Client();
        $response = $client->post('http://127.0.0.1:5000/btech', [
            'json' => $filteredProducts
        ]);
        if ($response->getStatusCode() === 200) {
            $scrapedData = json_decode($response->getBody(), true);
            // Assuming you have a model named ScrapeData
            foreach ($scrapedData['scraped_data'] as $data) {
                $url = $data['url'];
                $link = URL::where('url', $url)->first();
                $linkSeller = Result::where('url', $url)->first();
                $prod = Product::where('id',$link->prod_id)->first();
//                log::info($linkSeller);
                $lastSeller = $linkSeller ? $linkSeller->current_seller : null;
                $lastPrice = $linkSeller ? $linkSeller->current_price : null;

                // Create the result array with all necessary values
                $resultArray = [
                    'platform' => 'btech',
                    'date' => $data['date'],
                    'time' => $data['time'],
                    'brand' => $prod->brand,
                    'category' => $prod->category,
                    'identifier' => $link->identifier,
                    'sku' => $prod->sku,
                    'title' => $data['prod_title'],
                    'url' => $data['url'],
                    'current_seller' => $data['seller'],
                    'last_seller' => $lastSeller,
                    'current_price' => $data['price'],
                    'last_price' => $lastPrice
                ];

                // Create the result entry
                Result::create($resultArray);
            }
            return "Data sent to Flask server successfully and saved in the database";
        } else {
            return "Failed to send data to Flask server";
        }
    }
}
