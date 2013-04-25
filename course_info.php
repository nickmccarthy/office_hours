<?php
if(isset($_GET['cid'])){
	print("This course id is: " . $_GET['cid']);
}else{
	print("This does not have a class id!");
}
?>