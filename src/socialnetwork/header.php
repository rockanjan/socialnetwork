<?php 
include_once 'database_open.php';
$sitename = "Social Networking Using Image Content";
$base = "/sn";
$imagedir = "../images/";
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
<h2 id="sitename"><?php echo $sitename?></h2>
<div id="wrapper">  
	<div id="header">  
  	<ul id="navigation">  
    	<li class="home"><a <?php if ($current_page == "home") { ?>class="selected"<?php } ?> href="<?php echo $base?>">Home</a></li>
    </ul>  
  </div>
</div>
<hr>

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