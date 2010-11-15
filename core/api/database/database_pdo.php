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
 * File:        database_pdo.php
 * 
 * @author      Anis BEREJEB
 * @version     0.1
 */

/**
 * PDODatabase : a placeholder for the pdo database.
 * 
 */
class PDODatabase extends PDO 
{

    /**
     * Constructor
     * 
     * @param  string   $host   The Host name
     * @param  string   $dbname The Database name
     * @param  string   $user   The user name
     * @param  string   $pass   The password
     * @param  string   $dbType The database Type, Optional, defaults to 'mysql'. 
     *                          Supports all databases supported by PDO.
     * @param  arrat    $params An array for other PDO params, I.E. for mysql :
     *                          array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
     */
    public function __construct($host, $dbname, $user, $pass, $dbType='mysql', $params = array())
    {
        parent::__construct(
            "$dbType:host=$host;dbname=$dbname", 
            $user, 
            $pass,
            $params
        );

    }
}
