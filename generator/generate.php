<?php
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
