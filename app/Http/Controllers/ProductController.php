<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

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
                    'sku' => $product->sku,
                    'asin' => $identifier,
                    'urls' => $url
                ];
            }
        }

        return $filteredProducts;
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
                    'urls' => $url
                ];
            }
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
                    'sku' => $product->sku,
                    'urls' => $url
                ];
            }
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
                    'urls' => $url
                ];
            }
        }

        return $filteredProducts;
    }

}
