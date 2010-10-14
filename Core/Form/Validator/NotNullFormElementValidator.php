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
 * File:        NotNullFormElementValidator.php
 * 
 * @author      Anis BEREJEB
 * @version     0.1
 */

/**
 * Validates a non empty Element
 * 
 */
namespace Core\Form\Validator;

class NotNullFormElementValidator extends FormElementValidator
{
    /**
     * Hint Message
     * 
     * @var mixed  Defaults to "*". 
     */
    public $hintMessage = "*";

    /**
     * an error message
     * Can be Redefined on subclasses
     * 
     * @var string  Defaults to 'Element should not be empty'. 
     */
    public $message = 'Element should not be null';
    
    /**
     * Handles the null Value
     * 
     * @var mixed  Defaults to null. 
     */
    public $nullValue = null;
    
    /**
     * Null Value Setter
     * 
     * @param  mixed  $nullValue The null value
     */
    public function setNullValue($nullValue)
    {
        $this->nullValue = $nullValue;
    }

    /**
     * Null value Getter
     * 
     * @return mixed The null value 
     */
    public function getNullValue()
    {
        return $this->getNullValue();
    }    

    /**
     * Validates a not empty value
     * 0 will pass.
     *
     * @return boolean true on success, false on failure.
     */
    public function validate()
    {
        return ($this->nullValue != $this->element->getValue()) ? true : false;
    }    
}
            
