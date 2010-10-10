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
 * File:        ConfigurationParser.php
 * 
 * @author      Anis BEREJEB
 * 
 */

/**
 * Configuration parser base class
 * The configuration classes will handle all the configuration stuff for the framework. 
 * Configurations can be updated by application, or module using the naming rule.
 */
namespace Core\Configuration;
abstract class ConfigurationParser implements \ArrayAccess
{
    /**
     * Cache container
     * 
     * @var mixed  Defaults to array(). 
     */
    protected static $cache = array();

    /**
     * Configuration Container
     * 
     * @var array  Defaults to null. 
     */
    protected $settings = null;

    /**
     * Full path to the ini config file
     * 
     * @var string  Defaults to null. 
     */
    protected $file = null;

    /**
     * Loads a file into a string
     * 
     * @param  string  $file Absolute path to file
     */
    public function loadFile($file)
    {
          if (!file_exists($file))
          {
              throw new \Exception ('File ' . $file . ' Does not exist');
          }
          $this->file = $file;
    }
    
    /**
     * Parses the file, should be implemented in sub classes
     */
    abstract public function parse();

    /**
     * Gets a value of a given offset
     * 
     * @param  string  $offset The offset
     * @return string The value
     */
    public function offsetGet($offset)
    {
        return $this->settings[$offset];
    }    
    
    /**
     * Sets a value for a given offset
     * Throws always an exception since config files are read only
     *
     * @param  string  $offset   The offset 
     * @param  string  $value The value
     * @Exception Config object is read only
     */
    public function offsetSet($offset, $value)
    {
        throw new \BadMethodCallException ('Configuration object is read only.');
    }

    /**
     * Removes a value
     * Throws always an exception since config files are read only
     * 
     * @param  string  $offset The offset
     * @Exception Config object is  read only
     */
    public function offsetUnset($offset)
    {
        throw new \BadMethodCallException ('Configuration object is read only.');
    }

    /**
     * Checks if a value exists.
     * 
     * @param  object  $offset The offset
     * @return bool    true if the value exists, false otherwise
     */
    public function offsetExists($offset)
    {
        return (isset($this->settings[$offset]));
    } 

    /**
     * Returns all the config settings
     * 
     * @return array The config settings
     */
    public function getConfigs()
    {
        return $this->settings;
    } 
}
