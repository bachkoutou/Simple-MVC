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
 * File:        ListFormElement.php
 * 
 * @author      Anis BEREJEB
 * @version     0.1
 */

/**
 * Represents a List button element 
 * 
 */
abstract class ListFormElement extends FormElement 
{
    /**
     * List element
     * 
     * @var array  Defaults to array(). 
     */
    protected $options = array();
   
    /**
     * The selected option
     * 
     * @var mixed  Defaults to null. 
     */
    protected $selected = null;

    /**
     * options Setter 
     * @param  array  $options 
     */
    public function setOptions(array $options)
    {
        $this->options = $options;
    }

    /**
     * options Getter
     * 
     * @return array the options array
     */
    public function getOptions()
    {
        return $this->options;
    }    
    
    /**
     * adds an option
     * 
     * @param  mixed  $value the option value 
     * @param  mixed  $text the option text
     */
    public function addOption($value, $text)
    {
        $this->options[$value] = $text;
    }    
    
    /**
     * Removes an option 
     * 
     * @param  mixed  $value The value to be removed 
     */
    public function removeOption($value)
    {
        unset($this->options[$value]);
    }

    /**
     * selected option Setter
     * 
     * @param  mixed  $value The value to be selected
     */
    public function setSelected($value)
    {
        $this->selected = $value;
    }    
    
    /**
     * selected option Getter
     * 
     * @return mixed the selected option
     */
    public function getSelected()
    {
        return $this->selected;
    }
}    
