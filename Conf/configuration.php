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
 * File:        configuration.php
 * 
 * @author      Anis BEREJEB
 * @version     0.1
 */
/**
 * Configuration file 
 */

/******* PATH SETTINGS **********/
 define('DS', DIRECTORY_SEPARATOR);
 define('ABSP', dirname(__FILE__)  . DS . '..' . DS);
 define('CORE', ABSP . 'Core' . DS);
 define('MODULE', 'Front' . DS);
 define('BUSINESS', ABSP . 'Business' . DS . MODULE);
 define('CONTROLLERS_PATH', 'Controllers' . DS);
 define('LIBRARIES_PATH', 'Libraries' . DS);
 define('MODELS_PATH', 'Models' . DS);
 define('VIEWS_PATH', 'Views' . DS);
 define('TEMPLATES_PATH', 'Templates' . DS);
 define('DESCRIPTORS_PATH', 'Descriptors' . DS);
 define('AUTOLOAD_SAVE_PATH', '/tmp');

