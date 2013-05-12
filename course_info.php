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
	<link rel="stylesheet" type="text/css" href="styles/<?php echo strtolower(date('F'))?>.css">
	<link href='http://fonts.googleapis.com/css?family=Acme' rel='stylesheet' type='text/css' />
	<link href='http://fonts.googleapis.com/css?family=Gudea' rel='stylesheet' type='text/css' />
	<script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
	<script type="text/javascript" src="scripts/subscribe.js"></script>
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
if(isset($_GET['cid'])){

	$mydb = new mysqli($dbserver, $dbusername, $dbpassword, $dbname);
	if (mysqli_connect_errno()) {
    	printf("Connect failed: %s\n", mysqli_connect_error());
   		exit();
	}
	$query = "SELECT * FROM Class WHERE cid=" . $_GET['cid'];
	$result = $mydb->query($query);
	$row = $result->fetch_assoc();

	print("<h2>" . $row['department'] . " " . $row['number'] . " | " . $row['name'] . "</h2>");
	print ("<div class='sub_courses'>");
	print('<span class="sub_btn"><button id="' . $_GET["cid"] .'">Subscribe</button></span>');
	print('<span class="cid">' . $_GET['cid'] . '</span>');
	print("<div class='subscribe' id='" . $_GET['cid'] ."'>
				<button type='submit' class='sub_submit'>Go!</button>
				<input type='text' name='email' id='email' placeholder='netID@cornell.edu'>
			</div>
			</div>");

	$firstDayOfWeek = date("Y-m-d");
	$firstDayOfNextWeek = date("Y-m-d", strtotime("+1 week"));
	$firstForDisplay = date("F jS");
	print("<div><h3>Office Hours for the week following " . $firstForDisplay . "</h3></div>");

	$query = "SELECT * FROM (OfficeHours LEFT JOIN Repeating ON OfficeHours.repeat_tag=Repeating.repeat_tag) NATURAL JOIN Users WHERE cid=" . $_GET['cid'] . " AND((date <='" . $firstDayOfNextWeek . "' AND date >='" . $firstDayOfWeek . "') OR (Repeating.repeat_tag IS NOT NULL AND start_date <= '" . $firstDayOfWeek . "' AND end_date >= '" . $firstDayOfWeek . "'))";
	$ohResult = $mydb->query($query);

	if(isset($ohResult)){
		while($array = $ohResult->fetch_assoc()){
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
			print("<div class=\"courses\">
				  <span class=\"id\">Instructor: <a href='instructor_info.php?uid=" . $array['uid'] . "''>" . $array['first_name'] . " " . $array['last_name'] . "</a></span>
				  <span class=\"edit\">Day: " . $day ."</span>
				  <span class=\"id\">Starts: " . $array['start_time'] . " Ends: " . $array['end_time'] . "</span>
				  <span class=\"edit\">Location: " . $array['location'] . "</span>
				  </div>");
		}
	}
}else{
	print("<h2>No Course Found</h2>");
}
?>
</div>
</body>
</html>
