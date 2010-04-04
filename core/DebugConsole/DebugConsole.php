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
 * File:       DebugConsole.php
 * 
 * @author      Anis BEREJEB
 * @version     0.1
 */

/**
 * DebugConsole Class
 */
class DebugConsole
{
    /**
     * Exception object
     * 
     * @var Exception  Defaults to null. 
     */
    private $exception = null;

    /**
     * Constructor
     * 
     * @param  Exception  $e 
     */
    public function __construct(Exception $e)
    {
        $this->exception = $e;
    }

    /**
     * renders an error message
     * 
     * @return string the error message
     */
    public function render()
    {
        echo '<style>' . file_get_contents(dirname(__FILE__) . '/DebugConsole.css') . '</style>';
        echo '<div class="DebugConsole">';
        echo '<h3>SimpleMVC Console : We Encountered an error : </h3>';
        echo '<pre>';
        echo $this->exception->getMessage();
        echo '<h4>Trace</h4>';
        echo $this->exception->getTraceAsString();
        echo '</pre>';
        echo '</div>';
    }

}    
