@extends('layouts.header')

@section('content')
<div class="container">
    <div class="product-info">
        {{-- Carousel for product images --}}
       
        <div id="productCarousel" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner">
                @foreach($product->images as $index => $image)
                    <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                        <img src="{{ asset($image->image_path) }}" class="d-block mx-auto" style="width: 500px; height: auto;" alt="Product Image">
                    </div>
                @endforeach
            </div>
            <a class="carousel-control-prev" href="#productCarousel" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#productCarousel" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>
  
        <h1 class="h3 mt-4">Product Information</h1>
        <p class="product-text"><strong>Product Name:</strong> {{ $product->product_name }}</p>
        <p class="product-text"><strong>Description:</strong> {{ $product->description }}</p>
        <p class="product-text"><strong>Price:</strong> ₱{{ number_format($product->sell_price, 2) }}</p>
        <form id="productform_{{ $product->product_id }}" action="#" method="#" class="mt-2">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" id="product_id" name="product_id" value="{{ $product->product_id }}">
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
