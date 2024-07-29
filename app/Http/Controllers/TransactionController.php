<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Orderinfo;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index()
    {
        $orderinfos = Orderinfo::all();
        $transactions = $orderinfos->map(function ($orderinfo) {
            return [
                'orderinfos' => $orderinfo,
                'status' => $orderinfo->status // Adjust if the status is stored differently
            ];
        });
    
        return response()->json(['data' => $transactions]);

        // $orderinfos = Orderinfo::all();
        // return response()->json($orderinfos);

        // try {
        //     $orderinfos = Orderinfo::all(); // Or use paginate for large datasets
        //     // return response()->json($orderinfos);
        //     return view('admin.transaction.index', compact('orderinfos'));
        // } catch (\Exception $e) {
        //     return response()->json(['error' => 'View not found: ' . $e->getMessage()], 500);
        // }

        // $page = $request->get('page', 1);

        // $orderinfos = Orderinfo::skip(($page - 1)*10)
        // ->take(10)
        // ->get();
        // $end = $orderinfos->count() <10;
        // return response()->json([
        //     'data' => $orderinfos,
        //     'end' => $end,
        // ]);
    }
    
    // public function store(Request $request)
    // {
    //     $validatedData = $request->validate([
    //         'customer_id' => 'required|exists:customers,id',
    //         'date_placed' => 'required|date',
    //         'date_shipped' => 'nullable|date|after_or_equal:date_placed',
    //         'shipping_fee' => 'required|numeric',
    //         'status' => 'required|in:Pending,Shipped,Delivered,Cancelled',
    //     ]);

    //     $orderinfos = Orderinfo::create($validatedData);
    //     return response()->json(['success' => 'Transaction created successfully', 'data' => $orderinfos]);
    // }

    public function show($orderinfo_id)
    {
        // $orderinfos = Orderinfo::findOrFail($orderinfo_id);
        // return response()->json($orderinfos);

        $orderinfos = Orderinfo::findOrFail($orderinfo_id);
        return response()->json([
        'status' => $orderinfos->status, // Ensure that this line returns the correct status
        'orderinfo' => $orderinfos
    ]);
    }

    public function update(Request $request, $orderinfo_id)
    {
        $validatedData = $request->validate([
            'status' => 'required|integer|in:0,1,2,3',
            'date_shipped' => 'nullable|date_format:Y-m-d H:i:s', // Validate date_shipped format
        ]);
    
        $orderinfos = Orderinfo::findOrFail($orderinfo_id);
    
        // Update status and date_shipped if provided
        $orderinfos->status = $validatedData['status'];
        if (isset($validatedData['date_shipped'])) {
            $orderinfos->date_shipped = $validatedData['date_shipped'];
        }
        $orderinfos->save();
    
        return response()->json(['success' => 'Transaction updated successfully']);
    }

    public function destroy($orderinfo_id)
    {
        $orderinfos = Orderinfo::findOrFail($orderinfo_id);
        $orderinfos->delete();

        return response()->json(['success' => 'Transaction deleted successfully']);
    }

    public function getAll()
    {
        $orderinfos = Orderinfo::all();
        return response()->json([ 'data' => $orderinfos]);
    }

    public function updateOrderStatus(Request $request, $orderinfo_id)
{
    // Validate incoming request
    $validated = $request->validate([
        'status' => 'required|string|in:pending,shipped,delivered,cancelled',
    ]);

    // Find the order by ID and update its status
    $orderinfos = Orderinfo::findOrFail($orderinfo_id);
    $orderinfos->status = $validated['status'];
    $orderinfos->save();

    // Return a JSON response
    return response()->json([
        'success' => 'Order status updated successfully.',
        'order' => $orderinfos,
        'status' => 200
    ]);
}

    // In TransactionController.php
    // public function customerIndex()
    // {
    //     $user = Auth::user();
    //     $customer = $user->customer;

    //     if (!$customer) {
    //         return view('customer.custransactions', ['purchasedProducts' => collect()]);
    //     }

    //     $orders = $customer->orders()->with('products')->get();

    //     if ($orders->isEmpty()) {
    //         dd('No orders found for customer', $customer->customer_id);
    //     }

    //     foreach ($orders as $order) {
    //         $products = $order->products;
    //         if ($products->isEmpty()) {
    //             dd('No products found for order', $order->orderinfo_id);
    //         }
    //     }

    //     // At this point, we have verified orders and products exist
    //     return view('customer.custransactions', compact('orders'));
    // }

//     public function customerIndex()
// {
//     $user = Auth::user();
//     $orders = $user->customer->orders()->with('products')->get();

//     // Debug the orders data
//     foreach ($orders as $order) {
//         if (!$order->status) {
//             dd('Status not found for order', $order->orderinfo_id, $order);
//         }
//     }

//     return view('customer.custransactions', compact('orders'));
// }

    public function customerIndex()
    {
        $user = Auth::user();
        $orders = $user->customer->orders()->with('products')->get();

        // Debugging: log the orders and their statuses
        foreach ($orders as $order) {
            \Log::info('Order ID: ' . $order->orderinfo_id . ' Status: ' . $order->status);
            foreach ($order->products as $product) {
                \Log::info('Product ID: ' . $product->product_id . ' Product Name: ' . $product->product_name . ' Order Status: ' . $order->status);
            }
        }

        return view('customer.custransactions', compact('orders'));
    }

    public function getOrders()
    {
        $user = Auth::user();
        $orders = $user->customer->orders()->with('products')->get();

        return response()->json($orders);
    }

}
