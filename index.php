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

function redirect() {
    $location = '/';
    header('Location: '.$location);
}

switch ($action) {
    case 'git-pull':
        $content = shell_exec('git pull --ff-only 2>&1');
        $content .= shell_exec('git submodule update --init --recursive 2>&1');
        break;
    case 'off':
        $devices = $devicesRepository->getByIds($ids);

        foreach ($devices as $device) {
            $miioWrapper->off($device);
        }
        redirect();
        break;
    case 'off-all':
        $devices = $devicesRepository->getAvailableDevices();

        foreach ($devices as $device) {
            $miioWrapper->off($device);
        }
        redirect();
        break;
    case 'on':
        $devices = $devicesRepository->getByIds($ids);

        foreach ($devices as $device) {
            $miioWrapper->on($device);
        }
        redirect();
        break;
    case 'toggle-switch':
        $devices = $devicesRepository->getByIds($ids);

        foreach ($devices as $device) {
            $miioWrapper->toggleSwitch($device);
        }
        redirect();
        break;
    case 'set-brightness':
        $brightness = $_GET['brightness'];
        $devices = $devicesRepository->getByIds($ids);

        foreach ($devices as $device) {
            $miioWrapper->setBrightness($device, $brightness);
        }
        redirect();
        break;
    case 'get-status':
        $devices = $devicesRepository->getByIds($ids);

        $statuses = [];
        foreach ($devices as $device) {
            /** @var $device \App\Device */
            $miioWrapper->updateDeviceStatus($device);
            $statuses[] = [
                'id' => $device->getId(),
                'powerState' => $device->getPowerState() ? 'on' : 'off',
                'brightness' =>  null !== $device->getBrightness() ? $device->getBrightness() : '-',
            ];
        }
        echo json_encode($statuses);
        die;
    case 'by-ids':
        $content = $devicesRepository->getByIds($ids);
        break;
    case 'index':
    default:
        $content = $devicesRepository->getAvailableDevicesGrouped();
        break;
}
if (!empty($_GET['mini'])) {
    require_once 'web/mini-main.html.php';
} else {
    require_once 'web/main.html.php';
}


