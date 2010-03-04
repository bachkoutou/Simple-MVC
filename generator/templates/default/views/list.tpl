<h4><?php echo $this->getName()?> - List </h4>
<div class="container">
<table cellpadding="0" cellspacing="0" border="0" width="100%">
    <thead>
    <tr>
    <?php
        foreach ($this->model->fields as $field)
        {
            ?><th><?php echo $field?></th><?php
        }
        ?><th>edit</th><?php
        ?><th>delete</th><?php
    ?>
    </tr>
    </thead>
    <tbody>
    </tbody>
    <?php
    $this->model->beginEnum($this->where, $this->orderBy, $this->offset, $this->limit);
    while ($this->model->nextEnum())
    {
        ?>
        <tr>
        <?php
        foreach ($this->model->fields as $field)
        {
            ?><td><?php echo $this->model->$field; ?></td><?php
        }
        ?>
        <td><a href="<?php echo $this->getContextLink() . "&action=detail&id=" . $this->model->id?>">detail</a></td>
        <td><a href="<?php echo $this->getContextLink() . "&action=edit&id=" . $this->model->id?>">editer</a></td>
        <td><a href="<?php echo $this->getContextLink() . "&action=delete&id=" . $this->model->id?>" onclick="return confirm('Voulez vous vraiment supprimer cet element?')">delete</a></td>
        </tr>
        <?php
    }
    $this->model->endEnum();
    ?>
    <tr>
        <td colspan="10" align="center">
        <?php
            $this->model->renderPagination();
        ?>
        </td>
    </tr>
    </tbody>
</table>
<br/>
<a href="<?php echo $this->getContextLink() . "&action=edit"?>">add</a>
</div>
