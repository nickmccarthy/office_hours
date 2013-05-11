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
            print '<body id="dash">';
    } else {
            require 'inc/header.html';
            print '<body id="tab1">';
    }
?>
<div class="content">
    <div class="center">
        <?php
        include 'inc/addclass_form.php';
        ?>
    </div>
</div>

</body>
</html>


<?php

// TODO:

// Check that the course id and semester are unique in the classes db.
// If so, create a new class with the supplied information

// direct the user to the edit repeating hours page for this course

?>