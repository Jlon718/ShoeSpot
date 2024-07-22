@extends('layouts.header')
@section('content')
<div class="container">
        <div class="product-info">
        <h1 class="h3">Product Information</h1>

            <p><strong>Product Name:</strong> {{ $product->product_name }}</p>
            <p><strong>Description:</strong> {{ $product->description }}</p>
            <p><strong>Price:</strong> ₱{{ number_format($product->sell_price, 2) }}</p>
            <form id="productform_${value.product_id}" action="#" method="#" class="mt-2">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" id="product_id" name="product_id" value="{{$product->product_id}}">
                <div class="row">
                    <div class="col-md-10 mt-1 mt-md-0">
                        <button id="addCart" type="submit" class="btn btn-medium btn-black">
                            Add to Cart
                            <svg class="cart-outline">
                                <use xlink:href="#cart-outline"></use>
                            </svg>
                        </button>
                    </div>
                </div>
            </form>
            <a href="{{ url('/home') }}" class="back-button">Back to Products</a>

            
        </div>
    </div>
@endsection