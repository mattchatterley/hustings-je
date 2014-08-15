<?php
    include_once('inc/database.php');
  
    error_reporting(E_ALL);

    $database = new Database();
?>
<html>
    <head>
        <title>hustings.je prototype</title>

        <!-- jQuery -->
        <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>

        <!-- jQuery UI -->
        <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.1/jquery-ui.min.js"></script>    
        <link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.1/themes/smoothness/jquery-ui.css" />

        <!-- D3js -->
        <script src="http://d3js.org/d3.v3.min.js" charset="utf-8"></script>
    </head>
    <body>
        <h1>Table of data</h1>
        <?php
            $result = $database->QueryAssoc("select * from ScoredTweets");

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
                    <td>
                        <?php echo($field->name); ?>
                    </td>
                <?php
            }
            ?>
                </tr>
            </table>
            <?php

            $rows = $result->fetch_assoc();
            foreach($rows as $row)
            {
                foreach($fields as $field)
                {
                    ?>
                        <td>
                            <?php echo($row[$field->name]); ?>
                        </td>
                    <?php
                }
            }

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

            // TODO: UI - Type of graph
            // TODO: UI - users (all, top n, individuals)
            // TODO: UI - Time (All, Date Range)
            // TODO: UI - Slots - Month, Week, Day, Hour, Minute
            // TODO: UI - Dataset choice (as above)

            // TODO: rankings - most active (all/topN)
            // TODO: rankings - most positive (all/topN) - own tweets or tweets about?
            // TODO: rankings - most negative (all/topN) - own tweets or tweets about?

            // TODO: Track followers by point in time (1hr slots?)
            // TODO: Include mentioning in schema e.g. tweet mentions users x y z

            /* General thoughts
             * 
             * Flexible graph which has dimensions such as time and sentiment and allows you to add in datasets or users?
             * 
             */
        ?>
    </body>
</html>