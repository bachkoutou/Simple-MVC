<?php
/**
 * TODO: short description.
 * 
 * TODO: long description.
 * 
 */
abstract class FormElementValidator
{
    /**
     * TODO: description.
     * 
     * @var mixed
     */
    protected $element;

    /**
     * TODO: description.
     * 
     * @var mixed
     */
    protected $message;

    /**
     * TODO: short description.
     * 
     * @param  FormElement  $element 
     */
    public function __construct(FormElement $element)
    {
        $this->element = $element;
    }
    
    /**
     * TODO: short description.
     * 
     * @param  mixed  $message 
     * @return TODO
     */
    protected function setMessage($message)
    {
        $this->message = $message;
    }    

    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function getMessage()
    {
        return $this->message;
    }
    
    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function setOptions(array $options = array())
    {
        if (count($options))
        {
            foreach ($options as $key => $option)
            {
                if (property_exists($this, $key))
                {
                    $method = 'set' . ucfirst($key);
                    $this->$method($option);
                }
            }
        }
    }
    
    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function getHintMessage()
    {
        return (property_exists($this, 'hintMessage')) ? $this->hintMessage : '';
    }

    abstract public function validate();
}    
