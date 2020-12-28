<form action="/add.php" method="post">
    <label>name</label>
    <input type="text" name="name">
    <label>model</label>
    <input type="text" name="model">
    <label>token</label>
    <input type="text" name="token">
    <label>ip</label>
    <input type="text" name="ip">
    <input type="submit">
</form>

<?php

use App\SQLite3Wrapper;

require_once __DIR__ . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$checkRequiredParams = function ($requiredParams, $POST){
    foreach($requiredParams as $requiredParam) {
        if (!in_array($requiredParam, array_keys($POST)) || empty($POST[$requiredParam])) {
            return false;
        }
        return true;
    }
};

$requiredParams = [
    'name',
    'model',
    'token',
    'ip',
];

if ($checkRequiredParams($requiredParams, $_POST)) {
    $stmt = SQLite3Wrapper::getInstance()->prepare(
        "INSERT INTO devices (
                    'name',
                    'model',
                    'token',
                    'ip'
                )
                VALUES (
                    :name,
                    :model,
                    :token,
                    :ip
                );
        ");
    foreach($requiredParams as $requiredParam) {
        $stmt->bindValue($requiredParam, $_POST[$requiredParam], SQLITE3_TEXT);
    }
    $stmt->execute();
}