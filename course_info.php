<?php
	session_start();

	// Import db config info
	require 'config/mysql.config.php';
	require 'config/pageinfo.config.php';

?>
<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="styles/styles.css" />
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
if(isset($_GET['cid'])){
	print("This cid is: " . $_GET['cid']);
}else{
	print("This does not have a cid!");
}
?>
<h2>Course ID | Course Name</h2>
</div>
</body>
</html>
