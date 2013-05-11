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

$db = new mysqli($dbserver, $dbusername, $dbpassword, $dbname);

if (isset($_POST['start_date'])
    && isset($_POST['end_date'])
    && isset($_POST['start_time'])
    && isset($_POST['end_time'])
    && isset($_POST['location']))
{
    for ($i = 0; $i < count($_POST['repeat_tag']); $i++)
    {
        $day = $_POST['day'][$i];
        $sd = trim(htmlentities($_POST['start_date'][$i]));
        $ed = trim(htmlentities($_POST['end_date'][$i]));
        $st = trim(htmlentities($_POST['start_time'][$i]));
        $et = trim(htmlentities($_POST['end_time'][$i]));
        $loc = trim(htmlentities($_POST['location'][$i]));
        
        $rt = $_POST['repeat_tag'][$i];
        $roh = new repeating_office_hours($rt);

        $del = false;
        if (isset($_POST['delete'])){
            foreach ($_POST['delete'] as $del_rt) {
                $del = $del || ($rt == $del_rt);
            }
        }
        if ($del)
        {
            $roh->delete($db);
        }
        elseif ($day != '' && $sd != '' && $ed != '' && $st != '' && $et != '' && $loc != '') // make sure all set
        {

            if ($rt == -1)
            {
                $roh->set_data($sd, $ed, $st, $et, $day, $loc, $uid, $cid);
                $roh->add($db);
                office_hours::add_repeating($db, $roh);
            }
            else
            {

                $roh->lookup_data($db);
                if ($day != $roh->day_of_week
                    || $sd != $roh->start_date
                    || $ed != $roh->end_date
                    || $st != $roh->start_time
                    || $et != $roh->end_time
                    || $loc != $roh->location)
                {
                    $roh->set_data($sd, $ed, $st, $et, $day, $loc, $uid, $cid);
                    $roh->update($db);
                    office_hours::delete_repeating($db, $rt);
                    office_hours::add_repeating($db, $roh);
                }
            }
        }
    }
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

    $course = new course($cid);
    $course->lookup_data($db);

    $hours = repeating_office_hours::find_repeating_hours($db, $uid, $cid);

    ?>
    <div class="content">
        <h2><? print $course->department_number(); ?> | Edit Office Hours</h2>
        <div class="center">
           <form method="post" action="edit_repeating_hours.php?cid=<?print $cid?>">
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
    $del = "disabled";
    $rt = -1;
    if ($oh)
    {
        $dow = $oh->day_of_week;
        $sd = $oh->start_date;
        $ed = $oh->end_date;
        $st = $oh->start_time;
        $et = $oh->end_time;
        $loc = $oh->location;
        $rt = $oh->repeat_tag;
        $del = "";
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
    print " Del: <input type=\"checkbox\" name=\"delete[]\" $del value=\"$rt\">";
    print '<br>'; // remove when formatting exists

    print "<input type=\"hidden\" name=\"repeat_tag[]\" value=\"$rt\">"; // keep track or original to change
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