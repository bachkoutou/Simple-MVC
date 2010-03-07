<?php
/**
 * Note : Code is released under the GNU LGPL
 *
 * Please do not change the header of this file 
 *
 * This library is free software; you can redistribute it and/or modify it under the terms of the GNU 
 * Lesser General Public License as published by the Free Software Foundation; either version 2 of 
 * the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; 
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. 
 *
 * See the GNU Lesser General Public License for more details.
 */

/**
 * File:        toolbox.class.php
 * 
 * @author      Anis BEREJEB
 * @version     0.1
 */

/**
 * Toolbox class
 */
class Toolbox
{
    /**
     * Checks an absolute path
     * 
     * @param  string  $path The path to check
     * @return boolean true on success, false on failure
     */
    public static function checkAbsolutePath($path)
    {
        $pattern = '@^([a-zA-Z]:|(\\\){2,2}|\/)@';
        return preg_match($pattern, $path);
    }
    
    /**
     * Save a string into a file (will create file and directory if needed)
     *
     * @param string $pathName      Full path name
     * @param string $fileContent   File content
     * @param bool   append         True to append content to file (default false)
     * @param bool   utf8           True to convert and write file in UTF-8 (default is false)
     *
     * @return true on success
     *
     **/
    public static function saveFileWithContent($pathName, $fileContent, $append = false, $utf8 = true)
    {
        $pathName = str_replace("\\", "/", $pathName);
        $pos = strrpos($pathName, "/");
        $mask = umask(0);
        if ($pos)
        {
            $directory = substr($pathName, 0, $pos);
            if (!is_dir($directory))
            {
                if (!self::createDir($directory))
                {
                    umask($mask);
                    trigger_error("can't create directory $directory (saveFileWithContent $pathName)", E_USER_ERROR);
                    return false;
                }
            }
        }

        $flags = 0;
        if ($append)
        {
            $flags |= FILE_APPEND;
        }

        if(file_exists($pathName) && !is_writable($pathName))
        {
            chmod($pathName, 0777);
        }

        if (file_put_contents($pathName, $fileContent, $flags) === FALSE)
        {
            umask($mask);
            return false;
        }

        @chmod($pathName, 0777);
        umask($mask);

        return true;
    }

     /**
      * Create a directory with a specific mask
      *
      * @param string $path Path of directory to create
      * @param int $mask Optional mask (default is 0777)
      *
      * @return boolean TRUE on success, FALSE on failure
      */
        public static function createDir($path, $mask = 0777)
        {
            if (is_dir($path))
                return true;

            return mkdir($path, $mask, true);
        }

        public static function getFileContent($filename)
        {
            return file_get_contents($filename);
        }
}
