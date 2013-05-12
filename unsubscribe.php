<?php

require 'config/mysql.config.php';
require 'config/pageinfo.config.php';
require 'queries/queries.php';

$cid;
$db = new mysqli($dbserver, $dbusername, $dbpassword, $dbname);

if (isset($_POST['cid']) && isset($_POST['email']))
{
	$cid = $_POST['cid'];
	$email = $_POST['email'];

	$query = "
	DELETE FROM Emails
	WHERE cid=\"$cid\"
	AND email=\"$email\"";

	$db->query($query);
} else
{
	header("Location: $search_page");
}

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
<body>
	<?php
	if(isset($_SESSION['user'])) {
		require 'inc/header_in.html';
	} else {
		require 'inc/header_home.html';
	}

	$class = new course($cid);
	$class->lookup_data($db);

	print "<h2>You have been successfully unsubscribed from emails for $class->department $class->number.</h2>"
	?>


</body>
</html>


