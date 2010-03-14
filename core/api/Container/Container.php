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
 * File:        Container.php
 * 
 * @author      Anis BEREJEB
 * 
 */

/**
 * Dependency Container
 * Inject dependencies to one object.
 * 
 */
class Container implements ArrayAccess
{
    /**
     * Objects container
     * 
     * @var array  Defaults to array(). 
     */
    private $_container = array();

    /**
     * determines if an offset exists
     * 
     * @param  object  $offset 
     * @return boolean true if the offset exists, false otherwise
     */
    public function offsetExists($offset)
    {
        return isset($this->_container[$offset]) ? true : false;
    }

    /**
     * Returns the value of an offset
     * To set an implemented method,
     * The application container should have a 
     * method prefixed by 'set' (i.e. injectCacheManager)
     * 
     * @param  mixed  $offset The offset  
     * @return mixed The offset value
     */
    public function offsetGet($offset)
    {

        $method = 'set' . ucfirst($offset);
        if (method_exists($this, $method))
        {
            return $this->_container[$offset] = $this->$method();
        }
        elseif (isset($this->_container[$offset]))
        {
            return $this->_container[$offset];
        }
        else
        {
            throw new Exception('Method ' . $method . ' is not implemented in the Container');
        }    
    }

    /**
     * Sets a value to the cache
     * 
     * @param  string  $offset The offset
     * @param  mixed   $value  The value
     */
    public function offsetSet($offset, $value)
    {
        $this->_container[$offset] = $value;
    }

    /**
     * Removes a value from the cache
     * 
     * @param  mixed  $offset 
     */
    public function offsetUnset($offset)
    {
        unset($this->_container[$offset]);
    }

    /**
     * injeTODO: short description.
     * 
     * @param  Cache  $cache 
     * @return Cache The cache object or null
     */
    public function setCacheManager()
    {
        $cache = CacheFactory::get(DEFAULT_CACHE_TYPE);
        $cache->setPrefix(DEFAULT_CACHE_PREFIX);
        return $cache;
    }
}
