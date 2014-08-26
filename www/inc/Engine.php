<?php
/**
 * Description of Engine
 *
 * @author mattc
 */
include_once('inc/database.php');

class Engine
{
    public $Participants;
    public $From;
    public $To;
    public $TimeSlot;
    public $DataSet;

    private $db;
    public $Results;
    
    function __construct($participants, $from, $to, $timeslot, $dataset)
    {
        $this->Participants = $participants;
        $this->From = $from;
        $this->To = $to;
        $this->TimeSlot = $timeslot;
        $this->DataSet = $dataset;
        
        $this->db = new Database();
    }
    
    function Compute()
    {
        // prepare users
        $screenNames = "";
        
        foreach($this->Participants as $user)
        {
            $screenNames .= ",'" . $user->ScreenName . "'";
        }
        
        $screenNames = substr($screenNames, 1);                
                
        // prepare the timeslot 
        switch($this->TimeSlot)
        {
            case "month":
                $date = "DATE_FORMAT(Timestamp, '%Y-%m-01')";
                break;
            // TODO: Add support for week here
            case "day":
                $date = "DATE_FORMAT(Timestamp, '%Y-%m-%d')";
                break;
            case "hour":
                $date = "DATE_FORMAT(Timestamp, '%Y-%m-%d %H')";
                break;
            case "minute":
                $date = "DATE_FORMAT(Timestamp, '%Y-%m-%d %H:%i')";
                break;
            case "second":
                $date = "DATE_FORMAT(Timestamp, '%Y-%m-%d %H:%i:%s')";
                break;
        }
                
        switch($this->DataSet)
        {
            case 'sentiment-over-time':                
                $select = "SELECT $date AS Timestamp, ScreenName, SUM(SentimentScore) as SentimentScore";
                $from = "FROM ScoredTweets";
                $where = "WHERE ScreenName IN ($screenNames)";
                $group = "GROUP BY $date, ScreenName";                
                $order = "ORDER BY ScreenName";
                break;
            default: die('Invalid DataSet');
        }
        
        // execute query
        //var_dump($select . " " . $from . " " . $where . " " . $group . ";");
        $results = $this->db->query($select . " " . $from . " " . $where . " " . $group . " " . $order . ";");
                
        // get results into expected format
        switch($this->DataSet)
        {
            case 'sentiment-over-time':                
                $objectResults = array();
                
                // x, y, name
                $lastSN = '';
                $current = array();

                while($row = $results->fetch_assoc())
                {
                    if($row["ScreenName"] != $current["ScreenName"])
                    {
                        if(strlen($current["ScreenName"]) > 0)
                        {
                            $objectResults[] = $current;                            
                        }
                        
                        $current = array();
                        $current["ScreenName"] = $row["ScreenName"];
                        $current["Values"] = array();
                    }
                    
                    //var_dump($row);
                    $values = array('x'=>$row["Timestamp"], 'y'=>$row["SentimentScore"]);
                    $current["Values"][] = $values;
                }

                $objectResults[] = $current;
                
                $this->Results = $objectResults;
                break;
        }
    }
}
