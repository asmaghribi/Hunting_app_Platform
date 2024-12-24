$(function() {
    "use strict";

    // chart 1
    var ctx1 = document.getElementById('chart1').getContext('2d');

    var myChart1 = new Chart(ctx1, {
        type: 'line',
        data: {
            labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct"],
            datasets: [{
                label: 'New Visitor',
                backgroundColor: '#ff6384',
                borderColor: "transparent",
                pointRadius: 0,
                borderWidth: 3,
                data: [newVisitorsCount, 0, 0, 0, 0, 0, 0, 0, 0, 0]
            }, {
                label: 'Old Visitor',
                backgroundColor: '#36a2eb',
                borderColor: "transparent",
                pointRadius: 0,
                borderWidth: 1,
                data: [0, 0, 0, 0, 0, 0, 0, 0, 0, oldVisitorsCount]
            }]
        },
        options: {
            maintainAspectRatio: false,
            legend: {
                display: false,
                labels: {
                    fontColor: '#ddd',
                    boxWidth: 40
                }
            },
            tooltips: {
                displayColors: false
            },
            scales: {
                xAxes: [{
                    ticks: {
                        beginAtZero: true,
                        fontColor: '#ddd'
                    },
                    gridLines: {
                        display: true,
                        color: "rgba(221, 221, 221, 0.08)"
                    }
                }],
                yAxes: [{
                    ticks: {
                        beginAtZero: true,
                        fontColor: '#ddd'
                    },
                    gridLines: {
                        display: true,
                        color: "rgba(221, 221, 221, 0.08)"
                    }
                }]
            }
        }
    });

    // Function to update chart 1
    function updateChart1() {
        myChart1.data.datasets[0].data[0] = newVisitorsCount;
        myChart1.data.datasets[1].data[9] = oldVisitorsCount;
        myChart1.update();
    }

    // Call updateChart1 function to update chart on page load
    updateChart1();

    // Example of updating chart 1 periodically (every 5 seconds)
    setInterval(function() {
        updateChart1();
    }, 5000); // Update every 5 seconds

    var ctx2 = document.getElementById("chart2").getContext('2d');
var myChart2 = new Chart(ctx2, {
    type: 'doughnut',
    data: {
        labels: Object.keys(proiTypesCount),
        datasets: [{
            backgroundColor: [
                "#ffffff",
                "rgba(255, 255, 255, 0.70)",
                "rgba(255, 255, 255, 0.50)",
                "rgba(255, 255, 255, 0.20)"
            ],
            data: Object.values(proiTypesCount),
            borderWidth: [0, 0, 0, 0]
        }]
    },
    options: {
        maintainAspectRatio: false,
        legend: {
            position: "bottom",
            display: false,
            labels: {
                fontColor: '#ddd',
                boxWidth: 15
            }
        },
        tooltips: {
            displayColors: false
        }
    }
});


});
