<?php 
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
        $this->{objectName}Model->setItemsPerPage(CONTROLLER_LIST_DEFAULT_NUMBER);
        $this->{objectName}Model->offset = ToolBox::getArrayParameter($_REQUEST, 'offset', $offset);;
        $this->view->model = $this->{objectName}Model;
        $this->view->where = $where;
        $this->view->orderBy = $orderBy;
        $this->view->offset = $offset;
        $this->view->limit = $limit;
    }

    /**
     * detail action 
     */
    public function detailAction()
    {
        $fields = $this->{objectName}Model->getPublicProperties();
        $params = frontDispatcher::getInstance()->getParams();
        $this->{objectName}Model->bind($params);
        $this->{objectName}Model->checkin();
        $this->view->model = $this->{objectName}Model;
        $this->view->form = new {objectName}Form($this->{objectName}Model);
    }

    /**
     * edit action 
     */
    public function editAction()
    {
        $fields = $this->{objectName}Model->getPublicProperties();
        $params = frontDispatcher::getInstance()->getParams();
        $this->{objectName}Model->bind($params);
        $this->{objectName}Model->checkin();
        $this->view->model = $this->{objectName}Model;
        $this->view->form = new {objectName}Form($this->{objectName}Model);
        // Handle eventual errors
        if (isset($_REQUEST['errors']) && is_array($_REQUEST['errors']))
        {
            $this->view->form->setErrors($_REQUEST['errors']);
        }    
    }

    /**
     * delete action 
     */
    public function deleteAction()
    {
        $params = frontDispatcher::getInstance()->getParams();
        $this->{objectName}Model->bind($params);
        if ($this->{objectName}Model->delete())
        {
            $this->redirect('list', null,  "Item deleted successfully", CoreView::MESSAGE_TYPE_SUCCESS);
        }
        else
        {
            $this->redirect('list', null,  "Item could not be deleted", CoreView::MESSAGE_TYPE_ERROR);
        }
        exit();
    }

    public function saveAction()
    {
        $this->{objectName}Model->bind($_REQUEST);
        $form = new {objectName}Form($this->{objectName}Model);
        if ($form->validate())
        {    
            if ($this->{objectName}Model->save())
            {
                $this->redirect('list', null, "Item saved successfully", CoreView::MESSAGE_TYPE_SUCCESS);
            }
            else
            {
                $this->redirect('list', null, "Item could not be saved", CoreView::MESSAGE_TYPE_ERROR);
            }
        }
        else
        {
            $params = array_merge(array('id' => $this->{objectName}Model->id, 'errors' => $form->getErrors()));
             $this->redirect('edit', null, "Item could not be saved", CoreView::MESSAGE_TYPE_ERROR, $params);
        }
        exit();

    }
}
