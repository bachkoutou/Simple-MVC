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
        /*
           $this->view->model = new \Business\Front\Model\Calculator($this->Database);
           $this->view->model->operator1 = 5;
           $this->view->model->operator2 = 6;
           $this->view->model->operation = '+';
           $this->view->model->result = $this->view->model->operator1 + $this->view->model->operator2 = 6;
           $this->view->model->save();
         */

    }
    public function validateAddAction()
    {
        $params = $this->Router->getParams();
            $form = new \Business\Front\Form\Calculator('Form_Calculator');
            $form->bindFromArray($params);

            if ($form->Validate())
            {
                $this->Router->redirect('add', 'Calculator', 'Success', \Core\Mvc\View::MESSAGE_TYPE_SUCCESS); 
            }
            else
            {
                $this->Router->redirect('add', 'Calculator', 'Failure', \Core\Mvc\View::MESSAGE_TYPE_ERROR, $form->getErrors()); 
            }
    }    
}
