@extends('layouts.header')
@section('content')
    <section id="mobile-products" class="product-store position-relative padding-large no-padding-top">
        <div class="container">
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
        </div>
        <div class="swiper-pagination position-absolute text-center"></div>
    </section> 

    @if(session('success'))
        <div class="success-message" style="position: absolute; top: 60px; left: 50%; transform: translateX(-50%); z-index: 9999; background-color: #dff0d8; padding: 10px 20px; border-radius: 5px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);">
            {{ session('success') }}
        </div>
    @endif   
@endsection
