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
        echo '<select name="' . $this->getName() . '" id="' . $this->getName() . '" ' . $this->getAttributesString() . ' ' . $this->dropdownType . ' >';
        foreach ($this->options as $value => $text)
        {
            echo '<option value="' . $value . '"';
            if (
                ($this->dropdownType == '' && ($value == $this->selected || $value == $this->value)) ||
                ($this->dropdownType == 'multiple' && is_array($this->selected) && in_array($value, $this->selected))
               ) 
            {
                echo ' selected="selected"';
            }
            echo '>' . $text . '</option>';
        }
        echo '</select>';
    }    
}    
