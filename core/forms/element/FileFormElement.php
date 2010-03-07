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
 * File:        FileFormElement.php
 * 
 * @author      Anis BEREJEB
 * @version     0.1
 */


/**
 * Represents a file input element
 * 
 */
class FileFormElement extends FormElement
{
    /**
     * echoes a file input element
     * 
     * @return string
     */
    public function render()
    {
        echo '<input name="' . $this->getName() . '" type="' . $this->getType() . '" value="' . $this->getValue().'" ' . $this->getAttributesString() . '/>';
    }    
}    
