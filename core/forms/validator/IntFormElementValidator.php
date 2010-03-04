<?php
/**
 * TODO: short description.
 * 
 * TODO: long description.
 * 
 */
class IntFormElementValidator extends FormElementValidator
{

    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function validate()
    {
        if(!filter_var($this->element->getValue(), FILTER_VALIDATE_INT))
        {
            $this->setMessage('Element should be a number');
            return false;
        }
        return true;
    }    
}
            
