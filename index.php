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
	<!--<link rel="stylesheet" type="text/css" href="styles/april1.css">-->
	<link href='http://fonts.googleapis.com/css?family=Acme' rel='stylesheet' type='text/css' />
	<link href='http://fonts.googleapis.com/css?family=Gudea' rel='stylesheet' type='text/css' />
	<script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
	<script type="text/javascript" scr="../scripts/subscribe.js"></script>
</head>
<body>
<?php
if(isset($_SESSION['user'])) {
	require 'inc/header_in.html';
} else {
	require 'inc/header_home.html';
}
?>
<div class="center">
		<form action="search.php" method="GET">
			<input type="text" name="search_terms" id="search_terms" placeholder="Search courses, people, etc."/>
		</form>
			<?php
				if(isset($_GET['results'])){
					print("<p>No results found</p>");
				}
				if(isset($_POST['email'])){
					
				}
			?>
</div>

</body>
</html>