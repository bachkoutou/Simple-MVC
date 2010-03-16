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
 * File:       authManager.php
 * 
 * @author      Anis BEREJEB
 * @version     0.1
 */

/**
 * Session Manager Class
 * 
 * 
 */
class sessionManager
{
    /**
     * The session instance
     * 
     * @var sessionManager Defaults to null. 
     */
    private static $_instance = null;

    /**
     * A session Token
     * 
     * @var string  Defaults to null. 
     */
    private static $token = null;

        
    /**
     * initialises the session
     * 
     * @param  string  $sessionId Optional, defaults to 'admin'. 
     */
    public static function init($sessionId = 'admin')
    {

        self::$token = $token = 'mf_' . $sessionId;
        session_start($token);
        if (!isset($_SESSION[$token]))
        {
            $_SESSION[$token] = array();
        }    
    }     

    /**
     * Sets a value in the session
     * 
     * @param  mixed  $key The session Key
     * @param  mixed  $value    The session Value
     */
    public function set($key, $value)
    {
        $_SESSION[self::$token][$key] = $value;
    }

    /**
     * Removes a property from the sessionTODO: short description.
     * 
     * @param  mixed  $key The key to remove
     */
    public function remove($key)
    {
        if (isset($_SESSION[self::$token][$key]))
        {
            unset($_SESSION[self::$token][$key]);
        }
    }

    /**
     * Clears the session 
     * 
     */
    public function clear()
    {
        if (isset($_SESSION[self::$token]))
        {
            unset($_SESSION[self::$token]);
        }
    }
    
    /**
     * Returns a value of a given key
     * 
     * @param  mixed  $key The key 
     * @return mixed The value or false
     */
    public function get($key)
    {
        if (isset($_SESSION[self::$token][$key]))
        {
            return($_SESSION[self::$token][$key]);
        }
        return false;
    }
}
