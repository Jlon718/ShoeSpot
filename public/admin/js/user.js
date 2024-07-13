$(document).ready(function () {
    $('#utable').DataTable({
        ajax: {
            url: "/api/user",
            dataSrc: ""
        },
        dom: 'Bfrtip',
        buttons: [
            'pdf',
            'excel',
            {
                text: 'Add user',
                className: 'btn btn-primary',
                action: function (e, dt, node, config) {
                    $("#iform").trigger("reset");
                    $('#itemModal').modal('show');
                    $('#itemUpdate').hide();
                    $('#itemImage').remove()
                }
            }
        ],
        columns: [
            { data: 'customer.customer_id' },
            { data: 'customer.customer_name' },
            { data: 'customer.phone' },
            { data: 'customer.addressline' },
            {
                data: 'role_as',
                render: function (data, type, row) {
                    return data == '1' ? 'Admin' : 'User';
                }
            },
            {
                data: 'status',
                render: function (data, type, row) {
                    return data == '1' ? 'Activated' : 'Deactivated';
                }
            },
            {
                data: null,
                render: function (data, type, row) {
                    return "<a href='#' class = 'editBtn' id='editbtn' data-id=" + data.id + "><i class='fas fa-edit' aria-hidden='true' style='font-size:24px' ></i></a><a href='#'  class='deletebtn' data-id=" + data.id + "><i  class='fas fa-trash-alt' style='font-size:24px; color:red' ></a></i>";
                }
            }
        ],
    }); // end datatable


    $('#utable').on('click', 'a.editBtn', function (e) {
        e.preventDefault();
        $('#userId').remove()
        $("#uform").trigger("reset");
        var id = $(this).data('id');
        $('<input>').attr({ type: 'hidden', id: 'userId', name: 'user_id', value: id }).appendTo('#uform');
        $('#userModal').modal('show');
        $('#userSubmit').hide();
        $('#userUpdate').show();
    
        $.ajax({
            type: "GET",
            url: `http://localhost:8000/api/user/${id}`,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            dataType: "json",
            success: function (data) {
                console.log('Data received:', data);
                // Set role_as select field
            var roleSelect = $('#role_as');
            roleSelect.empty(); // Clear previous options

            var option1 = new Option('Admin', '1');
            var option2 = new Option('User', '0');
            if (data.role_as == '1') {
                option1.selected = true; // Set the selected option
            } else {
                option2.selected = true;
            }
            roleSelect.append(option1);
            roleSelect.append(option2);

            // Set status select field
            var statusSelect = $('#status');
            statusSelect.empty(); // Clear previous options
            var statusActive = new Option('Activated', '1');
            var statusDeactivated = new Option('Deactivated', '0');
            if (data.status == '1') {
                statusActive.selected = true; // Set the selected option
            } else {
                statusDeactivated.selected = true;
            }
            statusSelect.append(statusActive);
            statusSelect.append(statusDeactivated);
            },
            error: function (error) {
                console.log(error);
            }
        });
    });

    $("#userUpdate").on('click', function (e) {
        e.preventDefault();
        var id = $('#userId').val();
        console.log(id);
        var table = $('#utable').DataTable();
        // var cRow = $("tr td:eq(" + id + ")").closest('tr');
        var data = $('#uform')[0];
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
            url: `http://localhost:8000/api/user/${id}`,
            data: formData,
            contentType: false,
            processData: false,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            dataType: "json",
            success: function (data) {
                console.log(data);
                $('#userModal').modal("hide");
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

