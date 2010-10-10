<?php
namespace Business\Front\Controller;
/**
 * Calculator Controller
 */
class Calculator extends Main
{
    /**
     * Add Action 
     */
    public function addAction()
    {
        $this->view->model = new \Business\Front\Model\Calculator($this->Database);
        $this->view->model->operator1 = 5;
        $this->view->model->operator2 = 6;
        $this->view->model->operation = '+';
        $this->view->model->result = $this->view->model->operator1 + $this->view->model->operator2 = 6;
        $this->view->model->save();
    }    
}
