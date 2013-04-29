<?php
session_start();

//function that displays the search results
function display_search_results($terms){

	// Import db config info
	require 'config/mysql.config.php';
	require 'config/pageinfo.config.php';

	$terms = return_clean($terms);

	$mydb = new mysqli($dbserver, $dbusername, $dbpassword, $dbname);
	if (mysqli_connect_errno()) {
    	printf("Connect failed: %s\n", mysqli_connect_error());
   		exit();
	}

	$query_class = "SELECT * FROM Class WHERE inactive = 0 AND name REGEXP '" . $terms ."' OR number REGEXP '" . $terms ."' OR department REGEXP '" . $terms . "'";
	$result_class = $mydb->query($query_class);

	$query_instructors = "SELECT * FROM Users WHERE first_name REGEXP '" . $terms ."' OR last_name REGEXP '" . $terms . "'";
	$result_instructors = $mydb->query($query_instructors);

	if ($result_class->num_rows == 1 && $result_instructors->num_rows == 0){
		$row = $result_class->fetch_assoc();
		header("Location: ".$class_page."?cid=" . $row['cid']);
	}elseif($result_class->num_rows == 0 && $result_instructors->num_rows == 1){
		$row = $result_instructors->fetch_assoc();
		header("Location: ".$instructor_page ."?uid=" . $row['uid']);
	}elseif($result_class->num_rows == 0 && $result_instructors->num_rows == 0){
		header("Location: " . $search_page . "?results=0");
	}else{
		if(isset($result_class)){
		print("<div>");
		while($array = $result_class->fetch_assoc()){
			print('<a href="course_info.php?cid=' . $array["cid"] . '" alt="' . $array['name'] . '">' . $array["name"] . '</a><br />');
		}
		print("</div>");
		}
		if(isset($result_instructor)){
		print("<div>");
		while($array = $result_instructor->fetch_assoc()){
			print('<a href="instructor_info.php?uid=' . $array['uid'] . '" alt="' . $array["first_name"] . ' ' . $array["last_name"] . '">' . $array['first_name'] . $array['last_name'] . '</a><br />');
		}
		print("</div>");
		}
	}
	$mydb->close();
}

//checks if the entered data is valid, returns blank if there are database terms
function return_clean($tocheck){
	$tocheck = trim(htmlentities($tocheck));
	if(preg_match("/SELECT|DROP TABLE|;|INSERT INTO/",$tocheck)){
		return '';
	}else{
		return $tocheck;
	}

}

?>

<!DOCTYPE html>
<head>
	<link rel="stylesheet" type="text/css" href="styles/styles.css">
    <!-- style sheets will change depending on the month -->
	<link rel="stylesheet" type="text/css" href="styles/april.css">
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
			print '<div class="content">';
			display_search_results($_POST['search_terms']);
		?>
<div class="courses">
        <span class="id"><a href="course_info.php">CourseID (position)</a></span>
        <?php
            if(isset($_SESSION['user'])) {
                print '<span class="edit"><a href="">Join Class</a></span>';
            } else {
				print '<span class="edit"><a href="search.php">Subscribe</a></span>';
			}
        ?>
</div>
<div class="courses">
        <span class="id"><a href="course_info.php">CourseID (position)</a></span>
        <?php
            if(isset($_SESSION['user'])) {
                print '<span class="edit"><a href="">Join Class</a></span>';
            } else {
				print '<span class="edit"><a href="search.php">Subscribe</a></span>';
			}
        ?>
</div>
<div class="courses">
        <span class="id"><a href="course_info.php">CourseID (position)</a></span>
        <?php
            if(isset($_SESSION['user'])) {
                print '<span class="edit"><a href="">Join Class</a></span>';
            } else {
				print '<span class="edit"><a href="search.php">Subscribe</a></span>';
			}
        ?>
</div>
</div>
</body>
</html>

<?php

// TODO:

// Other than above, print out a couple instructors associated with a course
// for each course result (users join teaches join class)

// or classes taught by an instructor/ ta for each person result
// (also users join teaches join class)


// if logged in, add a join class button for classes if permissions allow
// joining of that class (permissions table and check self enrollment)

// if not logged in, print a notification button
// on submission of the dialog associated with this notification button, 
// check and see if the supplied email is in class join emails and if not, 
// add it

?>
