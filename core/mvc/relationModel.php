<?php
/**
 * TODO: short description.
 * 
 * TODO: long description.
 * 
 */
class RelationModel extends CoreModel
{
    /**
     * TODO: description.
     * 
     * @var mixed  Defaults to 'relation'. 
     */
    protected $_tableName = 'relation';
    /**
     * TODO: description.
     * 
     * @var mixed  Defaults to array('id'). 
     */
    protected $_tableKeys = array('id');

    /**
     * TODO: description.
     * 
     * @var string  Defaults to null. 
     */
    public $sourceId = null;


    /**
     * TODO: description.
     * 
     * @var string  Defaults to null. 
     */
    public $sourceClass= null;

    /**
     * TODO: description.
     * 
     * @var double  Defaults to null. 
     */
    public $destinationId = null;

    /**
     * TODO: description.
     * 
     * @var double  Defaults to null. 
     */
    public $destinationClass = null;

    /**
     * Returns the Related objects 
     *  
     * @param  int  $id 
     * @return TODO
     */
    public function getDestinations($sourceClass, $sourceId, $destinationClass)
    {
        $query = "SELECT d.* FROM {$this->getTableName()} r, $destinationClass d  WHERE r.sourceClass='$sourceClass' AND r.sourceId=$sourceId AND r.destinationClass='$destinationClass' and d.id=r.destinationId";
        $rows =  $this->database->query_all($query);
        
        return ($rows);
    }

    /**
     * TODO: short description.
     * 
     * @param  double  $destinationClass 
     * @param  double  $destinationId    
     * @param  string  $sourceClass      
     * @return TODO
     */
    public function getExactSources($destinationClass, $destinationId, $sourceClass, $sourceId)
    {
        $query = "SELECT d.*  FROM {$this->getTableName()} r, $sourceClass d WHERE r.destinationClass='$destinationClass' AND r.destinationId=$destinationId AND r.sourceClass='$sourceClass' AND r.sourceId=$sourceId  AND d.id=r.sourceId";
        $rows =  $this->database->query_all($query);
        return ($rows);
    }



    /**
     * TODO: short description.
     * 
     * @param  double  $destinationClass 
     * @param  double  $destinationId    
     * @param  string  $sourceClass      
     * @return TODO
     */
    public function getSources($destinationClass, $destinationId, $sourceClass)
    {
        $query = "SELECT d.*  FROM {$this->getTableName()} r, $sourceClass d WHERE r.destinationClass='$destinationClass' AND r.destinationId=$destinationId AND r.sourceClass='$sourceClass' AND d.id=r.sourceId";
        $rows =  $this->database->query_all($query);
        return ($rows);
    }

    /**
     * TODO: short description.
     * 
     * @param  double  $destinationClass 
     * @param  double  $destinationId    
     * @param  string  $sourceClass      
     * @return TODO
     */
    public function getRelated($sourceClass, $sourceId, $destinationClass)
    {
        $query = "SELECT d.* FROM {$this->getTableName()} r, $destinationClass d  WHERE r.sourceClass='$sourceClass' AND r.sourceId=$sourceId AND r.destinationClass='$destinationClass' and d.id=r.destinationId";
        $query .= " UNION SELECT d.* FROM {$this->getTableName()} r, $destinationClass d WHERE r.destinationClass='$sourceClass' AND r.destinationId=$sourceId AND r.sourceClass='$destinationClass' and d.id=r.sourceId";
        $rows =  $this->database->query_all($query);
        return ($rows);
    }

    /**
     * TODO: short description.
     * 
     * @param  string  $sourceClass      
     * @param  string  $sourceId         
     * @param  double  $destinationClass 
     * @return TODO
     */
    public function deleteAll($sourceClass, $sourceId, $destinationClass)
    {
        $query = "DELETE FROM {$this->getTableName()} WHERE sourceClass='{$sourceClass}' AND destinationClass='{$destinationClass}' AND sourceId={$sourceId}";
        return $this->database->query($query);
    }    


}

