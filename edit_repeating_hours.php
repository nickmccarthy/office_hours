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


?>
<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="styles/styles.css">
    <!-- style sheets will change depending on the month -->
	<!--<link rel="stylesheet" type="text/css" href="styles/april1.css">-->
    <link href='http://fonts.googleapis.com/css?family=Acme' rel='stylesheet' type='text/css' />
    <link href='http://fonts.googleapis.com/css?family=Gudea' rel='stylesheet' type='text/css' />
</head>
<body id="dash">
    <?php
    require 'inc/header_in.html';

    $db = new mysqli($dbserver, $dbusername, $dbpassword, $dbname);
    $course = new course($cid);
    $course->lookup_data($db);

    $hours = repeating_office_hours::find_repeating_hours($db, $uid, $cid);

    ?>
    <div class="content">
        <h2><? print $course->department_number(); ?> | Edit Office Hours</h2>
        <div class="center">
            <ul id="tabnav">
                <li class="tab2"><a href="edit_single_hours.php?cid=<?print $cid;?>">Edit Single Hours</a></li>
                <li class="tab1"><a href="#">Edit Repeating Hours</a></li>
                <li class="tab3"><a href="dashboard.php">Back to Dashboard</a></li>
            </ul>
            <div class="tabarea">
               <form method="post" action="addclass.php">
                <?
                if (count($hours) > 0)
                {
                    foreach ($hours as $oh) {
                        format_oh($oh);
                    }
                }
                format_oh(false);
                print '<input type="submit">';
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
    $days = array(
        "Sunday",
        "Monday",
        "Tuesday",
        "Wednesday",
        "Thursday",
        "Friday",
        "Saturday");

    $dow = $sd = $ed = $st = $et = $loc = "";
    if ($oh)
    {
        $dow = $oh->day_of_week;
        $sd = $oh->start_date;
        $ed = $oh->end_date;
        $st = $oh->start_time;
        $et = $oh->end_time;
        $loc = $oh->location;
    }

    print "Every ";

    print '<select name="day[]">';
    foreach ($days as $day) {
        $sel = ($day == $dow) ? 'selected="selected"' : '';
        print "<option value=\"$day\" $sel>$day</option>";
    }
    print '</select>';

    print " from ";
    print "<input type=\"date\" name=\"start_date[]\" value=\"$sd\">";
    print " to ";
    print "<input type=\"date\" name=\"end_date[]\" value=\"$ed\">";
    print " - ";
    print "<input type=\"time\" name=\"start_time[]\" value=\"$st\">";
    print " to ";
    print "<input type=\"time\" name=\"end_time[]\" value=\"$et\">";
    print " in ";
    // temporarily a textarea to not have text styling
    print "<input type=\"textarea\" rows=\"1\" cols=\"30\" name=\"location[]\" value=\"$loc\">";
    print '<br>'; // remove when formatting exists

 }

// TODO:

// Query the OfficeHours table for all office hours which are tagged as 
// repeating

// print out the information for each office hour for the chosen class
// as well as a delete button for that class

// On submit, check if any information has been changed. If so, send the
// notification message to all users who have signed up to be notified
// (so look in the emails table for this)

// send the user back to the dashboard

 ?>