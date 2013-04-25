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
</head>
<body>
	<div id="login">
		<form method="post" action="login.php">
			<table class="form_table">
				<tr>
					<td colspan="2">			
						<h1>Welcome! Please log in to continue.</h1>
					</td>
				</tr>
				<tr>
					<td class="label">
						<label for="username">Username</label>
					</td>
					<td class="input">
						<input type="text" name="username" id="username"/>
					</td>
				</tr>
				<tr>
					<td class="label">
						<label for="password">Password</label>
					</td>
					<td class="input">
						<input type="password" name="password" id="password"/>
					</td>
				</tr>
				<tr>
					<td>
					</td>
					<td class="input">			
						<button type="submit">Login</button>
					</td>
				</tr>
			</table>
		</form>
	</div>
</body>
</html>