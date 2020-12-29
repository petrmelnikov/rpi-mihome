<?php

use App\TemplateHelpers;

if (is_array($content)) {
    ?>
    <table class="table">
        <tr>
            <th>name</th>
            <th>control <a class="btn btn-primary" href="/?action=off-all">off all</a></th>
        </tr>
        <?php
        foreach ($content as $name => $device) {
            if (!is_array($device)) {
                $name = $device->getName();
            }
            ?>
            <tr>
                <td style="white-space:nowrap;"><?= is_array($device) ? '<a href="/?mini=1&action=by-ids&id=' . TemplateHelpers::getIdsSeparatedByComma($device) . '">' . $name . '</a>' : $name ?></td>
                <td style="white-space:nowrap;">
                    <a class="btn btn-primary btn-on-off"
                       href="/?action=on&id=<?= TemplateHelpers::getIdOrIds($device) ?>">on</a>
                    <a class="btn btn-primary btn-on-off"
                       href="/?action=off&id=<?= TemplateHelpers::getIdOrIds($device) ?>">off</a>
                    <a class="btn btn-primary btn-on-off"
                       href="/?action=toggle-switch&id=<?= TemplateHelpers::getIdOrIds($device) ?>">on/off</a>
                </td>
            </tr>
            <?php
        }
        ?>
    </table>
    <?php
} else {
    echo $content;
}
?>