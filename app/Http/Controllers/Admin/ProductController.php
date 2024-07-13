<?php

namespace App\Http\Controllers\Admin;

use App\Models\Brand;
use App\Models\Image;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ProductCollection;


class ProductController extends Controller
{
    public function index(Request $request)
    {
        $page = $request->get('page', 1);
        $products = Product::with('images')->skip(($page - 1) * 10)->take(10)->get();
        $end = $products->count() < 10;

        return response()->json([
            'data' => $products,
            'end' => $end,
        ]);
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
                $imagePath = $file->store('public/images'); 
                $image->image_path = Storage::url($imagePath); // Get the public URL of the image
                $image->save();
            }
        }

        return response()->json([
            'message' => 'Product created successfully',
            'product' => $product,
            'status' => 200
        ]);
    }

    public function show(string $id)
    {
        $product = Product::with('images')->where('product_id', $id)->first();
        $brands = Brand::all();
        if ($product) {
            return response()->json([
                'product' => $product,
                'brands' => $brands,
                'images' => $product->images->map(function ($image) {
                    return [
                        'image_path' => asset('storage/images/' . basename($image->image_path))
                    ];
                }),
            ]);
        } else {
            return response()->json([
                'message' => 'Product not found'
            ], 404);
        }
    }

    public function update(Request $request, Product $product)
    {
        $validatedData = $request->validate([
           'product_name' => 'required',
            'brand_name' => 'required',
            'description' => 'required',
            'sell_price' => 'required|numeric',
            'cost_price' => 'required|numeric',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        $product = Product::updateOrCreate(
            ['product_id' => $request->product_id],
            [
                'product_name' => $request->product_name,
                'brand_id' => $request->brand_name,
                'description' => $request->description,
                'sell_price' => $request->sell_price,
                'cost_price' => $request->cost_price
            ]
        );

        if ($request->hasFile('images')) {
            $existingImages = Image::where('product_id', $product->product_id)->get();
            foreach ($existingImages as $existingImage) {
                if (Storage::exists($existingImage->image_path)) {
                    Storage::delete($existingImage->image_path);
                }
                $existingImage->delete();
            }

            foreach ($request->file('images') as $file) {
                
                $image = new Image;
                $image->product_id = $request->product_id; 
                $imagePath = $file->store('public/images'); 
                $image->image_path = Storage::url($imagePath); // Get the public URL of the image
                $image->save();
            }
        }

    
        // Redirect back with success message
        return response()->json(["success" => "product updated successfully.", "product" => $product, "status" => 200]);
    }

    public function destroy(Product $product)
    {
        $product->delete();
        $data = array('success' => 'deleted', 'code' => 200);
            return response()->json($data);
    }
}
