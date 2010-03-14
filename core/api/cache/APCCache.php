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
 * File:        APCCache.php
 * 
 * @author      Anis BEREJEB
 * 
 */

/**
 * APCCache Cache implementation
 * Implements cache with APC 
 * 
 */
class APCCache extends Cache
{
    /**
     * Checks if the APC extension is loaded
     * @param  string  $prefix the prefix of the cache keys
     * @Exception if the APC extension is not Loaded
     * 
     */
    public function __construct()
    {
        if (!extension_loaded('apc'))
        {
            throw new Exception('APC Extension not found.');
        }
    }

    /**
     * determines if an offset exists
     * 
     * @param  object  $offset 
     * @return boolean true if the offset exists, false otherwise
     */
    public function exists($offset)
    {
        return (apc_fetch($this->prefix . '_' . $offset)) ? true : false;
    }

    /**
     * Returns the value of an offset
     * 
     * @param  mixed  $offset The offset  
     * @return mixed The offset value
     */
    public function get($offset)
    {
        return apc_fetch($this->prefix . '_' . $offset);
    }

    /**
     * Sets a value to the cache
     * 
     * @param  mixed  $offset The offset
     * @param  mixed   $value  The value
     * @param int $ttl The time to live
     */
    public function set($offset, $value, $ttl = null)
    {
        apc_store($this->prefix . '_' . $offset, $value);
    }

    /**
     * Removes a value from the cache
     * 
     * @param  mixed  $offset 
     */
    public function delete($offset)
    {
        apc_delete($this->prefix . '_' . $offset);
    }
    
    /**
     * Clears the cache
     * @WARNING this will clear all the cache! 
     * BE CAREFUL WITH THIS FUNCTION
     */
    public function clear()
    {
        apc_clear_cache('user');
    }    
}
