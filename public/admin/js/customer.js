$(document).ready(function () {
    const editProfileModal = new bootstrap.Modal(document.getElementById('editProfileModal'));
    const editProfileForm = document.getElementById('editProfileForm');

    document.querySelector('button[data-bs-target="#editProfileModal"]').addEventListener('click', function () {
        resetForm();
        hideAllErrors();
    });

    $('#customerUpdate').on('click', function (e) {
        e.preventDefault();

        if (validateForm()) {
            var customerId = $('#customerId').val();
            var formData = new FormData(editProfileForm);

            $.ajax({
                type: "POST",
                url: `/api/customers/${customerId}`,
                data: formData,
                contentType: false,
                processData: false,
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: function (response) {
                    if (response && response.success) {
                        // If update is successful, close modal and show success message
                        alert('Profile updated successfully.');
                       
                    } else {
                        // Handle errors if the update fails
                        alert('Error updating profile');
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Error updating customer profile:', xhr.responseText);
                    alert("Error: Could not update profile. Please check the console for more details.");
                }
            });
        }
    });

    // Function to reset form fields
    function resetForm() {
        editProfileForm.reset(); // Reset form fields
    }

    // Function to validate the form
    function validateForm() {
        let isValid = true;

        const name = document.getElementById('name').value;
        if (!name || name.length < 3) {
            showError('nameError', 'Name must be at least 3 characters long.');
            isValid = false;
        } else {
            hideError('nameError');
        }

        const email = document.getElementById('email').value;
        if (!email || !isValidEmail(email)) {
            showError('emailError', 'Please enter a valid email address.');
            isValid = false;
        } else {
            hideError('emailError');
        }

        const phone = document.getElementById('phone').value;
        if (!phone || !isValidPHPhoneNumber(phone)) {
            showError('phoneError', 'Please enter a valid Philippine SIM number.');
            isValid = false;
        } else {
            hideError('phoneError');
        }

        const address = document.getElementById('address').value;
        if (!address) {
            showError('addressError', 'Address is required.');
            isValid = false;
        } else {
            hideError('addressError');
        }

        return isValid;
    }

    // Email validation function
    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    // Philippine phone number validation function
    function isValidPHPhoneNumber(phone) {
        // Regular expression for Philippine phone number validation
        const phoneRegex = /^9\d{9}$/;
        return phoneRegex.test(phone);
    }

    // Show error messages
    function showError(elementId, message) {
        const errorElement = document.getElementById(elementId);
        errorElement.textContent = message;
        errorElement.style.display = 'block';
    }

    // Hide error messages
    function hideError(elementId) {
        const errorElement = document.getElementById(elementId);
        errorElement.style.display = 'none';
    }

    // Hide all error messages
    function hideAllErrors() {
        hideError('nameError');
        hideError('emailError');
        hideError('phoneError');
        hideError('addressError');
    }
});
