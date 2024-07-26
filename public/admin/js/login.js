$("#loginSubmit").on('click', function (e) {
    e.preventDefault();
    var data = $('#loginForm')[0];
    let formData = new FormData(data);
    $.ajax({
        type: "POST",
        url: "/login",
        data: formData,
        contentType: false,
        processData: false,
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        dataType: "json",
        success: function (response) {
            console.log(response);
            // Redirect to the login page
            window.location.href = response.redirect_url;
        },
        error: function (xhr, status, error) {
            console.error('Error:', error);
            console.log('Status:', status);
            console.log('Response:', xhr.responseText);
            
            // Display errors to the user
            if (xhr.status === 401) {
                alert('Invalid email or password. Please try again.');
            } else if (xhr.status === 403) {
                alert('Your account is deactivated. Please contact support.');
            } else {
                alert('An unexpected error occurred. Please try again later.');
            }
        }
    });
});