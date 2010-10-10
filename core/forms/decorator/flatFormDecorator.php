<?php
/**
 * Flat Form Decorator
 */
class flatFormDecorator extends FormDecorator
{
    public function render()
    {
        if (null === $this->form)
        {
            throw InvalidArgumentException('Form is null!');
        }

        ?>
            <form class="form_<?php echo $this->form->getName();?>" id="<?php echo $this->form->getName();?>" name="<?php echo $this->form->getName()?>" method="<?php echo $this->form->getMethod();?>" action="<?php echo $this->form->getAction();?>" enctype="<?php echo $this->form->getEncType();?>" >

            <fieldset>
            <table>
            <tr>
            <?php
            foreach ($this->form->getFields() as $field)
            {
                if (!in_array($field->getName(), $this->form->getHiddenFields()))
                {
                    ?>
                        <?php 
                        $label = $field->getLabel();
                    if (!empty($label))
                    {    
                        echo '<th>' . $label . '</th>'; 
                    } 
                }
            }
        ?></tr><tr><?php

            foreach ($this->form->getFields() as $field)
            {
                if (!in_array($field->getName(), $this->form->getHiddenFields()))
                {
                    ?>
                        <td valign="top">
                        <?php $this->form->renderFieldErrors($field);?>
                        <?php echo $field->render();?>

                        <?php if ($this->form->validatorHintsEnabled()) echo $field->renderValidatorsHint();?>
                        </td>    
                        <?php
                }
            }
        ?>
        <td>
            <input type="submit" value="<?php echo $this->form->getSubmitButtonLabel()?>"/>
        </td></tr></table><?php
            if (is_array($this->form->getHiddenFields()) && (count($this->form->getHiddenFields()) > 0))
            {
                foreach ($this->form->getHiddenFields() as $value)
                {
                    ?><input class="none" type="hidden" name="<?php echo $value?>" value="<?php echo (string)$this->form->model->$value ?>"><?php
                }

            }
        ?>
            </fieldset>
            <input class="none" type="hidden" name="message" value="">
            </form>

            <?php
    }
}    
