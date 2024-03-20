<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Result;
use App\Models\URL;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use League\CommonMark\Extension\CommonMark\Node\Inline\Link;

class ProductController extends Controller
{
    public function fetchDataAmazon()
    {
        $products = Product::with('links')->get();

        $filteredProducts = [];

        foreach ($products as $product) {
            $platform = $product->links->pluck('platform')->first(); // Assuming a product has only one platform
            if ($platform === 'amazon') {
                $identifier = $product->links->pluck('identifier')->first(); // Assuming a product has only one identifier
                $url = $product->links->pluck('url')->first();

                $filteredProducts[] = [
                    'asin' => $identifier,
                    'url' => $url
                ];
            }
        }
        $client = new Client();
        $response = $client->post('http://127.0.0.1:5000/amazon', [
            'json' => $filteredProducts
        ]);
        if ($response->getStatusCode() === 200) {
            return "Data sent to Flask server successfully";
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

    public function getDataAmazon(Request $request , Result $result , Product $product , URL $URL){
        $scrapedData = $request->json()->get('scraped_data');
        Result::create([
            'platfrom' => $product->platform,
            'date' => $request->date,
            'time' => $request->time,
            'brand' => $product->brand,
            'category' => $product->category,
            'identifier' => $URL->identifier,
            'sku' => $URL->sku,
            'title' => $request->prod_title,
            'current_seller' => $request->seller,
            'current_price' => $request->price
        ]);
    }
}
