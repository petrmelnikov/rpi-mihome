<?php
require_once __DIR__ . '/vendor/autoload.php';

use App\MiioWrapper;
use App\DevicesRepository;
use App\Device;
use App\SQLite3Wrapper;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$miioWrapper = new MiioWrapper();
$devicesRepository = new DevicesRepository();


$saveStateToDataBase = function (array $values){
    if (!empty($values)) {
        SQLite3Wrapper::getInstance()->exec(
            "CREATE TABLE IF NOT EXISTS humidifier_history (
                    id          INTEGER      PRIMARY KEY AUTOINCREMENT,
                    humidity    STRING (100),
                    temperature STRING (100),
                    water_level STRING (100),
                    unixtime    STRING (100) 
                    );"
        );

        $stmt = SQLite3Wrapper::getInstance()->prepare(
            "INSERT INTO humidifier_history (
                    'humidity',
                    'temperature',
                    'water_level',
                    'unixtime'
                )
                VALUES (
                    :humidity,
                    :temperature,
                    :water_level,
                    :unixtime
                );
        ");

        $stmt->bindValue('humidity', filter_var($values['Humidity'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION), SQLITE3_TEXT);
        $stmt->bindValue('temperature', filter_var($values['Temperature'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION), SQLITE3_TEXT);
        $stmt->bindValue('water_level', filter_var($values['Water Level'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION), SQLITE3_TEXT);
        $stmt->bindValue('unixtime', time(), SQLITE3_TEXT);
        $stmt->execute();
    }
};

$humidifiers = $devicesRepository->getDevicesByType(Device::TYPE_HUMIDIFIER);
$humidifier = reset($humidifiers);
if (false !== $humidifier) {
    $miioWrapper->updateDeviceStatus($humidifier);
    $valueNames = [
        'Temperature',
        'Humidity',
        'Water Level',
    ];
    $values = [];
    foreach ($valueNames as $valueName) {
        $values[$valueName] = $humidifier->getStatusValue($valueName);
    }
    $saveStateToDataBase($values);
}