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
 * File:        CacheFactory.php
 * 
 * @author      Anis BEREJEB
 * 
 */

/**
 * Cache Factory
 * 
 */
final class CacheFactory
{
    /**
     * Instanciates a cache object
     * 
     * @param  string  $cacheType The Cache Type
     * @return Cache object
     */
    public function get($cacheType = null)
    {
        $className = $cacheType . 'Cache';
        return (class_exists($className)) ? new $className() : new ArrayCache();
    }

    /**
     * Final private constructor
     */
    private function __construct(){}

    /**
     * private clone
     */
    private function __clone(){}
}    
