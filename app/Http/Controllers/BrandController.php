<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\BrandsImport;


class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $page = $request->get('page', 1);
        $brands = Brand::skip(($page - 1) * 10)
        ->take(10)
        ->get();
        $end = $brands->count() < 10;

        return response()->json([
            'data' => $brands,
            'end' => $end,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    // Validate incoming request data
    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
    ]);

    // Create a new Brand instance
    $brand = new Brand();
    $brand->name = $validatedData['name'];

    // Handle file upload if applicable
    if ($request->hasFile('images')) {
        $files = $request->file('images');
        $brand->images = 'storage/images/' . $files->getClientOriginalName();

        // Move the uploaded file to a directory within public storage (e.g., uploads/images)
        Storage::put('public/images/' . $files->getClientOriginalName(), file_get_contents($files));
    }

    // Save the brand record
    $brand->save();

    // Return a JSON response indicating success
    return response()->json(['success' => 'Brand created successfully']);
}


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $brand = Brand::where('brand_id', $id)->first();
        return response()->json($brand);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Brand $brand)
    {
        return view('admin.brands.edit', compact('brand'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Brand $brand)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);


        if ($request->hasFile('images')) {
            $files = $request->file('images');
            $brand->images = 'storage/images/' . $files->getClientOriginalName();
            Storage::put('public/images/' . $files->getClientOriginalName(), file_get_contents($files));
            $validatedData['images'] = 'storage/images/' . $files->getClientOriginalName();
        }
    
        // Update the supplier with validated data
        $brand->update($validatedData);
    
        // Redirect back with success message
        return response()->json(['success' => 'Brand updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Brand $brand)
    {
        $brand->delete();
        $data = array('success' => 'deleted', 'code' => 200);
            return response()->json($data);
    }

    public function brandsImport(Request $request)
    {
        $request->validate([
            'item_upload' => [
                'required',
                'file'
            ],
        ]);

        try {
            Excel::import(new BrandsImport, $request->file('item_upload'));
            
            // Import successful, now redirect
            return redirect('/brands')->with('success', 'File imported successfully');
            
        } catch (\Exception $e) {
            // Handle import failure, if needed
            return redirect()->back()->with('error', 'Error importing file: ' . $e->getMessage());
        }

    }

    public function getAll()
    {
        $brands = Brand::all();
        return response()->json(['data' => $brands]);
    }
}
