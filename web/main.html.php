<?php
use App\TemplateHelpers;
use App\Device;
require_once 'head.html';
?>
<body>
    <div class="container">
        <a class="btn btn-primary" href="/">index</a>
        <a class="btn btn-primary" href="/?mini=1">mini</a>
        <a class="btn btn-primary" href="/?action=humidifier-status">humidifier</a>
        <div class="row">
            <div class="col-sm">
                <?php
                    if (is_array($content)) {
                ?>
                <table class="table">
                    <tr>
                        <th>name</th>
                        <th>control <a class="btn btn-primary" href="/?action=off-all">off all</a></th>
                        <th>power state <a class="btn btn-primary btn-refresh-all" href="">refresh</a></th>
                        <th>brightness <a class="btn btn-primary btn-refresh-all" href="">refresh</a></th>
                        <th>model</th>
                    </tr>
                <?php
                foreach ($content as $name => $device) {
                    if (!is_array($device)) {
                        $name = $device->getName();
                    }
                    ?>
                    <tr>
                        <td style="white-space:nowrap;"><?= is_array($device) ? '<a href="/?action=by-ids&id='.TemplateHelpers::getIdsSeparatedByComma($device).'">'.$name.'</a>' : $name ?></td>
                        <td style="white-space:nowrap;">
                            <a class="btn btn-primary btn-on-off" href="/?action=on&id=<?=TemplateHelpers::getIdOrIds($device)?>">on</a>
                            <a class="btn btn-primary btn-on-off" href="/?action=off&id=<?=TemplateHelpers::getIdOrIds($device)?>">off</a>
                            <a class="btn btn-primary btn-on-off" href="/?action=toggle-switch&id=<?=TemplateHelpers::getIdOrIds($device)?>">on/off</a>
                        </td>
                        <td style="white-space:nowrap;"><a class="btn-refresh power-state" href="/?action=get-status&id=<?=TemplateHelpers::getIdOrIds($device)?>">?</a></td>
                        <td style="white-space:nowrap;">
                            <?php
                            if (is_array($device) || Device::TYPE_YEELIGHT === $device->getType() ) {
                                ?>
                                <a class="btn-refresh brightness"
                                   href="/?action=get-status&id=<?= TemplateHelpers::getIdOrIds($device) ?>">?</a>
                                (
                                <?php
                                for ($i = 10; $i <= 100; $i += 10) {
                                    ?>
                                    <a class="btn-set-brightness"
                                       href="/?action=set-brightness&brightness=<?= $i ?>&id=<?= TemplateHelpers::getIdOrIds($device) ?>"><?= $i ?></a>
                                    <?php
                                }
                                ?>
                                )
                                <?php
                            } else {
                                ?>
                                -
                                <?php
                            }
                            ?>
                        </td>
                        <td><?=TemplateHelpers::getModel($device)?></td>
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
            </div>
        </div>
        <div class="row">
            <div class="col-sm">
                <a class="btn btn-primary" href="/?action=git-pull">git pull</a>
            </div>
        </div>
    </div>
    <script src="/web/js.js"></script>
</body>
