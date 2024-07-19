$(document).ready(function () {
    let table = $('#itable').DataTable({
        paging: false,
        ajax: {
            url: '/api/brand',
            data: function(d) {
                // Add additional data if needed
                return $.extend({}, d, { additionalParam: 'value' });
            }
        },
        columns: [
            { data: 'brand_id' },
            {
                data: 'images',
                render: function(data) {
                    return data ? `<img src="${data}" width="50" height="60">` : 'No image';
                }
            },
            { data: 'name' },
            {
                data: null,
                render: function(data) {
                    return `<a href='#' class='editBtn' data-id='${data.brand_id}'><i class='fas fa-edit'></i></a>
                            <a href='#' class='deletebtn' data-id='${data.brand_id}'><i class='fas fa-trash-alt' style='color:red'></i></a>`;
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
            url: `/api/brand?page=${currentPage}`,
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
    
    $("#brandAdd").on('click', function (e) {
        $('#itemImage').remove()
        $('#brandId').remove()
        $("#iform").trigger("reset");
        $("#nameError").hide();
        $("#imageError").hide();
        $("#itemSubmit").show();
        $("#itemUpdate").hide();
    });

    
    $("#itemSubmit").on('click', function(e) {
        e.preventDefault();
        if (validateForm()) {
            var data = $('#iform')[0];
            let formData = new FormData(data);
            $.ajax({
                type: "POST",
                url: "/api/brand",
                data: formData,
                contentType: false,
                processData: false,
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                dataType: "json",
                success: function(data) {
                    console.log(data);
                    $('#alertContainer').empty();
                    $("#itemModal").hide();
                    $(".modal-backdrop").remove(); 
                    var alertHtml = '<div class="alert alert-success alert-dismissible fade show" role="alert">' +
                                        data.success +
                                        '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
                                    '</div>';

                    $('#alertContainer').html(alertHtml);
                    setTimeout(function() {
                        location.reload();
                    }, 2000);
                },
                error: function(error) {
                    console.log(error);
                }
            });
        }
    });

    $('#itable tbody').on('click', 'a.editBtn', function (e) {
        e.preventDefault();
        $('#itemImage').remove()
        $('#brandId').remove()
        $("#iform").trigger("reset");
        $("#nameError").hide();
        $("#imageError").hide();
        // var id = $(e.relatedTarget).attr('data-id');
        console.log(id);
        var id = $(this).data('id');
        $('<input>').attr({ type: 'hidden', id: 'brandId', name: 'brand_id', value: id }).appendTo('#iform');
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
                $('#name').val(data.name)
                $("#iform").append(`<img src=" ${data.images}" width='200px', height='200px' id="itemImage"   />`)

            },
            error: function (error) {
                console.log(error);
            }
        });
    });

    $("#itemUpdate").on('click', function (e) {
        e.preventDefault();
        // Check if the form is valid
        if (validateForm()) {
            var id = $('#brandId').val();
            var data = $('#iform')[0];
            let formData = new FormData(data);
            formData.append("_method", "PUT");
            $.ajax({
                type: "POST",
                url: `http://localhost:8000/api/brand/${id}`,
                data: formData,
                contentType: false,
                processData: false,
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                dataType: "json",
                success: function (response) {
                    console.log(response);
                    $('#alertContainer').empty();
                    $("#itemModal").modal("hide");
                    var alertHtml = '<div class="alert alert-success alert-dismissible fade show" role="alert">' +
                    response.success +
                    '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
                    '</div>';
    
                    $('#alertContainer').html(alertHtml);
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
    
    $('#itable ibody').on('click', 'a.deletebtn', function (e) {
        e.preventDefault();
        var table = $('#itable').DataTable();
        var id = $(this).data('id');
        var $row = $(this).closest('tr');
        
        bootbox.confirm({
            message: "Do you want to delete this brand?",
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
                        url: `http://localhost:8000/api/brand/${id}`,
                        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                        dataType: "json",
                        success: function (data) {
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
            }
        });
    });
    

    function validateForm() {
        let isValid = true;
    
        const name = $("#name").val();
        if (!name || name.length < 3) {
            $("#nameError").text("Name must be at least 3 characters long.").show();
            isValid = false;
        } else {
            $("#nameError").hide();
        }
    
        var image = $("#image").prop('files')[0];
    
        // Check if an image is selected
        if (image) {
            // Check if more than one file is selected
            if ($("#image").prop('files').length > 1) {
                $("#imageError").text("Only one image file is allowed.").show();
                isValid = false;
            } else {
                // Check file type
                var fileType = image.type.split('/').pop().toLowerCase();
                var allowedExtensions = ['jpeg', 'png', 'jpg', 'gif', 'svg'];
    
                if ($.inArray(fileType, allowedExtensions) === -1) {
                    $("#imageError").text("Only image files (JPEG, PNG, JPG, GIF, SVG) are allowed.").show();
                    isValid = false;
                } else {
                    $("#imageError").hide();
                }
            }
        } else {
            // Hide the error if no image is selected since it's not required
            $("#imageError").hide();
        }
    
        return isValid;
    }

    $("#itemSubmit").on('click', function (e) {
        e.preventDefault();
        if ($("#iform").validate()) {
            $("#iform").submit(); // This will call the submitHandler defined in validate
        }
    });

    $("#itemUpdate").on('click', function (e) {
        e.preventDefault();
        if ($("#iform").validate()) {
            $("#iform").submit(); // This will call the submitHandler defined in validate
        }
    });
})

