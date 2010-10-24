<?php 
class AccessHelper
{
    public static $Accesss;

    public static function getActions()
    {
        if (!isset(self::$Accesss))
        {    
            $parser = new INIConfigurationParser();
            $parser->loadFile(BUSINESS . 'conf/access.ini');
            $parser->parse();
            self::$Accesss = $parser->getConfigs();
        }
        return self::$Accesss;
    }
}    
