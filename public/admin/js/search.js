$(document).ready(function() {
    $('#search-form input[type="search"]').on('keyup', function() {
        let query = $(this).val();
        console.log("Keyup event fired. Query: ", query);
        if (query.length > 0) {
            $.ajax({
                url: '/home/search',
                type: 'GET',
                data: { product_name: query },
                success: function(data) {
                    $('#autocomplete-results').empty();
                    if (data.data.length > 0) {
                        data.data.forEach(function(item) {
                            $('#autocomplete-results').append('<li><a href="/products/info/' + item.product_id + '">' + item.product_name + '</a></li>');
                        });
                    } else {
                        $('#autocomplete-results').append('<li>No results found</li>');
                    }
                }
            });
        } else {
            $('#autocomplete-results').empty();
        }
    });

    $('#search-form').on('submit', function(e) {
        e.preventDefault(); // Prevent default form submission
        let query = $(this).find('input[type="search"]').val();
        $('#products').empty();
        if (query.length > 0) {
            $.ajax({
                type: "GET",
                url: "/home/search", // Ensure this endpoint is correct
                data: { product_name: query },
                dataType: 'json',
                success: function(response) {
                    console.log("Response data:", response);
                    if (response.data && Array.isArray(response.data)) {
                        response.data.forEach(function(value) {
                            var item = `<div class='swiper-slide'>
                                            <div class='product-card position-relative'>
                                                <div class='image-holder'>
                                                    <img src='customer/images/product-item1.jpg' alt='product-item' class='img-fluid'>
                                                </div>
                                                <div class='cart-concern position-absolute'>
                                                    <div class='cart-button d-flex flex-column'>
                                                        <a href='/products/info/${value.product_id}' class='btn btn-medium btn-black'>
                                                            View Product
                                                            <svg class='cart-outline'>
                                                                <use xlink:href='#cart-outline'></use>
                                                            </svg>
                                                        </a>
                                                        <form id="productform_${value.product_id}" action="#" method="#" class="mt-2">
                                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                            <input type="hidden" id="product_id" name="product_id" value="${value.product_id}">
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
                                                   </div>
                                                </div> 
                                                <div class='card-detail d-flex justify-content-between align-items-baseline pt-3'>
                                                    <h3 class='card-title text-uppercase'>
                                                        <a href='#'>${value.product_name}</a>
                                                    </h3>
                                                    <span class='item-price text-primary'>₱${value.sell_price}</span>
                                                </div>
                                            </div>
                                        </div>`;
                            $("#products").append(item);
                        });
                        $("#search-popup").hide();

                    } else {
                        console.error('Unexpected data format:', response);
                        $('#products').append('<div>No results found</div>');
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error('Error:', textStatus, errorThrown);
                    if (jqXHR.status == 404) {
                        alert("Endpoint not found: " + jqXHR.statusText);
                    } else if (jqXHR.status == 401) {
                        alert("Unauthorized access: " + jqXHR.statusText);
                    } else {
                        alert("Failed to load products: " + textStatus);
                    }
                }
            });
        }
    });
});
