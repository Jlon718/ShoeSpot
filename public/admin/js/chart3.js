$(document).ready(function () {
    $.ajax({
        type: "GET",
        url: "/api/items-chart",
        dataType: "json",
        success: function (data) {
            console.log(data);
  
            var colors = [];
            for (var i = 0; i < data.data.length; i++) {
                var letters = '0123456789ABCDEF'.split('');
                var color = '#';
                for (var x = 0; x < 6; x++) {
                    color += letters[Math.floor(Math.random() * 16)];
                }
                colors.push(color);
            }
    
            var ctx = $("#itemsChart");
            var myBarChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Number of Items Sold',
                        data: data.data,
                        backgroundColor: colors, 
                        borderColor: [
                            'rgba(75, 192, 192, 1)',
                            'rgba(255,99,132,1)'
                        ],
                        borderWidth: 1,
                        responsive: true,
                    }]
                },
            });
        },
        error: function (error) {
            console.log(error);
        }
    });
    
  });