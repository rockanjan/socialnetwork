<?php
$page_title = "Register";
$current_page = "home";
include_once('../header.php');
if(! isset($_SESSION['uid'])) {
	$_SESSION['err'] = "Login or Register to start";
	header("Location: /sn/index.php");
	exit();
}
?>

<h3> Feeds </h3>
<?php 
include_once('../footer.php');
?>