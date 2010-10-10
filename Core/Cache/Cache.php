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
 * File:        Cache.php
 * 
 * @author      Anis BEREJEB
 * 
 */

/**
 * Implements an array cache
 */
namespace Core\Cache;
abstract class Cache implements ICache 
{
    /**
     * prefix
     * 
     * @var mixed  Defaults to ''. 
     */
    protected $prefix = '';
    /**
     * prefix setter
     * 
     * @param  string  $prefix the prefix for all cache keys
     * @Exception if prefix is empty
     */
    public function setPrefix($prefix)
    {
        if (empty($prefix))
        {
            throw new Exception('prefix should not be empty.');
        }    
        $this->prefix = $prefix;
    } 
    
    /**
     * Prefix setter
     * 
     * @return string the cache prefix
     */
    public function getPrefix()
    {
        return $this->prefix;
    }    
}
