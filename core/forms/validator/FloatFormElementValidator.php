<?php
/**
 * TODO: short description.
 * 
 * TODO: long description.
 * 
 */
class FloatFormElementValidator extends FormElementValidator
{
    /**
     * TODO: description.
     * 
     * @var mixed  Defaults to "*". 
     */
    public $hintMessage = "Float number, i.e. 1.0, 2 etc.";
    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function validate()
    {
        if(!filter_var($this->element->getValue(), FILTER_VALIDATE_FLOAT))
        {
            $this->setMessage('Element should be a valid Float number');
            return false;
        }
        return true;
    }    
}
            
