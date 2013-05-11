<?php
session_start();

	// Import db config info
require 'config/mysql.config.php';
require 'config/pageinfo.config.php';
require 'queries/queries.php';

if (!isset($_SESSION['user']))
{
    header("Location: $login_page");
}
$uid = $_SESSION['user'];

$db = new mysqli($dbserver, $dbusername, $dbpassword, $dbname);

if (isset($_POST['cid'])
    && isset($_POST['cname'])
    && isset($_POST['csemester'])
    && isset($_POST['cyear']))
{
    $cid = trim(htmlentities($_POST['cid']));
    $cname = trim(htmlentities($_POST['cname']));
    $csemester = trim(htmlentities($_POST['csemester']));
    $cyear = trim(htmlentities($_POST['cyear']));

    $vcnum = preg_match('/^(?<dept>[a-zA-Z]+) (?<number>[0-9]{4})$/', $cid, $matches);
    $vcname = preg_match('/^[a-zA-Z0-9 ]+$/', $cname);
    $vsem = preg_match('/^(SP|FA)$/', $csemester);
    $vyear = preg_match('/^20(?<year>[0-9]{2})+$/', $cyear, $matches2);

    if ($vcnum && $vcname && $vsem && $vyear)
    {
        $class = new course();
        $class->set_data($cname, $matches['number'], $matches['dept'], $csemester.$matches2['year'], 0);
        $class->add($db);

        $t = new teaches($uid, $class->cid);
        $t->add($db);

        header("Location: $dashboard");

    }
}
?>
<!DOCTYPE html>
<html>
<head>
 <link rel="stylesheet" type="text/css" href="styles/styles.css">
 <link rel="stylesheet" type="text/css" href="styles/<?php echo strtolower(date('F'))?>.css" />

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