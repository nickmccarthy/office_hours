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
</head>
<?php
    if(isset($_SESSION['user'])) {
            require 'inc/header_in.html';
            print '<body id="dash">';
    } else {
            require 'inc/header.html';
            print '<body id="search_out">';
    }
?>
<div class="content">
    <h2>Course ID | Settings</h2>
    <p><a href="dashboard.php">Back to Dashboard</a></p>
    <form action="edit_permissions.php?cid=<?php print($_GET['cid']); ?>" method="post">
        Toggle Inactive: <input type="checkbox" name="inactive" value="1"><br />
        Change Course Name: <input type="text" name="coursename"><br />
        Drop any of the following from being an instructor:<br />
<?php
    require 'config/mysql.config.php';
    require 'config/pageinfo.config.php';
    $mydb = new mysqli($dbserver, $dbusername, $dbpassword, $dbname);
    if (mysqli_connect_errno()) {
        printf("Connect failed: %s\n", mysqli_connect_error());
        exit();
    }
    $query = "SELECT first_name, last_name, uid FROM Teaches NATURAL JOIN Users WHERE cid=" . $_GET['cid'];
    $teachers = $mydb->query($query);
    while($row = $teachers->fetch_assoc()){
        print("<label for " . $row['uid'] . "><input type='checkbox' name='teachers[]' value='" . $row['uid'] . "'>" . $row['first_name'] . " " . $row['last_name'] . "<br />");
    }
?> 
        <input type="submit" value="Submit">
    </form>
</div>

</body>
</html>


<?php
    require 'config/mysql.config.php';
    require 'config/pageinfo.config.php';
    $mydb = new mysqli($dbserver, $dbusername, $dbpassword, $dbname);
    if (mysqli_connect_errno()) {
        printf("Connect failed: %s\n", mysqli_connect_error());
        exit();
    }
    $re = false;
if(isset($_POST['inactive'])){
    $checkquery = "SELECT inactive FROM Class WHERE cid=" . $_GET['cid'];
    $check = $mydb->query($checkquery);
    $result_row = $check->fetch_assoc();
    if($result_row['inactive'] == 0){
        $change=1;
    }else{
        $change=0;
    }
    $query = "UPDATE Class SET inactive = " . $change . " WHERE cid=" . $_GET['cid'];
    $mydb->query($query);
    $re=true;
}
if(isset($_POST['coursename'])){
    $query = "UPDATE Class SET name ='" . $_POST['coursename'] . "' WHERE cid=" . $_GET['cid'];
    $mydb->query($query);
    $re=true;
}
if(isset($_POST['teachers'])){
    foreach($_POST['teachers'] as $t){
        $query = "DELETE FROM Teaches WHERE uid=" . $t . " AND cid=" . $_GET['cid'];
        $mydb->query($query);
        $re=true;
    }
}

if($re){
    header("Location: $dashboard");
}

// TODO:

// query users join teaches join class with uid to get permission level

// query class join permissions and to get all of the permissions
// print the name of that permission and a pair of checkboxes. Also look up the current
// permission settings for that class and use to set default values for the checkboxes.

// on form submission, set the value of the permissions in the DB with those
// values from the checkboxes. May be the same as before but doesn't matter.

?>