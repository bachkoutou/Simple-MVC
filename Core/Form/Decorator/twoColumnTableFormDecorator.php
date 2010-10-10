<?php
/**
 * Two Column Table Decorator for the forms
 * Displays labels in a column, values in another
 * 
 */
namespace Core\Form\Decorator;
class twoColumnTableFormDecorator extends FormDecorator
{
    /**
     * table class Name
     * 
     * @var mixed  Defaults to null. 
     */
    protected $tableClassName = null;

    /**
     * Table ClassName Setter
     * 
     * @param  mixed  $className 
     */
    public function setTableClassName($className)
    {
        $this->tableClassName = $className;
    }    

    /**
     * table id
     * 
     * @var mixed  Defaults to null. 
     */
    protected $tableId = null;

    /**
     * Table Id Setter
     * 
     * @param  mixed  $id
     */
    public function setTableId($id)
    {
        $this->tableId = $id;
    }    
 
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
    <table class="<?php echo $this->tableClassName?>" id="<?php echo $this->tableId?>">
<?php
            foreach ($this->form->getFields() as $field)
            {
                if (!in_array($field->getName(), $this->form->getHiddenFields()))
                {
                    if ('hidden' !== $field->getType()) {
                        ?><tr><td style="vertical-align:top;" ><?php
                        $label = $field->getLabel();
                        if (!empty($label))
                        {   
                            echo '<label for="'. $field->getId() . '" id="'. $field->getId().'Label">' . $label . '</label>'; 
                        }
                        ?></td><td><?php
                        $this->form->renderFieldErrors($field);
                        $field->render();
                        if ($this->form->validatorHintsEnabled()) echo $field->renderValidatorsHint();
                        ?></td><tr/><?php
                    } else {
                        $field->render();
                    }
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
            <tr><td></td><td>
            <input class="none" type="hidden" name="message" value="">
            <input type="submit" id="<?php echo $this->form->getName() . '_submit' ?>" value="<?php echo $this->form->getSubmitButtonLabel()?>" class="submit" />
            <?php if ($resetLabel = $this->form->getResetButtonLabel()) { ?>
            <input type="reset" id="<?php echo $this->form->getName() . '_reset' ?>" value="<?php echo $resetLabel?>" class="submit" />
            <?php } ?>
            </td></tr>
        </table>
            </form>
<?php
    }
}    
