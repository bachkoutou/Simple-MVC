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
 * File:        XMLConfigurationParser.php
 * 
 * @author      Anis BEREJEB
 * 
 */

/**
 * Defines an XML configuration parser
 */
class XMLConfigurationParser extends ConfigurationParser
{
    /**
     * Parses the file 
     * 
     * @param  bool  $enableSections true if sections should be enabled, 
     *                      false otherwise, Optional, defaults to true. 
     */
    public function parse()
    {
        $this->settings =  self::objectToArray(simplexml_load_file($this->file));
    }

    /**
     * Converts the simple xml object to an array
     *
     * @param  object  $object The object to convert
     * @return array the array representation
     */
    public static function objectToArray($object)
    {
        $vars = null;
        if (is_array($object))
        {
            $vars = $object;
        }
        elseif (is_object($object))
        {
            $vars = get_object_vars($object);
        }
        if (!is_array($vars))
        {
            return strval($object);
        }
        $array = null;
        foreach ($vars as $key => $value)
        {
            $array[$key] = self::objectToArray($value);
        }

        return $array;
    }
}
