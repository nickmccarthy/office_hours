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


	//initial queries for page information
	$query_class = "SELECT * FROM Class WHERE inactive = 0 AND (name REGEXP '" . $terms ."' OR number REGEXP '" . $terms ."' OR department REGEXP '" . $terms . "')";
	$result_class = $mydb->query($query_class);

	$query_instructors = "SELECT * FROM Users WHERE first_name REGEXP '" . $terms ."' OR last_name REGEXP '" . $terms . "'";
	$result_instructors = $mydb->query($query_instructors);

	if ($result_class->num_rows == 1 && $result_instructors->num_rows == 0){
		$row = $result_class->fetch_assoc();
		header("Location: ". $class_page."?cid=" . $row['cid']);
	}elseif($result_class->num_rows == 0 && $result_instructors->num_rows == 1){
		$row = $result_instructors->fetch_assoc();
		header("Location: ". $instructor_page ."?uid=" . $row['uid']);
	}elseif($result_class->num_rows == 0 && $result_instructors->num_rows == 0){
		header("Location: " . $search_page . "?results=0");
	}else{
		if(isset($result_class)){
			while($array = $result_class->fetch_assoc()){

				//query the database for the instructors of the current course
				$query = "SELECT first_name, last_name FROM Users NATURAL JOIN Teaches NATURAL JOIN Class WHERE cid=" . $array['cid'];
				$result = $mydb->query($query);
				$I1 = $result->fetch_assoc();
				$I1name = '';
				$I2name = '';
				if(isset($I1)){
					$I1name = 'Instructors: ' . $I1['first_name'] . ' ' . $I1['last_name'];
					$I2 = $result->fetch_assoc();
					if(isset($I2)){
						$I2name = ', ' . $I2['first_name'] . ' ' . $I2['last_name'];
					}
				}

				//print the courses
				print("<div class='courses'> \n");
				print('<span class="id"><a href="course_info.php?cid=' . $array["cid"] . '" alt = "' . $array["name"] . '">' . $array["name"] . '</a></span>');
				print('<span class="id">' . $I1name . ' ' . $I2name . '</span>');
				print ("<div class='sub_courses'>");
				if(isset($_SESSION['user'])){
					print('<span class="join_btn"><button alt="Join" id ="' . $array["cid"] . '">Join Class</button></span>');
					print('<span class="cid">' . $array['cid'] . '</span>');
					print("<div class='join_class' id='" . $array['cid'] ."'>
						<button type='submit' class='join_submit'>Go!</button>
						<input type='text' name='position' id='position' placeholder='Your Position (TA, Grader, Professor)'>
						</div>");
				}
					print('<span class="sub_btn"><button alt="Subscribe" id="' . $array["cid"] .'">Subscribe</button></span>');
				print('<span class="cid">' . $array['cid'] . '</span>');
				print("<div class='subscribe' id='" . $array['cid'] ."'>
						<button type='submit' class='sub_submit'>Go!</button>
						<input type='text' name='email' id='email' placeholder='netID@cornell.edu'>
					</div>");
				
				print('</div>
					  </div>');
				
			}
		}
		if(isset($result_instructors)){
			while($array = $result_instructors->fetch_assoc()){

				//query the database for the courses this instructor teaches
				$query = 'SELECT department, name, number FROM Users NATURAL JOIN Teaches NATURAL JOIN Class WHERE inactive = 0 ORDER BY cid DESC';
				$result = $mydb->query($query);
				$C1 = $result->fetch_assoc();
				$C1name = '';
				$C2name = '';
				if(isset($C1)){
					$C1name = "Classes: " . $C1['department'] . " " . $C1['number'] . ": " . $C1['name'];
					$C2 = $result->fetch_assoc();
					if(isset($C2)){
						$C2name = " " . $C2['department'] . " " . $C2['number'] . ": " . $C2['name'];
					}
				}

				//print instructors
				print('<div class="courses">');
				print('<span class="id"><a href="instructor_info.php?uid=' . $array["uid"] . '" alt="' . $array["first_name"] . ' ' . $array["last_name"] . '">' . $array["first_name"] . ' ' . $array["last_name"] . '</a></span>');
				print('</div>');
			}
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
		print '<div class="content">';
		display_search_results($_GET['search_terms']);
		?>
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
