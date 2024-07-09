$(document).ready(function() {
    $(".cart_update").click(function(e) {
        e.preventDefault();
        var ele = $(this);

        $.ajax({
            url: update_cart_url, // This will be set in the Blade template
            method: "patch",
            data: {
                _token: csrf_token,
                id: ele.parents("tr").attr("data-id"),
                quantity: ele.parents("tr").find(".quantity").val()
            },
            success: function(response) {
                window.location.reload();
            }
        });
    });

    $(".cart_remove").click(function(e) {
        e.preventDefault();
        var ele = $(this);

        if(confirm("Do you really want to remove?")) {
            $.ajax({
                url: remove_from_cart_url, // This will be set in the Blade template
                method: "DELETE",
                data: {
                    _token: csrf_token,
                    id: ele.parents("tr").attr("data-id")
                },
                success: function(response) {
                    window.location.reload();
                }
            });
        }
    });

    $(".cart_checkout").click(function(e) {
        e.preventDefault();
        if(confirm("Do you want to proceed with the checkout?")) {
            $.ajax({
                url: checkout_url, // This will be set in the Blade template
                method: "POST",
                data: {
                    _token: csrf_token
                },
                success: function(response) {
                    // Assuming you have a success page to redirect after checkout
                    window.location.reload();
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    alert('An error occurred during checkout: ' + xhr.responseText);
                }
            });
        }
    });
});
