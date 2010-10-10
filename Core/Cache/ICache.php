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
 * File:        ICache.php
 * 
 * @author      Anis BEREJEB
 * 
 */

/**
 * APCCache Cache implementation
 * Implements cache with APC 
 * 
 */
namespace Core\Cache;
interface ICache
{
    /**
     * Sets an element in the cache
     * 
     * @param  mixed $offset The offset
     * @param  mixed $value  The value
     * @param  mixed $ttl The time to live
     */
    public function set($offset, $value, $ttl = null);
    
    /**
     * Checks if an offset exists 
     * 
     * @param  mixed  $offset The offset
     * @return bool     true if exists false otherwise
     */
    public function exists($offset);
    
    /**
     * Deletes a value from the cache
     * 
     * @param  mixed  $offset The offset
     */
    public function delete($offset);
    
    /**
     * Gets a value from the cache
     * 
     * @param  mixed  $offset The offset
     * @return mixed The result
     */
    public function get($offset); 

    /**
     * Clears the cache
     */
    public function clear();
}
