<?php
/**
 * Description of Stats
 *
 * @author mattc
 */

class Stats 
{
    public $TotalTweets;
    public $TotalParticipants;
    public $SimplePositive;
    public $SimpleNeutral;
    public $SimpleNegative;
    public $NlpPositive;
    public $NlpNeutral;
    public $NlpNegative;

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

		$result = $db->Query("SELECT SUM(CASE WHEN SentimentScore = 1 THEN 1 ELSE 0 END) AS PositiveSimple, SUM(CASE WHEN SentimentScore = 0 THEN 1 ELSE 0 END) AS NeutralSimple, SUM(CASE WHEN SentimentScore = -1 THEN 1 ELSE 0 END) AS NegativeSimple FROM ScoredTweets;");
		$values = $result->fetch_assoc();

		$this->SimplePositive = intval($values["PositiveSimple"]);
		$this->SimpleNeutral = intval($values["NeutralSimple"]);
		$this->SimpleNegative = intval($values["NegativeSimple"]);

		// convert to percentages
		$totalSimple = $this->SimplePositive + $this->SimpleNeutral + $this->SimpleNegative;
		$this->SimplePositive = intval(round($this->SimplePositive / $totalSimple * 100, 0));
		$this->SimpleNeutral = intval(round($this->SimpleNeutral / $totalSimple * 100, 0));
		$this->SimpleNegative = intval(round($this->SimpleNegative / $totalSimple * 100, 0));

		$result = $db->Query("SELECT SUM(CASE WHEN NlpSentimentScore = 1 THEN 1 ELSE 0 END) AS NlpPositive, SUM(CASE WHEN NlpSentimentScore = 0 THEN 1 ELSE 0 END) AS NlpNeutral, SUM(CASE WHEN NlpSentimentScore = -1 THEN 1 ELSE 0 END) AS NlpNegative FROM ScoredTweets WHERE NlpSentimentScore IS NOT NULL;");
		$values = $result->fetch_assoc();
		$this->NlpPositive = intval($values["NlpPositive"]);
		$this->NlpNeutral = intval($values["NlpNeutral"]);
		$this->NlpNegative = intval($values["NlpNegative"]);

		// convert to percentages
		$totalNlp = $this->NlpPositive + $this->NlpNeutral + $this->NlpNegative;
		$this->NlpPositive = intval(round($this->NlpPositive / $totalNlp * 100, 0));
		$this->NlpNeutral = intval(round($this->NlpNeutral / $totalNlp * 100, 0));
		$this->NlpNegative = intval(round($this->NlpNegative / $totalNlp * 100, 0));
	}    
}
