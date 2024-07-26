$(document).ready(function () {
    let table = $('#suptable').DataTable({
        paging: false,
        ajax: {
            url: '/api/supplier',
            data: function(d) {
                // Add additional data if needed
                return $.extend({}, d, { additionalParam: 'value' });
            }
        },
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
    
    
    let currentPage = 1;
    let loading = false;
    let endOfData = false;

    function loadMoreData() {
        if (loading || endOfData) return;

        loading = true;
        currentPage++;

        $.ajax({
            url: `/api/supplier?page=${currentPage}`,
            method: 'GET',
            success: function(json) {
                if (json.data.length === 0) {
                    endOfData = true;
                } else {
                    json.data.forEach(brand => table.row.add(brand).draw(false));
                }
                loading = false;
            },
            error: function() {
                loading = false;
            }
        });
    }

    $(window).on('scroll', function() {
        if ($(window).scrollTop() + $(window).height() >= $(document).height() - 50) {
            loadMoreData();
        }
    });


    $("#supAdd").on('click', function (e) {
        $("#iform").trigger("reset");
        $("#nameError").hide();
        $("#emailError").hide();
        $("#phoneError").hide();
        $("#addressError").hide();
        $("#supSubmit").show();
        $("#supUpdate").hide();
    });

    $("#supSubmit").on('click', function (e) {
        e.preventDefault();
        if (validateForm()){
            var data = $('#supform')[0];
        let formData = new FormData(data);
        for (let pair of formData.entries()) {
            console.log(pair[0] + ': ' + pair[1]);
        }
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
                $('#supplierAlertContainer').empty();
                $("#supModal").hide();
                $(".modal-backdrop").remove(); 
                var alertHtml = '<div class="alert alert-success alert-dismissible fade show" role="alert">' +
                                    data.success +
                                    '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
                                '</div>';

                $('#supplierAlertContainer').html(alertHtml);
                setTimeout(function() {
                    location.reload();
                }, 2000);
            },
            error: function (error) {
                console.log(error);
                alert("Error: Could not save supplier. Please check the console for more details.");
            }
        });
        }
        
    });

    $('#suptable tbody').on('click', 'a.editBtn', function (e) {
        e.preventDefault();
        $('#supplierId').remove()
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
        if (validateForm()){
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
                success: function (response) {
                    console.log(response);
                    $('#supplierAlertContainer').empty();
                    $("#supModal").hide();
                    $(".modal-backdrop").remove(); 
                    var alertHtml = '<div class="alert alert-success alert-dismissible fade show" role="alert">' +
                                        response.success +
                                        '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
                                    '</div>';
    
                    $('#supplierAlertContainer').html(alertHtml);
                    setTimeout(function() {
                        location.reload();
                    }, 2000);
                },
                error: function (error) {
                    console.log(error);
                    alert("Error: Could not update supplier. Please check the console for more details.");
                }
            });
        }
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

    $("#supClose").on('click', function (e) {
        $("#supModal").hide();
        $(".modal-backdrop").remove(); 
              
    });

    function validateForm() {
        let isValid = true;
    
        const name = $("#supplier_name").val();
        if (!name || name.length < 3) {
            $("#nameError").text("Name must be at least 2 characters long.").show();
            isValid = false;
        } else {
            $("#nameError").hide();
        }

        const email = $("#email").val();
        if (!email || !isValidEmail(email)) {
            $("#emailError").text("Please enter a valid email address.").show();
            isValid = false;
        } else {
            $("#emailError").hide();
        }

        function isValidEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        }

        const phone = $("#phone_number").val();
        if (!phone || !isValidPHPhoneNumber(phone)) {
            $("#phoneError").text("Please enter a valid Philippine SIM number.").show();
            isValid = false;
        } else {
            $("#phoneError").hide();
        }
        
        function isValidPHPhoneNumber(phone) {
            // Regular expression for Philippine phone number validation
            const phoneRegex = /^9\d{9}$/;
            return phoneRegex.test(phone);
        }

        const address = $("#address").val();
        if (!address) {
            $("#addressError").text("Address is required.").show();
            isValid = false;
        } else {
            $("#addressError").hide();
        }
    
        return isValid;
    }
});
