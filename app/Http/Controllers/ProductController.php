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
                $prod = Product::where('id',$link->prod_id)->first();
                $linkSeller = Result::where('sku', $prod->sku)->latest()->first();
                log::info($linkSeller);
                $result->create([
                    'platform' => 'amazon',
                    'date' => $data['date'],
                    'time' => $data['time'],
                    'brand' => $prod->brand, // Use the brand from scraped data
                    'category' => $prod->category, // Use the category from scraped data
                    'identifier' => $link->identifier,
                    'sku' => $prod->sku,
                    'title' => $data['prod_title'],
                    'last_seller' => $linkSeller->current_seller,
                    'current_seller' => $data['seller'],
                    'current_price' => '20.45'
                ]);
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
                $result->create([
                    'platform' => 'noon',
                    'date' => $data['date'],
                    'time' => $data['time'],
                    'brand' => $prod->brand, // Use the brand from scraped data
                    'category' => $prod->category, // Use the category from scraped data
                    'identifier' => $link->identifier,
                    'sku' => $prod->sku,
                    'title' => $data['prod_title'],
                    'current_seller' => $data['seller'],
                    'current_price' => '50.50'
                ]);
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
                log::info($url);
                $prod = Product::where('id',$link->prod_id)->first();
                $result->create([
                    'platform' => 'jumia',
                    'date' => $data['date'],
                    'time' => $data['time'],
                    'brand' => $prod->brand, // Use the brand from scraped data
                    'category' => $prod->category, // Use the category from scraped data
                    'identifier' => $link->identifier,
                    'sku' => $prod->sku,
                    'title' => $data['prod_title'],
                    'current_seller' => $data['seller'],
                    'current_price' => '60.20'
                ]);
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
                log::info($linkSeller);
                $result->create([
                    'platform' => 'btech',
                    'date' => $data['date'],
                    'time' => $data['time'],
                    'brand' => $prod->brand, // Use the brand from scraped data
                    'category' => $prod->category, // Use the category from scraped data
                    'identifier' => $link->identifier,
                    'sku' => $prod->sku,
                    'title' => $data['prod_title'],
                    'current_seller' => $data['seller'],
                    'current_price' => '20.45'
                ]);
            }
            return "Data sent to Flask server successfully and saved in the database";
        } else {
            return "Failed to send data to Flask server";
        }
    }
}
