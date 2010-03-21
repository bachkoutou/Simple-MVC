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
 * File:        CoreModel.php
 * 
 * @author      Anis BEREJEB
 * @version     0.1
 */

/**
 * Class representing the model
 * 
 */
class CoreModel extends ArrayIterator
{
	/**
	 * Table name
	 * 
	 * @var string  Defaults to null. 
	 */
	protected $_tableName = null;
	
    /**
	 *  Table keys
	 * 
	 * @var array  Defaults to null. 
	 */
	protected $_tableKeys = null;
	
    /**
	 * database instance
	 * 
	 * @var PDODatabase  Defaults to null. 
	 */
	protected $database = null;

	/**
	 * The model name
	 * 
	 * @var string  Defaults to null. 
	 */
	protected $_modelName = null;

	/**
	 * The result set, used in the enumeration
	 * 
	 * @var array  Defaults to null. 
	 */
	protected $resultSet = null;

	/**
	 * query used in enumeration
	 * 
	 * @var string  Defaults to null. 
	 */
	protected $query = null;
	protected $totalCount = -1;
	protected $countQuery = null;

    /**
     * Field types
     * 
     * @var array
     */
    private $types = array();

    /**
     * Items per page, used in the pagination
     * 
     * @var int
     */
    private $itemsPerPage;

    /**
     * pagination object
     * 
     * @var stdClass  Defaults to null. 
     */
    private $pagination = null;
    
    /**
     * public properties of the object.
     * 
     * @var array  Defaults to null. 
     */
    private $publicProperties = null;
    
    /**
     * Cache for the public properties
     * used to avoid using reflection every time
     * 
     * @var array
     */
    private $cache;

    /**
     * Validators array
     * 
     * @var array Defaults to array
     */
    private $validators = array();

    /**
     * where clause
     * 
     * @var string
     */
    private $where;

    /**
     * order by clause
     * 
     * @var string
     */
    private $orderBy;
    
    /**
     * The configuration array
     * 
     * @var array  Defaults to array(). 
     */
    protected $configuration = array();

    /**
     * Constructor
     * 
     * @param  PDODatabase  $database      The database instance
     * @param  array        $configuration The configuration params Default to array
     */
    public function __construct(PDODatabase $database, array $configuration = array())
    {
        $this->database = $database;
        $this->configuration = $configuration;
        $this->setModelName();

        if (!$this->_tableName)
        {
            throw new Exception ('Model Table Name cannot be null');
        }

        if (!$this->_tableKeys || !(is_array($this->_tableKeys) || !count($this->_tableKeys)))
        {
            throw new Exception ('Model Table Keys cannot be null');
        }
        $this->publicProperties = $this->getPublicProperties();
        $this->fields = array_keys($this->getPublicProperties());
        $this->keys = $this->getTableKeys();

    }

    /**
     * returns an array of all the public properties
     * of the object
     *
     * @return array The cache with the public properties
     */
    public function getPublicProperties($noCache = false)
    {
        if (!isset($this->cache['publicProperties']) || !count($this->cache['publicProperties']) || true === $noCache)
        {
            $reflexionObject = new ReflectionClass(get_class($this));

            $publicProperties = array();

            foreach ($reflexionObject->getProperties() as $property)
            {
                if ($property->isPublic())
                {
                    $publicProperties[$property->name] = $this->{$property->name};
                }
            }
            $this->cache['publicProperties'] = $publicProperties;
        }
        return $this->cache['publicProperties'];
    }

    /**
     * Returns the descriptors to show
     * this method will scan the descriptors/{object}.xml descriptor file
     * and return the properties there.
     * This allows personnalizing the properties you want to show in a list
     * 
     * @return array
     */
    public function getDescriptors()
    {
        $action = frontDispatcher::getInstance()->getAction();
        $allowedProperties = new stdClass();
        $fields = array();
        $keys = array();
        $table = simplexml_load_file(BUSINESS . DS . DESCRIPTORS_PATH . $this->getTableName() . '.xml');
        if (count($table->children()->children()) > 0 )
        {
            foreach ($table->children()->children() as $field)
            {
                $display = $action.'Display';
                if (1 == $field->$display)
                {
                    if (('PRI' != $field->Key))
                    {
                        // May add some logic depending on $field->Type
                        $fields[(String)$field->Field] = $field;
                    }
                    else
                    {
                        $keys[(String)$field->Field] = $field;
                    }
                }
            }
        }
        $allowedProperties->fields = $fields;
        $allowedProperties->keys = $keys;
        return $allowedProperties;
    }

    /**
     * checks if an object exists in the database
     * 
     * @return mixed The row if the object exists, false otherwise.
     */
    private function check()
    {
        $where =  ($this->computePreparedStatementString());
        if ($where)
        {
            $stmt =  $this->database->prepare("SELECT count(*) as rows FROM " . $this->getTableName() . " WHERE " . $where . ' LIMIT 1');
            $stmt = $this->bindStatement($stmt);
            if ($stmt->execute())
            {    
                $row = $stmt->fetch();
                return ($row['rows']);
            }    
        }
        return false;
    }

    /**
     * Binds the respective row from the Database to the properties
     * of the current object
     */
    public function checkin()
    {
        $where =  ($this->_computeTableKeys());
        if ($where)
        {
            $query =  $this->database->prepare("SELECT * FROM " . $this->getTableName() . " WHERE " . $where) ;
            $query->execute($this->computePreparedKeysValues());
            $obj = $query->fetch();
            if ($obj)
            {
                foreach ($obj as $key =>$value)
                {
                    $this->$key = $value;
                }
            }
        }
    }


    /**
     * Saves the object. Used for both updates and inserts
     * 
     * @return mixed the id of the object on success, false on failure.
     */
    public function save()
    {
        // begin insertion - update - check
        if ($this->check())
        {
            /// update !
            foreach ($this->getPublicProperties(true) as $property => $value)
            {
                $value =  (get_magic_quotes_gpc()) ? $value : addslashes($value);

                $parts[] = "`" . $property . "`='" . $value . "'";
            }
            $sets = join(",",$parts);
            $query = $this->database->prepare("UPDATE " . $this->getTableName() . " SET " . $sets . " WHERE " . $this->_computeTableKeys());
            if ($query->execute($this->computePreparedKeysValues()))
            {
                return $this->id;
            }
            return false;
        }
        else
        {
            $publicProperties = array();
            foreach ($this->publicProperties as $property => $value)
            {
                $publicProperties[$property] = $this->$property;
            }
            $keys = "`" . trim(join("`,`", array_keys($publicProperties))) . "`";
            $values = "'" . trim(join("','", array_values($publicProperties))) . "'";
            $query =  "INSERT INTO " . $this->getTableName() . " (" . $keys . ") VALUES (" . $values . ")";
            if  ($this->database->query($query))
            {
                return $this->database->lastInsertId();
            }
            return false;
        }
    }

    /**
     * Model name Getter
     * 
     * @return string
     */
    public function getModelName()
    {
        return $this->_modelName;
    }

    /**
     * Model Name Setter from the table name
     * 
     * @return string
     */
    public function setModelName()
    {
        $this->_modelName = ucfirst($this->getTableName()) . 'Model';
    }


    /**
     * Table Name Getter
     * 
     * @return string
     */
    public function getTableName()
    {
        return $this->_tableName;
    }


    /**
     * Table Name Setter
     * 
     * @param  string  $tableName  The Table Name
     */
    public function setTableName($tableName)
    {
        $this->_tableName = $tableName;
    }

    /**
     * Table Keys Getter
     * 
     * @return array
     */
    public function getTableKeys()
    {
        return $this->_tableKeys;
    }

    /**
     * Returns an object
     * 
     */
    public function get()
    {
        $query =  "SELECT * FROM " . $this->getTableName() . " WHERE " . $this->_computeTableKeys();
        return $this->database->query($query);
    }

    /**
     * Computes the table keys to be passed in a query
     * 
     * @return mixed the clause for the keys or NULL
     */
    private function _computeTableKeys()
    {
        if (is_array($this->getTableKeys()) && count($this->getTableKeys()))
        {
            foreach ($this->getTableKeys() as $key)
            {
                $parts[] = "`" . $key . "`= ?";
            }
            $query = join(" AND ",$parts);
            return $query;
        }
        return null;
    }

    /**
     * Returns an array with the values of the keys 
     * Used in the prepared statements 
     * 
     * @return array the Values or NULL
     */
    private function computePreparedKeysValues()
    {
        if (is_array($this->getTableKeys()) && count($this->getTableKeys()))
        {
            foreach ($this->getTableKeys() as $key)
            {
                $parts[] = $this->$key;
            }
            return $parts;
        }
        return null;
    }

    /**
     * Returns a string representing the key clause for 
     * prepared statements
     * 
     * @return string the key clause
     */
    public function computePreparedStatementString()
    {
        if (is_array($this->getTableKeys()) && count($this->getTableKeys()))
        {
            foreach ($this->getTableKeys() as $key)
            {
                $parts[] = "`" . $key . "`= :" . $key;
            }
            $query = join(" AND ",$parts);
            return $query;
        }
        return null;
    }


    /**
     * Binds the keys of the object to a prepared statement
     * 
     * @return PDOStatement the binded statement
     */
    public function bindStatement($stmt)
    {
        if (is_array($this->getTableKeys()) && count($this->getTableKeys()))
        {
            foreach ($this->getTableKeys() as $key)
            {
                $stmt->bindParam(':' . $key, $this->$key);
            }
        }
        return $stmt;
    }

    /**
     * Begins Enumeration for an object
     * 
     * @param  string   $where   A where clause. Optional, defaults to null. 
     * @param  string   $orderBy An order by clause. Optional, defaults to null. 
     * @param  int      $offset  An offset. Optional, defaults to 0. 
     * @param  int      $limit   A limit. Optional, defaults to 0. 
     * @return boolean  
     */
    public function beginEnum($where = null, $orderBy = null, $offset = 0, $limit = 0)
    {
        $where = ($this->where) ? $this->where : $where;
        $orderBy = ($this->orderBy) ? $this->orderBy : $orderBy;
        $offset = ($this->offset) ? $this->offset : $offset;
        $limit = ($this->itemsPerPage) ? $this->itemsPerPage : $limit;
        $this->endEnum();

        if ($where == null || (strcmp($where, $this->query) != 0))
        {
            // Remember actual query
            $this->query = $where;

            $sql = 'SELECT COUNT(*) AS number FROM ' . $this->getTableName();
            if (!empty($where))
                $sql .= " WHERE ".$where;
            $this->countQuery = $sql;
            $count = $this->database->query($this->countQuery)->fetch();
            $this->totalCount = $count['number'];
        }
        $this->computePagination();
        // Prepare sql query
        $sql = 'SELECT * FROM ' . $this->getTableName();
        if ($where !== null && $where != '')
            $sql .= ' WHERE ' . $where;
        if ($orderBy != null)
            $sql .= ' ORDER BY '.$orderBy;
        if ($limit)
        {
            $sql .= " LIMIT $offset, $limit";
        }elseif ($offset)
        {
            $sql .= " LIMIT $offset, {$this->countQuery}";
        }
        // Load resultset
        $this->resultSet = $this->database->query($sql);
        if ($this->resultSet == null)
        {
            $this->lastErrorMsg = $this->database->getErrorMsg();
            return false;
        }

        return true;
    }

    /**
     * Moves to the next item in enumeration
     *
     * @return boolean true if an item is found, false otherwise
     */
    public function nextEnum()
    {
        if ($this->resultSet == null)
        {
            return false;
        }

        foreach ($this as $property => $value)
        {
            $this->$property = null;
        }
        
        $res = $this->resultSet->fetch();
        
        if (!$res)
        {
            $this->endEnum();
            return false;
        }
        else
        {
            foreach ($res as $key => $value)
            {
                $this->$key = $value;
            }
            return true;
        }
    }

    /**
     * Ends the enumeration
     */
    public function endEnum()
    {
        $this->resultSet = null;
        $this->query = null;
        $this->totalCount = null;
    }


    /**
     * Returns the total number of objects
     * @return int Total number of objects
     */
    public function enumCount()
    {
        if ($this->totalCount === null)
        {
            $this->totalCount = $this->database->query_affected($this->countQuery);
        }

        return $this->totalCount;
    }

    /**
     * binds the properties of the object
     * from an array
     *
     * @param array $inputArray
     */
    public function bind($inputArray)
    {
        foreach ($this->publicProperties as $property => $value)
        {
            if (isset($inputArray[$property]))
            {
                $this->$property = $inputArray[$property];
            }
        }
    }

    /**
     * Deletes the object
     * 
     * @return boolean true on Success, false otherwise.
     */
    public function delete()
    {
        $where =  ($this->_computeTableKeys());
        if ($where)
        {
            $query =  $this->database->prepare("DELETE FROM " . $this->getTableName() . " WHERE " . $where, $this->computePreparedKeysValues());
            $query->execute($this->computePreparedKeysValues());
            return ($query->rowCount() > 0) ? true : false;
        }
        return false;
    }

    /**
     * Computes the pagination
     * 
     */
    public function computePagination()
    {
        $dispatcher = frontDispatcher::getInstance();
        $controllerName = substr($dispatcher->getController(), 0,-10 ) ;
        if (!empty($this->itemsPerPage))
        {    
            $this->pagination->pages = ceil($this->totalCount / $this->itemsPerPage);
            $offset = 0;
            for ($i = 0; $i < $this->pagination->pages; $i++)
            {
                $page = new stdClass;
                $page->title = $i+1;
                $page->offset = $offset;
                $page->link = "/?controller={$controllerName}&action=list&offset={$offset}";
                // render previous link
                if (($offset - $this->itemsPerPage) >= 0)
                {
                    $previous = $offset - $this->itemsPerPage;
                    $page->previous = "/?controller={$controllerName}&action=list&offset={$previous}";
                }
                // render next link
                if (($offset + $this->itemsPerPage) < ($this->pagination->pages * $this->itemsPerPage))
                {
                    $next = $offset + $this->itemsPerPage;
                    $page->next = "/?controller={$controllerName}&action=list&offset={$next}";
                }

                $this->pagination->links[$offset] = $page;
                $offset+= $this->itemsPerPage;
            }
        }
    }    


    /**
     * get the pagination object
     * 
     * @return array pagination
     */
    public function getPagination()
    {
        return $this->pagination;
    }    

    /**
     * Echoes the pagination links
     * @param $offset The offset Defaults to 0
     * @return string
     */
    public function renderPagination($offset = 0)
    {
        $pagination = $this->getPagination();
        if (is_array($pagination->links))
        {    
            // render total 
            $string = 'Total pages (' . $pagination->pages . ') :  ' ;
            // render previous link
            if (isset($pagination->links[$offset]->previous))
            {
                $string.= '<a href="' . $pagination->links[$offset]->previous . '">previous</a> ';
            }

            // render links
            foreach ($pagination->links as $page)
            {
                if ($offset!= $page->offset)
                {    
                    $string.=  '<a href="' . $page->link . '">' . $page->title. '</a> | ';
                }
                else
                {

                    $string.=  ' ' . $page->title. ' | ';
                }
            }
            // render next link
            if (isset($pagination->links[$offset]->next))
            {
                $string.= '<a href="' . $pagination->links[$offset]->next . '">next</a> ';
            }
            echo (trim(trim ($string, ' '), '|'));
        }
    }

    /**
     * Items per page Setter
     * 
     * @param  int  $items  The number of items per page
     */
    public function setItemsPerPage($items)
    {
        $this->itemsPerPage = ($items > 0) ? $items : 1;
    }

    /**
     * Configures validators and input type for the object properties
     * Could be overriten on children to specify the appropriate validators 
     * and input types for each property.
     */
    public function configure()
    {

        foreach ($this->getFields() as $property)
        {
            $this->setValidator($property, array('class' => 'NotEmpty'));
            $this->setPropertyInputType($property, array('type' => 'text', 'attributes'=> array()));
        }    
    } 

    /**
     * Fields Getter
     * 
     * @return array the fields array
     */
    public function getFields()
    {
        return $this->fields;
    }    

    /**
     * Sets a set of validators to a property
     * 
     * @param  string  $property   The property name
     * @param  array   $validation The validation array
     */
    public function setValidator($property, array $validation)
    {
        if (property_exists($this, $property))
        {
            $this->validators[$property][$validation['class']] = $validation;
        }
    }

    /**
     * Sets a type to a given property
     * 
     * @param  string  $property 
     * @param  array  $type should be an array in this form :
     *                      array('type' => $type, 'attributes' => $attributes)
     */
    protected function setPropertyInputType($property, array $type)
    {   
        if (property_exists($this, $property) && in_array($type['type'], FormElement::$allowedInputTypes)) 
        {
            $this->types[$property] = $type;
        }    
    }    

    /**
     * Validators Getter
     * 
     * @return array validators
     */
    public function getValidators()
    {
        return $this->validators;
    }

    /**
     * Types Getter
     * 
     * @return array of types
     */
    public function getTypes()
    {
        return $this->types;
    }    

}
