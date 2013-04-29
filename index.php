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
	<link href='http://fonts.googleapis.com/css?family=Acme' rel='stylesheet' type='text/css' />
	<link href='http://fonts.googleapis.com/css?family=Gudea' rel='stylesheet' type='text/css' />
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
		<form action="search.php" method="POST">
			<input type="text" name="search_terms" id="search_terms" placeholder="Search courses, people, etc."/>
		</form>
			<?php
				if(isset($_GET['results'])){
					print("<p>No results found</p>");
				}
			?>
</div>

</body>
</html>