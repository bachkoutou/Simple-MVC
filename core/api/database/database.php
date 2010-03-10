<?php
/**
 * Note : Code is released under the GNU LGPL
 *
 * Please do not change the header of this file 
 *
 * This library is free software; you can redistribute it and/or modify it under the terms of the GNU 
 * Lesser General Public License as published by the Free Software Foundation; either version 2 of 
 * the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; 
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. 
 *
 * See the GNU Lesser General Public License for more details.
 */

/**
 * File:        database.php
 * 
 * @author      Anis BEREJEB
 * 
 */

/**
 * database class
 * implements a registery for each connection type
 * uses the DB_TYPE constant for the default database type
 */
class database
{

    /**
     * array of instances of databases types
     * 
     * @var array  Defaults to array(). 
     */
    private static $instances = array();

    /**
     * private constructor
     * 
     */
    private function __construct()
    {
    }

    /**
     * returns the Instance of the database. Creates one if it does not exist.
     * 
     * @param  string  $type The database type, Optional, defaults to null. 
     *                       Allows specifying a db default type
     * @return PDO object
     */
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

    /**
     * private and final clone method
     * 
     */
    private final function __clone()
    {
    }

}

