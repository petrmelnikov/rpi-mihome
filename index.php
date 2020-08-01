<?php

const KITCHEN_LAMPS_PREFIX = 'кухня лампа';
const LIVING_ROOM_LAMPS_PREFIX = 'Комната лампа';

function shellExec($cmd) {
    $homeDirectory = shell_exec('echo ~');
    $exportLocalBinPath = 'export PATH=$PATH:'.$homeDirectory.'.local/bin;';
    return shell_exec($exportLocalBinPath.$cmd);
}


function miPowerPlugIsOn($ip, $token) {
    $result = shellExec('miplug --ip '.$ip.' --token '.$token.' status');
    if (false === stristr($result, 'power: ')) {
        return null;
    } else {
        if (false !== stristr($result, 'power: true')) {
            return true;
        } else {
            return false;
        }
    }
}

function toggleSwitch($ip, $token, $model) {
    if ($model === 'chuangmi.plug.m1') {
        $miPowerPlugIsOn = miPowerPlugIsOn($ip, $token);
        if (null === $miPowerPlugIsOn) {
            return;
        }
        if ($miPowerPlugIsOn) {
            $param = 'off';
        } else {
            $param = 'on';
        }
        $command = 'miplug';
    } elseif (false !== stristr($model, 'yeelink.light')) {
        $param = 'toggle';
        $command = 'miiocli yeelight';
    }
    shellExec($command.' --ip '.$ip.' --token '.$token.' '.$param);
}

$myFilename = 'sqlite.sqlite';
$myDatabase = new SQLite3($myFilename);

$action = !empty($_GET['action']) ? $_GET['action'] : '';

switch ($action) {
    case 'git-pull':
        $content = shell_exec('git pull 2>&1');
        break;
    case 'toggle-switch':
        if (!empty($_GET['id'])) {
            $id = $_GET['id'];
        } else {
            break;
        }

        $idsArray = explode(',', $id);

        $placeHolders = [];
        foreach ($idsArray as $key => $idItem) {
            $placeHolders[':id'.$key] = $idItem;
        }

        $stmt = $myDatabase->prepare(
            'SELECT
                *
                FROM devices
                WHERE id IN ('.implode(',', array_keys($placeHolders)).');
        ');

        foreach ($placeHolders as $key => $idItem) {
            $stmt->bindValue($key, $idItem, SQLITE3_TEXT);
        }

        $result = $stmt->execute();
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            toggleSwitch($row['ip'], $row['token'], $row['model']);
        }
//        break;
    case 'index':
    default:
        $result = $myDatabase->query(
            'SELECT
                *
                FROM devices
                WHERE model = "chuangmi.plug.m1" OR model like "yeelink.light%"
                ORDER BY name ASC;
        ');
        $rows = [];
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $row['status'] = '';
            if (stristr($row['name'], LIVING_ROOM_LAMPS_PREFIX)){
                $rows[LIVING_ROOM_LAMPS_PREFIX] = [
                    'name' => LIVING_ROOM_LAMPS_PREFIX,
                    'id' => !empty($rows[LIVING_ROOM_LAMPS_PREFIX]['id']) ? $rows[LIVING_ROOM_LAMPS_PREFIX]['id'].','.$row['id'] : $row['id'],
                    'model' => $row['model'],
                    'status' => $row['status'],
                ];
            } elseif (stristr($row['name'], KITCHEN_LAMPS_PREFIX)) {
                $rows[KITCHEN_LAMPS_PREFIX] = [
                    'name' => KITCHEN_LAMPS_PREFIX,
                    'id' => !empty($rows[KITCHEN_LAMPS_PREFIX]['id']) ? $rows[KITCHEN_LAMPS_PREFIX]['id'].','.$row['id'] : $row['id'],
                    'model' => $row['model'],
                    'status' => $row['status'],
                ];
            } else {
                $rows[$row['name']] = $row;
            }
        }
        break;
}





require_once 'web/main.html.php';