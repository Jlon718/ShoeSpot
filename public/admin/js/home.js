$(document).ready(function () { 
    let currentPage = 1;
    let loading = false;
    let endOfData = false;
    let searchQuery = "";

    function loadMoreProducts() {
        if (loading || endOfData) return;
        loading = true;

        $.ajax({
            type: "GET",
            url: "/api/homepage",
            data: { page: currentPage, product_name: searchQuery },
            dataType: 'json',
            success: function(response) {
                console.log("Response data:", response); // Debugging statement
                if (response.data && response.data.length > 0) {
                    response.data.forEach(function(value) {
                        var imageUrl = value.images.length > 0 ? value.images[0].image_path : 'customer/images/default.jpg';
                        var item = `<div class="product">
                <img src="${imageUrl}" alt="${value.product_name}">
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
                    <span class="name"><strong>${value.product_name}</strong></span>
                    <span class="price">₱${value.sell_price}</span>
                    <span class="brand">Brand: ${value.brand.name}</span>
                </div>
            </div>`;

                        $("#mobile-productss").append(item);
                    });

                    if (response.current_page >= response.last_page) {
                        endOfData = true;
                    } else {
                        currentPage++;
                    }
                } else {
                    endOfData = true;
                }
                loading = false;
                $('#search-popup').fadeOut();
            },
            error: function() {
                loading = false;
            }
        });
    }

    // Initial load
    loadMoreProducts();

    // Load more products on scroll
    $(window).scroll(function() {
        if ($(window).scrollTop() + $(window).height() >= $(document).height() - 100) {
            loadMoreProducts();
        }
    });

    // Handle search form submission
    $('#search-form').on('submit', function(e) {
        e.preventDefault();
        searchQuery = $('#search-input').val();
        currentPage = 1;
        endOfData = false;
        $("#mobile-productss").empty();
        loadMoreProducts();
    });

    $('#open-search-popup-button').on('click', function() {
        $('#search-popup').fadeIn();
        $('#search-input').val(''); // Reset the search input field
    });
});
