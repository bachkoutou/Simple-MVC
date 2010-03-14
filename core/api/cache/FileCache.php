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
 * File:        FileCache.php
 * 
 * @author      Anis BEREJEB
 * 
 */

/**
 * Implements an array cache
 */
class FileCache extends Cache 
{
    /**
     * the cache handler
     * 
     * @var array  Defaults to array(). 
     */
    private $directory = array();
    
    /**
     * Constructor
     * 
     * @param  string  $prefix    all files will be prefixed with that prefix
     * @param  string  $directory the directory in which the files will be stored
     *                            or the temp directory , Defaults to null
     */
    public function __construct($directory = null)
    {
        parent::__construct();
        $this->directory = (!empty($directory)) ?  $directory : sys_get_temp_dir() . DIRECTORY_SEPARATOR;
    }

    /**
     * Sets an element in the cache
     * 
     * @param  mixed  $offset The offset
     * @param  mixed   $value  The value
     * @param int $ttl Time to live - NOT IMPLEMENTED
     */
    public function set($offset, $value, $ttl = null) 
    {
        file_put_contents($this->directory . md5($this->prefix) . '_' . md5($offset), $value);
    }
    
    /**
     * Checks if an offset exists 
     * 
     * @param  mixed  $offset The offset
     * @return bool     true if exists false otherwise
     */
    public function exists($offset) 
    {
        return is_file($this->directory . md5($this->prefix) . '_' . md5($offset));
    }
    
    /**
     * Deletes a value from the cache
     * 
     * @param  mixed  $offset The offset
     */
    public function delete($offset) 
    {
        return unlink($this->directory . md5($this->prefix) . '_' . md5($offset));
    }
    
    /**
     * Gets a value from the cache
     * 
     * @param  mixed  $offset The offset
     * @return mixed The result
     */
    public function get($offset) 
    {
        return file_get_contents($this->directory . md5($this->prefix) . '_' . md5($offset));
    }

    /**
     * Clears the cache
     */
    public function clear()
    {
        foreach (glob($this->directory . md5($this->prefix) . "/*") as $file)
        {
            unlink($file);
        }    
    }    
}
