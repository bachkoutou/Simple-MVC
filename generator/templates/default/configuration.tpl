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
 * File:        configuration_{moduleName}.php
 * 
 * @author      Anis BEREJEB
 * @version     0.1
 */
/**
 * Configuration file 
 */

/******* DB SETTINGS **********/
define('DB_HOST', '{mysqlHost}');
define('DB_USER', '{mysqlUser}');
define('DB_PASSWORD', '{mysqlPassword}');
define('DB_DATABASE', '{mysqlDatabase}');
define('DB_TYPE', 'mysql');



/******* PATH SETTINGS **********/
 define('DS', DIRECTORY_SEPARATOR);
 define('ABSP', dirname(__FILE__)  . DS . '..' . DS);
 define('CORE', ABSP . 'core' . DS);
 define('MODULE', '{moduleName}' . DS);
 define('BUSINESS', ABSP . 'business' . DS . MODULE);
 define('CONTROLLERS_PATH', 'controllers' . DS);
 define('LIBRARIES_PATH', 'libraries' . DS);
 define('MODELS_PATH', 'models' . DS);
 define('VIEWS_PATH', 'views' . DS);
 define('TEMPLATES_PATH', 'templates' . DS);
 define('DESCRIPTORS_PATH', 'descriptors' . DS);
 
 define('WEB',ABSP . 'document_{moduleName}' . DS);
 define('AUTO_RENDER_TEMPLATE', true);
 define('URL','http://simplemvc.berejeb.com/');
 define('AUTOLOAD_SAVE_PATH', '/tmp');
 /**** CONTROLLER *****/
 define('CONTROLLER_LIST_DEFAULT_NUMBER', 10);
 /*** CACHE **********/
 define ('DEFAULT_CACHE_TYPE', 'Array');
 define ('DEFAULT_CACHE_PREFIX', '{moduleName}');

