<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

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
        $productName = $request->input('product_name');
        $perPage = 10; // Number of items per page
        $page = $request->input('page', 1); // Default to page 1
    
        if ($productName) {
            // Perform the search with Algolia
            $products = Product::search($productName)->paginate($perPage, ['*'], 'page', $page);
            return response()->json(['data' => $products]);
        } else {
            // Retrieve products with stock quantity greater than 1
            $products = Product::with(['images', 'stock.suppliers'])
                ->whereHas('stock', function ($query) {
                    $query->where('quantity', '>', 1);
                })
                ->paginate($perPage, ['*'], 'page', $page);
        }
    
        return response()->json($products);
    }
}
