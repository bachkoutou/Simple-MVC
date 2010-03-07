<?php
/**
 * TODO: short description.
 * 
 * TODO: long description.
 * 
 */
class sessionManager
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

        self::$token = $token = 'mf_' . $sessionId;
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
    public function set($property, $value)
    {
        $_SESSION[self::$token][$property] = $value;
    }

    /**
     * TODO: short description.
     * 
     * @param  mixed  $property 
     * @return TODO
     */
    public function remove($property)
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
    public function clear()
    {
        foreach($_SESSION[self::$token] as $key => $value)
        {
            unset($_SESSION[self::$token][$key]);
        }    
    }
    
    /**
     * TODO: short description.
     * 
     * @param  mixed  $property 
     * @return TODO
     */
    public function get($property)
    {
        if (isset($_SESSION[self::$token][$property]))
        {
            return($_SESSION[self::$token][$property]);
        }
        return false;
    }
}
