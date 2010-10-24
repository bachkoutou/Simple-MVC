<?php
/**
 * TODO: short description.
 * 
 * TODO: long description.
 * 
 */
class SessionManager 
{
    /**
     * TODO: description.
     * 
     * @var int  Defaults to null. 
     */
    private static $_instance = null;

    /**
     * TODO: description.
     * 
     * @var mixed  Defaults to null. 
     */
    private static $token = null;

        
    /**
     * TODO: short description.
     * 
     * @param  string  $sessionId Optional, defaults to 'admin'. 
     * @return TODO
     */
    public static function init($sessionId = 'admin')
    {
        self::$token = $token = $sessionId;
        session_start($token);
        if (!isset($_SESSION[$token]))
        {
            $_SESSION[$token] = array();
        }    
    }     

    /**
     * TODO: short description.
     * 
     * @param  mixed  $property 
     * @param  mixed  $value    
     * @return TODO
     */
    public static function set($property, $value)
    {
        $_SESSION[self::$token][$property] = $value;
    }

    /**
     * TODO: short description.
     * 
     * @param  mixed  $property 
     * @return TODO
     */
    public static function remove($property)
    {
        if (isset($_SESSION[self::$token][$property]))
        {
            unset($_SESSION[self::$token][$property]);
        }
    }

    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public static function clear()
    {
        foreach($_SESSION[self::$token] as $key => $value)
        {
            unset($_SESSION[self::$token][$key]);
        }    
    }
    
    /**
     * returns a session variable
     * 
     * @param  mixed  $property 
     * @return TODO
     */
    public static function get($property)
    {
        if (isset($_SESSION[self::$token][$property]))
        {
            return($_SESSION[self::$token][$property]);
        }
        return false;
    }

    /**
     * Returns the session array
     * 
     * @param  string $sessionName
     * @return array
     */
    public static function getSession()
    {
        $sessionName = self::$token;
        if (isset($_SESSION[$sessionName]))
        {
            return($_SESSION[$sessionName]);
        }
        return array();
    }

}
