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
 * File:        FormElementValidator.php
 * 
 * @author      Anis BEREJEB
 * @version     0.1
 */

/**
 * Validator class for the form elements
 * 
 */
abstract class FormElementValidator
{
    /**
     * The FormElement element
     * 
     * @var FormElement
     */
    protected $element;

    /**
     * Message set on validation
     * 
     * @var string
     */
    protected $message;

    /**
     * constructor
     * Sets the FormElement
     * 
     * @param  FormElement  $element The element to validate
     */
    public function __construct(FormElement $element)
    {
        $this->element = $element;
    }
    
    /**
     * Message Setter
     * 
     * @param  string  $message The message to set
     */
    protected function setMessage($message)
    {
        $this->message = $message;
    }    

    /**
     * Message Getter
     * 
     * @return string The message
     */
    public function getMessage()
    {
        return $this->message;
    }
    
    /**
     * Method to set the options of a form
     * Takes an array with key as the property name 
     * and value as the value. 
     * Checks if there is a set{Key} defined and calls the method
     * 
     */
    public function setOptions(array $options = array())
    {
        if (count($options))
        {
            foreach ($options as $key => $option)
            {
                if (property_exists($this, $key))
                {
                    $method = 'set' . ucfirst($key);
                    $this->$method($option);
                }
            }
        }
    }
    
    /**
     * returns a Hint Message
     * 
     * @return string The hint message
     */
    public function getHintMessage()
    {
        return (property_exists($this, 'hintMessage')) ? $this->hintMessage : '';
    }
    
    /**
     * Abstract method to validate the FormElement.
     * Must be defined for each class implementing the FormElementValidator class
     */
    abstract public function validate();
}    
