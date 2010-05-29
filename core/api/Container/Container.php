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
     * The module name
     * 
     * @var string  Defaults to null. 
     */
    private $module = null;

    /**
     * a static config variable to handle configuration
     */
    private static $config = null;

    /**
     * Objects container
     * 
     * @var array  Defaults to array(). 
     */
    private $_container = array();
    
    /**
     * Constructor
     * 
     * @param  mixed  $moduleName 
     */
    public function __construct($moduleName)
    {
        $this->module = $moduleName;
    }    

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
     * injects the Cache Manager
     * 
     * @return Cache The cache object or null
     */
    public function setCacheManager()
    {
        $config = $this->loadConfig();
        $cache = CacheFactory::get($config['cache']['type']);
        $cache->setPrefix($config['cache']['prefix']);
        return $cache;
    }
    
    /**
     * Instanciates the configuration once
     * To avoid using Singletons. Checks if the static property $config
     * exits before re-instanciating config.
     * 
     * @return ConfigurationManager the config
     */
    private function loadConfig()
    {
        if (isset(self::$config))
        {
            return self::$config;
        }
        else
        {
            $config =  new ConfigurationManager(
                array(
                    BUSINESS . '../conf/apps.ini', 
                    BUSINESS . 'conf/' . $this->module . '.ini'),
                new INIConfigurationParser()    
            );
            $config->merge();
            self::$config = $config;
            return $config;
        }
    }
    /**
     * Injects the Configuration object 
     * Loads Ini files
     * 
     * @return ConfigurationManager the config
     */
    public function setConfig()
    {
        return $this->loadConfig();
    }

    /**
     * Injects the database to the container
     * 
     * @return database the database
     */
    public function setDatabase()
    {
        return database::getInstance(
                $this['Config']['database']['host'],
                $this['Config']['database']['database'],
                $this['Config']['database']['user'],
                $this['Config']['database']['password'],
                $this['Config']['database']['type']
                );
    }    
}
