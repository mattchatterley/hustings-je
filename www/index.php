<!DOCTYPE html>
<?php

    include_once('inc/database.php');
    include_once('inc/User.php');
    include_once('inc/Stats.php');

    error_reporting(E_ALL);
    ini_set('display_errors', '1');

    date_default_timezone_set("GMT");

    $database = new Database();
?>
<html lang="en">
    <head>
        <meta charset="utf-8"/>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="Data.je">
        <link rel="icon" href="favicon.ico">
        <title>Hustings.je &raquo; Jersey Hustings data portal</title>

        <!-- jQuery -->
        <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
        <script src="assets/js/ie10-viewport-bug-workaround.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>

        <!-- jQuery UI -->
        <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.1/jquery-ui.min.js"></script>
        <link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.1/themes/smoothness/jquery-ui.css" />

        <!-- Chart.js -->
        <script src="assets/js/Chart.min.js"></script>
        <!--<script src="assets/js/Chart.js"></script>-->

        <!-- Local -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
        <link rel="stylesheet" href="assets/css/hustings-je.css" />
        <link rel="stylesheet" href="assets/css/style.css">
        <script src="assets/js/hustings-main.js"></script>
        <script src="assets/js/barchart.js"></script>
        <script src="assets/js/linechart.js"></script>
        <script src="assets/js/charthelper.js"></script>

        <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
            <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
            <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->

    </head>
    <body>
       <div class="site-wrapper">
            <div class="site-wrapper-inner">
            <div class="cover-container">
                <div class="masthead clearfix">
                        <div class="inner">
                                <ul class="nav masthead-nav">
                                    <li><h3><a href="faq.htm">FAQ</a></h3></li>
                                    <li><h3><a href="changelog.htm">TODO / Changed</a></h3></li>
                                </ul>
                                <h1 class="masthead-brand">Hustings.je</h1>
                                
                        </div>
                </div>
            </div>
            <div class="cover-container-light">
            <div class="row">
                <div class="col-md-12">
                    <h2>Hustings.je is an experiment in applying sentiment-analysis to social media during the Jersey 2014 elections.<br/>
                    What can we learn about candidates from their tweets, and tweets mentioning them?</h2>
                    
                
                    <p>This site is under continual development and if there is something in particular you'd like to see, please let us know.</p>
                </div>

            </div>

           <div class="row" style="background:#f8f5f8;">
                <div class="col-sm-12" style="text-align:center;">
                <?php
                    $stats = new Stats();
                ?>
                    <h3>So far hustings.je has analysed <span><?php echo($stats->TotalTweets); ?></span> tweets by <span><?php echo($stats->TotalParticipants); ?></span> people.</h3>
                    <h3>The 'simple' method shows <span><?php echo($stats->SimplePositive); ?>%</span> of tweets to be positive, <span><?php echo($stats->SimpleNeutral); ?>%</span> neutral and <span><?php echo($stats->SimpleNegative); ?>%</span> negative.</h3>
                    <h3>The 'NLP' method shows <span><?php echo($stats->NlpPositive); ?>%</span> of tweets to be positive, <span><?php echo($stats->NlpNeutral); ?>%</span> neutral and <span><?php echo($stats->NlpNegative); ?>%</span> negative.</h3>
                </div>
            </div>

        <?php
            if(!empty($_GET["debug"]))
            {
                ?>
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
            }
                // prepare data for parameters
                //$users = User::All();
            ?>

    <div class="row" style="margin-top:20px;background:#f8f5f8;">
        <div class="col-md-3">
            <fieldset>
                <legend>Participants</legend>
                <!--<input id="participant-everyone" class="participant-group" type="checkbox" value="all"/>Everyone-->
                <input id="participant-most-frequent" class="participant-group" type="checkbox" value="most-frequent" checked onchange="updateParameterFields();"/>Most Frequent Tweeters (for time selected)
                <!--<input id="participant-most-mentioned" class="participant-group" type="checkbox" value="least" />Most Mentioned-->
                <!-- TODO: Offer specific user choice
                <br />
                <?php
                    foreach($users as $user)
                    {
                        ?>
                            <input class="participant" type="checkbox" value="<?php echo($user->ScreenName);?>" /><?php echo($user->Name . ' (' . $user->ScreenName . ')');?>
                        <?php
                    }
                    ?>-->
            </fieldset>
        </div>
        <div class="col-md-6">
            <fieldset>
                <legend>Time</legend>
                <input id="time-all" name="time-type" type="radio" value="all"/>All
                <input id="time-range" name="time-type" type="radio" value="from" checked />Date Range
                <input id="date-from" type="text" class="datepicker" value="<?php echo(date("d/m/Y", time() - 60*60*24*7));?>"/> to <input id="date-to" type="text" class="datepicker" value="<?php echo(date("d/m/Y", time())); ?>"/>
            </fieldset>
        </div>
        <div class="col-md-3">
            <fieldset>
                <legend>Resolution</legend>
                <select id="time-slot" class="form-control">
                    <!--<option value="month">Month</option>-->
                    <!--<option value="week">Week</option>-->
                    <option value="day" selected>Day</option>
                    <!--<option value="hour">Hour</option>-->
                </select>
            </fieldset>
        </div>
    </div>
    <div class="row"  style="background:#f8f5f8;">
        <div class="col-md-3">
            <fieldset id="number-of-users">
                <legend>Number of Users</legend>
                <input id="number-users" name="number-users" value="3">Number of Tweeters
            </fieldset>
<!--            <fieldset>
                <legend>Type of Graph</legend>
                <input id="graph-type" name="graph-type" type="radio" value="streamgraph" checked/>Line
                <input id="graph-type" name="graph-type" type="radio" value="pie" />Pie
                <input id="graph-type" name="graph-type" type="radio" value="bars" />Bars
            </fieldset>-->
        </div>
        <div class="col-md-6">
            <fieldset>
                <legend>Data Set</legend>
                <select id="dataset" class="form-control">
                    <option value="overall-sentiment-by-user">Overall Sentiment by User</option>
                    <!--<option value="sentiment-over-time">Sentiment over Time</option>-->
                </select>
            </fieldset>
        </div>
        <div class="col-md-3">
            <fieldset>
                <legend>Ready?</legend>
                <button onclick="updateVisuals();" class="btn btn-primary form-control">Show Graph</button>
            </fieldset>
        </div>
    </div>
    <div class="row">
            <div class="col-xs-12">
                <p id="dataset-description"></p>
            </div>
            <div id="chart-container"></div>
            <div id="chart-placeholder-legend" class="col-md-4"></div>
    </div>
        <?php

            // TODO: Update mentions in real time (script from CR to go into crontab)
            // TODO: At the moment it's not using mentions, only the 'from' data and just shows the top 3, so I think first priority is to be able to use mentions (Or not) optionally - and also pick your users?

            // TODO: finish fixing 'week' and re-instate
            // TODO: Can we re-instate hourly by limiting dates to a single day?

            // TODO: One for the list, but lower priority than mentions or selecting tweeters: can we make it so you can drill down to the tweets that make up a particular data point?


            // TODO: RENAME 'Overall sentiment by user' to 'Total positive/negative by user'

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
<div class="row">
                <div class="col-md-12">
                    <h4>This is a <a href="http://www.techtribes.je" target="_blank">TechTribes.je</a> production, featuring:</h4>
                    <ul style="list-style:none; padding:0;">
                        <li>Data Monkey: <a href="http://twitter.com/charles_jsy" target="_blank">Charles Robertson</a></li>
                        <li>Web Monkey: <a href="http://twitter.com/mattchedit" target="_blank">Matt Chatterley </a></li>
                        <li>Graphics Monkey: <a href="http://twitter.com/bearpig" target="_blank">Robbie Andrews</a></li>
                    </ul>
                </div>
            </div>

</div>
            
            <div class="mastfoot">
                <div class="inner">
                    <div class="pull-right">
                    <ul class="social-buttons">
                    <li class="follow-btn">
                    <a href="https://twitter.com/hustingsje" class="twitter-follow-button" data-show-count="false" data-dnt="true">Follow @hustingsje</a>
                    <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
                    </li>
                    </ul>
                    </div>
                <p>&copy; Copyright 2014 <a href="http://www.hustings.je">hustings.je</a>, site by <a href="https://twitter.com/techtribesje">@techtribesje</a>.</p>
                </div>
            </div>

        </div>
    </div>
</div>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-54797669-1', 'auto');
  ga('send', 'pageview');

</script>
    </body>
</html>
