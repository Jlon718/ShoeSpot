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
            <div class="button-container">
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
                @if ($canAddReview)
                    <button id="open-review-popup-button" class="btn btn-secondary mt-3">Add a review</button>
                @else
                    <p class="mt-3">You cannot add a review for this product.</p>
                @endif
            
                <a href="{{ url('/home') }}" class="btn btn-primary mt-3">← Back to Products</a>
            </div>
            
        </div>

    <div id="reviews">

    </div>

    <div id="review-popup" class="review-popup" style="display: none;">
        <div class="review-popup-container">
            <h2>Submit Your Review</h2>
            <form id="reviewForm" method="POST" action="#">
                @csrf
                <input type="hidden" id="product_id" name="product_id" value="{{ $product->product_id }}">
                <input type="hidden" id="customer_id" name="customer_id" value="{{ auth()->user()->customer->customer_id }}">

                <div class="form-group">
                    <label for="rating">Rating:</label>
                    <div id="star-rating" class="star-rating">
                        <input type="radio" id="star5" name="rating" value="5"><label for="star5" title="5 stars">&#9733;</label>
                        <input type="radio" id="star4" name="rating" value="4"><label for="star4" title="4 stars">&#9733;</label>
                        <input type="radio" id="star3" name="rating" value="3"><label for="star3" title="3 stars">&#9733;</label>
                        <input type="radio" id="star2" name="rating" value="2"><label for="star2" title="2 stars">&#9733;</label>
                        <input type="radio" id="star1" name="rating" value="1"><label for="star1" title="1 star">&#9733;</label>
                    </div>
                </div>
    
                <div class="form-group">
                    <label for="review_text">Review:</label>
                    <textarea id="review_text" name="review_text" class="form-control" rows="5" required></textarea>
                </div>
    
                <button type="submit" id="reviewSubmit" class="btn btn-primary">Submit Review</button>
                <button type="button" id="close-review-popup" class="btn btn-secondary">Cancel</button>
            </form>
            <div id="reviewMessage" class="mt-3"></div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script type="text/javascript">
        var productId = {{ $product->product_id }};
        var csrf_token = '{{ csrf_token() }}';
    </script>
    <script src="{{ asset('customer/js/review.js') }}"></script>
@endsection
