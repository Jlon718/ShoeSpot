$(document).ready(function () {
    var table = $('#suptable').DataTable({
        ajax: {
            url: "/api/supplier",
            dataSrc: ""
        },
        dom: 'Bfrtip',
        buttons: [
            'pdf',
            'excel',
            {
                text: 'Add supplier',
                className: 'btn btn-primary',
                action: function (e, dt, node, config) {
                    $("#supform").trigger("reset");
                    $('#supModal').modal('show');
                    $('#supUpdate').hide();
                    $('#supSubmit').show();
                }
            }
        ],
        columns: [
            { data: 'supplier_id' },
            { data: 'supplier_name' },
            { data: 'email' },
            { data: 'phone_number' },
            { data: 'address' },
            {
                data: null,
                render: function (data, type, row) {
                    return "<a href='#' class='editBtn' data-id='" + data.supplier_id + "'><i class='fas fa-edit' aria-hidden='true' style='font-size:24px'></i></a> <a href='#' class='deletebtn' data-id='" + data.supplier_id + "'><i class='fas fa-trash-alt' style='font-size:24px; color:red'></i></a>";
                }
            }
        ],
    });

    $("#supSubmit").on('click', function (e) {
        e.preventDefault();
        var data = $('#supform')[0];
        let formData = new FormData(data);
        $.ajax({
            type: "POST",
            url: "/api/supplier",
            data: formData,
            contentType: false,
            processData: false,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            dataType: "json",
            success: function (data) {
                console.log(data);
                $("#supModal").modal("hide");
                table.ajax.reload(); // Reload the DataTable data
            },
            error: function (error) {
                console.log(error);
                alert("Error: Could not save supplier. Please check the console for more details.");
            }
        });
    });

    $('#suptable tbody').on('click', 'a.editBtn', function (e) {
        e.preventDefault();
        $("#supform").trigger("reset");

        var id = $(this).data('id');
        $('#supModal').modal('show');
        $('#supSubmit').hide();
        $('#supUpdate').show();

        $.ajax({
            type: "GET",
            url: `/api/supplier/${id}`,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            dataType: "json",
            success: function (data) {
                $('#supplier_name').val(data.supplier_name);
                $('#email').val(data.email);
                $('#phone_number').val(data.phone_number);
                $('#address').val(data.address);
                $('<input>').attr({ type: 'hidden', id: 'supplierId', name: 'supplier_id', value: id }).appendTo('#supform');
            },
            error: function (error) {
                console.log(error);
            }
        });
    });

    $("#supUpdate").on('click', function (e) {
        e.preventDefault();
        var id = $('#supplierId').val();
        var data = $('#supform')[0];
        let formData = new FormData(data);
        formData.append("_method", "PUT");
        
        $.ajax({
            type: "POST",
            url: `/api/supplier/${id}`,
            data: formData,
            contentType: false,
            processData: false,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            dataType: "json",
            success: function (data) {
                console.log(data);
                $('#supModal').modal("hide");
                table.ajax.reload();
            },
            error: function (error) {
                console.log(error);
                alert("Error: Could not update supplier. Please check the console for more details.");
            }
        });
    });

    $('#suptable tbody').on('click', 'a.deletebtn', function (e) {
        e.preventDefault();
        var table = $('#suptable').DataTable();
        var id = $(this).data('id');
        var $row = $(this).closest('tr');
        console.log(id);
        bootbox.confirm({
            message: "Do you want to delete this item?",
            buttons: {
                confirm: {
                    label: 'Yes',
                    className: 'btn-success'
                },
                cancel: {
                    label: 'No',
                    className: 'btn-danger'
                }
            },
            callback: function (result) {
                console.log(result);
                if (result) {
                    $.ajax({
                        type: "DELETE",
                        url: `/api/supplier/${id}`,
                        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                        dataType: "json",
                        success: function (data) {
                            $row.fadeOut(4000, function () {
                                table.row($row).remove().draw();
                            });
                            bootbox.alert(data.success);
                        },
                        error: function (error) {
                            console.log(error);
                            bootbox.alert("Error: Could not delete supplier. Please check the console for more details.");
                        }
                    });
                }
            }
        });
    });
});
