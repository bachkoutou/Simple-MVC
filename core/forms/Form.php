<?php
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
     * An array of the fields keys
     * 
     * @var array  Defaults to array(). 
     */
    protected $keys = array();
    

    /**
     * The errors
     * 
     * @var array Defaults to array()
     */
    private $errors = array();

    /**
     * Constructor, binds the model to the Form.
     * 
     * @param  coreModel  $model the model to represent
     */
    public function __construct(CoreModel $model)
    {
        $this->model        = $model;
        $this->keys         = $this->model->keys;
        $fields             = $this->model->getFields();
        $this->model->configure();
        $configuredFields   = $this->model->getTypes();
        $validators         = $this->model->getValidators();
        
        if (is_array($fields) && count($fields))
        {    
            foreach ($fields as $fieldName)
            {
                if (!in_array($fieldName, $this->keys))
                {    
                    $type = isset($configuredFields[$fieldName]) ? $configuredFields[$fieldName] : 'text';
                    $element = FormElementFactory::getElement($type);
                    $element->setType($type);
                    $element->setName($fieldName);
                    $element->setValue($model->$fieldName);
                    $this->setValidators($element, $validators);
                    $this->setField($element);
                }
            }
        }

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
     * Returns a context Link in relation to the model.
     * 
     * @return string the context link
     */
    private function getContextLink()
    {
        $modelName = explode('Model', get_class($this->model));
        return '/?controller=' . $modelName[0];
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
     * TODO: short description.
     * 
     * @return TODO
     */
    public function render()
    {
        ?>
            <form  id="form<?php echo substr($this->model->getModelName(), 0, -5);?>" method="post" action="<?php echo $this->getContextLink() . "&action=save"?>">

            <fieldset>
            <?php
            foreach ($this->getFields() as $field)
            {
                if (!in_array($field->getName(), $this->keys))
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
        if (is_array($this->keys) && (count($this->keys) > 0))
        {
            foreach ($this->keys as $value)
            {
                ?><input class="none" type="hidden" name="<?php echo $value?>" value="<?php echo (string)$this->model->$value ?>"><?php
            }

        }
        ?>
            </fieldset>
            <div class="button">
            <input class="none" type="hidden" name="message" value="">
            <input type="submit" value="Valider" class="submit" />
            <input type="button" value="Retour a la liste" class="submit" onclick='document.location="<?php echo $this->getContextLink() . '&action=list'?>"' />
            </div>
            </form>

            <?php
    }

    /**
     * TODO: short description.
     * 
     * @param  mixed  $field 
     * @return TODO
     */
    public function renderFieldErrors($field)
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
