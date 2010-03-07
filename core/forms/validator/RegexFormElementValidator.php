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
 * File:        RegexFormElementValidator.php
 * 
 * @author      Anis BEREJEB
 * @version     0.1
 */

/**
 * Represents a Regular Expression validator 
 * 
 */
class RegexFormElementValidator extends FormElementValidator
{
    /**
     * The regular expression to be applied
     * 
     * @var string
     */
    private $regexp;

    /**
     * Regular Expression Setter
     * 
     * @param  string  $regexp The regular expression 
     */
    public function setRegexp($regexp)
    {
        $this->regexp = $regexp;
    }

    /**
     * Regular Expression Getter
     * 
     * @return string the regular expression applied
     */
    public function getRegexp()
    {
        return $this->regexp;
    }    

    /**
     * Validates the element against the regexp
     * 
     * @return boolean true on success, false on failure
     */
    public function validate()
    {
        if (filter_var($this->element->getValue(), FILTER_VALIDATE_REGEXP, array("options"=>array("regexp" => $this->regexp))))
        {
            $this->setMessage('Invalid value');
            return false;
        }
        return true;
    }    
}
            
