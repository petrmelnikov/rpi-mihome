<?php
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
                switch ($templateName) {
                    case 'humidity.html.php':
                        require_once 'web/humidity.html.php';
                        break;
                    case 'lights-control-mini.html.php':
                        require_once 'web/lights-control-mini.html.php';
                        break;
                    default:
                        require_once 'web/lights-control.html.php';
                        break;
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
