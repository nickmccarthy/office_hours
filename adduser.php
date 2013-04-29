<?php

require 'config/mysql.config.php';

// CHANGE THESE

$fname = 'Tingting';
$lname = 'Wu';
$pw = 'test';
$email = 'tw283@cornell.edu';

// END

$salt = md5(uniqid(rand(), true));
$hash = hash('sha256', $salt.$pw);

$db = new mysqli(
	$dbserver,
	$dbusername,
	$dbpassword,
	$dbname);

$query = "
INSERT INTO Users(first_name, last_name, email, hash, salt)
VALUES (\"$fname\",\"$lname\",\"$email\",\"$hash\",\"$salt\")";

$result = $db->query($query);
print $db->errno;

?>




<?php

// TODO:

// This is not part of the site, just a way of adding us to the users db
// before signup is complete

?>