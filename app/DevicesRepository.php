<?php

namespace App;

class DevicesRepository {
    const KITCHEN_LAMPS_PREFIX = 'кухня лампа';
    const LIVING_ROOM_LAMPS_PREFIX = 'комната лампа';

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
            $result[] = $this->prepareRow($row);
        }
        usort($result, function ($a, $b){
            return strcmp($a->getName(), $b->getName());
        });
        return $result;
    }

    public function getAvailableDevices(): array {
        $rows = SQLite3Wrapper::getInstance()->query(
            'SELECT
                *
                FROM devices
                WHERE model = "chuangmi.plug.m1" OR model like "yeelink.light%"
                ORDER BY name ASC;
        ');
        $result = [];
        while ($row = $rows->fetchArray(SQLITE3_ASSOC)) {
            $result[] =  $this->prepareRow($row);
        }
        return $result;
    }

    private function prepareRow(array $row): Device {
        $row['name'] = $this->normalizeNameCase($row['name']);
        return new Device($row);
    }

    private function normalizeNameCase(string $name): string {
        return mb_convert_case((mb_strtolower($name)), MB_CASE_TITLE, "UTF-8");
    }

    private function getDeviceGroupName(string $deviceName) {
        if (mb_stristr($deviceName, self::LIVING_ROOM_LAMPS_PREFIX)) {
            return self::LIVING_ROOM_LAMPS_PREFIX;
        } elseif (mb_stristr($deviceName, self::KITCHEN_LAMPS_PREFIX)) {
            return self::KITCHEN_LAMPS_PREFIX;
        } else {
            return '';
        }
    }

    public function getAvailableDevicesGrouped(): array {
        $devices = $this->getAvailableDevices();

        $result = [];
        foreach ($devices as $device) {
            $groupName = $this->getDeviceGroupName($device->getName());
            if (!empty($groupName)) {
                $groupName = $this->normalizeNameCase($groupName);
                $result[$groupName][] = $device;
            } else {
                $result[$device->getName()] = $device;
            }
        }
        ksort($result);
        return $result;
    }

}