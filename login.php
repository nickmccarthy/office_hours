<?php
session_start();

// Import db config info
require 'config/mysql.config.php';
require 'config/pageinfo.config.php';
require 'queries/queries.php';

// Logout request was sent
if (isset($_GET['logout']))
{
	unset($_SESSION['user']);
}

// Login from submitted
if (isset($_POST['username']) && isset($_POST["password"]))
{
	// escape input
	$un = trim(htmlentities($_POST['username']));
	$pw = trim(htmlentities($_POST["password"]));

	$db = new mysqli(
		$dbserver,
		$dbusername,
		$dbpassword,
		$dbname);

	if ($user = user::exists($db, $un))
	{
		if ($user->check_password($pw))
		{
			$_SESSION['user'] = $user->uid;
		}
	}

	$db->close();

	// successful login or going to this page not logged out
	if (isset($_SESSION['user']))
	{
		header("Location: $dashboard");
	}
}

// Need to log in
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
				include 'inc/login_form.php';
			?>
		</div>
	</div>
</body>
</html>