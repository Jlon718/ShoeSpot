$(document).ready(function () {
    var table = $('#stable').DataTable();
    table.destroy();
    $('#stable').DataTable({
        ajax: {
            url: "/api/stock",
            dataSrc: ""
        },
        dom: 'Bfrtip',
        buttons: [
            'pdf',
            'excel',
            {
                text: 'Add stock',
                className: 'btn btn-primary',
                action: function (e, dt, node, config) {
                    $("#sform").trigger("reset");
                    $('#stockModal').modal('show');
                    $('#stockUpdate').hide();
                }
            }
        ],
        columns: [
            { data: 'stock_id' },

            { data: 'product_name' },

            { data: 'quantity' },

            {
                data: null,
                render: function (data, type, row) {
                    return "<a href='#' class = 'editBtn' id='editbtn' data-id=" + data.stock_id + "><i class='fas fa-edit' aria-hidden='true' style='font-size:24px' ></i></a><a href='#'  class='deletebtn' data-id=" + data.stock_id + "><i  class='fas fa-trash-alt' style='font-size:24px; color:red' ></a></i>";
                }
            }
        ],
    }); // end datatable

    $('#stockAdd').on('click', function(e) {
        
            $.ajax({
                url: "/api/availableProduct", // Endpoint to fetch products
                method: "GET",
                success: function(data) {
                    var productSelect = $('#product_name');
                    productSelect.empty(); // Clear previous options
                    productSelect.append('<option value="">Select a product</option>'); // Default option
                    data.forEach(function(product) {
                        productSelect.append(new Option(product.product_name, product.product_id));
                    });
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                }
            });
    });
    

    $("#itemSubmit").on('click', function (e) {
        e.preventDefault();
        var data = $('#iform')[0];
        console.log(data);
        let formData = new FormData(data);
        console.log(formData);
        for (var pair of formData.entries()) {
            console.log(pair[0] + ', ' + pair[1]);
        }
        $.ajax({
            type: "POST",
            url: "/api/brand",
            data: formData,
            contentType: false,
            processData: false,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            dataType: "json",
            success: function (data) {
                console.log(data);
                $("#itemModal").modal("hide");
                var $itable = $('#itable').DataTable();
                // $itable.row.add(data.results).draw(false);
                $itable.ajax.reload()
            },
            error: function (error) {
                console.log(error);
            }
        });
    });

    $('#stable tbody').on('click', 'a.editBtn', function (e) {
        e.preventDefault();
        $('#itemImage').remove()
        $('#stock_id').remove()
        $("#sform").trigger("reset");
        // var id = $(e.relatedTarget).attr('data-id');
        console.log(id);

       
        var id = $(this).data('id');
        $('<input>').attr({ type: 'hidden', id: 'stockId', name: 'brand_id', value: id }).appendTo('#iform');
        $('#itemModal').modal('show');
        $('#itemSubmit').hide()
        $('#itemUpdate').show()

        $.ajax({
            type: "GET",
            url: `http://localhost:8000/api/brand/${id}`,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            dataType: "json",
            success: function (data) {
                console.log(data);
                $('#desc').val(data.name)
                $("#iform").append(`<img src=" ${data.images}" width='200px', height='200px' id="itemImage"   />`)

            },
            error: function (error) {
                console.log(error);
            }
        });
    });

    $("#itemUpdate").on('click', function (e) {
        e.preventDefault();
        var id = $('#brandId').val();
        console.log(id);
        var table = $('#itable').DataTable();
        // var cRow = $("tr td:eq(" + id + ")").closest('tr');
        var data = $('#iform')[0];
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
            url: `http://localhost:8000/api/brand/${id}`,
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

    $('#itable tbody').on('click', 'a.deletebtn', function (e) {
        e.preventDefault();
        var table = $('#itable').DataTable();
        var id = $(this).data('id');
        var $row = $(this).closest('tr');
        console.log(id);
        bootbox.confirm({
            message: "do you want to delete this item",
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
                        url: `http://localhost:8000/api/brand/${id}`,
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
    })
})

