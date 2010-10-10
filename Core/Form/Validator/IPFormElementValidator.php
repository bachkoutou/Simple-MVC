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
 * File:        IPFormElementValidator.php
 * 
 * @author      Anis BEREJEB
 * @version     0.1
 */

/**
 * Validates an IP Address
 * 
 */
class IPFormElementValidator extends FormElementValidator
{
    /**
     * Hint message
     * 
     * @var string  Defaults to "Should be a valid IP address". 
     */
    public $hintMessage = "Should be a valid IP address";

    /**
     * an error message
     * Can be Redefined on subclasses
     * 
     * @var string  Defaults to 'Element should be a valid IP address'. 
     */
    public $message = 'Element should be a valid IP address';

    /**
     * Validates an IP address
     * 
     * @return boolean true on success, false on failure
     */
    public function validate()
    {
        return (filter_var($this->element->getValue(), FILTER_VALIDATE_IP)) ? true : false;
    }    
}
            
