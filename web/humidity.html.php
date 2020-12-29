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