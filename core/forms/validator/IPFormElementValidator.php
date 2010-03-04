<?php
/**
 * TODO: short description.
 * 
 * TODO: long description.
 * 
 */
class IPFormElementValidator extends FormElementValidator
{
    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function validate()
    {
        if(!filter_var($this->element->getValue(), FILTER_VALIDATE_IP))
        {
            $this->setMessage('Element should be a valid IP address');
            return false;
        }
        return true;
    }    
}
            
