<head>
    <meta name="referrer" content="no-referrer"/>
<!--    <script src="/vendor/components/jquery/jquery.min.js"></script>-->
<!--    <script src="/vendor/twbs/bootstrap/dist/js/bootstrap.bundle.js"></script>-->
    <link rel="stylesheet" href="/vendor/twbs/bootstrap/dist/css/bootstrap.css">
</head>
<body>
    <div class="container">
        <a class="btn btn-primary" href="/">index</a>
        <div class="row">
            <div class="col-sm">
                <?php
                    if (is_array($content)) {
                ?>
                <table class="table">
                    <tr>
                        <th>name</th>
                        <th>control <a class="btn btn-primary" href="/?action=off-all">off all</a></th>
                        <th><a class="btn btn-primary" href="/?action=with-status">status</a></th>
                        <th>model</th>
                    </tr>
                <?php
                foreach ($content as $row) {
                    ?>
                    <tr>
                        <td><?= mb_convert_case((mb_strtolower($row['name'])), MB_CASE_TITLE, "UTF-8") ?></td>
                        <td>
                            <a class="btn btn-primary" href="/?action=on&id=<?=$row['id']?>">on</a>
                            <a class="btn btn-primary" href="/?action=off&id=<?=$row['id']?>">off</a>
                            <a class="btn btn-primary" href="/?action=toggle-switch&id=<?=$row['id']?>">on/off</a>
                        </td>
                        <td><?= $row['status'] ?></td>
                        <td><?= $row['model'] ?></td>
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

</body>
