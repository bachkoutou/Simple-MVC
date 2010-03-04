<?php
/**
 * TODO: short description.
 * 
 * TODO: long description.
 * 
 */
class RegexFormElementValidator extends FormElementValidator
{
    /**
     * TODO: description.
     * 
     * @var resource
     */
    private $regexp;

    /**
     * TODO: short description.
     * 
     * @param  FormElement  $element 
     * @param  resource     $regex   
     */
    public function __construct(FormElement $element, $regex)
    {
        parent::__construct($element);
        $this->regex = $regexp;
    }

    /**
     * TODO: short description.
     * 
     * @return TODO
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
            
