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

if (isset($_POST['npassword'])
    &&isset($_POST['cpassword']) 
    && isset($_POST['password']))
{
    $newpw = trim(htmlentities($_POST['npassword']));
    $confirm = trim(htmlentities($_POST['cpassword']));
    $oldpw = trim(htmlentities($_POST['password']));

    // PHONE NUMBER IGNORED BECAUSE NO PLACE IN DB

    if ($newpw == $confirm)
    {
        $db = new mysqli($dbserver, $dbusername, $dbpassword, $dbname);

        $user = new user($_SESSION['user']);
        $user->lookup_data($db);

        if ($user->check_and_set_password($db, $oldpw, $newpw))
        {
            header("Location: $dashboard");
        }
    }
}


?>
<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="styles/styles.css">
    <!-- style sheets will change depending on the month -->
	<link rel="stylesheet" type="text/css" href="styles/<?php echo strtolower(date('F'))?>.css">
        <link rel="stylesheet" type="text/css" href="styles/march.css" />
    <link rel="stylesheet" type="text/css" href="styles/february.css" />
    <link rel="stylesheet" type="text/css" href="styles/january.css" />
    <link rel="stylesheet" type="text/css" href="styles/december.css" /> 
    <link rel="stylesheet" type="text/css" href="styles/november.css" /> 
    <link rel="stylesheet" type="text/css" href="styles/october.css" /> 
    <link rel="stylesheet" type="text/css" href="styles/september.css" /> 
    <link rel="stylesheet" type="text/css" href="styles/august.css" /> 
    <link rel="stylesheet" type="text/css" href="styles/july.css" /> 
    <link href='http://fonts.googleapis.com/css?family=Acme' rel='stylesheet' type='text/css' />
    <link href='http://fonts.googleapis.com/css?family=Gudea' rel='stylesheet' type='text/css' />
</head>
<body id="settings">
    <?php
    require 'inc/header_in.html';

    ?>
    <div class="content">
        <h2>Account Settings</h2>
        <div class="center">
           <?php
           include 'inc/settings_form.php';
           ?>
    </div>
</div>

</body>
</html>




<?php

// TODO:

// check that old password matches the one currently in the db for this user
// if so, and if the new pass word and confirmation are the same, add
// a new salt and the hash of this password in the db, replacing the old ones

// redirect the user to the dashboard
?>