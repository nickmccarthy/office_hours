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

$db = new mysqli($dbserver, $dbusername, $dbpassword, $dbname);

if (isset($_POST['start_time'])
    && isset($_POST['end_time'])
    && isset($_POST['location']))
{
    for ($i = 0; $i < count($_POST['start_time']); $i++)
    {
        $st = trim(htmlentities($_POST['start_time'][$i]));
        $et = trim(htmlentities($_POST['end_time'][$i]));
        $loc = trim(htmlentities($_POST['location'][$i]));

        $ost = $_POST['orig_start_time'][$i];

        $del = false;
        if (isset($_POST['delete'])){
            foreach ($_POST['delete'] as $del_st) {
                $del = $del || ($ost == $del_st);
            }
        }
        if ($del)
        {
            $oh = new office_hours($cid, $uid, $date, $ost);
            $oh->delete($db);
        }
        elseif ($st != '' && $et != '' && $loc != '') // make sure all set
        {
            $oh;
            if ($ost == '')
            {
                $oh = new office_hours($cid, $uid, $date, $st);
                $oh->set_data($loc, $et);
                $oh->add($db, false);
            } else {
                $oh = new office_hours($cid, $uid, $date, $ost);
                $oh->lookup_data($db);

                if ($st != $ost || $et != $oh->end_time || $loc != $oh->location)
                {
                    $oldoh = new office_hours($cid, $uid, $date, $ost);
                    $oldoh->lookup_data($db);

                    $oh->set_data($loc, $et);
                    $oh->update($db, $st, $oldoh);
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
	<link rel="stylesheet" type="text/css" href="styles/<?php echo strtolower(date('F'))?>.css" />
    <link href='http://fonts.googleapis.com/css?family=Acme' rel='stylesheet' type='text/css' />
    <link href='http://fonts.googleapis.com/css?family=Gudea' rel='stylesheet' type='text/css' />
</head>
<body id="dash">
    <?php
    require 'inc/header_in.html';

    $course = new course($cid);
    $course->lookup_data($db);

    $hours = office_hours::find_hours_on_date($db, $uid, $cid, $date);
    ?>


    <div class="content">
        <h2>
            <span class="header1"><?php print $course->department_number(); ?> | Edit Office Hours: Single Edit</span>
            <span class="header2"><a href="edit_repeating_hours.php?cid=<?php print "$cid";?>">Edit Repeat Office Hours</a></span>
        </h2>
        <div class="center">
            <form method="get" action="edit_single_hours.php" required>
                <input type="hidden" name="cid" value="<?print $cid?>">
                <span class="edit_form">Choose Date: </span><input type="date" name="date" value="<?print $date?>">
                <button type="submit" class="date_btn">Go!</button>
            </form>
        </div>
        <div class="centeroh">
            <form method="post" action="edit_single_hours.php?<?php print "cid=$cid&date=$date"; ?>" required>
                <?
                if (count($hours) > 0)
                {
                    foreach ($hours as $oh)
                    {
                        format_oh($oh);
                    }
                }
                format_oh(false);
                print '<div class="lineoh"><button type="submit" class="oh_btn">Submit</button></div>';
                ?>
            </form>
        </div>
    </div>
</body>
</html>

<?php

function format_oh($oh)
{
    $st = $et = $loc = "";
    $del = "disabled";
    if ($oh)
    {
        $st = $oh->start_time;
        $et = $oh->end_time;
        $loc = $oh->location;
        $del = "";
    }
    print "<div class=\"lineoh\"><input type=\"time\" name=\"start_time[]\" value=\"$st\">";
    print "<span class=\"edit_form\"> to </span>";
    print " <input type=\"time\" name=\"end_time[]\" value=\"$et\">";
    print "<span class=\"edit_form\"> in </span><input type=\"textarea\" placeholder=\"Location\" rows=\"1\" cols=\"30\" name=\"location[]\" value=\"$loc\">";
    print "<span class=\"edit_form\"> Delete? </span><input type=\"checkbox\" name=\"delete[]\" $del value=\"$st\">";
    print '</div>'; // remove when formatting exists
    print "<input type=\"hidden\" name=\"orig_start_time[]\" value=\"$st\"></span>"; // keep track or original to change
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