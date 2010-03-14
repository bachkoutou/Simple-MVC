<?php
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
    echo $e->getMessage() . '<br/>';
}

