$(document).ready(function () {
    let table = $('#ptable').DataTable({
        paging: false,
        dom: 'Bfrtip',
        buttons: [
            'pdf',
            'excel',
            {
                text: 'Add product',
                className: 'btn btn-primary',
                action: function (e, dt, node, config) {
                    $("#pform").trigger("reset");
                    $('#productModal').modal('show');
                    $('#productUpdate').hide();
                    $('#productImage').remove();
                }
            }
        ],
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

    // Initial data load
    $.ajax({
        url: "/api/product?page=1",
        method: 'GET',
        success: function (json) {
            json.data.forEach(product => table.row.add(product).draw(false));
        }
    });

    $('#productAdd').on('click', function(e) {
        
        $.ajax({
            url: "/api/brand", // Endpoint to fetch products
            method: "GET",
            success: function(data) {
                var brandSelect = $('#brand_name');
                brandSelect.empty(); // Clear previous options
                brandSelect.append('<option value="">Select a brand</option>'); // Default option
                data.forEach(function(brands) {
                    brandSelect.append(new Option(brands.name, brands.brand_id));
                });
            },
            error: function(xhr) {
                console.error(xhr.responseText);
            }
        });

        $.ajax({
            url: "/api/supplier", // Endpoint to fetch products
            method: "GET",
            success: function(data) {
                var supplierSelect = $('#supplier_name');
                supplierSelect.empty(); // Clear previous options
                supplierSelect.append('<option value="">Select a supplier</option>'); // Default option
                data.forEach(function(suppliers) {
                    supplierSelect.append(new Option(suppliers.supplier_name, suppliers.supplier_id));
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
                    if (supplier.supplier_id == data.product.supplier.supplier_id) {
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
                $("#productModal").modal("hide");
                var $ptable = $('#ptable').DataTable();
                // $itable.row.add(data.results).draw(false);
                $ptable.ajax.reload()
            },
            error: function (error) {
                console.error("Error in AJAX request:", error);
            }
        });
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
})

