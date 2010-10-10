<?php
namespace Business\Front\Model;
/**
 * Calculator Model
 */
class Calculator extends \Core\Mvc\Model
{
    protected $_tableName = 'calculator';
    protected $_tableKeys = array('id');

    public $id = null;
    public $operator1 = null;
    public $operator2 = null;
    public $operation = null;
    public $result = null;
}    
