<?php
/**
 * Description of Engine
 *
 * @author mattc
 */
//include_once('inc/database.php');

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
                $timeSlotLengthSeconds = 60 * 60 * 24;
                break;
            case "hour":
                $date = "DATE_FORMAT(Timestamp, '%Y-%m-%d %H:00:00')";
                $timeSlotLengthSeconds = 60 * 60;
                break;
            case "minute":
                $date = "DATE_FORMAT(Timestamp, '%Y-%m-%d %H:%i:00')";
                $timeSlotLengthSeconds = 60;
                break;
            case "second":
                $date = "DATE_FORMAT(Timestamp, '%Y-%m-%d %H:%i:%s')";
                $timeSlotLengthSeconds = 1;
                break;
        }
                
        switch($this->DataSet)
        {
            // TODO: Can we set default where/group/etc so as to avoid repetition below?
            case 'sentiment-over-time':                
                $select = "SELECT $date AS Timestamp, ScreenName, SUM(SentimentScore) as SentimentScore";
                $from = "FROM ScoredTweets";
                $where = "WHERE ScreenName IN ($screenNames)";
                $group = "GROUP BY $date, ScreenName";                
                $order = "ORDER BY ScreenName";
                break;
            case 'overall-sentiment-by-user':
                $select = "SELECT $date AS Timestamp, ScreenName, SUM(CASE WHEN SentimentScore > 0 THEN 1 ELSE 0 END) AS Positive, -1 * SUM(CASE WHEN SentimentScore < 0 THEN 1 ELSE 0 END) AS Negative";
                $from = "FROM ScoredTweets";
                $where = "WHERE ScreenName IN ($screenNames)";
                $group = "GROUP BY $date, ScreenName";                
                $order = "ORDER BY ScreenName";
            break;
            default: die('Invalid DataSet');
        }
        
        // execute query
//echo($select . " " . $from . " " . $where . " " . $group . " " . $order . ";");
        $results = $this->db->query($select . " " . $from . " " . $where . " " . $group . " " . $order . ";");

        // calculate start and end point for time axis 
        $timeAxisResult = $this->db->query("SELECT MIN($date) AS min_time, MAX($date) AS max_time $from $where");       

        $timeAxis = $timeAxisResult->fetch_assoc();

        $timeAxisElapsed = strtotime($timeAxis["max_time"]) - strtotime($timeAxis["min_time"]); 

        $timeAxisPoints = 1;

        // total seconds in the difference, divided by size of the timeslot in seconds
        if($this->TimeSlot != 'month')
        {
            $timeAxisPoints =  $timeAxisElapsed > 0 ? $timeAxisElapsed / $timeSlotLengthSeconds : 1;
        }
        else
        {
            // Month is a special case as they are not all the same length
            $timeAxisPoints = $timeAxisElapsed; //->format("%m");
            // TODO: Need to work out how to calculate this from seconds - with a function presumably
            $timeAxisPoints = $timeAxisPoints == 0 ? 1 : $timeAxisPoints;
        }

//echo("<br>");
//echo("timeAxisPoints = $timeAxisPoints");

        // TODO: Calculate a set of all required points for plotting, to be filled in for each row (e.g. all the hours listed if 'hour')
        // Engine can then put in value, 0 or previous value depending on options

        // get results into expected format
        $objectResults = array();

        switch($this->DataSet)
        {
            case 'sentiment-over-time':                                
                // x, y, name
                $lastSN = '';
                $current = array();

                while($row = $results->fetch_assoc())
                {
                    if(count($current) == 0 || $row["ScreenName"] != $current["ScreenName"])
                    {
                        if(count($current) > 0 && strlen($current["ScreenName"]) > 0)
                        {
                            $objectResults[] = $current;                            
                        }
                        
                        $current = array();
                        $current["ScreenName"] = $row["ScreenName"];
                        $current["Values"] = array();
                    }

                    // at this point w eneed to turn timestamp into a scale rank from 1 to n where 1 is min time and n is max time and inc is timeslot based
                    //$pointInTime = date_diff(date_create($timeAxis["min_time"]), date_create($row["Timestamp"]));
                    $pointInTime = strtotime($row["Timestamp"]) - strtotime($timeAxis["min_time"]);

                    if($this->TimeSlot != 'month')
                    {
                        $pointInPoints = $pointInTime > 0 ? $pointInTime / $timeSlotLengthSeconds : 0;
                    }
                    else
                    {
                        // Month is a special case as they are not all the same length
                        $pointInMonths = $pointInTime->format("%m");
                        // TODO: we have ponit in seconds, so need to figure this out somehow
                        $pointInPoints = $pointInMonths == 0 ? 0 : $pointInMonths;
                    }

                    // TODO: Think we also need to output the min/max points for each axis here?

                    //var_dump($row);
                    //$values = array('x'=>$row["Timestamp"], 'y'=>$row["SentimentScore"]);
                    $values = array('x'=>$pointInPoints, 'y'=>(int)$row["SentimentScore"]);
                    $current["Values"][] = $values;
                }

                $objectResults[] = $current;
                
                $this->Results = $objectResults;
                break;
            case 'overall-sentiment-by-user':
                // do we have y0, y1 for positive + negative points, or do we just have x2 as many with duplicated x axis?
                // x, y, name

            // TODO: Get rid of ridiculously duplicated code
                $lastSN = '';
                $current = array();

                while($row = $results->fetch_assoc())
                {
                    if(count($current) == 0 || $row["ScreenName"] != $current["ScreenName"])
                    {
                        if(count($current) > 0 && strlen($current["ScreenName"]) > 0)
                        {
                            $objectResults[] = $current;                            
                        }
                        
                        $current = array();
                        $current["ScreenName"] = $row["ScreenName"];
                        $current["Values"] = array();
                    }

                    // at this point w eneed to turn timestamp into a scale rank from 1 to n where 1 is min time and n is max time and inc is timeslot based
                    //$pointInTime = date_diff(date_create($timeAxis["min_time"]), date_create($row["Timestamp"]));
                    $pointInTime = strtotime($row["Timestamp"]) - strtotime($timeAxis["min_time"]);

                    if($this->TimeSlot != 'month')
                    {
                        $pointInPoints = $pointInTime > 0 ? $pointInTime / $timeSlotLengthSeconds : 0;
                    }
                    else
                    {
                        // Month is a special case as they are not all the same length
                        $pointInMonths = $pointInTime->format("%m");
                        // TODO: we have ponit in seconds, so need to figure this out somehow
                        $pointInPoints = $pointInMonths == 0 ? 0 : $pointInMonths;
                    }

                    // TODO: Think we also need to output the min/max points for each axis here?

                    //var_dump($row);
                    //$values = array('x'=>$row["Timestamp"], 'y'=>$row["SentimentScore"]);
                    $values = array('x'=>$pointInPoints, 'y'=>(int)$row["Positive"], 'y1'=>(int)$row["Negative"]);
                    $current["Values"][] = $values;
                }

                $objectResults[] = $current;
                
                $this->Results = $objectResults;
            break;
        }

        // make sure all result sets are the same length - they should all go up to $timeAxisPoints

        for($i = 0; $i < count($objectResults); $i++)
        {
            $result = $objectResults[$i];

            $xAxisKeys = array_map("get_x_axis_keys", $result["Values"]);

            for($j = 1; $j <= $timeAxisPoints; $j++)
            {
                if(!in_array($j, $xAxisKeys))
                {
                    // TODO: If we set the default value to 0, it breaks the graphs (not sure why) - we may need to add 1 to all other values to compensate?
                    $result["Values"][] = array('x'=>$j, 'y'=>0, 'y1'=>0);
                }            
            }

            usort($result["Values"], 'sort_by_x_axis');

            $objectResults[$i] = $result;
        }

        // calculate the x-axis slot labels and add to first result
        $xAxis = array();

        if($this->TimeSlot != 'month')
        {
            $current = strtotime($timeAxis["min_time"]);
            while($current < strtotime($timeAxis["max_time"]))
            {
                $xAxis[] = date("Ymd His", $current);
                $current += $timeSlotLengthSeconds;

                // TODO: handle months
            }            
        }
        else
        {
            die('month not supported');
        }

        $objectResults[0]["Labels"] = $xAxis;

        $this->Results = $objectResults;
    }
}

function get_x_axis_keys($element)
{
    return $element["x"];
}

function sort_by_x_axis($a, $b)
{
    return $a["x"] - $b["x"];
}