$(document).ready(function () { 
    $.ajax({
        type: "GET",
        url: "/api/homepage", // Ensure this endpoint is correct
        dataType: 'json',
        success: function (data) {
            console.log(data);
            $('#products').empty();
            $.each(data, function (key, value) {
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
        }
    });
});

// $(document).ready(function () { 
//     $.ajax({
//         type: "GET",
//         url: "/api/homepage", // Ensure this endpoint is correct
//         dataType: 'json',
//         success: function (data) {
//             console.log(data);
//             $('#products').empty();
//             $.each(data, function (key, value) {
//                 var item = `<div class='swiper-slide'>
//                                 <div class='product-card position-relative'>
//                                     <div class='image-holder'>
//                                         <img src='customer/images/product-item1.jpg' alt='product-item' class='img-fluid'>
//                                     </div>
//                                     <div class='cart-concern position-absolute'>
//                                         <div class='cart-button d-flex flex-column'>
//                                             <a href='/products/info/${value.product_id}' class='btn btn-medium btn-black view-product' data-id='${value.product_id}'>
//                                                 View Product
//                                                 <svg class='cart-outline'>
//                                                     <use xlink:href='#cart-outline'></use>
//                                                 </svg>
//                                             </a>

                                            
//                                             <form id="productform_${value.product_id}" action="#" method="#" class="mt-2">
//                                                 <input type="hidden" name="_token" value="{{ csrf_token() }}">
//                                                 <input type="hidden" id="product_id" name="product_id" value="${value.product_id}">
//                                                 <div class="row">
//                                                     <div class="col-md-10 mt-1 mt-md-0">
//                                                         <button id="addCart" type="submit" class="btn btn-medium btn-black">
//                                                             Add to Cart
//                                                             <svg class="cart-outline">
//                                                                 <use xlink:href="#cart-outline"></use>
//                                                             </svg>
//                                                         </button>
//                                                     </div>
//                                                 </div>
//                                             </form>
//                                        </div>
//                                     </div> 
//                                     <div class='card-detail d-flex justify-content-between align-items-baseline pt-3'>
//                                         <h3 class='card-title text-uppercase'>
//                                             <a href='#'>${value.product_name}</a>
//                                         </h3>
//                                         <span class='item-price text-primary'>₱${value.sell_price}</span>
//                                     </div>
//                                 </div>
//                             </div>`;
//                 $("#products").append(item);
//             });
//         },
//         error: function (xhr, status, error) {
//             console.error("AJAX Error:", status, error);
//         }
//     });

//     $(document).on('click', '.view-product', function() {
//         var productId = $(this).data('id');
//         window.location.href = /products/info/${productId};
//     });
// });