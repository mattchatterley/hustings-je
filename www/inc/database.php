<?php

define('DB_HOST', 'hustingsjestaging.csxrxm32zjxg.eu-west-1.rds.amazonaws.com');
define('DB_USER', 'stagingUser');
define('DB_PASSWORD', 'stagingUserPwd');
define('DB_SCHEMA', 'hustingsStaging');

class Database
{
    public $db;
            
    function __construct()
    {
        $this->db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_SCHEMA) or die('Failed to connect to database');
    }
    
    function __destruct()
    {
        if($this->db)
        {
            $this->db->close();
        }
    }
    
    /* Utilities */
    
    function Query($query)
    {
        if($this->db)
        {
            $result = $this->db->query($query) or die('Failed to execute Query');
            return $result;
        }
    }
    
    /* Data Sets */
    
}