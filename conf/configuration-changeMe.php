<?php
/******* DB SETTINGS **********/
define('FRONT_DB_HOST', 'localhost');
define('FRONT_DB_USER', '*****');
define('FRONT_DB_PASSWORD', '*****');
define('FRONT_DB_DATABASE', '****');



/******* PATH SETTINGS **********/
 define('DS', DIRECTORY_SEPARATOR);
 define('FRONT_ABSP', dirname(__FILE__)  . DS . '..' . DS);
 define('FRONT_CORE', FRONT_ABSP . 'core' . DS);
 define('FRONT_BUSINESS', FRONT_ABSP . 'business' . DS);
 define('FRONT_CONTROLLERS_PATH', 'controllers' . DS);
 define('FRONT_LIBRARIES_PATH', 'libraries' . DS);
 define('FRONT_MODELS_PATH', 'models' . DS);
 define('FRONT_VIEWS_PATH', 'views' . DS);
 define('FRONT_TEMPLATES_PATH', 'templates' . DS);
 define('FRONT_DESCRIPTORS_PATH', 'descriptors' . DS);
 
 define('FRONT_WEB',FRONT_ABSP . 'document_root' . DS);
 define('AUTO_RENDER_TEMPLATE', true);
define('FRONT_URL','http://www.yourserver.com/');
 define('AUTOLOAD_SAVE_PATH', '/tmp/')


