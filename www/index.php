<?php
require_once('../Conf/configuration.php');
require_once('../Core/Autoload/AutoloadManager.php');

autoloadManager::addFolder(CORE);
autoloadManager::addFolder(BUSINESS);
autoloadManager::setSavePath(AUTOLOAD_SAVE_PATH);
spl_autoload_register('autoloadManager::loadClass');

use \Core\MVC\FrontDispatcher as FrontDispatcher;
use \Core\Container\ContainerFactory as ContainerFactory;

$front = FrontDispatcher::getInstance();
try
{
    $container = ContainerFactory::get('front');
    $front->route($container);
}
catch (Exception $e)
{
    echo $e->getMessage() . '<br/>';
}

