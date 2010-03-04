<?php
define('DS', DIRECTORY_SEPARATOR);
define('MF_ADMIN_FOLDER', dirname(__FILE__) . DS);
define('BACK_GENERATION_PATH', MF_ADMIN_FOLDER . 'objects');
define('BACK_DB_HOST', 'localhost');
define('BACK_DB_USER', '****');
define('BACK_DB_PASSWORD', '****');
define('BACK_DB_DATABASE', '****');


/**
 * xml file for the tables you want to generate
 *
 */
define('MF_TABLES_FILE',  MF_ADMIN_FOLDER . 'tables.xml');
/**
 * the table descriptorssss
 */
define('MF_DESCRIPTORS_FOLDER', 'descriptors' . DS);

/**
 * SOURCE TEMPLATES : this is where the controller, model, view and template files are 
 * stored
 */
define('MF_TEMPLATES_FOLDER', MF_ADMIN_FOLDER . 'templates' . DS);
define('MF_DEFAULT_TEMPLATE_FOLDER', 'default' . DS);

/**
 * GENERATION PARAMETERS
 */
// DEFAULT GENERATION FOLDER (default generation under business folder)
define('MF_DEFAULT_FOLDER', MF_ADMIN_FOLDER . '..' . DS . 'business' . DS);

// MODELS, CONTROLLERS, VIEWS AND TEMPLATE FOLDER
define('MF_MODELS_FOLDER', 'models' . DS );
define('MF_CONTROLLERS_FOLDER', 'controllers' . DS);
define('MF_LIBRARIES_FOLDER', 'libraries' . DS);
define('MF_VIEWS_FOLDER', 'views' . DS);
define('MF_FORMS_FOLDER', 'forms' . DS);
define('MF_MVC_TPL_FOLDER', 'templates' . DS);

/**
 * FORM GENERATION
 */

//type of the input field, default text
define('MF_FORM_DEFAULT_INPUT_TYPE','text');

// alpha,alphanum,nodigit,digit,number,email,phone,url 
// (if put empty, the system will try to hint the type of the field depending of the table field)
define('MF_FORM_DEFAULT_INPUT_TYPE_TEST','');

//confirm[field],differs[field],length[min,max]
define('MF_FORM_DEFAULT_INPUT_TYPE_CONFIRM','length[0,20]');

//if the field is required or not, put required or leave empty
define('MF_FORM_DEFAULT_INPUT_REQUIRED','required');

