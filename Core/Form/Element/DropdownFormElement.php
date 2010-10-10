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
 * File:        DropdownFormElement.php
 * 
 * @author      Anis BEREJEB
 * @version     0.1
 */

/**
 * Represents a Dropdown button element 
 * 
 */
namespace Core\Form\Element;
class DropdownFormElement extends ListFormElement
{

    /**
     * TODO: description.
     * 
     * @var double  Defaults to ''. 
     */
    private $dropdownType = '';
    
    /**
     * Makes the drop down multiple
     *
     */
    public function setMultiple()
    {

        $this->dropdownType = 'multiple';
    }
    
    /**
     * Makes the drop down simple
     * 
     */
    public function setSimple()
    {
        $this->dropdownType = '';
    }    
    /**
     * Echoes the Dropdown button element
     * 
     * @return string
     */
    public function render()
    {
    
        $id = $this->getId();
        $name = $this->getName();
        if (null === $id || empty($id))
        {
            $id = $name;
        }
        echo '<select name="' . $name .'" id="' . $id . '"' . $this->getAttributesString() . ' ' . $this->dropdownType . '>';
        foreach ($this->options as $value => $text)
        {
            if (is_array($text))
            {
                echo '<optgroup label="' . $value . '">';
                foreach ($text as $value2 => $text2)
                {
                    $this->renderOption($value2, $text2);
                }
                echo '</optgroup>';    
            }
            else
            {
                echo $this->renderOption($value, $text);
            }    
        }
        echo '</select>';
    }    

    /**
     * Renders an option of the select
     * 
     * @param  mixed  $value 
     * @param  mixed  $text  
     */
    public function renderOption($value, $text) 
    {
            echo '<option value="' . $value . '"';
            if (
                ($this->dropdownType == '' && ($value == $this->selected || $value == $this->value)) ||
                ($this->dropdownType == 'multiple' && is_array($this->selected) && in_array($value, $this->selected))
               ) 
            {
                echo ' selected="selected"';
            }
             if (
                ($this->dropdownType == 'multiple' && is_array($this->disabled) && in_array($value, $this->disabled))
               ) 
            {
                echo ' disabled';
            }
            
            echo '>' . $text . '</option>';
    }

}    
