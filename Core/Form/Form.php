<?php
/**
 * Note : Code is released under the GNU LGPL
 *
 * Please do not change the header of this file 
 *
 * This library is free software; you can redistribute it and/or modify it under the terms of the GNU 
 * Lesser General Public License as published by the Free Software Foundation; either version 2 of 
 * the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; 
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. 
 *
 * See the GNU Lesser General Public License for more details.
 */

/**
 * File:       Form.php
 * 
 * @author      Anis BEREJEB
 * @version     0.1
 */

/**
 * Form class
 * 
 * Represents a web form. Handles FormElements and renders the Form. 
 * The Form is related with the model. Basically it takes a model in
 * The constructor and binds the elements. 
 * The Form class allows setting and getting the FormFields, setting 
 * Validators and managing errors.
 */
namespace Core\Form;
class Form
{
    /**
     * The form action
     * 
     * @var string
     */
    private $action;

    /**
     * The form method
     * 
     * @var string Defaults to 'post'.
     */
    private $method = 'post';

    /**
     * Form encType
     * 
     * @var string  Defaults to 'application/x-www-form-urlencoded'. 
     */
    private $encType = 'application/x-www-form-urlencoded';

    /**
     * Form Name 
     * 
     * @var mixed  Defaults to null. 
     */
    private $name = null;

    /**
     * Form Id
     * 
     * @var int  Defaults to null. 
     */
    private $id = null;

    /**
     * The model represented.
     * 
     * @var CoreModel  Defaults to null. 
     */
    private $model = null;
    
    /**
     * An array of the fields
     * 
     * @var array  Defaults to array(). 
     */
    protected $fields = array();

    /**
     * An array of the hidden Fields
     * 
     * @var array  Defaults to array(). 
     */
    protected $hiddenFields = array();
    
    /**
     * Form Decorator
     * 
     * @var FormDecorator  Defaults to null. 
     */
    protected $decorator = null;
    /**
     * The errors
     * 
     * @var array Defaults to array()
     */
    private $errors = array();

    /**
     * Renders The Validators hints
     * 
     * @var bool  Defaults to true. 
     */
    protected $renderValidatorsHint = true;
    

    /**
     * Label for the submit button
     * 
     * @var string Defaults to "Valider"
     */
    private $submitButtonLabel = 'Valider';

    /**
     * Id Setter
     * 
     * @param  string  $id The form id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Returns the form id.
     * 
     * @return string The form id
     */
    public function getId()
    {
        return $this->id;
    }



    /**
     * submitButtonLabel Setter
     * 
     * @param  string  $label The submit button label
     */
    public function setSubmitButtonLabel($label)
    {
        $this->submitButtonLabel = $label;
    }    
    
    /**
     * submitButtonLabel Getter
     * 
     * @return string the label for the submit button
     */
    public function getSubmitButtonLabel()
    {
        return $this->submitButtonLabel;
    } 


    /**
     * Label for the reset button
     * 
     * @var string
     */
    private $resetButtonLabel;
    
    /**
     * resetButtonLabel Setter
     * 
     * @param  string  $label The reset button label
     */
    public function setResetButtonLabel($label)
    {
        $this->resetButtonLabel = $label;
    }    
    
    /**
     * resetButtonLabel Getter
     * 
     * @return string the label for the reset button
     */
    public function getResetButtonLabel()
    {
        return $this->resetButtonLabel;
    }    

    /**
     * Activates / desactivate the validator hints 
     *
     * @return string The form name
     */
    public function enableValidatorsHints($enable = true)
    {
        $this->renderValidatorsHint = (bool) $enable;
    }
    
    /**
     * tells if the validator hints are enabled
     * 
     * @return bool true if hints are enabled, false otherwise
     */
    public function validatorHintsEnabled()
    {
        return $this->renderValidatorsHint;
    }    
    
    /**
     * Name Setter
     * 
     * @param  string  $name The form name
     */
    public function setName($name)
    {
        $this->name = $name;
    } 
    
    /**
     * Returns the form name.
     * 
     * @return string The form name
     */
    public function getName()
    {
        return $this->name;
    }
 
    /**
     * enctype Setter
     * 
     * @param  string  $encType the enctype, 
     * Defaults to 'application/x-www-form-urlencoded'
     */
    public function setEncType($encType = 'application/x-www-form-urlencoded')
    {
        $this->encType = $encType;
    }

    /**
     * enctype Getter
     * 
     * @return string the enctype
     */
    public function getEncType()
    {
        return $this->encType;
    }

    /**
     * Constructor
     * 
     * @param  string  $name    The form name
     * @param  string  $action  The form action, Optional, defaults to null. 
     * @param  string  $method  The form method, Optional, defaults to post. 
     * @param  string  $encType The encType, Optional, defaults to 'application/x-www-form-urlencoded'
     */
    public function __construct($name, $action = null, $method = 'post', $encType = 'application/x-www-form-urlencoded', FormDecorator $decorator = null)
    {
        $this->setName($name);
        if ($action)
        {
            $this->setAction($action);
        }
        $this->setMethod($method);
        $this->setDecorator($decorator);
        $this->setEncType($encType);
    }
    
    /**
     * FormDecorator setter
     * If null defaultFormDecorator will be set
     *
     * @param  FormDecorator  $decorator Optional, defaults to null. 
     */
    public function setDecorator(FormDecorator $decorator = null)
    {
       if (null === $decorator)
       {
           $this->decorator = new twoColumnTableFormDecorator();
           $this->decorator->setTableClassName($this->getName() . 'Formcontainer tableDecorator');
           $this->decorator->setTableId($this->getName() . 'Formcontainer');
       }
       else
       {
           $this->decorator = $decorator;
       }
       $this->decorator->setForm($this);
    }   
    /**
     * binds the model to the Form.
     * 
     * @param  coreModel  $model the model to represent
     */
    public function initFromModel(CoreModel $model)
    {
        
        $this->model        = $model;
        $modelName = explode('Model', $model->getModelName());
        $this->setAction('/?controller=' . $modelName[0] . '&action=save');
        $this->setName($modelName[0]);
        $this->setHiddenFields($this->model->keys);
        $fields             = $this->model->getFields();
        $this->model->configure();
        $configuredFields   = $this->model->getTypes();
        $validators         = $this->model->getValidators();
        $formFields = array_keys($this->getFields());
        if (is_array($fields) && count($fields))
        {    
            foreach ($fields as $fieldName)
            {
                if (!in_array($fieldName, $this->hiddenFields))
                {    
                    $type = isset($configuredFields[$fieldName]) ? $configuredFields[$fieldName]['type'] : 'text';
                    if (!in_array($fieldName, $formFields))
                    {    
                        $element = FormElementFactory::getElement($type);
                        $element->setType($type);
                        $element->setAttributes($configuredFields[$fieldName]['attributes']);
                        $element->setName($fieldName);
                        $element->setLabel($fieldName);
                        $element->setValue($model->$fieldName);
                        $this->setValidators($element, $validators);
                        $this->setField($element);
                    }
                }
            }
        }
    }

    /**
     * Binds values to the elements from an array
     * 
     * @param  array $values An array of values
     *                       should have offsets with the 
     *                       Elements Name
     */
    public function bindFromArray(array $values = array())
    {
        if (count($values))
        {    
            foreach ($this->getFields() as $field)
            {
                $fieldName = $field->getName();
                if (array_key_exists($fieldName, $values))
                {
                    $field->setValue($values[$fieldName]);
                    $this->setField($field);
                }
            }    
        }
    }

    /**
     * Processes any attached files calling the 
     * FileFormElement::process 
     * Returns an array with keys as the element name and value as
     * the created file.
     *
     * @param  array The list of the files to be processed. this value should
     * have the same structure as the $_FILES array.
     * @return array The list of the paths processed
     */
    public function processFiles(array $files = array())
    {
        if (count($files))
        {

            $fields = $this->getFields();
            foreach ($files as $key => $file)
            {
                if (isset($files[$key]))
                {
                    $field = $fields[$key];
                    // process the file 
                    $field->setValue($file);
                    // update the field in the form
                    $this->setField($field);
                }
            }
        }
    }

    /**
     * Binds the processed files to the model
     * 
     * @param CoreModel The model in which we will bind the files values
     */
    public function bindFilesToModel(CoreModel $model)
    {
        foreach ($this->getFields() as $field)
        {
            if (('file' == $field->getType()) && (property_exists($model, $field->getName())))
            {
                $fields = explode($field->getDestinationDir(), $field->getValue());
                $model->{$field->getName()} = $fields[1];
            }
        }
    }    


    /**
     * Sets form hidden Fields
     * Used when a form is bound from a model so that the keys will not be shown
     *
     * @param array $hiddenFields array of hidden fields
     */
    public function setHiddenFields(array $hiddenFields)
    {
        $this->hiddenFields = $hiddenFields;
    }

    /**
     * Returns the forms hidden fileds
     * 
     * @return array The hidden fields array or null
     */
    public function getHiddenFields()
    {
        return $this->hiddenFields;
    }    

    /**
     * field setter
     * 
     * @param  FormElement  $element 
     */
    public function setField(FormElement $element)
    {
        $this->fields[$element->getName()] =$element;
    }

    /**
     * remove a field
     * 
     * @param  mixed  $offset The field name
     */
    public function removeField($offset)
    {
        unset($this->fields[$offset]);
    }    

    /**
     * remove a field
     * 
     * @param  mixed  $offset The field name
     */
    public function removeFields(array $array)
    {
        foreach ($array as $value)
        {
            unset($this->fields[$value]);
        }    
    }    


    /**
     * fields getter
     * 
     * @return array of FormFields
     */
    public function getFields()
    {
        return $this->fields;
    }    

    /**
     * Binds FormValidators to a FormElement field
     * 
     * accepts an array in this format : 
     * array('fieldName' => array(
     *                      0 => array(
     *                          'class' => 'validatorClass', 
     *                          'options' => array('key' => 'value')
     *                          ),
     *                      1 => array(
     *                          'class' => 'validatorClass', 
     *                          'options' => array('key' => 'value')
     *                          ),
     *                      )
     *                  );
     */
    public function setValidators(FormElement $element, array $validators)
    {
        foreach ($validators as $field => $validations)
        {
            if ($field === $element->getName())
            {
                foreach($validations as $validation)
                {
                    $classname = $validation['class'] . 'FormElementValidator';
                    if (!class_exists($classname))
                    {
                        throw new FormException('Validation classname "' . $classname . '" should be a valid validator class');
                    }
                    $validator = new $classname($element);
                    if (isset($validation['options']) && is_array($validation['options']) && count($validation['options']))
                    {
                        $validator->setOptions($validation['options']);
                    } 
                    $element->addValidator($validator);
                }    
            }        
        }
    }

    /**
     * Executes the FormValidator::validate method on the fields.
     * ans Sets Eventual Errors in Form::errors.
     * 
     * @return boolean true if there are errors, false if not.
     */
    public function validate()
    {
        foreach ($this->fields as $field)
        {
            $validators = $field->getValidators();
            if (count($validators))
            {    
                foreach($validators as $validator)
                {
                    if (!$validator->validate())
                    {
                        // remove field brakets on the name. this will resolve problems
                        // with the multiple list elements for example.
                        $name = str_replace(array('[', ']'), array('', ''), $field->getName());
                        $this->errors[$name][] = $validator->getMessage();
                    }
                }
            }
        }
        return  (count($this->errors)) ? false : true;
    }    

    /**
     * Errors getter
     * 
     * @return array of errors
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Sets a list of errors
     * 
     * @param  array $errors Array containing errors 
     */
    public function setErrors(array $errors = array())
    {
        $this->errors = $errors;
    }
    /**
     * Sets the Form Method
     * 
     * @param string $method the form Method
     */
    public function setMethod($method)
    {
        $this->method = $method;
    }

    /**
     * Returns the form Method
     * 
     * @return string the Form method
     */
    public function getMethod()
    {
        return $this->method;
    }    


    /**
     * Sets the Form Action
     * 
     * @param string $action the form Action
     */
    public function setAction($action)
    {
        $this->action = $action;
    }

    /**
     * Returns the form Action
     * 
     * @return string the Form Action
     */
    public function getAction()
    {
        return $this->action;
    }    

    /**
     * default field type
     *
     * @return unknown
     */
    public function renderFieldType($field)
    {
        return ('' != (string)$field->inputType) ? (string) $field->inputType : 'text';
    }

    /**
     * Renders the form fields
     * 
     */
    public function render()
    {
        $this->decorator->render();
    }

    /**
     * Renders a list of errors
     *
     * 
     * @param  FormElement  $field A form element
     */
    public function renderFieldErrors(FormElement $field)
    {
        // Strip brakets on field to fix problem with arrays. 
        // this happens with the multi select elements
        $name = str_replace(array('[', ']'), array('', ''), $field->getName());
        if (isset($this->errors[$name]) && count($this->errors[$name]))
        {
            echo "<ul class=\"form_input_error\">";
            foreach ($this->errors[$name] as $error)
            {
                echo "<li>$error</li>";
            }    
            echo "</ul>";
        }
    }    
}    
