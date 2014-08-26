<?php
/**
 * Description of User
 *
 * @author mattc
 */
include_once('inc/database.php');

class User 
{
    public $ScreenName;
    public $Name;
    
    function __construct($assocResult)
    {
        $this->ScreenName = htmlspecialchars($assocResult["ScreenName"]);
        $this->Name = htmlspecialchars($assocResult["Name"]);
    }
    
    static function All()
    {
        $db = new Database();
        
        $results = $db->Query("SELECT DISTINCT Name, ScreenName FROM ScoredTweets ORDER BY ScreenName");
        
        $users = Array();
        
        while($row = $results->fetch_assoc())
        {
            $users[] = new User($row);
        }
        
        return $users;
    }
    
    // TODO: Most popular
    // TODO: least popular
    // TODO: set of csv usernames
}
