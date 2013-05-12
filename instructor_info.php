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
	<link rel="stylesheet" type="text/css" href="styles/<?php echo strtolower(date('F'))?>.css">
	<link href='http://fonts.googleapis.com/css?family=Acme' rel='stylesheet' type='text/css' />
	<link href='http://fonts.googleapis.com/css?family=Gudea' rel='stylesheet' type='text/css' />
</head>
<?php
    if(isset($_SESSION['user'])) {
            require 'inc/header_in.html';
            print '<body id="search_in">';
    } else {
            require 'inc/header.html';
            print '<body id="search_out">';
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
		print("<div class=\"classlist\">
			  <div class=\"title\"><h3><a href='course_info.php?cid=" . $first_row['cid'] . "' alt = '" . $first_row['name'] . "'>" . $first_row['department'] . " " . $first_row['number'] . ": " . $first_row['name'] . "</a></h3></div>");
		$teachesquery = "SELECT * FROM Teaches WHERE cid=" . $first_row['cid'] . " AND uid=" . $_GET['uid'];
		$teaches = $mydb->query($teachesquery);
		if($teaches->num_rows > 0){
			printOfficeHours($mydb, $first_row, $_GET['uid']);
		}
		print("</div>");
		while($next_row = $result->fetch_assoc()){
			print("<div class=\"classlist\">
				  <div class=\"title\"><h3><a href='course_info.php?cid=" . $next_row['cid'] . "' alt = '" . $next_row['name'] . "'>" . $next_row['department'] . " " . $next_row['number'] . ": " . $next_row['name'] . "</a></h3></div>");
			$teachesquery = "SELECT * FROM Teaches WHERE cid=" . $first_row['cid'] . " AND uid=" . $_GET['uid'];
			$teaches = $mydb->query($teachesquery);
			if($teaches->num_rows > 0){
				printOfficeHours($mydb, $next_row, $_GET['uid']);
			}
			print("</div>");
		}
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

function printOfficeHours($mydb, $row, $uid){
	$firstDayOfWeek = date("Y-m-d");
	$firstDayOfNextWeek = date("Y-m-d", strtotime("+1 week"));
	$ohquery = "SELECT * FROM (OfficeHours LEFT JOIN Repeating ON OfficeHours.repeat_tag=Repeating.repeat_tag) NATURAL JOIN Users WHERE uid =" . $uid . " AND cid=" . $row['cid'] . " AND((date <='" . $firstDayOfNextWeek . "' AND date >='" . $firstDayOfWeek . "') OR (Repeating.repeat_tag IS NOT NULL AND start_date <= '" . $firstDayOfWeek . "' AND end_date >= '" . $firstDayOfWeek . "'))";
	$ohs = $mydb->query($ohquery);
	while($array = $ohs->fetch_assoc()){
		if(isset($array['start_date'])){
				$myDateTime = DateTime::createFromFormat('Y-m-d', $array['date']);
				$dayOfWeek = $myDateTime->format('N');
				$currentDayOfWeek = date("N");
				$difference = abs($dayOfWeek-$currentDayOfWeek);
				$day = date("F jS", strtotime("+".$difference." day"));
			}else{
				$myDateTime = DateTime::createFromFormat('Y-m-d', $array['date']);
				$day = $myDateTime->format('F jS');
			}
		print("<div class=\"officehours\">
			  <span class=\"ohlist\">Day: " . $day . "</span>
			  <span class=\"ohlist\">Start Time: " . $array['start_time'] . "</span>
			  <span class=\"ohlist\">Location: " . $array['location'] . "</span></div>");
	}
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