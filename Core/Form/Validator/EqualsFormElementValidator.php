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
 * File:        EqualsFormElementValidator.php
 * 
 * @author      Anis BEREJEB
 * @version     0.1
 */

/**
 * Represents a range Element Validator
 * 
 */
namespace Core\Form\Validator;

class EqualsFormElementValidator extends FormElementValidator
{
    /**
     * The equals to field
     * 
     * @var string
     */
    public $equalsTo;

    /**
     * EqualsTo Setter
     * 
     * @param  string $equalsTo  The equals to field
     */
    public function setEqualsTo($equalsTo)
    {
        $this->equalsTo = $equalsTo;
    }

    /**
     * EqualsTo Getter
     * 
     * @return string the equals to field
     */
    public function getEqualsTo()
    {
        return $this->equalsTo;
    }

    /**
     * Validates the current field with the equals to field
     * 
     * @return boolean true on success, false on failure
     */
    public function validate()
    {
        if ($this->equalsTo !== $this->element->getValue())
        {
            $this->setMessage('Element values are not equal');
            return false;
        }
        return true;
    }    
}
            
