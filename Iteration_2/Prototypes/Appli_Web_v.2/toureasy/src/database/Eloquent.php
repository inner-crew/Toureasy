<?php

namespace toureasy\database;
use Illuminate\Database\Capsule\Manager as DB;
class Eloquent
{
    public static function start(string $file) {
        $db = new DB();
        $db->addConnection(parse_ini_file($file));
        $db->setAsGlobal();
        $db->bootEloquent();
    }
}