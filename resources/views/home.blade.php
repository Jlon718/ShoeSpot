@extends('layouts.header')
@section('content')
    <section  class="product-store position-relative padding-large no-padding-top">
        <div class="product-grid" id="mobile-productss">
            
        </div>
    </section> 

    @if(session('success'))
        <div class="success-message" style="position: absolute; top: 60px; left: 50%; transform: translateX(-50%); z-index: 9999; background-color: #dff0d8; padding: 10px 20px; border-radius: 5px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);">
            {{ session('success') }}
        </div>
    @endif   
@endsection
