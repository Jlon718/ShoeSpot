$(document).ready(function () {
    $.ajax({
        type: "GET",
        url: "/api/sales-chart",
        dataType: "json",
        success: function (data) {
            console.log(data);
            var ctx = $("#salesChart");
            var myBarChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Monthly Sales',
                        data: data.data,
                        backgroundColor: [
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(255, 99, 132, 0.2)'
                        ],
                        borderColor: [
                            'rgba(75, 192, 192, 1)',
                            'rgba(255,99,132,1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                },
            });
  
        },
        error: function (error) {
            console.log(error);
        }
    });
  
  });