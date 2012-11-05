<?php
$page_title = "Welcome";
$current_page = "home";
include_once('header.php');
?>

<h3>Welcome</h3>
Welcome to <?php echo $sitename; ?>

<p> Login </p>
 <a href="users/login.php">Login</a> 
<p> Register Yourself</p>
<a href="users/register.php">Register</a>

<?php 
include_once('../footer.php');
?>