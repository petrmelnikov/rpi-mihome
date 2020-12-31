<?php

namespace App;

use SQLite3;
use App\Tools\AppPathHelper;

class SQLite3Wrapper
{
    private static $instance = null;

    public static function getInstance(){
        if (null === self::$instance) {
            $databasePath = AppPathHelper::getAppRootPath() . $_ENV['DATABASE_PATH'];
            self::$instance = new SQLite3($databasePath);
        }
        return self::$instance;
    }
}