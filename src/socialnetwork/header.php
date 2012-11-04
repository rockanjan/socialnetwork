<?php 
include_once 'database_open.php';
?>
<html>
<head>
	<title>Social Network : <?php echo $page_title; ?></title>
<!-- 	<link rel="stylesheet" type="text/css" href="styles/style.css"> -->
</head>
<body>
<h2>Social Network</h2>
<div id="wrapper">  
	<div id="header">  
  	<ul id="navigation">  
    	<li class="home"><a <?php if ($current_page == "home") { ?>class="selected"<?php } ?> href="#">Home</a></li>
    </ul>  
  </div> 
</div>
<hr>