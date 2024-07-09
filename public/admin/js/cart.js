$(document).ready(function() {
    $(document).on('click', '#addCart', function(e) {
        e.preventDefault();
    
        var form = $(this).closest('form');
        var formData = new FormData(form[0]); 
        
        $.ajax({
            type: "POST",
            url: "/api/cart",
            data: formData,
            contentType: false,
            processData: false,
            dataType: "json",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Ensure CSRF token is set
            },
            success: function(response) {
                if (response.success) {
                    alert(response.message);
                    window.location.href = response.redirect_url;
                    // Optionally, you can update the cart UI here
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function(xhr) {
                alert('An error occurred: ' + xhr.responseText);
            }
        });
    });
});