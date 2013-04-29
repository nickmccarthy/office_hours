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
    <!-- style sheets will change depending on the month -->
	<link rel="stylesheet" type="text/css" href="styles/april.css">
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
    <h2>Course ID | Settings</h2>
    <p><a href="dashboard.php">Back to Dashboard</a></p>
</div>

</body>
</html>


<?php

// TODO:

// query users join teaches join class with uid to get permission level

// query class join permissions and to get all of the permissions
// print the name of that permission and a pair of checkboxes. Also look up the current
// permission settings for that class and use to set default values for the checkboxes.

// on form submission, set the value of the permissions in the DB with those
// values from the checkboxes. May be the same as before but doesn't matter.

?>