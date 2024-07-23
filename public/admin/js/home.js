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
                                <button class="view-product">View Product</button>
                                <button class="add-to-cart">Add to Cart</button>
                            </div>
                            <div class="details">
                                <span class="name">${value.product_name}</span>
                                <span class="price">₱${value.sell_price}</span>
                                <span class="brand">${value.brand_name}</span>
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