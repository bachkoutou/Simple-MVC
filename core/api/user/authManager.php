<?php 
/**
 * TODO: short description.
 * 
 * TODO: long description.
 * 
 */
class authManager
{
    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public static function check()
    {
        sessionManager::init();
        // implement access here
        if (false === sessionManager::get('login') || (false === sessionManager::get('password')))
        {
            return false;
        }
        return true;
    }
}    
