<?php
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 1);

include_once('../inc/database.php');
include_once('../inc/User.php');
include_once('../inc/Engine.php');

//var_dump($_POST);

echo("Starting...");

// get participants
$users = User::All();

$from = date('Y-m-d H:i:s', 0);
$to = date('Y-m-d H:i:s', time());
$timeslot = 'hour';
$dataset = 'sentiment-over-time';

echo("Calling Engine...");

// compute results
$engine = new Engine($users, $from, $to, $timeslot, $dataset);
$engine->Compute();

echo("<pre>");
var_dump($engine->Results);
echo("</pre>");
// use json_encode to output results

//var_dump($engine->Results);
echo(json_encode($engine->Results));
die("...finished");
?>
