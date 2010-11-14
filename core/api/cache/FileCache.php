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
    // the length of the TTL into the cache file
    const TTL_LENGTH = 10;

    // The string that prepend the ttl value into the cache file
    const TTL_PREPEND_STRING = 'TTL=';

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
        $this->directory = (!empty($directory)) ?  $directory : sys_get_temp_dir() . DIRECTORY_SEPARATOR;
    }
    
    /**
     * Directory setter
     * 
     * @param  string  $directory 
     */
    public function setDirectory($directory)
    {
        if (!is_dir($directory))
        {
            throw new InvalidArgumentException('directory ' . $directory . ' is not a directory');
        }    
        if (!is_writable($directory))
        {
            throw new InvalidArgumentException('directory ' . $directory . ' is not writeable');
        }    
        $this->directory = $directory;
    }    

    /**
     * Directory Getter
     * 
     * @return string $directory
     */
    public function getDirectory()
    {
        return $this->directory;
    }
    /**
     * Sets an element in the cache
     * 
     * @param  mixed  $offset The offset
     * @param  mixed   $value  The value
     * @param int $ttl Time to live
     */
    public function set($offset, $value, $ttl = null) 
    {
        $ttl = self::TTL_PREPEND_STRING . str_pad((empty($ttl) ? 0 : (int)$ttl), self::TTL_LENGTH, ' ', STR_PAD_RIGHT);
        file_put_contents($this->directory . $this->prefix . '_' . md5($offset), $ttl . $value);
    }

    /**
     * Checks if an offset exists 
     * 
     * @param  mixed  $offset The offset
     * @return bool     true if exists false otherwise
     */
    public function exists($offset) 
    {
        $filePath = $this->directory . $this->prefix . '_' . md5($offset);
        if (is_file($filePath))
        {
            $cacheContent = file_get_contents($filePath);

            $ttl = (int)rtrim(substr($cacheContent, strlen(self::TTL_PREPEND_STRING), self::TTL_LENGTH));
            // ttl of 0 = unlimited cache time.
            if (0 === $ttl)
            {
                return substr($cacheContent, (self::TTL_LENGTH + strlen(self::TTL_PREPEND_STRING)));
            }

            // get the cache file creation time
            $fileTimestamp = filectime($filePath);
            // if the cache file creation time + the ttl still lower than the current time, we return the cache content.
            // otherwise, the cache content is expired
            if ($fileTimestamp + $ttl > time())
            {
                return substr($cacheContent, (self::TTL_LENGTH + strlen(self::TTL_PREPEND_STRING)));
            }
            // cache content TTL expired, let's delete it from the cache
            $this->delete($offset);
        }
        return false;
    }

    
    /**
     * Deletes a value from the cache
     * 
     * @param  mixed  $offset The offset
     */
    public function delete($offset) 
    {
        return unlink($this->directory . $this->prefix . '_' . md5($offset));
    }

    /**
     * Gets a value from the cache
     * 
     * @param  mixed  $offset The offset
     * @return mixed The result
     */
    public function get($offset) 
    {
        return substr(file_get_contents($this->directory . $this->prefix . '_' . md5($offset)), (self::TTL_LENGTH + strlen(self::TTL_PREPEND_STRING)));
    }

    /**
     * Clears the cache
     */
    public function clear()
    {
        foreach (glob($this->directory . $this->prefix . "/*") as $file)
        {
            unlink($file);
        }    
    }   
}    
