$(document).ready(function () {
  $.ajax({
      type: "GET",
      url: "/api/title-chart",
      dataType: "json",
      success: function (data) {
          console.log(data.labels, data.data);
          var ctx = $("#titleChart");
          var myBarChart = new Chart(ctx, {
              type: 'bar',
              data: {
                  labels: data.labels,
                  datasets: [{
                      label: 'Number of Customers per Town',
                      data: data.data,
                      backgroundColor: [
                          'rgba(255, 99, 132, 0.2)',
                          'rgba(255, 159, 64, 0.2)',
                          'rgba(255, 205, 86, 0.2)',
                          'rgba(75, 192, 192, 0.2)',
                          'rgba(54, 162, 235, 0.2)',
                          'rgba(153, 102, 255, 0.2)',
                          'rgba(201, 203, 207, 0.2)'
                      ],
                      borderColor: [
                          'rgb(255, 99, 132)',
                          'rgb(255, 159, 64)',
                          'rgb(255, 205, 86)',
                          'rgb(75, 192, 192)',
                          'rgb(54, 162, 235)',
                          'rgb(153, 102, 255)',
                          'rgb(201, 203, 207)'
                      ],
                      borderWidth: 1,

                  }]
              },
              options: {
                  scales: {
                      y: {
                          beginAtZero: true
                      }
                  },
                  indexAxis: 'y',
              },
          });

      },
      error: function (error) {
          console.log(error);
      }
  });

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