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
//    public function fetchDataAmazon(Request $request, Result $result, Product $product, URL $URL)
//    {
//        $products = Product::with('links')->get();
//
//
//        $filteredProducts = [];
//
//        foreach ($products as $product) {
//            $platform = $product->links->pluck('platform')->first(); // Assuming a product has only one platform
//            if ($platform === 'amazon') {
//                $identifier = $product->links->pluck('identifier')->first(); // Assuming a product has only one identifier
//                $url = $product->links->pluck('url')->first();
//                $filteredProducts[] = [
//                    'asin' => $identifier,
//                    'url' => $url
//                ];
//            }
//        }
//        $client = new Client();
//        $response = $client->post('http://127.0.0.1:5000/amazon', [
//            'json' => $filteredProducts
//        ]);
//        if ($response->getStatusCode() === 200) {
//            $scrapedData = json_decode($response->getBody(), true);
//
//            // Assuming you have a model named ScrapeData
//            foreach ($scrapedData['scraped_data'] as $data) {
//                $result->create([
//                    'platform' => $platform,
//                    'date' => $data['date'],
//                    'time' => $data['time'],
//                    'brand' => $product->brand,
//                    'category' => $product->category,
//                    'identifier' => $URL->identifier,
//                    'sku' => $URL->sku,
//                    'title' => $data['prod_title'],
//                    'current_seller' => $data['seller'],
//                    'current_price' => $data['price']
//                ]);
//            }
//            return "Data sent to Flask server successfully and saved in the database";
//        } else {
//            return "Failed to send data to Flask server";
//        }
//
//    }
    public function fetchDataAmazon(Result $result)
    {
        $products = Product::with('links')->whereHas('links', function ($query) {
            $query->where('platform', 'amazon');
        })->get();

        foreach ($products as $product) {
            $platform = $product->links->pluck('platform')->first(); // Assuming a product has only one platform
            if ($platform === 'amazon') {
                $identifier = $product->links->pluck('identifier')->first(); // Assuming a product has only one identifier
                $url = $product->links->pluck('url')->first();
                $sku = $product->sku;
                $brand = $product->brand;
                $category = $product->category;
                $filteredProducts[] = [
                    'asin' => $identifier,
                    'url' => $url,
                ];
            }
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
                $link = Link::where('url', $url)->first();
                log::info($link);
                $result->create([
                    'platform' => 'amazon',
                    'date' => $data['date'],
                    'time' => $data['time'],
                    'brand' => $brand, // Use the brand from scraped data
                    'category' => $category, // Use the category from scraped data
                    'identifier' => $identifier,
                    'sku' => $sku,
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




    public function fetchDataNoon(Result $result)
    {
        $products = Product::with('links')->whereHas('links', function ($query) {
            $query->where('platform', 'noon');
        })->get();
        $filteredProducts = [];

        foreach ($products as $product) {
            $platform = $product->links->pluck('platform')->first(); // Assuming a product has only one platform
            if ($platform === 'noon') {
                $identifier = $product->links->pluck('identifier')->first(); // Assuming a product has only one identifier
                $url = $product->links->pluck('url')->first();
                $brand = $product->brand;
                $category = $product->category;
                $sku = $product->sku;
                $filteredProducts[] = [
                    'asin' => $identifier,
                    'url' => $url
                ];
            }
        }
        $client = new Client();
        $response = $client->post('http://127.0.0.1:5000/noon', [
            'json' => $filteredProducts
        ]);
        if ($response->getStatusCode() === 200) {
            $scrapedData = json_decode($response->getBody(), true);
            // Assuming you have a model named ScrapeData
            foreach ($scrapedData['scraped_data'] as $data) {
                $result->create([
                    'platform' => 'noon',
                    'date' => $data['date'],
                    'time' => $data['time'],
                    'brand' => $brand, // Use the brand from scraped data
                    'category' => $category, // Use the category from scraped data
                    'identifier' => $identifier,
                    'sku' => $sku,
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

    public function fetchDataJumia(Result $result)
    {
        $products = Product::with('links')->whereHas('links', function ($query) {
            $query->where('platform', 'jumia');
        })->get();

        $filteredProducts = [];

        foreach ($products as $product) {
            $platform = $product->links->pluck('platform')->first(); // Assuming a product has only one platform
            if ($platform === 'jumia') {
                $identifier = $product->links->pluck('identifier')->first(); // Assuming a product has only one identifier
                $url = $product->links->pluck('url')->first();
                $brand = $product->brand;
                $category = $product->category;
                $sku = $product->sku;
                $filteredProducts[] = [
                    'query' => $url
                ];
            }
        }
        $client = new Client();
        $response = $client->post('http://127.0.0.1:5000/jumia', [
            'json' => $filteredProducts
        ]);
        if ($response->getStatusCode() === 200) {
            $scrapedData = json_decode($response->getBody(), true);
            // Assuming you have a model named ScrapeData
            foreach ($scrapedData['scraped_data'] as $data) {
                $result->create([
                    'platform' => 'jumia',
                    'date' => $data['date'],
                    'time' => $data['time'],
                    'brand' => $brand, // Use the brand from scraped data
                    'category' => $category, // Use the category from scraped data
                    'identifier' => $identifier,
                    'sku' => $sku,
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

    public function fetchDataBtech(Result $result)
    {
        $products = Product::with('links')->whereHas('links', function ($query) {
            $query->where('platform', 'btech');
        })->get();

        $filteredProducts = [];

        foreach ($products as $product) {
            $platform = $product->links->pluck('platform')->first(); // Assuming a product has only one platform
            if ($platform === 'btech') {
                $identifier = $product->links->pluck('identifier')->first(); // Assuming a product has only one identifier
                $url = $product->links->pluck('url')->first();
                $brand = $product->brand;
                $category = $product->category;
                $sku = $product->sku;
                $filteredProducts[] = [
                    'sku' => $product->sku,
                    'url' => $url
                ];
            }
        }
        $client = new Client();
        $response = $client->post('http://127.0.0.1:5000/btech', [
            'json' => $filteredProducts
        ]);
        if ($response->getStatusCode() === 200) {
            $scrapedData = json_decode($response->getBody(), true);
            // Assuming you have a model named ScrapeData
            foreach ($scrapedData['scraped_data'] as $data) {
                $result->create([
                    'platform' => 'btech',
                    'date' => $data['date'],
                    'time' => $data['time'],
                    'brand' => $brand, // Use the brand from scraped data
                    'category' => $category, // Use the category from scraped data
                    'identifier' => $identifier,
                    'sku' => $sku,
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
