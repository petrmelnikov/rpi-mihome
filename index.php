<?php
require_once __DIR__ . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

use App\MiioWrapper;
use App\DevicesRepository;

$miioWrapper = new MiioWrapper();
$devicesRepository = new DevicesRepository();

$action = !empty($_GET['action']) ? $_GET['action'] : '';

if (!empty($_GET['id'])) {
    $id = $_GET['id'];
    $ids = explode(',', $id);
} else {
    $ids = [];
}
switch ($action) {
    case 'git-pull':
        echo shell_exec('git pull 2>&1');
    case 'off':
        $devices = $devicesRepository->getByIds($ids);

        foreach ($devices as $device) {
            $miioWrapper->off($device['ip'], $device['token'], $device['model']);
        }
        header('Location: /');
        break;
    case 'off-all':
        $devices = $devicesRepository->getAvailableDevices();

        foreach ($devices as $device) {
            $miioWrapper->off($device['ip'], $device['token'], $device['model']);
        }
        header('Location: /');
        break;
    case 'on':
        $devices = $devicesRepository->getByIds($ids);

        foreach ($devices as $device) {
            $miioWrapper->on($device['ip'], $device['token'], $device['model']);
        }
        header('Location: /');
        break;
    case 'toggle-switch':
        $devices = $devicesRepository->getByIds($ids);

        foreach ($devices as $device) {
            $miioWrapper->toggleSwitch($device['ip'], $device['token'], $device['model']);
        }
        header('Location: /');
        break;
    case 'with-status':
        $content = $devicesRepository->getAvailableDevicesGrouped(true);
        break;
    case 'index':
    default:
        $content = $devicesRepository->getAvailableDevicesGrouped();
        break;
}


require_once 'web/main.html.php';