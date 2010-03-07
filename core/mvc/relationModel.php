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
 * File:        relationModel.php
 * 
 * @author      Anis BEREJEB
 * @version     0.1
 */

/**
 * A Generic relation model
 * 
 */
class RelationModel extends CoreModel
{
    /**
     * The table name
     * 
     * @var string  Defaults to 'relation'. 
     */
    protected $_tableName = 'relation';
    /**
     * The table keys
     * 
     * @var array  Defaults to array('id'). 
     */
    protected $_tableKeys = array('id');

    /**
     * the source object Id
     * 
     * @var int  Defaults to null. 
     */
    public $sourceId = null;


    /**
     * The source class
     * 
     * @var string  Defaults to null. 
     */
    public $sourceClass= null;

    /**
     * The destination object id
     * 
     * @var int  Defaults to null. 
     */
    public $destinationId = null;

    /**
     * The destination object class
     * 
     * @var string  Defaults to null. 
     */
    public $destinationClass = null;

    /**
     * Returns the Related objects  (Destinations)
     *  
     * 
     * @param  string  $sourceClass      The source Class
     * @param  int     $sourceId         The source Id
     * @param  string  $destinationClass The Destination Class
     * @return array the result rows
     */
    public function getDestinations($sourceClass, $sourceId, $destinationClass)
    {
        $query = "SELECT d.* FROM {$this->getTableName()} r, $destinationClass d  WHERE r.sourceClass='$sourceClass' AND r.sourceId=$sourceId AND r.destinationClass='$destinationClass' and d.id=r.destinationId";
        $rows =  $this->database->query_all($query);
        
        return ($rows);
    }

    /**
     * Returns the destinations objects for a given relation row
     * 
     * @param  string  $destinationClass The destination Class
     * @param  int     $destinationId    The destination Id
     * @param  string  $sourceClass      The source class
     * @param  int     $sourceClass      The source Id
     * @return array The result rows
     */
    public function getExactSources($destinationClass, $destinationId, $sourceClass, $sourceId)
    {
        $query = "SELECT d.*  FROM {$this->getTableName()} r, $sourceClass d WHERE r.destinationClass='$destinationClass' AND r.destinationId=$destinationId AND r.sourceClass='$sourceClass' AND r.sourceId=$sourceId  AND d.id=r.sourceId";
        $rows =  $this->database->query_all($query);
        return ($rows);
    }



    /**
     * Returns the sources for a destination object
     * 
     * @param  string   $destinationClass The destination Class
     * @param  int      $destinationId    The destination Id
     * @param  string   $sourceClass      The source Class
     * @return array the result rows
     */
    public function getSources($destinationClass, $destinationId, $sourceClass)
    {
        $query = "SELECT d.*  FROM {$this->getTableName()} r, $sourceClass d WHERE r.destinationClass='$destinationClass' AND r.destinationId=$destinationId AND r.sourceClass='$sourceClass' AND d.id=r.sourceId";
        $rows =  $this->database->query_all($query);
        return ($rows);
    }

    /**
     * Returns all the related objects to a class name
     * 
     * @param  string  $sourceClass      The source class
     * @param  int     $sourceId         The source Id
     * @param  string  $destinationClass The destination Class
     * @return array the result rows
     */
    public function getRelated($sourceClass, $sourceId, $destinationClass)
    {
        $query = "SELECT d.* FROM {$this->getTableName()} r, $destinationClass d  WHERE r.sourceClass='$sourceClass' AND r.sourceId=$sourceId AND r.destinationClass='$destinationClass' and d.id=r.destinationId";
        $query .= " UNION SELECT d.* FROM {$this->getTableName()} r, $destinationClass d WHERE r.destinationClass='$sourceClass' AND r.destinationId=$sourceId AND r.sourceClass='$destinationClass' and d.id=r.sourceId";
        $rows =  $this->database->query_all($query);
        return ($rows);
    }

    /**
     * Deletes all related objects for a given class name
     * 
     * @param  string  $sourceClass      The source class
     * @param  int     $sourceId         The source Id
     * @param  string  $destinationClass The destination class
     * @return array the result rows
     */
    public function deleteAll($sourceClass, $sourceId, $destinationClass)
    {
        $query = "DELETE FROM {$this->getTableName()} WHERE sourceClass='{$sourceClass}' AND destinationClass='{$destinationClass}' AND sourceId={$sourceId}";
        return $this->database->query($query);
    }    
}

