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
	<link rel="stylesheet" type="text/css" href="styles/april.css">
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
        <ul id="tabnav">
	    <li class="tab1"><a href="addclass.php">Add Class</a></li>
	    <li class="tab2"><a href="dashboard.php">Back to Dashboard</a></li>
	</ul>
	<div class="tabarea">
	    <form method="post" action="addclass.php">
            <div class="line">
                <label for="cid">Course ID</label>
                <input type="text" name="cid" id="cid" placeholder="Department ####"/>
            </div>
            <div class="line">
                <label for="cname">Course Name</label>
                <input type="text" name="cname" id="cname" placeholder="Course Name"/>
            </div>
            <div class="line">
                <label for="instructor">Instructor</label>
                <input type="text" name="instructor" id="instructor" placeholder="First Name, Last Name"/>
            </div>
            <div class="line">
                <label for="csemester">Semester</label>
                <input type="text" name="csemester" id="csemester" placeholder="Semester (S/F, Year)"/>
            </div>
            <div class="line">
                <label for="terms"></label>
                <button type="submit">Add Class</button>
            </div>
	    </form>
	</div>
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