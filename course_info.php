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
if(isset($_GET['course_number'])){
	print("This course number is: " . $_GET['course_number']);

if(isset($_GET['cid'])){
	print("This cid is: " . $_GET['cid']);
}else{
	print("This does not have a cid!");
}
?>
<H2>Course ID | Course Name</H2>
</div>
</body>
</html>

<?php

//TODO:

// query class table and print associated data (course id, course name, meeting
// time, instructor(s))

// query for the office hours associated with that class (users join teaches join
// class join officeHours), select those that occur in the next week

// list the office hours, sorted by day and then by time

?>