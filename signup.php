<?php
session_start();

	// Import db config info
require 'config/mysql.config.php';
require 'config/pageinfo.config.php';
require 'queries/queries.php';

if (isset($_POST['fname']) 
	&& isset($_POST['lname'])
	&& isset($_POST['username'])
	&& isset($_POST['password']))
{
	$fname = trim(htmlentities($_POST['fname']));
	$lname = trim(htmlentities($_POST['lname']));
	$email = trim(htmlentities($_POST['username']));
	$password = trim(htmlentities($_POST['password']));


	// CHECK user input here
	$valid = true;

	if ($valid)
	{
		$db = new mysqli($dbserver, $dbusername, $dbpassword, $dbname);

		if (user::exists($db, $email))
		{
			// Handle more gracefully
			print "User already exists";
		}
		else
		{
			$user = new user();
			$user->set_data($fname, $lname, $email, $password);
			$user->add($db);

			$_SESSION['user'] = $user->uid;
			header("Location: $dashboard");

		}
	}
}

?>


<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="styles/styles.css">
	<!-- style sheets will change depending on the month 
	<link rel="stylesheet" type="text/css" href="styles/<?php echo strtolower(date('F'))?>.css"> -->
	<!-- <link rel="stylesheet" type="text/css" href="styles/april.css">-->
	<link href='http://fonts.googleapis.com/css?family=Acme' rel='stylesheet' type='text/css' />
	<link href='http://fonts.googleapis.com/css?family=Gudea' rel='stylesheet' type='text/css' />
</head>
<body id="log">
	<?php
	if(isset($_SESSION['user'])) {
		require 'inc/header_in.html';
	} else {
		require 'inc/header.html';
	}
	?>
	<div class="content">
		<div class="center">
			<?php
				include 'inc/signup_form.php';
			?>
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