@extends('layouts.header')
@section('content')
    <section id="mobile-products" class="product-store position-relative padding-large no-padding-top">
        <div class="product-grid">
            <div class="product">
                <img src="customer/images/product-item1.jpg" alt="iPhone">
                <div class="details">
                    <span class="name">LIPSTICK</span>
                    <span class="price">₱2000</span>
                    <span class="brand">GULONG</span>
                </div>
            </div>
            <div class="product">
                <img src="customer/images/product-item1.jpg" alt="iPhone">
                <div class="details">
                    <span class="name">GULONG</span>
                    <span class="price">₱123</span>
                    <span class="brand">GULONG</span>
                </div>
            </div>
            <div class="product">
                <img src="customer/images/product-item1.jpg" alt="iPhone">
                <div class="details">
                    <span class="name">GULONG</span>
                    <span class="price">₱123</span>
                    <span class="brand">GULONG</span>
                </div>
            </div>
            <div class="product">
                <img src="customer/images/product-item1.jpg" alt="iPhone">
                <div class="details">
                    <span class="name">GULONG</span>
                    <span class="price">₱123</span>
                    <span class="brand">GULONG</span>
                </div>
            </div>
        </div>

        {{-- <div class="container">
            <div class="row">
                <div class="display-header d-flex justify-content-between pb-3">
                    <h2 class="display-7 text-dark text-uppercase">Products</h2>
                    <div class="btn-right">
                        <a href="customer/shop" class="btn btn-medium btn-normal text-uppercase">Go to Shop</a>
                    </div>
                </div>
                <div class="swiper product-swiper">
                    <div class="swiper-wrapper" id="products">
                        <!-- Dynamic products will be appended here -->
                    </div>
                </div>
            </div>
        </div> --}}

        {{-- <div class="container">
            <div class="image-container">
                <img src='customer/images/product-item1.jpg' alt="Product" class="product-image">
                <div class="hover-overlay">
                    <button class="hover-button">VIEW PRODUCT <span class="cart-icon">🛒</span></button>
                    <button class="hover-button">Add to Cart <span class="cart-icon">🛒</span></button>
                </div>
            </div>
            <div class="text-container">
                <span class="label">LIPSTICK</span>
                <span class="price">₱2000</span>
            </div>
        </div> --}}
        {{-- <div class="swiper-pagination position-absolute text-center"></div> --}}
    </section> 

    @if(session('success'))
        <div class="success-message" style="position: absolute; top: 60px; left: 50%; transform: translateX(-50%); z-index: 9999; background-color: #dff0d8; padding: 10px 20px; border-radius: 5px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);">
            {{ session('success') }}
        </div>
    @endif   
@endsection
