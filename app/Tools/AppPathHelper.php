<?php

namespace App\Tools;

class AppPathHelper {
    public static function getAppRootPath() {
        $currentDir = __DIR__;
        $rootDir = $currentDir . '/../../';
        return realpath($rootDir);
    }
}