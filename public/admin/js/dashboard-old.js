$(function () {
  'use strict'
  let message = $("#message").data('message');
  if (message) {
      Swal.fire({
          title: message,
          icon: 'error',
          showConfirmButton: false,
          timer: 1500
      })
  }
  $.getJSON(`${BASE_URL}/dashboard/report`, function (data) {
      let label = [];
      let total = [];
      $(data).each(function (i) {
          label.push(data[i].month)
          total.push(data[i].total)
      })
      let ctx = document.getElementById("report-sale");
      let myChart = new Chart(ctx, {
          type: 'bar',
          data: {
              labels: label,
              datasets: [
                  {
                      label: 'Total Customer',
                      backgroundColor: 'rgba(54, 162, 235, 100)',
                      borderColor: 'rgba(54, 162, 235, 100)',
                      data: total
                  }
              ]
          },
          options: {
              scales: {
                  yAxes: [{
                      ticks: {
                          beginAtZero: true
                      }
                  }]
              }
          }
      });
  })
})
