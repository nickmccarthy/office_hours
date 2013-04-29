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
        print '<body id="settings">';
} else {
	require 'inc/header.html';
        print '<body id="tab1">';
}
?>
<div class="content">
<p>this is the settings page</p>
</div>

</body>
</html>




<?php

// TODO:

// check that old password matches the one currently in the db for this user
// if so, and if the new pass word and confirmation are the same, add
// a new salt and the hash of this password in the db, replacing the old ones

// redirect the user to the dashboard
?>