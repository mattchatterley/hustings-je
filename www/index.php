<!DOCTYPE html>
<?php
    include_once('inc/database.php');
    include_once('inc/User.php');
  
    error_reporting(E_ALL);

    $database = new Database();
?>
<html lang="en">
    <head>
        <meta charset="utf-8"/>
        <title>hustings.je prototype</title>

        <!-- jQuery -->
        <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>

        <!-- jQuery UI -->
        <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.1/jquery-ui.min.js"></script>    
        <link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.1/themes/smoothness/jquery-ui.css" />

        <!-- D3js -->
        <script src="http://d3js.org/d3.v3.min.js" charset="utf-8"></script>
        
        <!-- Local -->
        <link rel="stylesheet" href="assets/css/hustings-je.css" />
        <script src="assets/js/hustings-main.js"></script>
        <script src="assets/js/streamgraph.js"></script>
        <script src="assets/js/stackedbars.js"></script>
    </head>
    <body>
        <h1>hustings.je prototypes</h1>
        <h2>Table of data (latest 10 tweets)</h2>
        <?php
            $result = $database->Query("SELECT * from ScoredTweets ORDER BY Timestamp DESC LIMIT 10");

            ?>
            <h4><?php echo($result->num_rows); ?> rows found...</h4>
            <?php

            $fields = $result->fetch_fields();

            ?>
            <table>
                <tr>
            <?php
            foreach($fields as $field)
            {
                ?>
                    <th>
                        <?php echo($field->name); ?>
                    </th>
                <?php
            }
            ?>
                </tr>
            <?php

            while($row = $result->fetch_assoc())
            {
                ?>
                <tr>
                <?php
                foreach($fields as $field)
                {
                    ?>
                        <td>
                            <?php echo(htmlspecialchars($row[$field->name])); ?>
                        </td>
                    <?php
                }
                ?>
                </tr>
                <?php
            }
        ?>
            </table>
            
            <?php
                // prepare data for parameters
                $users = User::All();
            ?>
            <h2>Configurable graph(s)</h2>
            <fieldset>
                <legend>Type of Graph</legend>
                <input id="graph-type" name="graph-type" type="radio" value="streamgraph" checked/>StreamGraph
                <input id="graph-type" name="graph-type" type="radio" value="pie" />Pie
                <input id="graph-type" name="graph-type" type="radio" value="bars" />Bars
            </fieldset>
            <fieldset>
                <legend>Participants</legend>
                <input id="participant-everyone" class="participant-group" type="checkbox" value="all" checked/>Everyone
                <input id="participant-most-popular" class="participant-group" type="checkbox" value="most" />Most Popular
                <input id="participant-least-popular" class="participant-group" type="checkbox" value="least" />Least Popular
                <br />
                <br />
                <?php
                    foreach($users as $user)
                    {
                        ?>
                            <input class="participant" type="checkbox" value="<?php echo($user->ScreenName);?>" /><?php echo($user->Name . ' (' . $user->ScreenName . ')');?>
                        <?php
                    }
                    ?>
            </fieldset>
            <fieldset>
                <legend>Time</legend>
                <input id="time-all" type="radio" value="all" checked/>All
                <input id="time-range" type="radio" value="from" />Date Range
                <input id="date-from" type="text" class="datepicker"/> to <input id="date-to" type="text" class="datepicker"/>
            </fieldset>
            <fieldset>
                <legend>Resolution</legend>
                <select id="time-slot">
                    <option value="month">Month</option>
                    <option value="week">Week</option>
                    <option value="day">Day</option>
                    <option value="hour">Hour</option>
                </select>
            </fieldset>
            <fieldset>
                <legend>Data Set</legend>
                <select id="dataset">
                    <option value="sentiment-over-time">Sentiment over Time</option>
                </select>
            </fieldset>
            <fieldset>
                <legend>Debugging</legend>
                <button onclick="updateVisuals();">Update</button>
            </fieldset>
            <div id="d3-placeholder"></div>
        <?php

            // TODO: UI - Validation

            // TODO: When a user group (e.g. everyone, etc) is picked, uncheck all others
            // TODO: When an individual user is checked, remove all groups
        
            // TODO: Populate all datasets

            // TODO: Line of user tweets/time, with sentiment (flow)
            // TODO: tweets/time with sentiment, average + total
            // TODO: Overall user sentiment (sum) over time
            // TODO: Overall user sentiment (sum) over time - average over all users
            // TODO: Sentiment over time - positive/negative total at time points
            // TODO: User activity against time (num of tweets in time slice)
            // TODO: User activity against time, num of tweets in slice, total + average
            // TODO: Overall feeling about User X (based on tweets mentioning them)
            // TODO: Breakdown of tweet share in time period
            // TODO: Candidate basic profile - link to vote.je, draw in hansard and voting data?

            // TODO: rankings - most active (all/topN)
            // TODO: rankings - most positive (all/topN) - own tweets or tweets about?
            // TODO: rankings - most negative (all/topN) - own tweets or tweets about?

            // TODO: Track followers by point in time (1hr slots?)
            // TODO: Include mentioning in schema e.g. tweet mentions users x y z

            // TODO: Ability to record events e.g. hustings?
        
            /* General thoughts
             * 
             * Flexible graph which has dimensions such as time and sentiment and allows you to add in datasets or users?
             * 
             */
        ?>
    </body>
</html>