<?php
/**
 * TODO: short description.
 * 
 * TODO: long description.
 * 
 */
class EmailFormElementValidator extends FormElementValidator
{
    /**
     * TODO: description.
     * 
     * @var mixed  Defaults to "*". 
     */
    public $hintMessage = "Should be a valid email address";
    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function validate()
    {
        if(!filter_var($this->element->getValue(), FILTER_VALIDATE_EMAIL))
        {
            $this->setMessage('Element should be a valid email address');
            return false;
        }
        return true;
    }    
}
            
