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
 * File:        FloatFormElementValidator.php
 * 
 * @author      Anis BEREJEB
 * @version     0.1
 */

/**
 * Represents a float value validator
 * 
 */
class FloatFormElementValidator extends FormElementValidator
{
    /**
     * Hint message
     * 
     * @var string  Defaults to "Float number, i.e. 1.0, 2 etc.". 
     */
    public $hintMessage = "Float number, i.e. 1.0, 2 etc.";
    
    /**
     * Validates a float value
     * 
     * @return boolean true on success, false on failure
     */
    public function validate()
    {
        if(!filter_var($this->element->getValue(), FILTER_VALIDATE_FLOAT))
        {
            $this->setMessage('Element should be a valid Float number');
            return false;
        }
        return true;
    }    
}
            
