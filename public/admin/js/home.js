$(document).ready(function () { 
    let page = 1;
    let loading = false;

    function loadProducts(page) {
        $.ajax({
            type: "GET",
            url: "/api/homepage",
            data: { page: page },
            dataType: 'json',
            success: function (response) {
                console.log("Response data:", response);
                if (response.data && response.data.length > 0) {
                    response.data.forEach(function (value) {
                        var item = `<div class="product-grid">
                                        <div class="product">
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
                        $("#mobile-product").append(item);
                    });
                } else {
                    $(window).off('scroll');
                    $('#mobile-products').append('<div>No more products to load</div>');
                }
                loading = false;
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.error('Error:', textStatus, errorThrown);
                if (jqXHR.status == 404) {
                    alert("Endpoint not found: " + jqXHR.statusText);
                } else if (jqXHR.status == 401) {
                    alert("Unauthorized access: " + jqXHR.statusText);
                } else {
                    alert("Failed to load products: " + textStatus);
                }
                loading = false;
            }
        });
    }

    // Load initial products
    loadProducts(page);

    // Infinite scroll event
    $(window).on('scroll', function () {
        if ($(window).scrollTop() + $(window).height() >= $(document).height() - 100) {
            if (!loading) {
                loading = true;
                page++;
                loadProducts(page);
            }
        }
    });
});