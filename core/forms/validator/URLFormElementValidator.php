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
 * File:       URLFormElementValidator.php
 * 
 * @author      Anis BEREJEB
 * @version     0.1
 */

/**
 * Validates an URL
 * 
 */
class URLFormElementValidator extends FormElementValidator
{
    /**
     * Hint Message
     * 
     * @var mixed  Defaults to "Should be a valid URL address". 
     */
    public $hintMessage = "Should be a valid URL address";
    
    /**
     * Validates an url
     * 
     * @return TODO
     */
    public function validate()
    {
        if(!filter_var($this->element->getValue(), FILTER_VALIDATE_URL))
        {
            $this->setMessage('Element should be a valid URL address');
            return false;
        }
        return true;
    }    
}
            
