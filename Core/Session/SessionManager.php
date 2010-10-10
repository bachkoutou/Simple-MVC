<?php
/**
 * the Session Manager 
 */
namespace Core\Session;
class SessionManager 
{
    /**
     * The session id token
     * 
     * @var string  Defaults to null. 
     */
    private static $token = null;


    /**
     * Initializes the Session
     *
     * @param  string  $sessionId Optional, defaults to 'admin'. 
     */
    public function init($sessionId = 'admin')
    {
        self::$token = $token = $sessionId;
        session_start($token);
        if (!isset($_SESSION[$token]))
        {
            $_SESSION[$token] = array();
        }    
    }     

    /**
     * Sets a session value
     *
     * @param  mixed  $property 
     * @param  mixed  $value    
     */
    public function set($property, $value)
    {
        $_SESSION[self::$token][$property] = $value;
    }

    /**
     * Removes a value from the session
     *
     * @param  mixed  $property 
     */
    public function remove($property)
    {
        if (isset($_SESSION[self::$token][$property]))
        {
            unset($_SESSION[self::$token][$property]);
        }
    }

    /**
     * Clears the session
     *
     */
    public function clear()
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
     * @return mixed the session variable
     */
    public function get($property)
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
    public function getSession()
    {
        $sessionName = self::$token;
        if (isset($_SESSION[$sessionName]))
        {
            return($_SESSION[$sessionName]);
        }
        return array();
    }
}
