<?php
// Désactivation du cache WSDL
require_once('../conf/configuration_{moduleName}.php');
require_once('../core/autoloadManager.php');
// Require components
autoloadManager::addFolder(CORE);

autoloadManager::addFolder(BUSINESS);
spl_autoload_register('autoloadManager::loadClass');

// Enable Layout ?
$_REQUEST['controller'] = Toolbox::getArrayParameter($_REQUEST, 'controller', null);
$_REQUEST['action'] = Toolbox::getArrayParameter($_REQUEST, 'action', null);

$front = frontDispatcher::getInstance();
try
{
    $front->route();
}
catch (Exception $e)
{
    echo $e->getMessage() . '<br/>';
}

