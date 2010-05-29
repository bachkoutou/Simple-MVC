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
 * File:        CurlClient.php
 * 
 * @author      Anis BEREJEB
 * 
 */

/**
 * Implements a curl client
 */
class CurlClient extends Client
{
    /**
     * Curl URL
     * 
     * @var mixed  Defaults to null. 
     */
    private $url = null;

    /**
     * The Curl Params
     * 
     * @var array  Defaults to array(). 
     */
    private $params = array();
    
    /**
     * Curl response
     * 
     * @var string  Defaults to null. 
     */
    private $response = null;
    
    /**
     * request type
     * 
     * @var string  Defaults to 'get'. 
     */
    private $request = 'get';
    
    /**
     * Defines return transfer
     * 
     * @var bool  Defaults to true. 
     */
    private $returnTransfer = true;

    /**
     * Curl status
     * 
     * @var mixed  Defaults to null. 
     */
    private $status = null;
    

    /**
     * Response Getter
     * 
     * @return string the response
     */
    public function getResponse()
    {
        return $this->response;
    }    
    
    /**
     * Status Getter
     *
     * @return mixed the Status
     */
    public function getStatus()
    {
        return $this->status;
    }  

    /**
     * Url Setter
     *
     * @param  string  $url the url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    } 

    /**
     * Url Getter
     *
     * @return string the url
     */
    public function getUrl()
    {
        return $this->url;
    }    

    /**
     * Params Setter
     * 
     * @param  string  $params The params
     */
    public function setParams($params)
    {
        $this->params = $params;
    }    

    /**
     * Params Getter
     * 
     * @return array the params
     */
    public function getParams()
    {
        return $this->params;
    }
    
    /**
     * Defines a post request
     * 
     */
    public function setAsPost()
    {
        $this->request = 'post';
    }    

    /**
     * Defines a get request.
     * 
     */
    public function setAsGet()
    {
        $this->request = 'get';
    }
    
    /**
     * Sets the return transfer type 
     * 
     * @param $bool true to set return transfer, false to disable it
     */
    public function setReturnTransfer($bool = true)
    {

        $this->returnTransfer = (bool) $bool;
    }    

    /**
     * executes the curl call
     *
     * @Exception if curl is not installed
     * @return string the result if CURLOPT_RETURNTRANSFER is true, 
     * null otherwise
     */
    public function call()
    {
        if (!function_exists('curl_init'))
        {
            throw new Exception('CURL is not installed in your environment');
        }    
        $curl = curl_init();
        if ('post' == $this->request)
        {
            curl_setopt($curl, CURLOPT_POST, 1);
        }
        if ('post' == $this->request && count($this->params))
        {    
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($this->params));
            curl_setopt($curl, CURLOPT_URL, $this->url);
        }
        else
        {
            curl_setopt($curl, CURLOPT_URL, $this->url . http_build_query($this->params));
        }    

        if ($this->returnTransfer)
        {    
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $this->response = curl_exec($curl);
        }
        else
        {
            curl_exec($curl);
        }    
        
        $this->status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        if (isset($this->response))
        {
            return $this->response;
        }    
    }    
}
