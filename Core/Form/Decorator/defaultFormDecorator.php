<?php
/**
 * Default Decorator for the forms
 * 
 * 
 */
namespace Core\Form\Decorator;
class DefaultFormDecorator extends FormDecorator
{
    /**
     * Renders the form fields
     * 
     */
    public function render()
    {
        if (null === $this->form)
        {
            throw InvalidArgumentException('Form is null!');
        }
        $id = $this->form->getId();
        if (empty($id)) $id = $this->form->getName();
        ?>
            <form class="form_<?php echo $this->form->getName();?>" id="<?php echo $id;?>" name="<?php echo $this->form->getName()?>" method="<?php echo $this->form->getMethod();?>" action="<?php echo $this->form->getAction();?>" enctype="<?php echo $this->form->getEncType();?>" >

            <fieldset>
            <?php
            foreach ($this->form->getFields() as $field)
            {
                if (!in_array($field->getName(), $this->form->getHiddenFields()))
                {
                    $label = $field->getLabel();
                    if (!empty($label) && 'hidden' !== $field->getType())
                    {   
                        echo '<label for="'. $field->getId() . '" id="'. $field->getId().'Label">' . $label . '<br/></label>'; 
                    }
                    $this->form->renderFieldErrors($field);
                    $field->render();
                    if ($this->form->validatorHintsEnabled()) echo $field->renderValidatorsHint();
                    if ('hidden' !== $field->getType()) echo "<br/>";
                }
            }
        if (is_array($this->form->getHiddenFields()) && (count($this->form->getHiddenFields()) > 0))
        {
            foreach ($this->form->getHiddenFields() as $value)
            {
                ?><input class="none" type="hidden" name="<?php echo $value?>" value="<?php echo (string)$this->form->model->$value ?>"><?php
            }

        }
        ?>
            </fieldset>
            <div class="button">
            <input class="none" type="hidden" name="message" value="">
            <input type="submit" value="<?php echo $this->form->getSubmitButtonLabel()?>" class="submit" />
            </div>
            </form>

            <?php
    }
}    
