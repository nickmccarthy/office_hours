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
<body id="tab2">
<?php
if(isset($_SESSION['user'])) {
	require 'inc/header_in.html';
} else {
	require 'inc/header.html';
}
?>
<div class="content">
	<div class="center">
		<ul id="tabnav">
				<li class="tab1"><a href="login.php">Login</a></li>
				<li class="tab2"><a href="signup.php">Sign Up</a></li>
		</ul>
		<div class="tabarea">
			<form method="post" action="login.php">
                             <div class="line">
					<label for="fname">First Name</label>
					<input type="text" name="fname" id="fname" placeholder="First Name"/>
				</div>
				<div class="line">
					<label for="lname">Last Name</label>
					<input type="password" name="lname" id="lname" placeholder="Last Name"/>
				</div>
				<div class="line">
					<label for="username">Email</label>
					<input type="text" name="username" id="username" placeholder="netID@cornell.edu"/>
				</div>
				<div class="line">
					<label for="password">Password</label>
					<input type="password" name="password" id="password" placeholder="password"/>
				</div>
				<div class="line">
					<label for="terms"></label>
					<button type="submit">Sign Up</button>
				</div>
			</form>
		</div>
	</div>
</div>
</body>
</html>





<?php

// TODO: 

//Check that the supplied email is a cornell email, and has
// not been used by a different user. (query users db and check EXISTS)

// If unique, add this person's supplied info (first and last name, email,
// password to the users db and redirect them to their dashboard)


?>