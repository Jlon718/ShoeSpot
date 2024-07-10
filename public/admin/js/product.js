$(document).ready(function () {
    $('#ptable').DataTable({
        ajax: {
            url: "/api/product",
            dataSrc: ""
        },
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
                    $('#productImage').remove()
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
                data: null,
                render: function (data, type, row) {
                    return "<a href='#' class = 'editBtn' id='editbtn' data-id=" + data.product_id + "><i class='fas fa-edit' aria-hidden='true' style='font-size:24px' ></i></a><a href='#'  class='deletebtn' data-id=" + data.product_id + "><i  class='fas fa-trash-alt' style='font-size:24px; color:red' ></a></i>";
                }
            }
        ],
    }); // end datatable

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
                console.log(data);
                $("#productModal").modal("hide");
                var $ptable = $('#ptable').DataTable();
                $ptable.ajax.reload();
            },
            error: function (error) {
                console.log(error);
            }
        });
    });

    // $('#itable tbody').on('click', 'a.editBtn', function (e) {
    //     e.preventDefault();
    //     $('#itemImage').remove()
    //     $('#brandId').remove()
    //     $("#iform").trigger("reset");
    //     // var id = $(e.relatedTarget).attr('data-id');
    //     console.log(id);

       
    //     var id = $(this).data('id');
    //     $('<input>').attr({ type: 'hidden', id: 'brandId', name: 'brand_id', value: id }).appendTo('#iform');
    //     $('#itemModal').modal('show');
    //     $('#itemSubmit').hide()
    //     $('#itemUpdate').show()

    //     $.ajax({
    //         type: "GET",
    //         url: `http://localhost:8000/api/brand/${id}`,
    //         headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
    //         dataType: "json",
    //         success: function (data) {
    //             console.log(data);
    //             $('#desc').val(data.name)
    //             $("#iform").append(`<img src=" ${data.images}" width='200px', height='200px' id="itemImage"   />`)

    //         },
    //         error: function (error) {
    //             console.log(error);
    //         }
    //     });
    // });

    // $("#itemUpdate").on('click', function (e) {
    //     e.preventDefault();
    //     var id = $('#brandId').val();
    //     console.log(id);
    //     var table = $('#itable').DataTable();
    //     // var cRow = $("tr td:eq(" + id + ")").closest('tr');
    //     var data = $('#iform')[0];
    //     let formData = new FormData(data);
    //     formData.append("_method", "PUT")
    //     // // var formData = $("#cform").serialize();
    //     // console.log(formData);
    //     // formData.append('_method', 'PUT')
    //     // for (var pair of formData.entries()) {
    //     //     console.log(pair[0] + ', ' + pair[1]);
    //     // }
    //     $.ajax({
    //         type: "POST",
    //         url: `http://localhost:8000/api/brand/${id}`,
    //         data: formData,
    //         contentType: false,
    //         processData: false,
    //         headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
    //         dataType: "json",
    //         success: function (data) {
    //             console.log(data);
    //             $('#itemModal').modal("hide");

    //             table.ajax.reload()

    //         },
    //         error: function (error) {
    //             console.log(error);
    //         }
    //     });
    // });

    // $('#itable tbody').on('click', 'a.deletebtn', function (e) {
    //     e.preventDefault();
    //     var table = $('#itable').DataTable();
    //     var id = $(this).data('id');
    //     var $row = $(this).closest('tr');
    //     console.log(id);
    //     bootbox.confirm({
    //         message: "do you want to delete this item",
    //         buttons: {
    //             confirm: {
    //                 label: 'yes',
    //                 className: 'btn-success'
    //             },
    //             cancel: {
    //                 label: 'no',
    //                 className: 'btn-danger'
    //             }
    //         },
    //         callback: function (result) {
    //             console.log(result);
    //             if (result)
    //                 $.ajax({
    //                     type: "DELETE",
    //                     url: `http://localhost:8000/api/brand/${id}`,
    //                     headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
    //                     dataType: "json",
    //                     success: function (data) {
    //                         console.log(data);
    //                         $row.fadeOut(4000, function () {
    //                             table.row($row).remove().draw();
    //                         });

    //                         bootbox.alert(data.success);
    //                     },
    //                     error: function (error) {
    //                         bootbox.alert(data.error);
    //                     }
    //                 });
    //         }
    //     });
    // });
})

