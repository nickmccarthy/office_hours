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
    <h2>Welcome, First Name</h2>
    <div class="courses">
        <span class="id"><a href="course_info.php">CourseID (position)</a></span>
        <?php
            if(isset($_SESSION['user'])) {
                print '<span class="edit"><a href="edit_permissions.php">Course Permissions</a></span>';
            }
        ?>
        <span class="edit"><a href="edit_single_hours.php">Edit Office Hours</a></span>
        
    </div>
    <div class="courses">
        <span class="id"><a href="course_info.php">CourseID (position)</a></span>
        <?php
            if(isset($_SESSION['user'])) {
                print '<span class="edit"><a href="edit_permissions.php">Course Permissions</a></span>';
            }
        ?>        <span class="edit"><a href="edit_single_hours.php">Edit Office Hours</a></span>
    </div>
    <div class="courses">
        <span class="id"><a href="course_info.php">CourseID (position)</a></span>
        <?php
            if(isset($_SESSION['user'])) {
                print '<span class="edit"><a href="edit_permissions.php">Course Permissions</a></span>';
            }
        ?>
        <span class="edit"><a href="edit_single_hours.php">Edit Office Hours</a></span>
    </div>
    <div class="courses">
        <span class="edit"><a href="addclass.php">Add Class</a></span>
    </div>
</div>

</body>
</html>

<?php

// TODO:

// query users with uid in session to get name

// query users join teaches join class with their uid
// for each, print a line with
// Department . Number . (Position)

// For each of these, check permissions this user has
// use level from previous query and see what permissions that level has
// in class join permissions

// print office hours link for each and also, if have permission to change
// course permissions, print a link for that too

?>