<table class="table">
    <?php
    foreach ($content as $name => $value) {
        ?>
        <tr>
            <td style="white-space:nowrap;"><?= $name ?></td>
            <td style="white-space:nowrap;" id="<?= mb_strtolower(str_ireplace(' ', '-', $name)) ?>"><?= $value ?></td>
        </tr>
        <?php
    }
    ?>
</table>
<script src="/vendor/nnnick/chartjs/dist/Chart.js"></script>
<canvas id="myChart1" width="500" height="200"></canvas>
<script>
var chartTime = <?= json_encode($time)?>;
var chartTemperature = <?= json_encode($temperature)?>;
var chartHumidity = <?= json_encode($humidity)?>;
var chartWaterLevel = <?= json_encode($waterLevel)?>;
</script>
<script src="/web/humidity.js"></script>
