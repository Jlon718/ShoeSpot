<?php

namespace App\Http\Controllers\Admin;

use App\Models\Image;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;


class ProductController extends Controller
{
    public function index()
    {
         // Fetch products from the database
         $products = Product::with('images')->get();
         return response()->json($products);
    }

    public function store(Request $request)
{
    $validatedData = $request->validate([
        'product_name' => 'required',
        'brand_name' => 'required',
        'description' => 'required',
        'sell_price' => 'required|numeric',
        'cost_price' => 'required|numeric',
        'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
    ]);

    $product = new Product;
    $product->product_name = $request->product_name;
    $product->brand_id = $request->brand_name;
    $product->description = $request->description;
    $product->sell_price = $request->sell_price;
    $product->cost_price = $request->cost_price;
    $product->save();

    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $file) {
            
            $image = new Image;
            $image->product_id = $product->product_id; 
            $files = $request->file('images');
            $image->image_path = 'storage/images/' . $files->getClientOriginalName();
    
            // Move the uploaded file to a directory within public storage (e.g., uploads/images)
            Storage::put('public/images/' . $files->getClientOriginalName(), file_get_contents($files));
            $image->save();
        }
    }

    return response()->json([
        'message' => 'Product created successfully',
        'product' => $product,
        'status' => 200
    ]);
}
}
