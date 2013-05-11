<?php

$rawterms = $_GET['terms'];

$terms = sanitize($rawterms);

if(isset($terms)){

	// Import db config info
	require 'config/mysql.config.php';
	require 'config/pageinfo.config.php';
	$mydb = new mysqli($dbserver, $dbusername, $dbpassword, $dbname);
	if (mysqli_connect_errno()) {
	   	printf("Connect failed: %s\n", mysqli_connect_error());
	  	exit();
	}

	$classquery = "SELECT name, number, department FROM Class WHERE name REGEXP '" . $terms . "' OR number REGEXP '" . $terms . "' OR department REGEXP '" . $terms . "'";
	$instructorquery = "SELECT first_name, last_name FROM  Users WHERE first_name REGEXP '" . $terms ."' OR last_name REGEXP '" . $terms . "'";
	$class_result = $mydb->query($classquery);
	$instructor_result = $mydb->query($instructorquery);
	$array = array();
	if($class_result->num_rows != 0){
	while($row = $class_result->fetch_assoc()){
		$array[] = $row['department'] . $row['number'] . " " . $row['name'];
	}
	}
	if($instructor_result->num_rows != 0){
	while($row = $instructor_result->fetch_assoc()){
		$array[] = $row['first_name'] . " " . $row['last_name'];
	}
	}
	print_r($classquery);
}else{
	print("");
}
// TODO:

// Query the users and class tables with the supplied post parameters
// (the current search string)
// if there are any mathes, echo them formatted as json

// this will be added to a drop down on the search window
// that part done through js

function sanitize($rawterms){
	$rawterms = trim(htmlentities($rawterms));
	$pattern = '/[DROP TABLE]|[ALTER TABLE]|[INSERT INTO]|[<script>]/';
	if(preg_match($pattern, $rawterms) || $rawterms == ''){
		return NULL;
	}else{
		return $rawterms;
	}
}

?>