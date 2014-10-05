<?php
/**
 * Description of Stats
 *
 * @author mattc
 */
//include_once('inc/database.php');

class Stats 
{
    public $TotalTweets;
    public $TotalParticipants;
    
    function __construct()
    {
    	$this->UpdateStats();
    }

	private function UpdateStats()
	{
		$db = new Database();

		$result = $db->Query("SELECT COUNT(DISTINCT TweetId) AS Total FROM ScoredTweets;");
		$values = $result->fetch_assoc();
		$this->TotalTweets = intval($values["Total"]);

		$result = $db->Query("SELECT COUNT(DISTINCT ScreenName) AS Total FROM ScoredTweets;");
		$values = $result->fetch_assoc();
		$this->TotalParticipants = intval($values["Total"]);
	}    
}
