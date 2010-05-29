<?php
function exception_error_handler($errno, $errstr, $errfile, $errline ) {
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
}
set_error_handler("exception_error_handler");

require_once('../conf/configuration_{moduleName}.php');
require_once('../core/autoloadManager.php');

autoloadManager::addFolder(CORE);
autoloadManager::addFolder(BUSINESS);
spl_autoload_register('autoloadManager::loadClass');

$front = frontDispatcher::getInstance();
try
{
    $front->route(ContainerFactory::get('{moduleName}'));
}
catch (Exception $e)
{
    echo $console->render();
}

