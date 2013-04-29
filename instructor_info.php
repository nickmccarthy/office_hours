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
if(isset($_GET['uid'])){
	print("This instructor's id is: " . $_GET['uid']);
}else{
	print("This does not have an instructor id!");
}
?>
<h2>First Name, Last Name</h2>

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