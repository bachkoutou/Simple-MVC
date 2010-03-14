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
 * File:        ContainerFactory.php
 * 
 * @author      Anis BEREJEB
 * 
 */

/**
 * Container Factory
 * 
 */
final class ContainerFactory
{
    /**
     * Instanciates a container object
     * 
     * @param  string  $module The module 
     * @return Container object
     */
    public function get($module = null)
    {
        $className = ucfirst($module) . 'Container';
        return (class_exists($className)) ? new $className() : new Container();
    }

    /**
     * Final private constructor
     */
    private function __construct(){}

    /**
     * private clone
     */
    private function __clone(){}
}    
