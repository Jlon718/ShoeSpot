$(document).ready(function () { 
    $('#search-form input[type="search"]').on('keyup', function() {
        let query = $(this).val();
        console.log("Keyup event fired. Query: ", query);
        if (query.length > 0) {
            $.ajax({
                url: '/home/search',
                type: 'GET',
                data: { product_name: query },
                success: function(data) {
                    $('#autocomplete-results').empty();
                    if (data.data.length > 0) {
                        data.data.forEach(function(item) {
                            $('#autocomplete-results').append('<li><a href="/products/info/' + item.product_id + '">' + item.product_name + '</a></li>');
                        });
                    } else {
                        $('#autocomplete-results').append('<li>No results found</li>');
                    }
                }
            });
        } else {
            $('#autocomplete-results').empty();
        }
    });
});
