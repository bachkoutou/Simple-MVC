<h4><?php echo $this->getName()?> - Details </h4>
    <?php
    foreach ($this->model->fields as $field)
    {
        if (!in_array($field, $this->model->keys))
        {
        
           echo $field . ' : '. $this->model->$field . '<br/>';
        }
    }

?>
<br/>
<a href="<?php echo $this->getContextLink() . '&action=list'?>"'/> Return to the list</a>
