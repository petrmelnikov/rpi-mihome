<?php
namespace App;

class TemplateHelpers {
    public static function getIdsSeparatedByComma(array $devices): string {
        $ids = '';
        foreach ($devices as $device) {
            $ids .= $device->getId().',';
        }
        $ids = substr($ids, 0, -1);
        return $ids;
    }

    public static function getIdOrIds($device): string {
        if (is_array($device)) {
            return self::getIdsSeparatedByComma($device);
        } else {
            return $device->getId();
        }
    }

    public static function getModel($devices): string {
        $result = '';
        if (is_array($devices)) {
            $models = [];
            foreach ($devices as $device) {
                if (!isset($models[$device->getModel()])) {
                    $models[$device->getModel()] = 0;
                }
                $models[$device->getModel()]++;
            }
            foreach ($models as $model => $count) {
                $result = sprintf('%s (%s)', $model, $count).',';
            }
            $result = substr($result, 0, -1);
        } else {
            $result = $devices->getModel();
        }
        return $result;
    }
}