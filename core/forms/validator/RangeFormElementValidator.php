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
 * File:        RangeFormElementValidator.php
 * 
 * @author      Anis BEREJEB
 * @version     0.1
 */

/**
 * Represents a range Element Validator
 * 
 */
class RangeFormElementValidator extends FormElementValidator
{
    /**
     * Minimum value
     * 
     * @var int
     */
    public $min;

    /**
     * Maximum value
     * 
     * @var int
     */
    private $max;

    /**
     * Minimum Setter
     * 
     * @param  int  $min The minimum 
     */
    public function setMin($min)
    {
        $this->min = $min;
    }

    /**
     * Minimum Getter
     *
     * @return int The minimum value
     */
    public function getMin()
    {
        return $this->min;
    }

    /**
     * Maximum Setter
     * 
     * @param  int  $max  The maximum value
     */
    public function setMax($max)
    {
        $this->max = $max;
    }

    /**
     * Maximum Getter
     * 
     * @return int The maximum value
     */
    public function getMax()
    {
        return $this->max;
    }


    /**
     * Validates a range between min and max
     * 
     * @return boolean true on success, false on failure
     */
    public function validate()
    {
        if (isset($this->min) &&  $this->min > $this->element->getValue())
        {
            $this->setMessage('Element value not should be lower than ' . $this->min);
            return false;
        }
        if (isset($this->max) &&  $this->max < $this->element->getValue())
        {
            $this->setMessage('Element value not should be bigger than ' . $this->max);
            return false;
        }
        return true;
    }    
}
            
