<?php

class database
{

    private static $instances = array();

    private function __construct()
    {
    }

    public static function getInstance($type = null)
    {
        if (!DB_TYPE)
        {
            throw new Exception('constant DB TYPE not Defined');
        }

        if (!$type)
        {
            $type = DB_TYPE;
        }

        if (!isset(self::$instances[DB_TYPE]))
        {
           self::$instances[DB_TYPE] = new PDODatabase(DB_HOST, DB_DATABASE, DB_USER, DB_PASSWORD, DB_TYPE);
        }
        return self::$instances[DB_TYPE];
    }

    private final function __clone()
    {
    }

}

