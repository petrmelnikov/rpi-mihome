<?php

namespace App;

class DevicesRepository {
    const KITCHEN_LAMPS_PREFIX = 'кухня лампа';
    const LIVING_ROOM_LAMPS_PREFIX = 'Комната лампа';

    public function __construct() {

    }

    public function getByIds(array $ids): array {
        $placeHolders = [];
        foreach ($ids as $key => $id) {
            $placeHolders[':id'.$key] = $id;
        }

        $stmt = SQLite3Wrapper::getInstance()->prepare(
            'SELECT
                *
                FROM devices
                WHERE id IN ('.implode(',', array_keys($placeHolders)).');
        ');

        foreach ($placeHolders as $key => $idItem) {
            $stmt->bindValue($key, $idItem, SQLITE3_TEXT);
        }

        $rows = $stmt->execute();
        $result = [];
        while ($row = $rows->fetchArray(SQLITE3_ASSOC)) {
            $result[] = $row;
        }
        return $result;
    }

    public function getAvailableDevices($withStatus = false): array {
        $rows = SQLite3Wrapper::getInstance()->query(
            'SELECT
                *
                FROM devices
                WHERE model = "chuangmi.plug.m1" OR model like "yeelink.light%"
                ORDER BY name ASC;
        ');
        $result = [];
        while ($row = $rows->fetchArray(SQLITE3_ASSOC)) {
            $row['status'] = $withStatus ? $this->getDeviceStatus($row['ip'], $row['token'], $row['model']) : '?';
            $result[] = $row;
        }
        return $result;
    }

    private function getDeviceGroupName(string $deviceName) {
        if (stristr($deviceName, self::LIVING_ROOM_LAMPS_PREFIX)) {
            return self::LIVING_ROOM_LAMPS_PREFIX;
        } elseif (stristr($deviceName, self::KITCHEN_LAMPS_PREFIX)) {
            return self::KITCHEN_LAMPS_PREFIX;
        } else {
            return '';
        }
    }

    public function getAvailableDevicesGrouped($withStatus = false): array {
        $devices = $this->getAvailableDevices($withStatus);

        $result = [];
        foreach ($devices as $device) {
            $groupName = $this->getDeviceGroupName($device['name']);
            if (!empty($groupName)) {
                $id = !empty($result[$groupName]['id']) ? $result[$groupName]['id'] . ',' . $device['id'] : $device['id'];
                $status = !empty($result[$groupName]['status']) ? $result[$groupName]['status'] . '/' . $device['status'] : $device['status'];
                $model = !empty($result[$groupName]['model']) ? $result[$groupName]['model'] . '/' . $device['model'] : $device['model'];
                $result[$groupName] = [
                    'name' => $groupName,
                    'id' => $id,
                    'model' => $model,
                    'status' => $status,
                ];
            } else {
                $result[$device['name']] = $device;
            }
        }
        return $result;
    }

    public function getDeviceStatusByIds(array $ids): array {
        $devices = $this->getByIds($ids);
        $statuses = [];
        foreach ($devices as $device) {
            $statuses[] = [
                'id' => $device['id'],
                'status' => $this->getDeviceStatus($device['ip'], $device['token'], $device['model']),
            ];
        }
        return $statuses;
    }

    private function getDeviceStatus(string $ip, string $token, string $model): string {
        $miioWrapper = new MiioWrapper();
        $status = $miioWrapper->getDevicePowerStateByModel($ip, $token, $model);
        $result = 'offline';
        if (null !== $status) {
            $result = $status ? 'on' : 'off';
        }
        return $result;
    }

}