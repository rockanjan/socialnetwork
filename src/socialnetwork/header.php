<?php 
include_once 'database_open.php';
$sitename = "Social Networking Using Image Content";
$base = "/sn";
session_start();
?>
<html>
<head>
	<title>Social Network : <?php echo $page_title; ?></title>
	<link rel="stylesheet" type="text/css" href="<?php echo $base?>/styles/style.css">
</head>
<body>
<h2 id="sitename"><?php echo $sitename?></h2>
<div id="wrapper">  
	<div id="header">  
  	<ul id="navigation">  
    	<li class="home"><a <?php if ($current_page == "home") { ?>class="selected"<?php } ?> href="<?php echo $base?>">Home</a></li>
    </ul>  
  </div>
</div>
<hr>

<div id="message"> 
	<p>
		<?php 
			if( isset($_SESSION['message']) ) {
				echo $_SESSION['message'];
				unset($_SESSION['message']);
			}
		?>
	</p>
</div>

<div id="error"> 
	<p>
		<?php
			if( isset($_SESSION['err']) ) {
				echo $_SESSION['err'];
				unset($_SESSION['err']);
			}
		?>
	</p>
</div>