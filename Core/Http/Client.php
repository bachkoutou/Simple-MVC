<?php
/**
 * Client base class 
 * 
 */
class Client
{
    /**
     * Logger
     * 
     * @var mixed  Defaults to null. 
     */
    protected $logger = null;

    /**
     * Logger Setter
     * 
     * @param  mixed  $logger 
     */
    public function setLogger($logger)
    {
        $this->logger = $logger;
    }

    /**
     * Logger Getter
     * 
     * @return Logger
     */
    public function getLogger()
    {
        return $this->logger;
    }    
}    

