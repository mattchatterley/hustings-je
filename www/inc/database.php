<?php

define('DB_HOST', 'web02.mattchedit.com');
define('DB_USER', 'hustings-je');
define('DB_PASSWORD', 'Hust1ngs!');
define('DB_SCHEMA', 'hustings_je');

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