<?php

include_once('inc/database.php');
include_once('inc/User.php');
include_once('inc/Engine.php');

header('Content-Type: application/json');

// get participants
$users = array();

// dates and or date range
$from = date('Y-m-d H:i:s', 0);
$to = date('Y-m-d H:i:s', time());

if($_POST["range"] == "true")
{
    $from = DateTime::createFromFormat('d/m/Y', $_POST["from"]);;
    $from = $from->format('Y-m-d 00:00:00');

    $to = DateTime::createFromFormat('d/m/Y', $_POST["to"]);;
    $to = $to->format('Y-m-d 23:59:59');

}

// number of users
$numberOfUsers = intval($_POST["userlimit"]);

if($numberOfUsers <= 0)
{
    $numberOfUsers = -1;
}

// load user group

if(strlen($_POST["group"]) > 0)
{
    switch($_POST["group"])
    {
        case "all":
            $users = User::All();
            break;
        case "most-frequent":
            $users = User::MostFrequent($numberOfUsers, $from, $to);
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
