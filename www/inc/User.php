<?php
/**
 * Description of User
 *
 * @author mattc
 */
//include_once('inc/database.php');

class User 
{
    public $ScreenName;
    public $Name;
    
    function __construct($assocResult)
    {
        $this->ScreenName = htmlspecialchars($assocResult["ScreenName"]);
        $this->Name = htmlspecialchars($assocResult["Name"]);

        if(empty($this->Name))
        {
            $this->Name = $this->ScreenName;
        }
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
    
    static function MostFrequent($numberOfUsers, $startDate, $endDate)
    {
        $db = new Database();

        if(!empty($startDate))
        {
            $where =  "WHERE Timestamp BETWEEN '$startDate' AND '$endDate'";
        }
        else
        {
            $where = "";
        }

        if($numberOfUsers > 0)
        {
            $limit = "LIMIT $numberOfUsers";
        }
        else
        {
            $limit = "";
        }

        $results = $db->Query("SELECT Name, ScreenName FROM ScoredTweets $where GROUP BY Name, ScreenName ORDER BY COUNT(TweetId) DESC $limit");
        
        $users = Array();

        while($row = $results->fetch_assoc())
        {
            $users[] = new User($row);
        }

        return $users;
    }

    // TODO: most mentioned

    // TODO: set of csv usernames? not sure where this is needed from
}
