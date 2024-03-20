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
    public function fetchDataAmazon(Request $request, Result $result, Product $product, URL $URL)
    {
        $products = Product::with('links')->whereHas('links', function ($query) {
            $query->where('platform', 'amazon');
        })->get();

        foreach ($products as $product) {
            $platform = $product->links->pluck('platform')->first(); // Assuming a product has only one platform
            if ($platform === 'amazon') {
                $identifier = $product->links->pluck('identifier')->first(); // Assuming a product has only one identifier
                $sku = $product->sku; // Assuming a product has only one identifier
                $url = $product->links->pluck('url')->first();
                $brand = $product->brand;
                $category = $product->category;
                $filteredProducts[] = [
                    'asin' => $identifier,
                    'url' => $url,
                    'platform' => $platform
                ];
            }
        }

        $client = new Client();
        $response = $client->post('http://127.0.0.1:5000/amazon', [
            'json' => $filteredProducts
        ]);

        if ($response->getStatusCode() === 200) {
            $scrapedData = json_decode($response->getBody(), true);
//            $platform = $product->platform;

            // Assuming you have a model named ScrapeData
            foreach ($scrapedData['scraped_data'] as $data) {
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




    public function fetchDataNoon()
    {
        $products = Product::with('links')->get();

        $filteredProducts = [];

        foreach ($products as $product) {
            $platform = $product->links->pluck('platform')->first(); // Assuming a product has only one platform
            if ($platform === 'noon') {
                $identifier = $product->links->pluck('identifier')->first(); // Assuming a product has only one identifier
                $url = $product->links->pluck('url')->first();

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
            return "Data sent to Flask server successfully";
        } else {
            return "Failed to send data to Flask server";
        }

        return $filteredProducts;
    }

    public function fetchDataJumia()
    {
        $products = Product::with('links')->get();

        $filteredProducts = [];

        foreach ($products as $product) {
            $platform = $product->links->pluck('platform')->first(); // Assuming a product has only one platform
            if ($platform === 'jumia') {
                $identifier = $product->links->pluck('identifier')->first(); // Assuming a product has only one identifier
                $url = $product->links->pluck('url')->first();

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
            return "Data sent to Flask server successfully";
        } else {
            return "Failed to send data to Flask server";
        }

        return $filteredProducts;
    }

    public function fetchDataBtech()
    {
        $products = Product::with('links')->get();

        $filteredProducts = [];

        foreach ($products as $product) {
            $platform = $product->links->pluck('platform')->first(); // Assuming a product has only one platform
            if ($platform === 'btech') {
                $identifier = $product->links->pluck('identifier')->first(); // Assuming a product has only one identifier
                $url = $product->links->pluck('url')->first();

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
            return "Data sent to Flask server successfully";
        } else {
            return "Failed to send data to Flask server";
        }
        return $filteredProducts;
    }

    public function getDataAmazon(Request $request, Result $result, Product $product, URL $URL) {
        Log::info('Received data from Flask:', $request->all());
        $scrapedData = $request->json()->get('scraped_data');
        $platform = $product->platform;


        // Loop through each scraped data item and create a Result record
        foreach ($scrapedData as $data) {
            Log::info('Inserting data:', $data);
            $result->create([
                'platform' => $platform,
                'date' => $data['date'],
                'time' => $data['time'],
                'brand' => $product->brand,
                'category' => $product->category,
                'identifier' => $URL->identifier,
                'sku' => $URL->sku,
                'title' => $data['prod_title'],
                'current_seller' => $data['seller'],
                'current_price' => $data['price']
            ]);
        }
        return response()->json(['message' => 'Data received and processed successfully'], 200);
    }

}
