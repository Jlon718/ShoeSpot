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
    
        if ($productName) {
            // Perform the search with Algolia
            $products = Product::search($productName)
            ->skip(($page - 1) * 10)
            ->take(10)
            ->get();
        } else {
            // Retrieve products with stock quantity greater than 1
            $products = Product::with(['images', 'stock.suppliers'])
                ->whereHas('stock', function ($query) {
                    $query->where('quantity', '>', 1);
                })
                ->skip(($page - 1) * 10)
            ->take(10)
            ->get();
        }
    
        $end = $products->count() < 10;
    
        return response()->json([
            'data' => $products,
            'end' => $end,
        ]);
    }
}
