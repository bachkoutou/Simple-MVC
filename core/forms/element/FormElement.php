<?php
/**
 * TODO: short description.
 * 
 * TODO: long description.
 * 
 */
abstract class FormElement
{
    /**
     * TODO: description.
     * 
     * @var mixed
     */
    private $name;

    /**
     * TODO: description.
     * 
     * @var mixed
     */
    private $value;

    /**
     * TODO: description.
     * 
     * @var array
     */
    private $attributes;

    /**
     * TODO: description.
     * 
     * @var mixed
     */
    private $type;
    
    /**
     * TODO: description.
     * 
     * @var mixed
     */
    private $validators;

    /**
     * TODO: description.
     * 
     * @var mixed
     */
    private $errors;
    
    public static $allowedInputTypes = array('text', 'textarea', 'checkbox', 'radio', 'file');
    /**
     * TODO: short description.
     * 
     * @param  array  $attributes 
     * @return TODO
     */
    public function setAttributes($attributes)
    {
        $this->attributes = $attributes;
    }    
    
    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function getAttributes()
    {
        return $this->attributes;
    } 
    
    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function getAttributesString()
    {
        $string = '';
        if (is_array($this->attributes) && count($this->attributes))
        {
            foreach ($this->attribues as $keya => $value)
            {
                $string.= ' ' . $key . '=' . '"' . $value . '"';
            }    
        }    
        return $string;
    }
    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * TODO: short description.
     * 
     * @param  mixed  $name 
     * @return TODO
     */
    public function setName($name)
    {
        $this->name = $name;
    }    
    
    /**
     * TODO: short description.
     * 
     * @param  mixed  $value 
     * @return TODO
     */
    public function setValue($value)
    {
        $this->value = $value;
    }
    
    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function getValue()
    {
        return $this->value;
    }    
    
    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * TODO: short description.
     * 
     * @param  mixed  $type 
     * @return TODO
     */
    public function setType($type)
    {
        $this->type = $type;
    }    
    
    /**
     * TODO: short description.
     * 
     * @param  FormValidator  $validator 
     * @return TODO
     */
    public function addValidator(FormElementValidator $validator)
    {
        $this->validators[] = $validator;
    }    

    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function getValidators()
    {
        return $this->validators;
    }    
    
    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function validate()
    {
        foreach ($this->validators as $validator)
        {
            if (!$validator->validate($this))
            {
                $this->errors[] =  $validator->getError();
            }    
        }    
        return (count($this->errors)) ? true : false;
    }
    
    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function getErrors()
    {
        return $this->errors;
    }    
    /**
     * Renders a text Help with the validations.
     * 
     * @return TODO
     */
    public function renderValidatorsHint()
    {
        $validators = $this->getValidators();
        if (count($validators))
        {
            $string = '';
            foreach ($validators as $validator)
            {
                $string.= ", {$validator->getHintMessage()}";
            }
            echo trim(trim($string, ','));
        }
    }    

    abstract public function render();

}    
