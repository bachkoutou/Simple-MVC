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
 * File:        SolrSearcher.php
 * 
 * @author      Anis BEREJEB
 * @version     0.1
 */

/**
 * SolrSearcher Interface
 * Fixes the contract for all search plugins
 * 
 */
class SolrSearcher extends Apache_Solr_Service implements ISearcher
{

    /**
     * The Searcher instance
     * 
     * @var Apache_Solr_Service  Defaults to null. 
     */
    private $searcher = null;

    /**
     * The search response
     * 
     * @var Apache_Solr_Response  Defaults to null. 
     */
    private $response = null;

    /**
     * indexes an object
     *
     * @exception Exception if the object does not implement a method called 
     * getIndexableProperties() to be indexed. This method should return a traversable
     * object or an associative array with the properties to be indexed.
     *
     * @exception InvalidArgumentException if the properties to be indexed does not contain an id.
     *
     * @return bool true if the article has been indexed, false otherwise.
     */
    public function index($object)
    {
        if (!method_exists($object, 'getIndexableProperties'))
        {
            throw new Exception(get_class($object) . 'Should implement getIndexableProperties() to be indexed');
        }
         
        $properties = $object->getIndexableProperties();
        if (!isset($properties['id']))
        {
            throw new InvalidArgumentException('id not found in the properties to be indexed');
        }
        
        $document = new Apache_Solr_Document();
        foreach ($properties as $key => $value)
        {
            $document->$key = $value;
        }
        return $this->addDocument($document);
    }

    /**
     * Redefines the search method to return
     * the documents instead of the whole response
     * 
     * @param string $query The raw query string
     * @param int $offset The starting offset for result documents
     * @param int $limit The maximum number of result documents to return
     * @param array $params key / value pairs for other query parameters (see Solr documentation), use arrays for parameter keys used more than once (e.g. facet.field)
     * @return Apache_Solr_Response
     *
     * @throws Exception If an error occurs during the service call
     */
    public function search($query, $offset = 0, $limit = 10, $params = array(), $method = self::METHOD_GET)
    {
        $response = parent::search($query, $offset, $limit, $params, $method);
        $rawResponse = $response->getRawResponse();
        $responseDocs = json_decode($rawResponse);
        return $responseDocs;
    }    
}    
