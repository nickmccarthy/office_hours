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
	<link rel="stylesheet" type="text/css" href="styles/april.css" />
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

	$mydb = new mysqli($dbserver, $dbusername, $dbpassword, $dbname);
	if (mysqli_connect_errno()) {
    	printf("Connect failed: %s\n", mysqli_connect_error());
   		exit();
	}
	$query = "SELECT * FROM Class WHERE cid=" . $_GET['cid'];
	$result = $mydb->query($query);
	$row = $result->fetch_assoc();

	print("<h2>" . $row['department'] . " " . $row['number'] . "|" . $row['name'] . "</h2>");

	$year = date("Y");
	$week = date("W");
	$firstDayOfWeek = strtotime($year."W".str_pad($week,2,"0",STR_PAD_LEFT));
	print("<div>Office Hours Week of " . date("F jS",$firstDayOfWeek));
	print("<br />" . $firstDayOfWeek);
	print("<br />". $week);
	print("<br />". $year);
	$firstDayOfNextWeek = strtotime($year."W".str_pad($week+1,2,"0",STR_PAD_LEFT));
//SELECT * FROM 'OfficeHours' NATURAL JOIN Repeating WHERE cid = 1 AND (() OR ())
	$ohQuery = "SELECT * FROM OfficeHours WHERE cid=" . $_GET['cid'] . " AND (date > ". $firstDayOfNextWeek . " AND date < " . $firstDayOfWeek . ") ";
	$ohResult = $mydb->query($ohQuery);
	if(isset($ohResult)){
		while($array = $ohResult->fetch_assoc()){
			print("<div>" . $array['date'] . " " . $array['location'] . " " . "</div>");
		}
	}
}else{
	print("<h2>No Course Found</h2>");
}
?>
</div>
</body>
</html>
