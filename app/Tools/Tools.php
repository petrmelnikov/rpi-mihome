<?php

namespace App\Tools;

class Tools {
    public static function getAppRootPath() {
        $currentDir = __DIR__;
        $rootDir = $currentDir . '/../../';
        return realpath($rootDir);
    }

    public static function toCamelCase(string $input, string $separator = ' ') {
        return str_replace($separator, '', lcfirst(ucwords($input, $separator)));
    }
}