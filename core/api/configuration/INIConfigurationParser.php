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
        $this->settings =  $this->buildArrayWithMultiNodes(parse_ini_file($this->file, $enableSections));
    }

    /**
     * Builds an array with multi nodes
     * Accepts values like 
     *
     * [section]
     * var.variable = value
     * var.subvar.subvar2 = value2
     * var2.subvar2.subsubvar2 = value3
     *
     * @param  array  $config The config array (from parse_ini_file())
     * @return array The multi level array
     */
    private function buildArrayWithMultiNodes(array $config)
    {
        $returnConfig = array();

        foreach($config as $key => $value)
        {
            $elements = explode('.', $key);
            $tempArray = &$returnConfig;

            $counter = count($elements);
            for ($i = 0 ; $i < $counter - 1 ; $i++)
            {
                if (!isset($tempArray[$elements[$i]]))
                {
                    $tempArray[$elements[$i]] = array();
                }

                $tempArray = &$tempArray[$elements[$i]];
            }

            $tempArray[$elements[$counter - 1]] = is_array($value) ?
                $this->buildArrayWithMultiNodes($value) : $value;
        }

        return $returnConfig;
    }
}
