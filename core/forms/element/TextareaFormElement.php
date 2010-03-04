<?php
/**
 * TODO: short description.
 * 
 * TODO: long description.
 * 
 */
class TextareaFormElement extends FormElement
{
    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function render()
    {
        echo '<textarea name="' . $this->getName(). '" ' . $this->getAttributesString() . '> ' . $this->getValue() . '</textarea>';
    }    
}    
