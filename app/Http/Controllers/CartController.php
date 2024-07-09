<?php

namespace App\Http\Controllers;

use App\Mail\Receipt;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Orderinfo;
use App\Models\Orderline;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('carts.index');
    }
       
 // 
    /**
     * Show the form for creating a new resource.
     */

    public function store(Request $request)
    {
        try {
            $id = $request->product_id;
            $product = Product::findOrFail($id);

            $cart = session()->get('cart', []);

            if (isset($cart[$id])) {
                $cart[$id]['quantity']++;
            } else {
                $cart[$id] = [
                    "product_name" => $product->product_name,
                    "image" => 'customer/images/product-item1.jpg',
                    "sell_price" => $product->sell_price,
                    "quantity" => 1
                ];
            }

            session()->put('cart', $cart);

            return response()->json([
                'success' => true,
                'message' => 'Product added to cart successfully!',
                'redirect_url' => route('home'),
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error adding product to cart: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to add product to cart!',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function checkout(Request $request)
    {
        $user_id = auth()->id();

        // Now fetch the customer_id based on the user_id
        $customer_id = Customer::where('user_id', $user_id)->value('customer_id');
        $orderInfo = Orderinfo::create([
           'customer_id'=>$customer_id,
           'date_place'=>now(),
           'shipping_fee'=>'50',
        ]);

        // Iterate over cart items and create order lines
        foreach(session('cart') as $id => $details) {
            Orderline::create([
                'orderinfo_id' => $orderInfo->orderinfo_id,
                'product_id' => $id,
                'quantity' => $details['quantity'],
                // You might want to include the price at the time of purchase too
            ]);
        }

        // Clear the cart after checkout
        // $email = auth()->user()->email;
        // $customer = Customer::where('user_id', auth()->user()->id)->first();
        // $orderinfo = Orderinfo::where('customer_id', $customer->customer_id)->latest('orderinfo_id')->first();
        // $orderlines = Orderline::where('orderinfo_id', $orderinfo->orderinfo_id)->get();
        // // Send the email using the TransactionReceipt mailable
        // Mail::to($email)->send(new Receipt($customer, $orderinfo, $orderlines));
        session()->forget('cart');

        // You can redirect the user to a success page or any other page
        return redirect()->route('home')->with('success', 'Order placed successfully!');
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
    public function update(Request $request)
    {
        $cart = session()->get('cart');
        $cart[$request->id]["quantity"] = $request->quantity;
        session()->put('cart', $cart);
        session()->flash('success', 'Cart successfully upated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        if($request->id) {
            $cart = session()->get('cart');
            if(isset($cart[$request->id])) {
                unset($cart[$request->id]);
                session()->put('cart', $cart);
            }
            session()->flash('success', 'Product seccessfully removed!');
        }
    }
}
