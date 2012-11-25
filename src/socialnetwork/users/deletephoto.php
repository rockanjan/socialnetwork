<?php
session_start();
if(! isset($_SESSION['uid'])) {
	echo "You must login to perform the action"; 
	exit();
}

include_once "../database_open.php";
$photoid = $_GET['photoid'];
//query to delete the photo
$result = pg_exec($dbconn, "delete from photo where photoid='$photoid'");
if($result) {
	echo "photo was successfully deleted.";
} else {
	echo "there was problem in deleting the photo.";
}
include_once "../database_close.php";
?>