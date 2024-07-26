<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $page = $request->get('page', 1);
        $productName = $request->input('product_name');
        $perPage = 10; // Number of items per page

        if ($productName) {
            // Perform the search with Algolia or Eloquent
            $products = Product::where('product_name', 'LIKE', '%' . $productName . '%')
                ->skip(($page - 1) * $perPage)
                ->take($perPage)
                ->with(['images', 'stock.suppliers', 'brand'])
                ->get();
        } else {
            // Retrieve products with stock quantity greater than 1
            $products = Product::with(['images', 'stock.suppliers', 'brand'])
                ->whereHas('stock', function ($query) {
                    $query->where('quantity', '>', 1);
                })
                ->skip(($page - 1) * $perPage)
                ->take($perPage)
                ->get();
        }

        // Add brand_name to each product
        $products->map(function ($product) {
            $product->brand_name = $product->brand ? $product->brand->name : null;
            return $product;
        });

        $end = $products->count() < $perPage;

        return response()->json([
            'data' => $products,
            'end' => $end,
            'current_page' => $page,
            'last_page' => ceil(Product::count() / $perPage),
        ]);
    }

}
