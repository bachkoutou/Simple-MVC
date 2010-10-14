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
 * File:        FileFormElementValidator.php
 * 
 * @author      Anis BEREJEB
 * @version     0.1
 */

/**
 * Represents a Regular Expression validator 
 * 
 */
namespace Core\Form\Validator;

class FileFormElementValidator extends FormElementValidator
{
    /**
     * a hint Message
     * Could be redefined on child classes
     * @var string  Defaults to ''. 
     */
    public $hintMessage = '';
    
    /**
     * an error Message
     * Could be redefined on child classes 
     *
     * @var string  Defaults to 'Invalid Value'. 
     */
    public $message = 'Invalid Value';

    /**
     * Max File size
     * 
     * @var int  Defaults to null. 
     */
    private $maxSize = null;

    /**
     * array of allowed extensions
     * 
     * @var array  Defaults to array(). 
     */
    private $allowedExtensions = array();

     /**
      * Max Size setter
      * 
      * @param  int  $maxSize The maximum file size permitted
      */
     public function setMaxSize($maxSize)
     {
         $this->maxSize = $maxSize;
     }

     /**
      * Max Size Getter
      * 
      * @return int the max file size
      */
     public function getMaxSize()
     {
         return $this->maxSize;
     }

     /**
      * The allowed extensions setter 
      * 
      * @param  array  $allowedExtensions the allowed extensions
      */
     public function setAllowedExtensions(array $allowedExtensions)
     {
         $this->allowedExtensions = $allowedExtensions;
     }

     /**
      * Allowed Extensions Getter
      * 
      * @return array the list of allowed extensions
      */
     public function getAllowedExtensions()
     {
         return $this->allowedExtensions;
     }


    /**
     * Validates the element against the File
     * 
     * @return boolean true on success, false on failure
     */
    public function validate()
    {
        $elementValue = $this->element->getValue();
        // validate extension
        if (!in_array($this->element->getExtension(), $this->getAllowedExtensions()))
        {
            $this->message = 'Invalid Extension for file ' . $elementValue['name'];
            return false;
        }

        // validate filesize
        if ($this->getMaxSize() < filesize($elementValue['tmp_name']))
        {
            $this->message = 'Incorrect size for file ' . $elementValue['name'];
            return false;
        }

        // When validating the files, we need also to store the file uploaded to the 
        // destination directory and see if this operation finishes 
        // with success as well. otherwise we will also trigger an error.
        if (!$this->element->processFile())
        { 
            $this->message = 'Unable to store the file';
            return false;
        }
        return true;
    }   
}

