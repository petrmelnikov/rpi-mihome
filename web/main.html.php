<head>
    <meta name="referrer" content="no-referrer"/>
<!--    <script src="/vendor/components/jquery/jquery.min.js"></script>-->
<!--    <script src="/vendor/twbs/bootstrap/dist/js/bootstrap.bundle.js"></script>-->
    <link rel="stylesheet" href="/vendor/twbs/bootstrap/dist/css/bootstrap.css">
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-sm">
                <table class="table">
                    <tr>
                        <th>name</th>
                        <th>model</th>
                        <th>status</th>
                        <th>control</th>
                    </tr>
                <?php
                foreach ($rows as $row) {
                    ?>
                    <tr>
                        <td><?= $row['name'] ?></td>
                        <td><?= $row['model'] ?></td>
                        <td><?= $row['status'] ?></td>
                        <td><a class="btn btn-primary" href="/?action=toggle-switch&id=<?=$row['id']?>">on/off</a></td>
                    </tr>
                    <?php
                }
                ?>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-sm">
                <a class="btn btn-primary" href="/?action=git-pull">git pull</a>
            </div>
        </div>
    </div>

</body>
