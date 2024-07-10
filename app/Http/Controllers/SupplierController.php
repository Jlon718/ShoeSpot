<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::all();
        return response()->json($suppliers);
    }

    public function store(Request $request)
    {
        try{
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

        return response()->json(['message' => 'Supplier added successfully', 'supplier' => $supplier]);
        } catch (\Exception $e) {
            Log::error('Error saving supplier: ' . $e->getMessage());
            return response()->json(['error' => 'Error saving supplier.'], 500);
        }
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
}
