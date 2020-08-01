<?php


$mihomeFilename = '1597270033_mihome.sqlite';
$mihomeDatabase = new SQLite3($mihomeFilename);

$result = $mihomeDatabase->query(
    'SELECT
            ZLOCALIP,
            ZMODEL,
            ZNAME,
            ZTOKEN
            FROM ZDEVICE;
');

$myFilename = 'sqlite.sqlite';
$myDatabase = new SQLite3($myFilename);

$myDatabase->exec('DELETE FROM devices;');

while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    if (empty($row['ZLOCALIP'])) {
        continue;
    }
    $tokenEncoded = $row['ZTOKEN'];
    $token = shell_exec("echo '0: ".$tokenEncoded."' | xxd -r -p | openssl enc -d -aes-128-ecb -nopad -nosalt -K 00000000000000000000000000000000");

    $token = preg_replace("/[^a-zA-Z0-9]/", "", $token);

    $insert = [
        'ip' => $row['ZLOCALIP'],
        'model' => $row['ZMODEL'],
        'name' => $row['ZNAME'],
        'token' => $token,
    ];

    $stmt = $myDatabase->prepare("INSERT INTO devices (`ip`, `model`, `name`, `token`) VALUES (:ip,:model,:name,:token)");
    $stmt->bindParam(':ip', $insert['ip'], SQLITE3_TEXT);
    $stmt->bindParam(':model', $insert['model'], SQLITE3_TEXT);
    $stmt->bindParam(':name', $insert['name'], SQLITE3_TEXT);
    $stmt->bindParam(':token', $insert['token'], SQLITE3_TEXT);

    $stmt->execute();

}