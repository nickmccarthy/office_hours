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

function format_course($teaches)
{
    $class = $teaches->class;

    print '<div class="courses">';

    print '<span class="id">';
    print "<a href=\"course_info.php?cid=$class->cid\">$class->department $class->number | $teaches->level</a>";
    print '</span>';

    if ($teaches->level == 'Professor')
    {
        print '<span class="edit">';
        print "<a href=\"edit_permissions.php?cid=$class->cid\">Course Permissions</a>";
        print '</span>';
    }

    print '<span class="edit">';
    print "<a href=\"edit_single_hours.php?cid=$class->cid\">Edit Office Hours</a>";
    print '</span>';

    print '</div>';
}

?>
<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="styles/styles.css" />
    <!-- style sheets will change depending on the month -->
	<link rel="stylesheet" type="text/css" href="styles/<?php echo strtolower(date('F'))?>.css" />
    <link href='http://fonts.googleapis.com/css?family=Acme' rel='stylesheet' type='text/css' />
    <link href='http://fonts.googleapis.com/css?family=Gudea' rel='stylesheet' type='text/css' />
</head>
<body id="dash">
    <?php
    require 'inc/header_in.html';

    $db = new mysqli($dbserver, $dbusername, $dbpassword, $dbname);
    $user = new user($_SESSION['user']);
    $user->lookup_data($db);

    ?>
    <div class="content">
        <h2>Welcome, <?print $user->first_name; ?></h2>

        <?php
        foreach ($user->get_classes($db) as $class)
        {
            format_course($class);
        }

        ?>

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