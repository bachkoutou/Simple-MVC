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
 * File:        generate.php
 * 
 * @author      Anis BEREJEB
 * @version     0.1
 */
/**
 * Generates an application using the tables.xml
 */
require_once('configuration.php');
require_once(dirname(__FILE__) . '/../core/api/database/database.php');
require_once(dirname(__FILE__) . '/../core/api/database/database_pdo.php');
require_once('classes/generator.class.php');
require_once('classes/toolbox.class.php');


if (!file_exists(MF_TABLES_FILE))
{
	die (MF_TABLES_FILE . ' does not exist ');
}

if (2 !== $_SERVER['argc']){
	die (PHP_EOL . "USAGE : admingenerator.php <moduleName>" . PHP_EOL);
}

$module = $_SERVER['argv'][1];


// initialize generator
$generator = new Generator($module);

$tables = simplexml_load_file(MF_TABLES_FILE);
if (count($tables->children()) > 0 )
{
	foreach($tables->children() as $table)
	{
		$generator->generate($table);
		echo PHP_EOL . 'Successfully generated code from Table ' . $table->name . '.' . PHP_EOL;
	}
}
