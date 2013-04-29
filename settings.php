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
    <div class="center">
        <ul id="tabnav">
	    <li class="tab1"><a href="addclass.php">Account Settings</a></li>
	</ul>
	<div class="tabarea">
	    <form method="post" action="settings.php">
            <div class="line">
                <label for="npassword">New Password</label>
                <input type="password" name="npassword" id="npassword" placeholder="New Password"/>
            </div>
            <div class="line">
                <label for="cpassword">Confirm Password</label>
                <input type="password" name="cpassword" id="cpassword" placeholder="Confirm Password"/>
            </div>
            <div class="line">
                <label for="password">Old Password</label>
                <input type="password" name="password" id="password" placeholder="Old Password"/>
            </div>
            <div class="line">
                <label for="phone">Phone Number</label>
                <input type="tel" name="phone" id="phone" placeholder="(###) ###-#### (Optional)"/>
            </div>
            <div class="line">
                <label for="terms"></label>
                <button type="submit">Save Changes</button>
            </div>
	    </form>
	</div>
    </div>
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