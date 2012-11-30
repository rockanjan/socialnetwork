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

<h2>View Album</h2>
<?php
$userid = $_SESSION['uid']; 
$albumid = $_GET['id'];
if($albumid == '' || $albumid == null) {
	$result = pg_exec($dbconn, "select * from album where userid='$userid'");
	$numrows = pg_num_rows($result);
	if($numrows == 0) {
		echo "<p> You do not have an album yet. <a href='createalbum.php'> Create a new album </a> </p>";
	} else {
		echo "<p> Choose an album from below</a></p>";
		echo "<ul>";
		for ($i = 0; $i < $numrows; $i++) {
			$row = pg_fetch_array($result, NULL, PGSQL_ASSOC);
			$albumid = $row['albumid'];
			echo "<li> <a href='viewalbum.php?id=$albumid'>" . $row['albumname'] . "</a></li>";
		}
		echo "</ul>";
	}
	include_once('../footer.php');
	exit;
}
$result = pg_exec($dbconn, "select * from album where albumid='$albumid'");
$numrows = pg_num_rows($result);
if($numrows == 0) {
	echo "No such album found";
} else {
	$row = pg_fetch_array($result, NULL, PGSQL_ASSOC);
	$useridforalbum = $row['userid'];
	$visibility = $row['visibility'];
	$description = $row['description'];
	$albumname = $row['albumname'];
	if($useridforalbum != $userid) {
		if($visibility == "private") {
			echo "You are not allowed to view this album";
		} 
		elseif($visibility == "friends") {
			//TODO: check if two users are friends
		}
	} else {
		//current user is the owner.
		//also show the album options
		echo "<a href='#'>Modify Album</a> &nbsp; &nbsp;";
		echo "<a href='addphoto.php?albumid=$albumid'>Add a photo</a> &nbsp; &nbsp;";
		echo "<a href='cluster.php?albumid=$albumid'>Cluster Album</a>";
		echo "<br />";
		//fetch all photos in this album
		$result = pg_exec($dbconn, "select * from photo where albumid='$albumid'");
		$numrows = pg_num_rows($result);
		if($numrows == 0) {
			echo "You have no photos in this album yet.";
		} else {
			$columns = 3;
			echo "<table width='60%'>";
			for ($i = 0; $i < $numrows; $i++) {
				if($i % $columns == 0) {
					echo "<tr><td style='border: 1px solid gray; padding:10px;'>";
				} else {
					echo "<td style='border: 1px solid gray'; padding:10px;>";
				}
				$row = pg_fetch_array($result, NULL, PGSQL_ASSOC);
				$photoid = $row['photoid'];
				$caption = $row['caption'];
				$locationpath = $row['locationpath'];
				//display image
				echo "<img src='$locationpath' style='max-width: 200px; max-height: 200px;' width=200px height=200px>" . $row['albumname'] . "</img> <br />";
				echo $caption;
				echo "<br />";
				echo "<a href='#' class='removeAction' id='$photoid'>Delete</a> &nbsp;";
				echo "<a href='$base/users/findsimilar.php?photoid=$photoid' target='_blank'>Find Similiar</a>";
				if($i % $columns == $columns - 1) {
					echo "</td></tr>";
				} else {
					echo "</td>";
				}
			}
		}
	}
}
?>

