<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $productName = $request->input('product_name');

        if ($productName) {
            // Perform the search with Algolia
            $products = Product::search($productName)->get();
        } else {
            // Retrieve all products if no search term is provided
            $products = Product::all();
        }

        // Limit the number of results for autocomplete
        $products = $products->take(10);

        return response()->json(['data' => $products]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function searchIndex(Request $request)
    {
        $page = $request->get('page', 1);
        $productName = $request->input('product_name');
        $perPage = 10; // Number of items per page
    
        if ($productName) {
            // Perform the search with Algolia
            $products = Product::search($productName)
                ->skip(($page - 1) * $perPage)
                ->take($perPage)
                ->get()
                ->load('brand');
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

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
