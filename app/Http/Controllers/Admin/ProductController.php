<?php

namespace App\Http\Controllers\Admin;

use App\Models\Brand;
use App\Models\Image;
use App\Models\Stock;
use App\Models\Product;
use App\Models\Stockline;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ProductCollection;
use App\Imports\ProductsImport;
use App\Imports\SuppliersImport;
use App\Imports\BrandsImport;
use Maatwebsite\Excel\Facades\Excel;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $page = $request->get('page', 1);

        $products = Product::with(['images', 'stock.suppliers'])
            ->skip(($page - 1) * 10)
            ->take(10)
            ->get();

        $products->map(function ($product) {
            $product->stock_quantity = $product->stock->first() ? $product->stock->first()->quantity : null;
            return $product;
        });
    
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
            'quantity' => 'required|numeric',
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

        $stock = new Stock();
        $stock->product_id = $product->product_id;
        $stock->quantity = $request->quantity;
        $stock->save();

        $selectedSuppliers = $request->input('supplier_name', []); // Default to an empty array if no checkboxes are checked

    // Process the selected suppliers
        foreach ($selectedSuppliers as $supplierId) {
            $stockline = new Stockline();
            $stockline->stock_id = $stock->stock_id;
            $stockline->supplier_id = $supplierId;
            $stockline->save();     
        }

        return response()->json(['success' => 'Product created successfully']);
    }

    public function show(string $id)
    {
        $product = Product::with(['images', 'stock.suppliers'])->where('product_id', $id)->first();
        if ($product) {
            $brands = Brand::all();
            $suppliers = Supplier::all();

            // Ensure stock is a collection and extract supplier IDs, handle products without stocks
           $supplierIds = $product->stock->map(function ($stock) {
            return $stock->suppliers->pluck('supplier_id');
            })->flatten()->unique();

            return response()->json([
                'product' => $product,
                'brands' => $brands,
                'suppliers' => $suppliers,
                'product_supplier_ids' => $supplierIds,
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
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'suppliers' => 'array', // Ensure it's an array
            'suppliers.*' => 'exists:suppliers,supplier_id', // Validate each supplier ID
            'quantity' => 'required|numeric' // Ensure quantity is validated
        ]);
    
        // Update or create the product
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
    
        // Fetch the stock associated with the product
        $stock = $product->stock->first(); // Assumes that the product has only one stock record
    
        if ($stock) {
            // Update existing stock record
            $stock->update([
                'quantity' => $request->quantity,
            ]);
        }

        $selectedSupplierIds = $request->input('suppliers', []);
        $stock->suppliers()->sync($selectedSupplierIds);

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
        return response()->json(['success' => 'Product created successfully']);
    }

    public function destroy(Product $product)
    {
        $product->delete();
        $data = array('success' => 'deleted', 'code' => 200);
            return response()->json($data);
    }

    public function productsImport(Request $request)
    {
        $request->validate([
            'item_upload' => [
                'required',
                'file'
            ],
        ]);

        try {
            Excel::import(new ProductsImport, $request->file('item_upload'));
            
            // Import successful, now redirect
            return redirect('/products')->with('success', 'File imported successfully');
            
        } catch (\Exception $e) {
            // Handle import failure, if needed
            return redirect()->back()->with('error', 'Error importing file: ' . $e->getMessage());
        }
    }
}
