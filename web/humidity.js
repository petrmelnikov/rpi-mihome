$(window).on("load", function (event) {
    // event.preventDefault();
    let url = '/?action=humidifier-status-json';
    $.get(url, function (data) {
        dataArray = JSON.parse(data);
        $('#power').text(dataArray.power);
        $('#temperature').text(dataArray.temperature)
        $('#humidity').text(dataArray.humidity);
        $('#water-level').text(dataArray.waterLevel);
    });
});


var ctx = document.getElementById('myChart1');
var chartData = {
    labels: chartTime,
    datasets: [
        {
            label: 'Temperature C°',
            data: chartTemperature,
            borderColor: [
                'rgba(255, 206, 86, 1)',
            ],
            borderWidth: 3,
            pointRadius: 1,
            pointBorderWidth: 0,
            pointBorderColor: 'black',
            pointBackgroundColor: 'rgba(255, 206, 86, 1)',
            fill: false
        },
        {
            label: 'Humidity %',
            data: chartHumidity,
            borderColor: [
                'rgba(86, 206, 255, 1)',
            ],
            borderWidth: 3,
            pointRadius: 1,
            pointBorderWidth: 0,
            pointBorderColor: 'black',
            pointBackgroundColor: 'rgba(86, 206, 255, 1)',
            fill: false
        },
        {
            label: 'Water level %',
            data: chartWaterLevel,
            borderColor: [
                'rgba(71,145,255, 1)',
            ],
            borderWidth: 3,
            pointRadius: 1,
            pointBorderWidth: 0,
            pointBorderColor: 'black',
            pointBackgroundColor: 'rgba(71,145,255, 1)',
            fill: false
        }
    ]
};
var myChart = new Chart(ctx, {
    type: 'line',
    data: chartData,
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