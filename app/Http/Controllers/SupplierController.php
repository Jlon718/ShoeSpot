<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\SuppliersImport;
use App\Imports\BrandsImport;
use Illuminate\Support\Facades\Storage;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        $page = $request->get('page', 1);

        $suppliers = Supplier::skip(($page - 1)*10)
        ->take(10)
        ->get();
        $end = $suppliers->count() <10;
        return response()->json([
            'data' => $suppliers,
            'end' => $end,
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'supplier_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:suppliers',
            'phone_number' => 'required|string|max:20',
            'address' => 'required|string|max:255',
        ]);

        $supplier = new Supplier();
        $supplier->supplier_name = $validatedData['supplier_name'];
        $supplier->email = $validatedData['email'];
        $supplier->phone_number = $validatedData['phone_number'];
        $supplier->address = $validatedData['address'];

        $supplier->save();

        return response()->json(['success' => 'Supplier created successfully']);
    }

    public function show($id)
    {
        $supplier = Supplier::findOrFail($id);
        return response()->json($supplier);
    }

    public function edit(Brand $brand)
    {
        return view('admin.suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, $id)
{
    $request->validate([
        'supplier_name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255',
        'phone_number' => 'required|string|max:20',
        'address' => 'required|string|max:255',
    ]);

    $supplier = Supplier::findOrFail($id);
    $supplier->supplier_name = $request->supplier_name;
    $supplier->email = $request->email;
    $supplier->phone_number = $request->phone_number;
    $supplier->address = $request->address;
    $supplier->save();

    return response()->json(['success' => 'Supplier updated successfully']);
}


    public function destroy($id)
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->delete();
        return response()->json(['message' => 'Supplier deleted successfully']);
    }

    public function suppliersImport(Request $request)
    {
        $request->validate([
            'item_upload' => [
                'required',
                'file'
            ],
        ]);

        try {
            Excel::import(new SuppliersImport, $request->file('item_upload'));
            
            // Import successful, now redirect
            return redirect('/suppliers')->with('success', 'File imported successfully');
            
        } catch (\Exception $e) {
            // Handle import failure, if needed
            return redirect()->back()->with('error', 'Error importing file: ' . $e->getMessage());
        }
    }

    public function getAll()
    {
        $suppliers = Supplier::all();
        return response()->json([ 'data' => $suppliers]);
    }

}
