<?php
/**
 * TODO: short description.
 * 
 * TODO: long description.
 * 
 */
class FormElementFactory
{
    private function __construct(){}

    /**
     * TODO: short description.
     * 
     * @param  mixed  $type 
     * @return TODO
     */
    public function getElement($type)
    {
        $classname = ucfirst($type) . 'FormElement';
        return  (class_exists($classname)) ? new $classname() : new TextFormElement();
    }
}    
            
