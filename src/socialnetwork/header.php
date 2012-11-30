<?php 
include_once 'database_open.php';
$sitename = "Social Networking Using Image Content";
$base = "/sn";
$imagedir = $base . "/images/";
session_start();
?>
<html>
<head>
	<title>Social Network : <?php echo $page_title; ?></title>
	<link rel="stylesheet" type="text/css" href="<?php echo $base?>/styles/style.css" />
	<script src="<?php echo $base?>/lib/jquery-1.8.3.min.js"> </script>
	<script>
		$(document).ready(function()
		{
			//deleting photo
		  $(".removeAction").click(function()
	    {
		    var name = $(location).attr('href');
		    var albumid = name.split("=")[1].split("#")[0];
		    $.get("<?php echo $base?>/users/deletephoto.php", 
	    	    	{ photoid: this.id}, 
	    	    	function(data) {
		    	    	alert(data);
		    	    	var url = "<?php echo $base?>/users/viewalbum.php?id="+albumid;    
		    	    	$(location).attr('href',url);
	    				}
		    	);	    	
	    });

		  $(".cluster_album_form").submit(function(e)
				  {
			  		e.preventDefault();
			  		$.post("<?php echo $base?>/users/createalbumfromcluster.php", $(this).serializeArray(), function(data) {
						   alert(data);						   
						 });
			  		$(this).hide();
				  });
		  return false;
		});
</script>
</head>
<body>
<div id="wrapper">
		<div id="sitename">
			<div class="clear"></div>
			<div id="logo">
				<a href="<?php echo $base?>"><img src="<?php echo $imagedir . 'logo.png'?>" /> </a>
			</div>
			<div class="clear"></div>
			<?php if(! isset($_SESSION['uid'])) {?>
			<div id="loginbar">
				<form action="<?php echo $base?>/users/login.php" method="post">
				<table>
				<tr>
					<td><label>Username: </label><input type="text" name="username" class="textinput"/></td>
					<td><label>Password :</label><input type="password" name="password" class="textinput"></td>
					<td><input name="login_submit" type="submit" value="Submit" class="button"/><br /></td>
				</tr>
				</table>
				</form>
			</div>
			<?php }?>
			<div class="clear"></div>
		</div>
		<div id="header">  
	  	<ul id="navigation">  
	    	<!--  <li class="home"><a <?php if ($current_page == "home") { ?>class="selected"<?php } ?> href="<?php echo $base?>">Home</a></li>-->
	    </ul>  
	  </div>
	<div class="clear"></div>
	<div id="page">	
	<?php
	if(isset($_SESSION['uid'])) {
		include_once("user_header.php");
	} 
	?>
	
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
