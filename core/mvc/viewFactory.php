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
 * File:        viewFactory.php
 * 
 * @author      Anis BEREJEB
 * @version     0.1
 */

/**
 * View Factory class
 */
class viewFactory
{
    /**
     * Returns a new View based on the view name and the action name
     * the view should be located under BUSINESS/VIEWS_PATH/
     * 
     * @param  string  $viewName   The view name
     * @param array $configuration The Configuration array Defaults to array
     * @return CoreView the view on sucess or MainView
     */
    public static function getview($viewName, array $configuration = array())
    {
    	$viewFile = BUSINESS . DS . VIEWS_PATH . $viewName . '.php';
		$viewNameView = ucfirst($viewName) . 'View';
        if (file_exists($viewFile))
        {           
            require_once($viewFile);
            if(class_exists($viewNameView))
            {
                return new $viewNameView($viewName, $configuration);
            }
        }
        return new MainView($viewName, $configuration);
    }
}
