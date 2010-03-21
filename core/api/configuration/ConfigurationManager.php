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
 * File:        ConfigurationManager.php
 * 
 * @author      Anis BEREJEB
 * 
 */

/**
 * Configuration Manager
 * Take ini files and merges them to inherit Configuration
 */
class ConfigurationManager implements ArrayAccess
{
    /**
     * A list of files to merge
     * 
     * @var array  Defaults to array(). 
     */
    private $files = array();
    
    /**
     * The configuration handler
     * 
     * @var mixed  Defaults to null. 
     */
    private $configurationHandler = null;

    /**
     * The configuration container
     * 
     * @var mixed  Defaults to null. 
     */
    private $config = array();

    /**
     * Sets a list of files to be merged
     * 
     * @param array $files an array containing the full paths
     *                      to the files to be merged
     * @param ConfigurationParser $configuration The Configuration handling class                     
     */
    public function __construct(array $files, ConfigurationParser $configurationHandler)
    {
        $this->files = $files;
        $this->configurationHandler = $configurationHandler;
    }

    /**
     * merges two arrays recursively without deleting values in $a.
     * Just adds and updates values.
     * 
     * @param  array  $a  an array
     * @param  array  $b  a second array
     * @return array the resulting recursively merged array
     */
    public function mergeArrays(array $a, array $b) 
    {
        foreach($b as $k=>$v) 
        {
            if(is_array($v)) 
            {
                if(!isset($a[$k])) 
                {
                    $a[$k] = $v;
                } 
                else 
                {
                    $a[$k] = $this->mergeArrays($a[$k], $v);
                }
            } 
            else 
            {
                $a[$k] = $v;
            }
        }
        return $a;
    }

    /**
     * Merges the files 
     * 
     */
    public function merge()
    {
        foreach ($this->files as $file)
        {
            $this->configurationHandler->loadFile($file);
            $this->configurationHandler->parse();
            $this->config = $this->mergeArrays($this->config, $this->configurationHandler->getConfigs());
        }
    }

    /**
     * Gets a value of a given offset
     * 
     * @param  string  $offset The offset
     * @return string The value
     */
    public function offsetGet($offset)
    {
        return $this->config[$offset];
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
        throw new Exception ('Configuration object is read only.');
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
        throw new Exception ('Configuration object is read only.');
    }

    /**
     * Checks if a value exists.
     * 
     * @param  object  $offset The offset
     * @return bool    true if the value exists, false otherwise
     */
    public function offsetExists($offset)
    {
        return (isset($this->config[$offset]));
    } 

}
