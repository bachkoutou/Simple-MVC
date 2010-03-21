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
 * File:        controllerFactory.php
 * 
 * @author      Anis BEREJEB
 * @version     0.1
 */

/**
 * Controller Factory
 * 
 */
class controllerFactory
{
	/**
	 * Returns the appropriate controller based on the controller name
     *
	 * 
	 * @param  string $controllerName The controller name
	 * @return CoreController The controller, Defaults to Maincontroller.
	 */
	public static function getController($controllerName, array $configuration = array())
	{
		$dispatcher = FrontDispatcher::getInstance();
		if(class_exists($controllerName))
		{
			return new $controllerName($configuration);
		}
		return new MainController($configuration);
	}
}
