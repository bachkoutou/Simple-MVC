<?php
class PDODatabase extends PDO 
{
    public function __construct($host, $dbname, $user, $pass, $dbType='mysql')
    {
        try 
        {
            parent::__construct("$dbType:host=$host;dbname=$dbname", $user, $pass);

        } 
        catch (PDOException $e) 
        {
            echo $e->getMessage();
        }
    }
}
