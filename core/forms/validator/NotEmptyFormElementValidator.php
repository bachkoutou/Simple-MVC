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
 * File:        NotEmptyFormElementValidator.php
 * 
 * @author      Anis BEREJEB
 * @version     0.1
 */

/**
 * Validates a non empty Element
 * 
 */
class NotEmptyFormElementValidator extends FormElementValidator
{
    /**
     * Hint Message
     * 
     * @var mixed  Defaults to "*". 
     */
    public $hintMessage = "*";
    /**
     * Validates a not empty value
     * 0 will pass.
     *
     * @return boolean true on success, false on failure.
     */
    public function validate()
    {
        if('' == $this->element->getValue())
        {
            $this->setMessage('Element should not be empty');
            return false;
        }
        return true;
    }    
}
            
