<?php
/**
 * Abstract Class FormDecorator
 * 
 */
namespace Core\Form\Decorator;
abstract class FormDecorator implements IFormDecorator
{
    /**
     * Form Element
     * 
     * @var Form Defaults to null. 
     */
    protected $form = null;

    /**
     * Form setter.
     * 
     * @param  Form  $form 
     */
    public function setForm(\Core\Form\Form $form)
    {
        if (null === $form)
        {
            throw InvalidArgumentException('Form is null!');
        }    
        $this->form = $form;
    }
    
    /**
     * Form Getter
     *
     * @return Form the form
     */
    public function getForm()
    {
        return $this->form;
    }    
        

}    
