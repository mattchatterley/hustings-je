<?php

include_once('inc/database.php');
include_once('inc/User.php');
include_once('inc/Engine.php');

header('Content-Type: application/json');

//var_dump($_POST);


// get participants
$users = array();

if(strlen($_POST["group"]) > 0)
{
    switch($_POST["group"])
    {
        case "all":
            $users = User::All();
            break;
        case "most":
            // TODO: Implement 'most'
            break;
        case "least":
            // TODO: Implement 'least'
            break;
    }
}
else
{
    // TODO: Implement 'list of users'
    // participants
}

// TODO: Pick up from parameters if specified
$from = date('Y-m-d H:i:s', 0);
$to = date('Y-m-d H:i:s', time());

if($_POST["range"] == "true")
{
    // TODO: Implement date range (from, to)
}

// TODO: Implement timeslot size (timeslots) - do we need to do anything more complicated?
$timeslot = $_POST["timeslots"];

// TODO: HACK THIS TO DAY TEMPORARY
$timeslot = "hour";
/* Not sure we need this?
switch($_POST["dataset"])
{
    case "sentiment-over-time":
        // TODO: Implement sentiment-over-time (x=time, y=score)
        break;
    default:
        // TODO: Implement datasets (dataset)
        break;
}
*/
$dataset = $_POST["dataset"];

// compute results
$engine = new Engine($users, $from, $to, $timeslot, $dataset);
$engine->Compute();

//var_dump($engine->Results);
// use json_encode to output results

//var_dump($engine->Results);
echo(json_encode($engine->Results));
die();
?>
