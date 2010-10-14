<?php
namespace Business\Front\Form;
/**
 * Form Calculatore 
 */
class Calculator extends \Core\Form\Form
{
    /**
     * constructor
     */
    public function __construct($formName)
    {
        parent::__construct($formName);
        $this->setAction('/?controller=Calculator&action=validateAdd');
        $text = new \Core\Form\Element\TextFormElement();
        $text->setName('text');
        $text->addValidator(new \Core\Form\Validator\NotNullFormElementValidator($text));
        $this->setField($text);
    }
}    
