<?php
/**
 * TODO: short description.
 * 
 * TODO: long description.
 * 
 */
class NotEmptyFormElementValidator extends FormElementValidator
{
    /**
     * TODO: description.
     * 
     * @var mixed  Defaults to "*". 
     */
    public $hintMessage = "*";
    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function validate()
    {
        if('' == $this->element->getValue())
        {
            $this->setMessage('Element should not be empty');
            return false;
        }
        return true;
    }    
}
            
