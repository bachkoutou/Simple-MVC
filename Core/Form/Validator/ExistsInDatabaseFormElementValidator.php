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
 * File:        ExistsInDatabaseFormElementValidator.php
 * 
 * @author      Anis BEREJEB
 * @version     0.1
 */

/**
 * Represents a range Element Validator
 * 
 */
namespace Core\Form\Validator;

class ExistsInDatabaseFormElementValidator extends FormElementValidator
{
    /**
     * The model to check
     * 
     * @var string
     */
    public $model;

    /**
     * model Setter
     * 
     * @param  string $model The model
     */
    public function setModel($model)
    {
        $this->model = $model;
    }

    /**
     * model Getter
     * 
     * @return string the model field
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * The field name
     * 
     * @var string
     */
    public $field;

    /**
     * field Setter
     * 
     * @param  string $field The field
     */
    public function setField($field)
    {
        $this->field = $field;
    }

    /**
     * field Getter
     * 
     * @return string the field field
     */
    public function getField()
    {
        return $this->field;
    }


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
            $found = $this->model->$method(strtolower($value))->fetch();
            if ($found && $found->id)
            {
                $this->setMessage($this->element->getValue() . ' already exists.');
                return false;
            }
        }
        return true;
    }    
}

