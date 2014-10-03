<?php

include_once('inc/database.php');
include_once('inc/User.php');
include_once('inc/Engine.php');

header('Content-Type: application/json');

// get participants
$users = array();

if(strlen($_POST["group"]) > 0)
{
    switch($_POST["group"])
    {
        case "all":
            $users = User::All();
            break;
        case "most-frequent":
            $users = User::MostFrequent(3);
            break;
        case "most-mentioned":
            // TODO: Implement 'least'
            break;
    }
}
else
{
    // TODO: Implement 'list of users'
    // participants
}

// dates and or date range
$from = date('Y-m-d H:i:s', 0);
$to = date('Y-m-d H:i:s', time());

if($_POST["range"] == "true")
{
    $from = $_POST["from"];
    $to = $_POST["to"];
}

// TODO: Add toggle parameter for NLP vs Simple

// TODO: Add parameter to determine if participants are "mentioned" or "tweeting themselves"

$timeslot = $_POST["timeslots"];

$dataset = $_POST["dataset"];

// compute results
$engine = new Engine($users, $from, $to, $timeslot, $dataset);
$engine->Compute();

// use json_encode to output results
echo(json_encode($engine->Results));
die();
?>
