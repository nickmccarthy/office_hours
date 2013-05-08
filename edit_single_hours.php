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
    <link rel="stylesheet" type="text/css" href="styles/april.css">
    <link href='http://fonts.googleapis.com/css?family=Acme' rel='stylesheet' type='text/css' />
    <link href='http://fonts.googleapis.com/css?family=Gudea' rel='stylesheet' type='text/css' />
</head>
<body id="dash">
    <?php
    require 'inc/header_in.html';

    ?>
    <div class="content">
        <h2>Course ID | Edit Office Hours</h2>
        <div class="center">
            <ul id="tabnav">
                <li class="tab1"><a href="edit_single_hours.php">Edit Single Hours</a></li>
                <li class="tab2"><a href="edit_repeating_hours.php">Edit Repeating Hours</a></li>
                <li class="tab3"><a href="dashboard.php">Back to Dashboard</a></li>
            </ul>
            <div class="tabarea">
                <form method="post" action="addclass.php">
                </form>
            </div>
        </div>
    </div>
</body>
</html>

<?php

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