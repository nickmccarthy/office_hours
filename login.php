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

$vun = true;
$vpw = true;
$un = '';
// Login from submitted
if (isset($_POST['username']) && isset($_POST["password"]))
{
	// escape input
	$un = trim(htmlentities($_POST['username']));
	$pw = trim(htmlentities($_POST["password"]));

	$vun = preg_match('/^[a-zA-Z]{2,3}[0-9]{1,4}@cornell.edu$/', $un);
	if ($vun)
	{
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
			else
			{
				$vpw = false;
			}
		}

		$db->close();
	}

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
	<link rel="stylesheet" type="text/css" href="styles/<?php echo strtolower(date('F'))?>.css">
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
			<form method="post" action="login.php">
				<div class="line">
					<label for="username">Email</label>
					<input type="text" name="username" id="username" placeholder="netID@cornell.edu"/>
					<label class="error"><?if(!$vun) print "Invalid username. Must be a cornell email.";?></label>
				</div>
				<div class="line">
					<label for="password">Password</label>
					<input type="password" name="password" id="password" placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;"/>
					<label class="error"><?if(!$vpw) print "Incorrect password.";?></label>
				</div>
				<div class="line">
					<button type="submit">Login</button>
				</div>
				<div class="line">
					<label id="new"><a href="signup.php">New user? Sign up!</a></label>
				</div>
			</form>
		</div>
	</div>
</body>
</html>