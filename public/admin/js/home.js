$(document).ready(function () { 
    let page = 1;
    let loading = false;

    function loadProducts(page) {
        if (loading) return;
        loading = true;

        $.ajax({
            type: "GET",
            url: "/api/homepage",
            data: { page: page },
            dataType: 'json',
            success: function(response) {
                console.log("Response data:", response);
                if (response.data && response.data.length > 0) {
                    response.data.forEach(function(value) {
                        var item = `<div class="product">
                            <img src="customer/images/product-item1.jpg" alt="iPhone">
                            <div class="product-overlay">
                                <div class='cart-concern'>
                                    <div class='cart-button'>
                                        <a href='/products/info/${value.product_id}' class='btn btn-medium btn-black'>
                                            View Product
                                            <svg class='cart-outline'>
                                                <use xlink:href='#cart-outline'></use>
                                            </svg>
                                        </a>
                                    </div>
                                    <div class='cart-button'>
                                        <form id="productform_${value.product_id}" action="#" method="#">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <input type="hidden" id="product_id" name="product_id" value="${value.product_id}">
                                            <button id="addCart" type="submit" class="btn btn-medium btn-black">
                                                Add to Cart
                                                <svg class="cart-outline">
                                                    <use xlink:href="#cart-outline"></use>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="details">
                                <span class="name">${value.product_name}</span>
                                <span class="price">₱${value.sell_price}</span>
                                <span class="brand">${value.brand_id}</span>
                            </div>
                        </div>`;
                        $("#mobile-productss").append(item);
                    });
                }
                loading = false;
            },
            error: function() {
                loading = false;
            }
        });
    }

    // Initial load
    loadProducts(page);

    // Example: Load more products on scroll
    $(window).scroll(function() {
        if ($(window).scrollTop() + $(window).height() >= $(document).height() - 100) {
            page++;
            loadProducts(page);
        }
    });
});