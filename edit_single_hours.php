<?php
session_start();

// Import db config info
require 'config/mysql.config.php';
require 'config/pageinfo.config.php';
require 'queries/queries.php';

if (!isset($_SESSION['user']))
{
    header("Location: $login_page");
}
$uid = $_SESSION['user'];

if (!isset($_GET['cid']))
{   
    header("Location: $dashboard");
}
$cid = htmlentities($_GET['cid']);
if (!preg_match('/^[0-9]+$/', $cid))
{
    header("Location: $dashboard");
}

$date = date("Y-m-d");
if (isset($_GET['date']))
{
    // check date
    $date = $_GET['date'];
}


?>
<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="styles/styles.css">
    <!-- style sheets will change depending on the month -->
    <link rel="stylesheet" type="text/css" href="styles/april.css">
    <link href='http://fonts.googleapis.com/css?family=Acme' rel='stylesheet' type='text/css' />
    <link href='http://fonts.googleapis.com/css?family=Gudea' rel='stylesheet' type='text/css' />
</head>
<body id="dash">
    <?php
    require 'inc/header_in.html';

    $db = new mysqli($dbserver, $dbusername, $dbpassword, $dbname);
    $course = new course($cid);
    $course->lookup_data($db);

    $hours = office_hours::find_hours_on_date($db, $uid, $cid, $date);
    ?>


    <div class="content">
        <h2><?php print $course->department." ".$course->number; ?> | Edit Office Hours</h2>
        <div class="center">
            <ul id="tabnav">
                <li class="tab1"><a href="edit_single_hours.php">Edit Single Hours</a></li>
                <li class="tab2"><a href="edit_repeating_hours.php?cid=<?print $cid;?>">Edit Repeating Hours</a></li>
                <li class="tab3"><a href="dashboard.php">Back to Dashboard</a></li>
            </ul>
            <div class="tabarea">
                <form method="get" action="edit_single_hours.php">
                    <input type="date" name="date" value="<?print $date?>">
                    <input type="hidden" name="cid" value="<?print $cid?>">
                    <input type="submit">
                </form>
                <form method="post" action="edit_single_hours.php?cid=<?print $cid;?>">
                    <?
                    if (count($hours) > 0)
                    {
                        foreach ($hours as $oh)
                        {
                            format_oh($oh);
                        }
                        print '<input type="submit">';
                    }
                    else
                    {
                        print "No office hours on this date!";
                    }
                    ?>
                </form>
            </div>
        </div>
    </div>
</body>
</html>

<?php

function format_oh($oh)
{
    print "<input type=\"time\" name=\"start_time[]\" value=\"$oh->start_time\">";
    print " to ";
    print "<input type=\"time\" name=\"end_time[]\" value=\"$oh->end_time\">";
    print " in ";
    print "<input type=\"text\" name=\"location[]\" value=\"$oh->location\">";
}

// TODO:

// Query the OfficeHours table with the date chosen by the date picker
// and the uid

// print out the information for each office hour for the chosen class
// as well as a delete button for that class

// On submit, check if any information has been changed. If so, send the
// notification message to all users who have signed up to be notified
// (so look in the emails table for this)

// send the user back to the dashboard

?>