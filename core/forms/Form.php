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
class Form
{
    /**
     * Form Name 
     * 
     * @var mixed  Defaults to null. 
     */
    private $name = null;

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
     * The errors
     * 
     * @var array Defaults to array()
     */
    private $errors = array();

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
        return $name;
    }
    
    /**
     * Constructor
     * 
     * @param  string  $name The form name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }    
    
    /**
     * Constructor, binds the model to the Form.
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
        
        if (is_array($fields) && count($fields))
        {    
            foreach ($fields as $fieldName)
            {
                if (!in_array($fieldName, $this->hiddenFields))
                {    
                    $type = isset($configuredFields[$fieldName]) ? $configuredFields[$fieldName]['type'] : 'text';
                    $element = FormElementFactory::getElement($type);
                    $element->setType($type);
                    $element->setAttributes($configuredFields[$fieldName]['attributes']);
                    $element->setName($fieldName);
                    $element->setValue($model->$fieldName);
                    $this->setValidators($element, $validators);
                    $this->setField($element);
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
                        $this->errors[$field->getName()][] = $validator->getMessage();
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
        ?>
            <form  id="form<?php echo $this->getName();?>" method="post" action="<?php echo $this->getAction();?>">

            <fieldset>
            <?php
            foreach ($this->getFields() as $field)
            {
                if (!in_array($field->getName(), $this->hiddenFields))
                {
                    ?>
                        <label>
                        <span><?php echo $field->getName(); ?> : </span>
                        <?php $this->renderFieldErrors($field);?>
                        <?php echo $field->render();?>
                        <?php echo $field->renderValidatorsHint();?>
                        
                        <br/>
                        </label>
                        <?php
                }
            }
        if (is_array($this->hiddenFields) && (count($this->hiddenFields) > 0))
        {
            foreach ($this->hiddenFields as $value)
            {
                ?><input class="none" type="hidden" name="<?php echo $value?>" value="<?php echo (string)$this->model->$value ?>"><?php
            }

        }
        ?>
            </fieldset>
            <div class="button">
            <input class="none" type="hidden" name="message" value="">
            <input type="submit" value="Valider" class="submit" />
            </div>
            </form>

            <?php
    }

    /**
     * Renders a list of errors
     *
     * 
     * @param  FormElement  $field A form element
     */
    public function renderFieldErrors(FormElement $field)
    {
        if (isset($this->errors[$field->getName()]) && count($this->errors[$field->getName()]))
        {
            echo "<ul class=\"form_input_error\">";
            foreach ($this->errors[$field->getName()] as $error)
            {
                echo "<li>$error</li>";
            }    
            echo "</ul>";
        }
    }    
}    
