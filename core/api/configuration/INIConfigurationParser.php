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
 * File:        INIConfigurationParser.php
 * 
 * @author      Anis BEREJEB
 * 
 */

/**
 * Defines an ini configuration parser
 */
class INIConfigurationParser extends ConfigurationParser
{
    /**
     * Parses the file 
     * 
     * @param  bool  $enableSections true if sections should be enabled, 
     *                      false otherwise, Optional, defaults to true. 
     */
    public function parse($enableSections = true)
    {
        $this->settings =  parse_ini_file($this->file, $enableSections);
    }
}
