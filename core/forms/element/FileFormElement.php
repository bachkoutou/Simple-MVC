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
 * File:        FileFormElement.php
 * 
 * @author      Anis BEREJEB
 * @version     0.1
 */

/**
 * Represents a File form element
 * 
 */
class FileFormElement extends FormElement
{
    /**
     * Temporary directory for the upload
     * 
     * @var string  Defaults to null. 
     */
    private $destinationDir = null;
    

     /**
      * The upload temp directory setter 
      * 
      * @param string  $DestinationDir the temporary directory
      */
     public function setDestinationDir($destinationDir)
     {
         $this->destinationDir = $destinationDir;
     }    
     
     /**
      * Upload temp directory getter
      * 
      * @return string the temporary directory path
      */
     public function getDestinationDir()
     {
         return $this->destinationDir;
     }

    /**
     * Echoes a File form element
     * 
     * @return string
     */
    public function render()
    {
        $id = $this->getId();
        $id = (!empty($id)) ? $id: $this->getName();
        echo '<input name="' . $this->getName() . '" id="' . $id . '" type="file" ' . $this->getAttributesString() . '/>';
    }
    


    /**
     * Stores the file 
     * 
     * @param  array  $file The file to be processed
     * array structure should be equivalent to the $_FILES 
     * element structure
     * @Exception if the structure of the file is not correct
     */
    public function processFile()
    {
        $destination = rtrim($this->getDestinationDir(), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR .  md5(uniqid($this->value['name'])) . '.' . $this->getExtension();
        if (copy($this->value['tmp_name'], $destination))
        {
            $this->value = $destination;
            return true;
        }
        return false;
    }

    /**
     * Returns the file extension based on the last '.'
     * 
     * @return string the file extension.
     */
    public function getExtension()
    {
        return strtolower(substr($this->value['name'], strrpos($this->value['name'], '.') + 1));
    }

}    
