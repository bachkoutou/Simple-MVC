<?php
/**
 * TODO: short description.
 * 
 * TODO: long description.
 * 
 */
class RangeFormElementValidator extends FormElementValidator
{
    /**
     * TODO: description.
     * 
     * @var mixed
     */
    public $min;

    /**
     * TODO: description.
     * 
     * @var mixed
     */
    private $max;

    /**
     * TODO: short description.
     * 
     * @param  mixed  $min 
     * @return TODO
     */
    public function setMin($min)
    {
        $this->min = $min;
    }

    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function getMin()
    {
        return $this->min;
    }

    /**
     * TODO: short description.
     * 
     * @param  mixed  $max 
     * @return TODO
     */
    public function setMax($max)
    {
        $this->max = $max;
    }

    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function getMax()
    {
        return $this->max;
    }


    /**
     * TODO: short description.
     * 
     * @return TODO
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
            
