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
 * File:        Base{objectName}Controller.php
 * 
 * @author      Anis BEREJEB
 * @version     0.1
 */
/**
 * Base{objectName}Controller class
 */
class Base{objectName}Controller extends MainController
{
    /**
     * used to define if the controller uses a model or not
     * overrides mainController::$_useModel (array())
     *
     * @var boolean
     */
    protected $_useModels = array('{objectName}');

    /**
     * list action 
     * @param string $where     Optional where clause
     * @param string $orderby   Optional order clause
     * @param int    $offset    Optional offset of first row (default is zero)
     * @param int    $limit     Optional maximum number of returned rows (default is zero, means return all rows)
     * 
     */
    public function listAction($where = null, $orderBy = null, $offset = 0, $limit = 0)
    {
        $this->{objectName}Model->setItemsPerPage($this->configuration['list_default_number']);
        $this->view->model = $this->{objectName}Model;
        $this->view->where = $where;
        $this->view->orderBy = $orderBy;
        $this->view->limit = $limit;
        $this->view->offset = ToolBox::getArrayParameter($this->Dispatcher->getParams(), 'offset', $offset);;
    }

    /**
     * detail action 
     */
    public function detailAction()
    {
        $fields = $this->{objectName}Model->getPublicProperties();
        $params = $this->Dispatcher->getParams();
        $this->{objectName}Model->bind($params);
        $this->{objectName}Model->checkin();
        $this->view->model = $this->{objectName}Model;
        $this->view->form = new {objectName}Form('{objectName}');
        $this->view->form->initFromModel($this->{objectName}Model);
    }

    /**
     * edit action 
     */
    public function editAction()
    {
        $fields = $this->{objectName}Model->getPublicProperties();
        $params = $this->Dispatcher->getParams();
        $this->{objectName}Model->bind($params);
        $this->{objectName}Model->checkin();
        $this->view->model = $this->{objectName}Model;
        $this->view->form = new {objectName}Form('{objectName}');
        $this->view->form->initFromModel($this->{objectName}Model);
        // Handle eventual errors
        $params = $this->Dispatcher->getParams();
        if (isset($params['errors']) && is_array($params['errors']))
        {
            $this->view->form->setErrors($params['errors']);
        }    
    }

    /**
     * delete action 
     */
    public function deleteAction()
    {
        $params = $this->Dispatcher->getParams();
        $this->{objectName}Model->bind($params);
        if ($this->{objectName}Model->delete())
        {
            $this->Router->redirect('list', '{objectName}',  "Item deleted successfully", CoreView::MESSAGE_TYPE_SUCCESS);
        }
        else
        {
            $this->Router->redirect('list', '{objectName}',  "Item could not be deleted", CoreView::MESSAGE_TYPE_ERROR);
        }
        exit();
    }

    public function saveAction()
    {
        $this->{objectName}Model->bind($this->Dispatcher->getParams());
        $form = new {objectName}Form('{objectName}');
        $form->initFromModel($this->{objectName}Model);
        if ($form->validate())
        {    
            if ($this->{objectName}Model->save())
            {
                $this->Router->redirect('list', '{objectName}', "Item saved successfully", CoreView::MESSAGE_TYPE_SUCCESS);
            }
            else
            {
                $this->Router->redirect('list', '{objectName}', "Item could not be saved", CoreView::MESSAGE_TYPE_ERROR);
            }
        }
        else
        {
            $params = array_merge(array('id' => $this->{objectName}Model->id, 'errors' => $form->getErrors()));
             $this->Router->redirect('edit', '{objectName}', "Item could not be saved", CoreView::MESSAGE_TYPE_ERROR, $params);
        }
        exit();

    }
}
