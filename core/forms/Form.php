<?php
/**
 * TODO: short description.
 * 
 * TODO: long description.
 * 
 */
class Form
{
    /**
     * TODO: description.
     * 
     * @var mixed  Defaults to null. 
     */
    private $model = null;
    
    /**
     * TODO: description.
     * 
     * @var mixed  Defaults to array(). 
     */
    protected $fields = array();

    /**
     * TODO: description.
     * 
     * @var mixed  Defaults to array(). 
     */
    protected $keys = array();
    

    /**
     * TODO: description.
     * 
     * @var mixed
     */
    private $errors = array();

    /**
     * TODO: short description.
     * 
     * @param  coreModel  $model 
     */
    public function __construct(CoreModel $model)
    {
        $this->model = $model;
        $this->keys = $this->model->keys;
        
        $fields = $this->model->getFields();
        $this->model->configure();
        $configuredFields = $this->model->getTypes();
        $validators = $this->model->getValidators();
        if (is_array($fields) && count($fields))
        {    
            foreach ($fields as $fieldName)
            {
                if (!in_array($fieldName, $this->keys))
                {    
                    // build a text element by default
                    // could be redefined by configure.
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
     * TODO: short description.
     * 
     * @param  mixed  $element 
     * @return TODO
     */
    public function setField($element)
    {
        $this->fields[$element->getName()] =$element;
    }    
    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function getFields()
    {
        return $this->fields;
    }    

    /**
     * TODO: short description.
     * 
     * accept an array in this format : 
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
     * @return TODO
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
     * TODO: short description.
     * 
     * @return TODO
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
     * TODO: short description.
     * 
     * @return TODO
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * TODO: short description.
     * 
     * @param  mixed  $errors 
     * @return TODO
     */
    public function setErrors($errors)
    {
        $this->errors = $errors;
    }

    /**
     * TODO: short description.
     * 
     * @return TODO
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
