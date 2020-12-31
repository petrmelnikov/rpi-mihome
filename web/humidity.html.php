<table class="table">
    <?php
    foreach ($content as $name => $value) {
        ?>
        <tr>
            <td style="white-space:nowrap;"><?= $name ?></td>
            <td style="white-space:nowrap;"><?= $value ?></td>
        </tr>
        <?php
    }
    ?>
</table>
<script src="/vendor/nnnick/chartjs/dist/Chart.js"></script>
<canvas id="myChart1" width="500" height="200"></canvas>
<script>
    var ctx = document.getElementById('myChart1');
    var chartData = {
            labels: <?= json_encode($time)?>,
            datasets: [
                {
                    label: 'Temperature C°',
                    data: <?= json_encode($temperature)?>,
                    borderColor: [
                        'rgba(255, 206, 86, 1)',
                    ],
                    borderWidth: 5,
                    fill: false
                    },
                {
                    label: 'Humidity %',
                    data: <?= json_encode($humidity)?>,
                    borderColor: [
                        'rgba(86, 206, 255, 1)',
                    ],
                    borderWidth: 5,
                    fill: false
                },
                {
                    label: 'Water level %',
                    data: <?= json_encode($waterLevel)?>,
                    borderColor: [
                        'rgba(71,145,255, 1)',
                    ],
                    borderWidth: 5,
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
</script>