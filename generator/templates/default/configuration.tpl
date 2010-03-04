<?php
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
