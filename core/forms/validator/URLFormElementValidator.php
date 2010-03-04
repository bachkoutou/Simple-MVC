<?php
/**
 * TODO: short description.
 * 
 * TODO: long description.
 * 
 */
class URLFormElementValidator extends FormElementValidator
{
    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function validate()
    {
        if(!filter_var($this->element->getgetValue()(), FILTER_VALIDATE_URL))
        {
            $this->setMessage('Element should be a valid URL address');
            return false;
        }
        return true;
    }    
}
            
