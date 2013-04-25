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

	$query_class = "SELECT * FROM Class WHERE name REGEXP '" . $terms ."' OR number REGEXP '" . $terms ."' OR department REGEXP '" . $terms . "'";
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
		print("<div>");
		while($array = $result_class->fetch_assoc()){
			print('<a href="' . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"] . '?cid=' . $array["cid"] . '" alt="' . $array['name'] . '">' . $array["name"] . '</a>');
		}
		print("</div>");
		print("<div>");
		while($array = $result_instructor->fetch_assoc()){
			print('<a href="' . $_SERVER[HTTP_HOST] . $_SERVER[REQUEST_URI] . '?uid=' . $array['uid'] . '" alt="' . $array["first_name"] . ' ' . $array["last_name"] . '">' . $array['first_name'] . $array['last_name'] . '</a>');
		}
		print("</div>");
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
</head>
<body>

<?php
	display_search_results($_POST['search_terms']);
?>

</body>
</html>