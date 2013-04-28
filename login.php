<?php
session_start();

// Import db config info
require 'config/mysql.config.php';
require 'config/pageinfo.config.php';

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

	$query = "
	SELECT hash, salt
	FROM Users
	WHERE email=\"$un\"";

	$result = $db->query($query);

	if ($result->num_rows == 1)
	{
		$info = $result->fetch_assoc();
		$salt = $info['salt'];
		$hash = $info['hash'];

		if (hash('sha256', $salt.$pw) == $hash)
		{
			$_SESSION['user'] = $un;
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
	<link href='http://fonts.googleapis.com/css?family=Acme' rel='stylesheet' type='text/css' />
	<link href='http://fonts.googleapis.com/css?family=Gudea' rel='stylesheet' type='text/css' />
</head>
<body id="tab1">
<?php
if(isset($_SESSION['user'])) {
	require 'inc/header_in.html';
} else {
	require 'inc/header.html';
}
?>
	<div class="center">
		<ul id="tabnav">
				<li class="tab1"><a href="login.php">Login</a></li>
				<li class="tab2"><a href="signup.php">Sign Up</a></li>
		</ul>
		<div class="tabarea">
			<form method="post" action="login.php">
				<div class="line">
					<label for="username">Email</label>
					<input type="text" name="username" id="username" placeholder="netID@cornell.edu"/>
				</div>
				<div class="line">
					<label for="password">Password</label>
					<input type="password" name="password" id="password" placeholder="password"/>
				</div>
				<div class="line">
					<label for="forgot">Forgot password?</label>
					<button type="submit">Login</button>
				</div>
			</form>
		</div>
	</div>
</body>
</html>