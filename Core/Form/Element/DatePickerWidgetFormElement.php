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
 * File:        DatePickerWidgetFormElement.php
 * 
 * @author      Anis BEREJEB
 * @version     0.1
 */

/**
 * Represents a DatePickerWidgetFormElement form element
 * 
 */
namespace Core\Form\Element;
class DatePickerWidgetFormElement extends FormElement
{
    /**
     * range array
     * 
     * @var range  Defaults to array(). 
     */
    private $range = array();

    /**
     * Range Setter
     * 
     * @param  array  $range 
     */
    public function setRange(array $range)
    {
        if (2 !== count($range))
        {    
           throw new InvalidArgumentException('incorrect range count');
        }
        else
        {    
            $this->range = $range;
        }    
    }
    
    /**
     * Range getter
     * 
     * @return array
     */
    public function getRange()
    {
        return $this->range;
    }

    public function getJSScripts()
    {
        return array(
        '/scripts/ui.datepicker.js',
        '/scripts/demopicker.js',
        );
    }

    public function getCSSStyles()
    {
       return array(
        '/styles/screen.css',
       ); 
    }

    /**
     * render function
     * 
     * @return string 
     */
    public function render()
    {
        echo '
                <div class="rangePicker futureRange"> 
                    <label for="range1">From:</label> 
                    <input type="text" name="range1" id="range1" value="' . $this->range[0]. '" /> 
                    <label for="range1">To:</label> 
                    <input type="text" name="range2" name="range2" value="' . $this->range[1]. '" /> 
                </div>



        ';        
    }
}
