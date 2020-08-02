<head>
    <meta name="referrer" content="no-referrer"/>
    <script src="/vendor/components/jquery/jquery.min.js"></script>
    <script src="/vendor/twbs/bootstrap/dist/js/bootstrap.bundle.js"></script>
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
                        <th><a class="btn btn-primary btn-refresh-all" href="/?action=with-status">status</a></th>
                        <th>model</th>
                    </tr>
                <?php
                foreach ($content as $row) {
                    ?>
                    <tr>
                        <td style="white-space:nowrap;"><?= isset($row['group']) ? '<a href="/?action=by-ids&id='.$row['id'].'">'.$row['name'].'</a>' : $row['name'] ?></td>
                        <td style="white-space:nowrap;">
                            <a class="btn btn-primary btn-on-off" href="/?action=on&id=<?=$row['id']?>">on</a>
                            <a class="btn btn-primary btn-on-off" href="/?action=off&id=<?=$row['id']?>">off</a>
                            <a class="btn btn-primary btn-on-off" href="/?action=toggle-switch&id=<?=$row['id']?>">on/off</a>
                        </td>
                        <td style="white-space:nowrap;"><a class="btn-refresh" href="/?action=status&id=<?=$row['id']?>"><?= $row['status'] ?? '?' ?></a></td>
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
    <script type="text/javascript">
        $(".btn-refresh").click(function(event) {
            event.preventDefault();
            let button = $(this);
            let url = button.attr('href');
            $.get(url, function( data ) {
                let statuses = '';
                dataArray = JSON.parse(data);
                dataArray.forEach(function(value) {
                    statuses += value.status+'/';
                });
                button.parent().find('a').text(statuses)
            });
        });
        $(".btn-on-off").click(function(event) {
            event.preventDefault();
            let button = $(this);
            let url = button.attr('href');
            $.get(url, function() {});
        });
        $(".btn-refresh-all").click(function(event) {
            event.preventDefault();
            $(".btn-refresh").click();
        });
    </script>
</body>
