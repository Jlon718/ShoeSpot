// Define rolesMap globally
const rolesMap = {
    '0': 'Pending',
    '1': 'Shipped',
    '2': 'Delivered',
    '3': 'Cancelled'
};

// Initialize DataTable
$(document).ready(function () {
    let table = $('#transactionTable').DataTable({
        ajax: {
            url: "/api/transactions", // Replace with your actual API endpoint URL
            dataSrc: 'data', // Ensure to use the correct data source
            data: function(d) {
                return $.extend({}, d, { additionalParam: 'value' });
            }
        },
        columns: [
            { data: 'orderinfos.orderinfo_id' },
            { data: 'orderinfos.customer_id' },
            { data: 'orderinfos.date_place' },
            { data: 'orderinfos.date_shipped' },
            { data: 'orderinfos.shipping_fee' },
            {
                data: 'orderinfos.status',
                render: function (data, type, row) {
                    // Map status integer to its corresponding string
                    return rolesMap[data] || 'Pending';
                }
            },
            {
                data: null,
                render: function (data, type, row) {
                    if (row.orderinfos.status === 2) {
                        return "<i class='fas fa-lock' aria-hidden='true' style='font-size:24px; color:gray'></i>"; // Display a lock icon for delivered transactions
                    }
                    return "<a href='#' class='editTransactionBtn' data-id='" + row.orderinfos.orderinfo_id + "'><i class='fas fa-edit' aria-hidden='true' style='font-size:24px'></i></a>";
                }
            }
        ],
        // Add this option to disable default pagination, allowing custom pagination
        paging: false,
        // Add this option to enable infinite scrolling with custom scrollbar
        scrollY: 500,
        deferRender: true
    });

    // Event handler for Edit Transaction button
    $('#transactionTable').on('click', 'a.editTransactionBtn', function (e) {
        e.preventDefault();
        $('#orderinfo_id').remove();
        $("#transactionForm").trigger("reset");

        var orderinfoId = $(this).data('id');

        $('<input>').attr({
            type: 'hidden',
            id: 'orderinfo_id',
            name: 'orderinfo_id',
            value: orderinfoId
        }).appendTo('#transactionForm');

        $('#transactionModal').modal('show');
        $('#transactionSubmit').hide();
        $('#transactionUpdate').show();

        $.ajax({
            type: "GET",
            url: `/api/transactions/${orderinfoId}`,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            dataType: "json",
            success: function (data) {
                console.log('Data received:', data);

                // Set status select field
                const statusSelect = $('#status');
                statusSelect.empty(); // Clear previous options

                // Loop over rolesMap to create options
                for (const [value, text] of Object.entries(rolesMap)) {
                    const option = new Option(text, value);
                    if (data.status == value) { // Compare with string representation
                        option.selected = true; // Set the selected option
                    }
                    statusSelect.append(option);
                }
            },
            error: function (error) {
                console.log('Error fetching transaction details:', error);
            }
        });
    });

    function formatDate(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        const hours = String(date.getHours()).padStart(2, '0');
        const minutes = String(date.getMinutes()).padStart(2, '0');
        const seconds = String(date.getSeconds()).padStart(2, '0');
        return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
    }

    // Handle status update
    $('#transactionUpdate').on('click', function (e) {
        e.preventDefault();
    
        // Get the selected status and order ID
        var orderinfoId = $('#orderinfo_id').val();
        var status = $('#status').val();
    
        console.log("Selected Status (before sending):", status); // Log status value
    
        // Ensure a valid status is selected
        if (status === null || status === "") {
            alert("Please select a valid status.");
            return;
        }
    
        // Get current date and time if status is 'Shipped'
        let dateShipped = null;
        if (status === '1') { // '1' corresponds to 'Shipped'
            dateShipped = formatDate(new Date()); // Format date as 'YYYY-MM-DD HH:MM:SS'
        }
    
        // Create FormData object for AJAX request
        var formData = new FormData();
        formData.append("status", status); // Use the correct integer value
        if (dateShipped) {
            formData.append("date_shipped", dateShipped); // Append current date if status is 'Shipped'
        }
        formData.append("_method", "PUT"); // Simulate PUT request
    
        // AJAX request to update the status
        $.ajax({
            type: "POST", // Use POST to simulate PUT
            url: `/api/transactions/${orderinfoId}`,
            data: formData,
            contentType: false,
            processData: false,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            dataType: "json",
            success: function (response) {
                console.log('Update successful:', response);
    
                // Hide the modal
                $('#transactionModal').modal('hide');
    
                // Reload the DataTable
                table.ajax.reload(null, false); // False to not reset pagination
            },
            error: function (xhr, status, error) {
                console.log('Error updating transaction status:', xhr.responseText);
                alert("Error: Could not update status. Please check the console for more details.");
            }
        });
    });

    // Infinite Scroll Setup
    let currentPage = 1;
    let loading = false;
    let endOfData = false;

    function loadMoreData() {
        if (loading || endOfData) return;
        loading = true;
        currentPage++;

        $.ajax({
            type: "GET",
            url: `/api/transactions?page=${currentPage}`, // Paginated URL
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            dataType: "json",
            success: function (json) {
                if (json.data.length === 0) {
                    endOfData = true;
                } else {
                    json.data.forEach(transaction => {
                        // Check if the transaction is already in the table
                        const exists = table
                            .column(0)
                            .data()
                            .some(id => id === transaction.orderinfos.orderinfo_id);

                        if (!exists) {
                            table.row.add(transaction).draw(false);
                        }
                    });
                }
                loading = false;
            },
            error: function () {
                loading = false;
            }
        });
    }

    // Infinite Scroll Event
    $('#transactionTable_wrapper').on('scroll', function () {
        // Ensure that infinite scroll triggers at the bottom of the table wrapper
        if ($('#transactionTable_wrapper').scrollTop() + $('#transactionTable_wrapper').height() >= $('#transactionTable').height() - 50) {
            loadMoreData();
        }
    });

    const updateTransactionStatus = (id, status) => {
        console.log("Attempting to update status for ID:", id, "to Status:", status);

        $.ajax({
            url: `/api/transactions/${id}`,
            method: 'POST',
            data: {
                _method: 'PUT',
                status: status // Ensure to send the correct integer value
            },
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function (response) {
                console.log('Transaction status updated:', response);
            },
            error: function (xhr) {
                console.error('Error updating transaction status:', xhr.responseText);
            }
        });
    };
});
