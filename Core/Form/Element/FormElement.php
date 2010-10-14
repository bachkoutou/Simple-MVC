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
 * File:        FormElement.php
 * 
 * @author      Anis BEREJEB
 * @version     0.1
 */

/**
 * Abstract class representing a form element
 * 
 */
namespace Core\Form\Element;
abstract class FormElement
{
    /**
     * form name
     * 
     * @var string  Defaults to null. 
     */
    public $formName = null;

    /**
     * Form name Setter
     * 
     * @param  string  $formName The form name
     */
    public function setFormName($formName)
    {
        $this->formName = $formName;
    }

    /**
     * Form name Getter
     * 
     * @return string the form name
     */
    public function getFormName()
    {
        return $this->formName;
    }

    /**
     * form id
     * 
     * @var string  Defaults to null. 
     */
    public $formId = null;

    /**
     * Form id Setter
     * 
     * @param  string  $formId The form id
     */
    public function setFormId($formId)
    {
        $this->formId = $formId;
    }

    /**
     * Form id Getter
     * 
     * @return string the form id
     */
    public function getFormId()
    {
        return $this->formId;
    }

    /**
     * Name of the element
     * 
     * @var string
     */
    private $name;
    
    /**
     * Id of the element
     * 
     * @var string
     */
    private $id;

    /**
     * Value of the element
     * 
     * @var string
     */
    protected $value;

    /**
     * Label of the element
     * 
     * @var string
     */
    private $label;

    /**
     * Attributes of the element
     * 
     * @var array
     */
    private $attributes;

    /**
     * type of the element
     * 
     * @var string
     */
    private $type;
    
    /**
     * array of validators applied
     * 
     * @var array
     */
    private $validators;

    /**
     * array of errors
     * 
     * @var array
     */
    private $errors;
    
    /**
     * static input types allowed
     * 
     * @var array  Defaults to array('text', 'textarea', 'checkbox', 'radio', 'file'). 
     */
    public static $allowedInputTypes = array('text', 'textarea', 'checkbox', 'radio', 'file');
    
    /**
     * TODO: short description.
     * 
     */
    public function __construct()
    {
        $this->type = strtolower(substr(get_class($this), 0, -11));
    }    
    /**
     * Attributes Setter
     * 
     * @param  array  $attributes 
     */
    public function setAttributes(array $attributes)
    {
        $this->attributes = $attributes;
    }    
    
    /**
     * Attributes getter
     * 
     * @return array the attributes array
     */
    public function getAttributes()
    {
        return $this->attributes;
    } 
    
    /**
     * Returns a string representation of the attributes
     * 
     * @param $attributes the attributes to render as string, if null it will
     * take the attributes property
     * @return string 
     */
    public function getAttributesString($attributes = null)
    {
        $attributes = (null !== $attributes) ? $attributes : $this->attributes;
        $string = '';
        if (is_array($attributes) && count($attributes))
        {
            foreach ($attributes as $key => $value)
            {
                $string.= ' ' . $key . '=' . '"' . $value . '"';
            }    
        }    
        return $string;
    }
    /**
     * Name Getter
     * 
     * @return string The name of the element
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Name Setter
     * 
     * @param  string  $name 
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Id Setter
     * 
     * @param  string  $id 
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Id Getter
     * 
     * @return string the id
     */
    public function getId()
    {
        return (isset($this->id) && (null !== $this->id) && !empty($this->id)) ? $this->id : $this->name;
    }    
    /**
     * Label Getter
     * 
     * @return string The label of the element
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Label Setter
     * 
     * @param  string  $label 
     */
    public function setLabel($label)
    {
        $this->label = $label;
    }    
    
    /**
     * Value Setter
     * 
     * @param  string  $value 
     */
    public function setValue($value)
    {
        $this->value = $value;
    }
    
    /**
     * Value Getter
     * 
     * @return string The value
     */
    public function getValue()
    {
        return $this->value;
    }    
    
    /**
     * Type Getter
     * 
     * @return string The type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Type Setter.
     * 
     * @param  string  $type 
     */
    public function setType($type)
    {
        $this->type = $type;
    }    
    
    /**
     * Adds a validator to the element
     * 
     * @param  FormValidator  $validator the validator to be added  
     */
    public function addValidator(\Core\Form\Validator\FormElementValidator $validator)
    {
        $this->validators[] = $validator;
    }    

    /**
     * Returns the list of validators
     * 
     * @return array The validators
     */
    public function getValidators()
    {
        return $this->validators;
    }    
    
    /**
     * Validate a form element with all the validators associated
     * Sets in FormElement::errors the errors if found
     * 
     * @return boolean true if no error found, false otherwise.
     */
    public function validate()
    {
        foreach ($this->validators as $validator)
        {
            if (!$validator->validate($this))
            {
                $this->errors[] =  $validator->getError();
            }    
        }    
        return (!count($this->errors)) ? true : false;
    }
    
    /**
     * Errors Getter
     * 
     * @return array The errors
     */
    public function getErrors()
    {
        return $this->errors;
    }    
    /**
     * Renders a text Help with the validations.
     * 
     * @return string the Help text
     */
    public function renderValidatorsHint()
    {
        $validators = $this->getValidators();
        if (count($validators))
        {
            $string = '';
            foreach ($validators as $validator)
            {
                $string.= ", {$validator->getHintMessage()}";
            }
            echo trim(trim($string, ', '));
        }
    }    

    /**
     * Abstract function 
     * Must be implemented on children to specify how to render the element.
     */
    abstract public function render();
}    
