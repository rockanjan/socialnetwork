<?php
$page_title = "Register";
$current_page = "home";
include_once('../header.php');
?>
<h2> User landing page</h2>
<?php echo "Hello, " . $_SESSION['uname']; ?>
<p>
<a href="logout.php">logout</a>
</p>
<?php 
include_once('../footer.php');
?>