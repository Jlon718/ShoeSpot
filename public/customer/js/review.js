$(document).ready(function () {
    $.ajax({
        type: "GET",
        url: `/api/review/${productId}`, // Ensure this URL is correct
        dataType: 'json',
        success: function(response) {
            console.log("Response data:", response); // Debugging statement

            // The response is expected to be an array of reviews
            let reviews = response;

            if (reviews.length > 0) {
                // Use template literals to properly format the review list
                var reviewList = reviews.map(review => `
                    <li>
                        <strong>${review.customer.customer_name}</strong> (Rating: ${review.rating})
                        <p>${review.review_text}</p>
                    </li>
                `).join('');
                
                var reviewHTML = `
                    <h4>Reviews:</h4>
                    <ul>${reviewList}</ul>
                `;
                
                $('#reviews').html(reviewHTML); // Use html() to replace content
            } else {
                $('#reviews').html('<p>No reviews available.</p>'); // Use html() to replace content
            }
        },
        error: function(xhr, status, error) {
            console.error('Error:', status, error);
            $('#reviews').html('<p>An error occurred while loading reviews.</p>'); // Use html() to replace content
        }
    });

    $('#open-review-popup-button').on('click', function (e) {
        e.preventDefault();
        $('#review-popup').show();
    });

    // Hide the review pop-up when the close button is clicked
    $('#close-review-popup').on('click', function () {
        $('#review-popup').hide();
    });

    // Handle form submission
    $('#reviewForm').on('submit', function (e) {
        e.preventDefault(); // Prevent default form submission

        var formData = $(this).serialize(); // Serialize form data

        $.ajax({
            type: 'POST',
            url: '/api/review/store', // API endpoint for review submission
            data: formData,
            dataType: 'json',
            success: function (response) {
                $('#reviewMessage').html('<div class="alert alert-success">Thank you for your review!</div>');
                $('#reviewForm')[0].reset(); // Reset the form fields
                setTimeout(function () {
                    $('#review-popup').hide();
                }, 2000);
            },
            error: function (xhr) {
                var errors = xhr.responseJSON.errors;
                var errorMessage = '<div class="alert alert-danger">An error occurred: ';
                
                for (var key in errors) {
                    if (errors.hasOwnProperty(key)) {
                        errorMessage += errors[key].join(' ') + ' ';
                    }
                }
                
                errorMessage += '</div>';
                $('#reviewMessage').html(errorMessage);
            }
        });
    });
});

