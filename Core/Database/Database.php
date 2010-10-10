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
namespace Core\Database;
class Database
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
     * 
     * @param  string  $DB_HOST     The Host
     * @param  string  $DB_DATABASE The Database
     * @param  string  $DB_USER     The User
     * @param  string  $DB_PASSWORD The password
     * @param  string  $DB_TYPE     The database type, all what is supported by PDO, Optional, defaults to 'mysql'. 
     * @return PDO object
     */
    public static function getInstance($DB_HOST, $DB_DATABASE, $DB_USER, $DB_PASSWORD, $DB_TYPE = 'mysql')
    {
        if (!isset(self::$instances[$DB_TYPE]))
        {
           self::$instances[$DB_TYPE] = new PDODatabase($DB_HOST, $DB_DATABASE, $DB_USER, $DB_PASSWORD, $DB_TYPE);
        }
        return self::$instances[$DB_TYPE];
    }

    /**
     * private and final clone method
     * 
     */
    private final function __clone()
    {
    }
}

