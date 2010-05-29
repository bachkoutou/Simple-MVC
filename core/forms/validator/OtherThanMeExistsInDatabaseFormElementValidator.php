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
 * File:        OtherThanMeExistsInDatabaseFormElementValidator.php
 * 
 * @author      Anis BEREJEB
 * @version     0.1
 */

/**
 * Represents a range Element Validator
 * 
 */
class OtherThanMeExistsInDatabaseFormElementValidator extends ExistsInDatabaseFormElementValidator
{
    /**
     * Validates the current field with the model
     * 
     * @return boolean true on success, false on failure
     */
    public function validate()
    {
        $value = $this->element->getValue();
        if (!empty($this->field) && !empty($value))
        {    
            $method = 'findBy' . ucfirst($this->field);
            $found = $this->model->$method($value)->fetch();
            if ($found && $found->id)
            {
                if ($found->id == $this->model->id)
                {
                    return true;
                }    
                $this->setMessage($this->element->getValue() . ' already exists.');
                return false;
            }
        }
        return true;
    }    
}

