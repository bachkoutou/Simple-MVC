<?php
/**
 * Feed Model 
 */
class FeedModel extends CoreModel
{
    /**
     * Table Name
     * 
     * @var mixed  Defaults to 'feed'. 
     */
    protected $_tableName = 'feed';

    /**
     * Table keys
     * 
     * @var array  Defaults to array('id'). 
     */
    protected $_tableKeys = array('id');
    
    // public properties
    /**
     * Feed Name
     * 
     * @var string  Defaults to null.
     */
    public $name = null;

    /**
     * Feed Url
     * 
     * @var string  Defaults to null. 
     */
    public $url = null;

    /**
     * Items to be shown
     * 
     * @var int  Defaults to null. 
     */
    public $itemsNumber = null;

}
