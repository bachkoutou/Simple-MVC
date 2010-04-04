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
 * File:        FormElementFactory.php
 * 
 * @author      Anis BEREJEB
 * @version     0.1
 */

/**
 * Factory for the FormElement objects
 * 
 */
class FormElementFactory
{

    /**
     * private constructor
     * 
     */
    private function __construct()
    {
    }

    /**
     * returns the element if exists, based on the $type argument
     * 
     * @param  string  $type 
     * @return FormElement The element to return, Defaults to TextFormElement.
     */
    public static function getElement($type)
    {
        $classname = ucfirst($type) . 'FormElement';
        return  (class_exists($classname)) ? new $classname() : new TextFormElement();
    }
}    
            
