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
}else{
	print("This does not have a course number!");
}
?>
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