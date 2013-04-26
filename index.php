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
	require 'inc/header.html';
}
?>
<div id="search_home">
	<form action="search_results.php" method="POST">
		<label for="Search">Search</label>
		<input type="text" name="search_terms" id="search_terms"/>
		<?php
			if(isset($_GET['results'])){
				print("No results found");
			}
		?>
	</form>
</div>

</body>
</html>