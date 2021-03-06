<?php
session_start();

if(isset($_GET['sub_email'])){
	$email = $_GET['sub_email'];
	$pattern = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/';
	if(preg_match($pattern,$email)){
		// Import db config info
		require 'config/mysql.config.php';
		require 'config/pageinfo.config.php';
		require 'queries/queries.php';

		$db = new mysqli($dbserver, $dbusername, $dbpassword, $dbname);
		$query = "SELECT * FROM Emails WHERE cid=" . $_GET['cid'] . " AND email = '" . $email . "'";
		$checkExists = $db->query($query);
		if($checkExists->num_rows == 0){
			$addquery = "INSERT INTO Emails (cid, email) VALUES (" . $_GET['cid'] . ", '" . $email ."')";
			$db->query($addquery);
			print('not there');
		}else{
			print('there');
		}
	}else{
		print('failed');
	}
}elseif(isset($_GET['position'])){
	$position = sanitize($_GET['position']);
	if(isset($position)){
		// Import db config info
		require 'config/mysql.config.php';
		require 'config/pageinfo.config.php';
		require 'queries/queries.php';

		$db = new mysqli($dbserver, $dbusername, $dbpassword, $dbname);
		$checkQuery = "SELECT uid, cid FROM Teaches WHERE uid=" . $_SESSION['user'] . " AND cid=" . $_GET['cid'];
		$checkExists = $db->query($checkQuery);
		if($checkExists->num_rows == 0){
			$addquery = "INSERT INTO Teaches (uid, cid, level) VALUES (" . $_SESSION['user'] . ", " . $_GET['cid'] . ", '" . $_GET['position']."')";
			$db->query($addquery);
			print('not there');
		}else{
			print('there');
		}
	}else{
		print('failed');
	}
}


function sanitize($rawterms){
	$rawterms = trim(htmlentities($rawterms));
	$pattern = '/(DROP TABLE | ALTER TABLE | INSERT INTO | <script>)/';
	if(preg_match($pattern, $rawterms) || $rawterms == ''){
		return NULL;
	}else{
		return $rawterms;
	}
}

?>