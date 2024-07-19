$(document).ready(function () {
    let table = $('#ptable').DataTable({
        paging: false,
        ajax: {
            url: '/api/product',
            data: function(d) {
                // Add additional data if needed
                return $.extend({}, d, { additionalParam: 'value' });
            }
        },
        columns: [
            { data: 'product_id' },
            { data: 'product_name' },
            { data: 'brand_id' },
            { data: 'description' },
            { data: 'sell_price' },
            { data: 'cost_price' },
            {
                data: 'images',
                render: function (data, type, row) {
                    var imagesHtml = '';
                    data.forEach(function(image) {
                        imagesHtml += `<img src="${image.image_path}" alt="product image" width="50" height="60"> `;
                    });
                    return imagesHtml;
                }
            },
            { 
                data: 'stock.quantity', 
                defaultContent: 'No stock',
                render: function(data, type, row) {
                    return data !== null ? data : 'No stock';
                }
            },
            { 
                data: 'stock.suppliers',
                defaultContent: 'No suppliers',
                render: function(data, type, row) {
                    if (data && data.length > 0) {
                        return data.map(supplier => supplier.supplier_name).join(', ');
                    }
                    return 'No suppliers';
                }
            },
            {
                data: null,
                render: function (data, type, row) {
                    return "<a href='#' class='editBtn' id='editbtn' data-id='" + data.product_id + "'><i class='fas fa-edit' aria-hidden='true' style='font-size:24px'></i></a><a href='#' class='deletebtn' data-id='" + data.product_id + "'><i class='fas fa-trash-alt' style='font-size:24px; color:red'></i></a>";
                }
            }
        ]
    });

    let currentPage = 1;
    let loading = false;
    let endOfData = false;

    function loadMoreData() {
        if (loading || endOfData) return;

        loading = true;
        currentPage++;

        $.ajax({
            url: `/api/product?page=${currentPage}`,
            method: 'GET',
            success: function (json) {
                if (json.data.length === 0) {
                    endOfData = true;
                } else {
                    json.data.forEach(product => table.row.add(product).draw(false));
                }
                loading = false;
            },
            error: function () {
                loading = false;
            }
        });
    }

    $(window).on('scroll', function () {
        if ($(window).scrollTop() + $(window).height() >= $(document).height() - 50) {
            loadMoreData();
        }
    });


    $('#productAdd').on('click', function(e) {
        $.ajax({
            url: "/api/all-brands", // Endpoint to fetch all brands
            method: "GET",
            success: function(response) {
                var brandSelect = $('#brand_name');
                brandSelect.empty(); // Clear previous options
                brandSelect.append('<option value="">Select a brand</option>'); // Default option
                response.data.forEach(function(brand) {
                    brandSelect.append(new Option(brand.name, brand.brand_id));
                });
            },
            error: function(xhr) {
                console.error(xhr.responseText);
            }
        });
    
        $.ajax({
            url: "/api/all-suppliers", // Endpoint to fetch all suppliers
            method: "GET",
            success: function(response) {
                var supplierSelect = $('#supplier_name');
                supplierSelect.empty(); // Clear previous options
                supplierSelect.append('<option value="">Select a supplier</option>'); // Default option
                response.data.forEach(function(supplier) {
                    supplierSelect.append(new Option(supplier.supplier_name, supplier.supplier_id));
                });
            },
            error: function(xhr) {
                console.error(xhr.responseText);
            }
        });
    });

    $('#ptable').on('click', 'a.editBtn', function (e) {
        e.preventDefault();
        console.log('Edit button clicked');
    
        // Clear previous data and reset the form
        $('#productId').remove();
        $("#pform").trigger("reset");
        $('#existingImages').remove(); // Clear existing images
    
        var id = $(this).data('id');
        console.log('Product ID:', id);
    
        // Add hidden input for product ID
        $('<input>').attr({ type: 'hidden', id: 'productId', name: 'product_id', value: id }).appendTo('#pform');
    
        // Show modal
        $('#productModal').modal('show');
        $('#productSubmit').hide();
        $('#productUpdate').show();
    
        // Fetch product details
        $.ajax({
            type: "GET",
            url: `http://localhost:8000/api/product/${id}`,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            dataType: "json",
            success: function (data) {
                console.log('Data received:', data);
    
                var brandSelect = $('#brand_name');
                brandSelect.empty(); // Clear previous options
                brandSelect.append('<option value="">Select a brand</option>'); // Default option
                if (data.brands) {
                    data.brands.forEach(function (brand) {
                        var option = new Option(brand.name, brand.brand_id);
                        if (brand.brand_id == data.product.brand_id) {
                            option.selected = true; // Set the selected option
                        }
                        brandSelect.append(option);
                    });
                }
    
                var supplierSelect = $('#supplier_name');
                supplierSelect.empty(); // Clear previous options
                supplierSelect.append('<option value="">Select a supplier</option>'); // Default option
                data.suppliers.forEach(function (supplier) {
                    var option = new Option(supplier.supplier_name, supplier.supplier_id);
                    if (supplier.supplier_id == data.product.supplier_id) {
                        option.selected = true; // Set the selected option
                    }
                    supplierSelect.append(option);
                });
    
                $('#product_name').val(data.product.product_name);
                $('#brand_name').val(data.product.brand_id);
                $('#description').val(data.product.description);
                $('#sell_price').val(data.product.sell_price);
                $('#cost_price').val(data.product.cost_price);
    
                // Handle stock quantity
                if (data.product.stock) {
                    $('#quantity').val(data.product.stock.quantity);
                } else {
                    $('#quantity').val('');
                }
    
                // Append existing images
                if (data.images && data.images.length > 0) {
                    var imageContainer = $('<div id="existingImages" class="form-group"><label>Existing Images:</label></div>');
                    data.images.forEach(image => {
                        var imagePath = image.image_path;
                        imageContainer.append(`<img src="${imagePath}" width="200px" height="200px" class="existingImage" />`);
                    });
                    $('#pform').append(imageContainer);
                }
            },
            error: function (error) {
                console.log('Error:', error);
            }
        });
    });

    $("#productSubmit").on('click', function (e) {
        e.preventDefault();
        if (validateForm()){
            var data = $('#pform')[0];
            let formData = new FormData(data);
        
            $.ajax({
                type: "POST",
                url: "/api/product",
                data: formData,
                contentType: false,
                processData: false,
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                dataType: "json",
                success: function (data) {
                    console.log(data.message);
                if (data.status === 200) {
                    $("#productModal").modal("hide");
                    location.reload();
                } else {
                    console.error("Error: Invalid response status:", data.status);
                }
    
                },
                error: function (error) {
                    console.error("Error in AJAX request:", error);
                }
            });
        }
    });
    

    $("#productUpdate").on('click', function (e) {
        e.preventDefault();
        var id = $('#productId').val();
        console.log(id);
        var table = $('#ptable').DataTable();
        // var cRow = $("tr td:eq(" + id + ")").closest('tr');
        var data = $('#pform')[0];
        let formData = new FormData(data);
        formData.append("_method", "PUT")
        // // var formData = $("#cform").serialize();
        // console.log(formData);
        // formData.append('_method', 'PUT')
        // for (var pair of formData.entries()) {
        //     console.log(pair[0] + ', ' + pair[1]);
        // }
        $.ajax({
            type: "POST",
            url: `http://localhost:8000/api/product/${id}`,
            data: formData,
            contentType: false,
            processData: false,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            dataType: "json",
            success: function (data) {
                console.log(data);
                $('#itemModal').modal("hide");

                table.ajax.reload()

            },
            error: function (error) {
                console.log(error);
            }
        });
    });

    $('#ptable').on('click', 'a.deletebtn', function (e) {
        e.preventDefault();
        var table = $('#ptable').DataTable();
        var id = $(this).data('id');
        var $row = $(this).closest('tr');
        console.log(id);
        bootbox.confirm({
            message: "do you want to delete this product?",
            buttons: {
                confirm: {
                    label: 'yes',
                    className: 'btn-success'
                },
                cancel: {
                    label: 'no',
                    className: 'btn-danger'
                }
            },
            callback: function (result) {
                console.log(result);
                if (result)
                    $.ajax({
                        type: "DELETE",
                        url: `http://localhost:8000/api/product/${id}`,
                        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                        dataType: "json",
                        success: function (data) {
                            console.log(data);
                            $row.fadeOut(4000, function () {
                                table.row($row).remove().draw();
                            });

                            bootbox.alert(data.success);
                        },
                        error: function (error) {
                            bootbox.alert(data.error);
                        }
                    });
            }
        });
    });

    function validateForm() {
        let isValid = true;
    
        const name = $("#product_name").val();
        if (!name || name.length < 3) {
            $("#nameError").text("Name must be at least 3 characters long.").show();
            isValid = false;
        } else {
            $("#nameError").hide();
        }

        const brand = $("#brand_name").val();
        if (!brand) {
            $("#brandError").text("Please select a brand.").show();
            isValid = false;
        } else {
            $("#brandError").hide();
        }

        const description = $("#description").val();
        if (!description || description.length < 4) {
            $("#descError").text("Please enter a description (at least 4 characters long).").show();
            isValid = false;
        } else {
            $("#descError").hide();
        }

        const sellPrice = $("#sell_price").val();
        const sellPriceNum = parseFloat(sellPrice);
        if (isNaN(sellPriceNum) || sellPriceNum <= 1) {
            $("#sellError").text("Sell price must be an integer greater than 1.").show();
            isValid = false;
        } else {
            $("#sellPriceError").hide();
        }

        const costPrice = $("#cost_price").val();
        const costPriceNum = parseFloat(costPrice);
        if (isNaN(costPriceNum) || costPriceNum <= 1) {
            $("#costError").text("Cost price must be an integer greater than 1.").show();
            isValid = false;
        } else {
            $("#costError").hide();
        }

        const stock = $("#quantity").val();
        const stockNum = parseFloat(stock);
        if (isNaN(stockNum) || stockNum < 0) {
            $("#stockError").text("Stock must be an integer greater than or equal to 0.").show();
            isValid = false;
        } else {
            $("#stockError").hide();
        }

        const supplier = $("#supplier_name").val();
        if (!supplier) {
            $("#supplierError").text("Please select a supplier.").show();
            isValid = false;
        } else {
            $("#supplierError").hide();
        }
    
        const files = $("#images").prop('files');

        if (files.length > 0) {
            // Loop through all selected files
            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                const fileType = file.type.split('/').pop().toLowerCase();
                const allowedExtensions = ['jpeg', 'png', 'jpg', 'gif', 'svg'];
    
                // Check if file type is valid
                if ($.inArray(fileType, allowedExtensions) === -1) {
                    $("#imagesError").text("Only image files (JPEG, PNG, JPG, GIF, SVG) are allowed.").show();
                    isValid = false;
                    break; // Exit loop on first invalid file
                } else {
                    $("#imagesError").hide();
                }
            }
        } else {
            // Hide the error if no file is selected since it's not required
            $("#imageError").hide();
        }
    
        return isValid;
    }
})

