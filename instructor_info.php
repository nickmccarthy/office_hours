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
	<!--<link rel="stylesheet" type="text/css" href="styles/april1.css">-->
	<link href='http://fonts.googleapis.com/css?family=Acme' rel='stylesheet' type='text/css' />
	<link href='http://fonts.googleapis.com/css?family=Gudea' rel='stylesheet' type='text/css' />
</head>
<body id="search">
<?php
if(isset($_SESSION['user'])) {
	require 'inc/header_in.html';
} else {
	require 'inc/header.html';
}
?>
<div class="content">
<?php
if(isset($_GET['uid'])){
	$mydb = new mysqli($dbserver, $dbusername, $dbpassword, $dbname);
	if (mysqli_connect_errno()) {
    	printf("Connect failed: %s\n", mysqli_connect_error());
   		exit();
	}
	//get the classes this instructor teaches
	$query = "SELECT first_name, last_name, level, department, number, name, semester, inactive, cid FROM Users NATURAL JOIN Teaches NATURAL JOIN Class WHERE inactive = 0 AND uid=" . $_GET['uid'];
	$result = $mydb->query($query);
	$first_row = $result->fetch_assoc();
	if(isset($first_row)){
		print("<h2>" . $first_row['first_name'] . " " . $first_row['last_name'] . "</h2>");
		print("<a href='course_info.php?cid=" . $first_row['cid'] . "' alt = '" . $first_row['name'] . "'>" . $first_row['department'] . " " . $first_row['number'] . ": " . $first_row['name'] . "</a>");
	}else{
		$queryname = "SELECT first_name, last_name FROM Users WHERE uid=" . $_GET['uid'];
		$r = $mydb->query($queryname);
		$name = $r->fetch_assoc();
		print("<h2>" . $name['first_name'] . " " . $name['last_name'] . "</h2>");
	}
	$mydb->close();
}else{
	print("This does not have an instructor id!");
}
?>

</div>
</body>
</html>



<?php

// TODO:

// Query users join teaches join class for the supplied uid (can only get here
// if the query had a unique match or the user chose a result, meaning this is 
// known)

// Print the name of this person as well as each of their classes and associated
// office hours (officeHours table)

?>