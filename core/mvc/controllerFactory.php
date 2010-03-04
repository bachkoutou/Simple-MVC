<?php 
class controllerFactory
{
	public static function getController($controllerName, $actionName)
	{
		$dispatcher = FrontDispatcher::getInstance();
		if(class_exists($controllerName))
		{
			return new $controllerName($controllerName, $actionName);
		}
		return new MainController('Main', $actionName);
	}
}
