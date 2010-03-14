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
 * File:        toolbox.php
 * 
 * @author      Anis BEREJEB
 * 

/**
 * Toolbox class
 * 
 */
class Toolbox
{
	/**
	 * gets a value of an index type, or returns a default value
	 * 
	 * @param  array   $array   The array in which to seek 
	 * @param  int     $index   The index
	 * @param  double  $default The default value
	 * @return mixed   The value of the index.
	 */
	public static function getArrayParameter($array, $index, $default)
	{
		return (is_array($array) && isset($index) && isset($array[$index])) ? $array[$index] : $default;
	}
	
    /**
	 * transforms a simplexml to an array
	 *
	 * @param   string  $xml    The xml source
	 * @param   array   $arr    The result array
	 * @return  array   $array  The resulting array
	 */
	public static function xml2phpArray($xml,$arr)
	{
		$iter = 0;
		foreach($xml->children() as $b){
			$a = $b->getName();
			if(!$b->children()){
				$arr[$a] = trim($b[0]);
			}
			else{
				$arr[$a][$iter] = array();
				$arr[$a][$iter] = xml2phpArray($b,$arr[$a][$iter]);
			}
			$iter++;
		}
		return $arr;
	}	

    /**
     * TODO: short description.
     * 
     * @param  mixed    
     * @return TODO
     */
    public static function cleanParameters(array $params = array())
    {
        $clean = array();
        foreach ($params as $key => $param)
        {
            $key = stripslashes($key);
            $param = (is_array($param)) ? self::cleanParameters($param) : stripslashes($param);
            $clean[$key] = $param;
        }
        return $clean;
    }    
}
