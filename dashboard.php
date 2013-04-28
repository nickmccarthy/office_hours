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
<?php
if(isset($_SESSION['user'])) {
	require 'inc/header_in.html';
        print '<body id="dash">';
} else {
	require 'inc/header.html';
        print '<body id="tab1">';
}
?>
<div class="content">
<p>dashboard page</p>
</div>

</body>
</html>