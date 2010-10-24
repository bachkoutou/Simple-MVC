<?php 
class LanguageHelper
{
    public static $Languages;

    /**
     * Returns the language list
     * 
     * @param  string  $index Optional, defaults to 'name'
     * could be name or short. 
     * @return array of languages
     */
    public static function getLanguages($index = 'name')
    {
        if (!in_array($index, array('name', 'short', 'all')))
        {
            throw new InvalidArgumentException($index  . ' is not a valid language offset');
        }    
        if (!isset(self::$Languages))
        {    
            $parser = new INIConfigurationParser();
            $parser->loadFile(BUSINESS . 'conf/langs.ini');
            $parser->parse();
            $languages = $parser->getConfigs();
            if ('all' == $index)
            {
                self::$Languages = $languages;
            }
            else
            {    
                foreach ($languages as $key => $language)
                {
                    self::$Languages[$key] = $language[$index];
                }
            }
        }
        return self::$Languages;
    }

}    
