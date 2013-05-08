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
    <link rel="stylesheet" type="text/css" href="styles/april.css">
    <link href='http://fonts.googleapis.com/css?family=Acme' rel='stylesheet' type='text/css' />
    <link href='http://fonts.googleapis.com/css?family=Gudea' rel='stylesheet' type='text/css' />
</head>
<body id="settings">
    <?php
    require 'inc/header_in.html';

    ?>
    <div class="content">
        <div class="center">
            <ul id="tabnav">
             <li class="tab1"><a href="addclass.php">Account Settings</a></li>
         </ul>
         <div class="tabarea">
             <form method="post" action="settings.php">
                <div class="line">
                    <label for="npassword">New Password</label>
                    <input type="password" name="npassword" id="npassword" placeholder="New Password"/>
                </div>
                <div class="line">
                    <label for="cpassword">Confirm Password</label>
                    <input type="password" name="cpassword" id="cpassword" placeholder="Confirm Password"/>
                </div>
                <div class="line">
                    <label for="password">Old Password</label>
                    <input type="password" name="password" id="password" placeholder="Old Password"/>
                </div>
                <div class="line">
                    <label for="phone">Phone Number</label>
                    <input type="tel" name="phone" id="phone" placeholder="(###) ###-#### (Optional)"/>
                </div>
                <div class="line">
                    <label for="terms"></label>
                    <button type="submit">Save Changes</button>
                </div>
            </form>
        </div>
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