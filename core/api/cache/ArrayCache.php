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
 * File:        ArrayCache.php
 * 
 * @author      Anis BEREJEB
 * 
 */

/**
 * Implements an array cache
 */
class ArrayCache extends Cache 
{
    /**
     * the cache handler
     * 
     * @var array  Defaults to array(). 
     */
    private $cache = array();
    
    /**
     * Sets an element in the cache
     * 
     * @param  mixed  $offset The offset
     * @param  mixed   $value  The value
     * @param int $ttl Time to live - NOT IMPLEMENTED
     */
    public function set($offset, $value, $ttl = null) 
    {
        $this->cache[$this->prefix][$offset] = $value;
    }
    
    /**
     * Checks if an offset exists 
     * 
     * @param  mixed  $offset The offset
     * @return bool     true if exists false otherwise
     */
    public function exists($offset) 
    {
        return isset($this->cache[$this->prefix][$offset]);
    }
    
    /**
     * Deletes a value from the cache
     * 
     * @param  mixed  $offset The offset
     */
    public function delete($offset) 
    {
        unset($this->cache[$this->prefix][$offset]);
    }
    
    /**
     * Gets a value from the cache
     * 
     * @param  mixed  $offset The offset
     * @return mixed The result
     */
    public function get($offset) 
    {
        return isset($this->cache[$this->prefix][$offset]) ? $this->cache[$this->prefix][$offset] : null;
    }

    /**
     * Clears the cache
     */
    public function clear()
    {
        unset($this->cache[$this->prefix]);
    }    
}
