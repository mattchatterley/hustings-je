<?php
    include_once('inc/database.php');
?>
<html>
    <head>
        <title>hustings.je prototype</title>
    </head>
    <body>
    <?php
        error_reporting(E_ALL);

        $database = new Database();

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
    ?>
    </body>
</html>