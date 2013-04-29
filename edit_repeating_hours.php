<?php
	session_start();

	// Import db config info
	require 'config/mysql.config.php';
	require 'config/pageinfo.config.php';

?>
<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="styles/styles.css">
	<link href='http://fonts.googleapis.com/css?family=Acme' rel='stylesheet' type='text/css' />
	<link href='http://fonts.googleapis.com/css?family=Gudea' rel='stylesheet' type='text/css' />
</head>
<?php
    if(isset($_SESSION['user'])) {
            require 'inc/header_in.html';
            print '<body id="dash">';
    } else {
            require 'inc/header.html';
            print '<body id="tab1">';
    }
?>
<div class="content">
    <h2>Course ID | Edit Office Hours</h2>
    <div class="center">
        <ul id="tabnav">
            <li class="tab2"><a href="edit_single_hours.php">Edit Single Hours</a></li>
            <li class="tab1"><a href="edit_repeating_hours.php">Edit Repeating Hours</a></li>
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

// Query the OfficeHours table for all office hours which are tagged as 
// repeating

// print out the information for each office hour for the chosen class
// as well as a delete button for that class

// On submit, check if any information has been changed. If so, send the
// notification message to all users who have signed up to be notified
// (so look in the emails table for this)

// send the user back to the dashboard

?>