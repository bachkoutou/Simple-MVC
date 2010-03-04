<?php
class CoreModel extends ArrayIterator
{
	protected $_tableName = null;
	protected $_tableKeys = null;
	protected $database = null;
	protected $_results = null;
	protected $_iterator = null;
	protected $_modelName = null;
	protected $enumResultSet = null;
	protected $enumQuery = null;
	protected $enumTotalCount = -1;
	protected $enumCountQuery = null;

    /**
     * TODO: description.
     * 
     * @var mixed
     */
    private $types = array();
    /**
     * TODO: description.
     * 
     * @var int
     */
    private $itemsPerPage;
    /**
     * TODO: description.
     * 
     * @var mixed  Defaults to null. 
     */
    private $pagination = null;
    /**
     * TODO: description.
     * 
     * @var mixed  Defaults to null. 
     */
    private $publicProperties = null;
    /**
     * TODO: description.
     * 
     * @var mixed
     */
    private $cache;

    /**
     * TODO: description.
     * 
     * @var mixed
     */
    private $validators = array();

    /**
     * TODO: description.
     * 
     * @var mixed
     */
    private $where;
    
    /**
     * TODO: description.
     * 
     * @var object
     */
    private $orderBy;

	public function __construct()
	{
		$this->database =  database::getInstance();
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
	 * @return unknown
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

	private function check()
	{
		$where =  ($this->computePreparedStatementString());
		//$where =  ($this->_computeTableKeys());
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

	public function getModelName()
	{
		return $this->_modelName;
	}

	public function setModelName()
	{
		$this->_modelName = ucfirst($this->getTableName()) . 'Model';
	}


	public function getTableName()
	{
		return $this->_tableName;
	}


	public function setTableName($tableName)
	{
		$this->_tableName = $tableName;
	}

	public function getTableKeys()
	{
		return $this->_tableKeys;
	}

	public function get(){
		$query =  "SELECT * FROM " . $this->getTableName() . " WHERE " . $this->_computeTableKeys();
		return $this->database->query($query);
	}

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
     * TODO: short description.
     * 
     * @return TODO
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
     * TODO: short description.
     * 
     * @return TODO
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
     * TODO: short description.
     * 
     * @return TODO
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

    public function beginEnum($where = null, $orderBy = null, $offset = 0, $limit = 0)
    {
        $where = ($this->where) ? $this->where : $where;
        $orderBy = ($this->orderBy) ? $this->orderBy : $orderBy;
        $offset = ($this->offset) ? $this->offset : $offset;
        $limit = ($this->itemsPerPage) ? $this->itemsPerPage : $limit;
        $this->endEnum();

        if ($where == null || (strcmp($where, $this->enumQuery) != 0))
        {
            // Remember actual query
            $this->enumQuery = $where;

            $sql = 'SELECT COUNT(*) AS number FROM ' . $this->getTableName();
            if (!empty($where))
                $sql .= " WHERE ".$where;
            $this->enumCountQuery = $sql;
            $count = $this->database->query($this->enumCountQuery)->fetch();
            $this->enumTotalCount = $count['number'];
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
            $sql .= " LIMIT $offset, {$this->enumCountQuery}";
        }
        // Load resultset
        $this->enumResultSet = $this->database->query($sql);
        if ($this->enumResultSet == null)
        {
            $this->lastErrorMsg = $this->database->getErrorMsg();
            return false;
        }

        return true;
    }

    /**
     * Moves to the next item of current enumeration
     *
     * @return boolean True if enumeration has succeed, false otherwise
     */
    public function nextEnum()
    {
        if ($this->enumResultSet == null)
            return false;

        foreach ($this as $property => $value)
        {
            $this->$property = null;
        }
        $res = $this->enumResultSet->fetch();
        if (!$res)
        {
            $this->endEnum();
            return false;
        }else
        {
            foreach ($res as $key => $value)
            {
                $this->$key = $value;
            }

            return true;
        }

    }

    /**
     * Stops current enumeration
     */
    public function endEnum()
    {
        // Free resultset
        $this->enumResultSet = null;
        $this->enumQuery = null;
        $this->enumTotalCount = -1;
    }


    /**
     * Returns the total number of objects which can be enumerated (regardless of the
     * offset and limit parameters passed in the {@link beginEnum()} method)
     *
     * @return int Total number of enumerable objects
     */
    public function enumCount()
    {
        // compute enum count on demand and only once!
        if ($this->enumTotalCount == -1)
        {
            $this->enumTotalCount = $this->database->query_affected($this->enumCountQuery);
        }

        return $this->enumTotalCount;
    }

    /**
     * binds the properties of the object from an array
     *
     * @param unknown_type $inputArray
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
            $this->pagination->pages = ceil($this->enumTotalCount / $this->itemsPerPage);
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
     * 
     * @return string
     */
    public function renderPagination()
    {
        $pagination = $this->getPagination();
        if (is_array($pagination->links))
        {    
        // render total 
        $string = 'Total pages (' . $pagination->pages . ') :  ' ;
        $offset = ToolBox::getArrayParameter($_REQUEST, 'offset', 0);
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
     * TODO: short description.
     * 
     * @param  int  $items 
     * @return TODO
     */
    public function setItemsPerPage($items)
    {
        $this->itemsPerPage = ($items > 0) ? $items : 1;
    }

    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function configure()
    {

        foreach ($this->getFields() as $property)
        {
            $this->setValidator($property, array('class' => 'NotEmpty'));
            $this->setPropertyInputType($property, 'text');
        }    
    } 

    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function getFields()
    {
        return $this->fields;
    }    

    /**
     * TODO: short description.
     * 
     * @param  mixed  $property   
     * @param  array  $validation 
     * @return TODO
     */
    public function setValidator($property, array $validation)
    {
        if (property_exists($this, $property) && isset($this->$property))
        {
            $this->validators[$property][$validation['class']] = $validation;
        }
    }

    /**
     * TODO: short description.
     * 
     * @param  mixed  $property 
     * @param  mixed  $type     
     * @return TODO
     */
    protected function setPropertyInputType($property, $type)
    {
        if ($this->$property && in_array($type, FormElement::$allowedInputTypes)) 
        {
            $this->types[$property] = $type;
        }    
    }    

    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function getValidators()
    {
        return $this->validators;
    }

    /**
     * TODO: short description.
     * 
     * @return TODO
     */
    public function getTypes()
    {
        return $this->types;
    }    

}
