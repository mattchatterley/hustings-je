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
    </head>
    <body>
        <h1>hustings.je prototypes</h1>
        <h2>Table of data</h2>
        <?php
            $result = $database->Query("select * from ScoredTweets");

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
                <input type="radio" value="pie" />Pie
                <input type="radio" value="stream" />Stream
                <input type="radio" value="bars" />Bars
            </fieldset>
            <fieldset>
                <legend>Participants</legend>
                <input type="checkbox" value="all" />Everyone
                <input type="checkbox" value="all" />Most Popular
                <input type="checkbox" value="all" />Least Popular
                <br />
                <br />
                <?php
                    foreach($users as $user)
                    {
                        ?>
                            <input type="checkbox" value="<?php echo($user->ScreenName);?>" /><?php echo($user->Name . ' (' . $user->ScreenName . ')');?>
                        <?php
                    }
                    ?>
            </fieldset>
            <fieldset>
                <legend>Time</legend>
                <input type="radio" value="all" />All
                <input type="radio" value="from" />Date Range
                <input type="text" /> to <input type="text" />
            </fieldset>
            <fieldset>
                <legend>Resolution</legend>
                <select>
                    <option>Month</option>
                    <option>Week</option>
                    <option>Day</option>
                    <option>Hour</option>
                </select>
            </fieldset>
            <fieldset>
                <legend>Data Set</legend>
                <select>
                    <!-- TODO: populate this as they become available -->
                    <option>Sentiment over Time</option>
                </select>
            </fieldset>
            <fieldset>
                <legend>Debugging</legend>
                <button>Update</button>
            </fieldset>
        <?php
            // TODO: UI - Type of graph
            // TODO: UI - users (all, top n, individuals)
            // TODO: Query -get list of users
            // TODO: UI - Time (All, Date Range)
            // TODO: UI - Slots - Month, Week, Day, Hour
            // TODO: UI - Dataset choice (as above)

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