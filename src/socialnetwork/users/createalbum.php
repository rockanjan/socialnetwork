<?php
$page_title = "Create Album";
$current_page = "createalbum";
include_once('../header.php');
if(! isset($_SESSION['uid'])) {
	header("Location: /sn/index.php");
	exit();
}
if($_POST['createalbum_submit'] == "Submit") { //form has been submitted
	$albumname = $_POST['albumname'];
	$description = $_POST['description'];
	$visibility = $_POST['visibility'];
	
	if(trim($albumname) == "") {
		$_SESSION['err'] = "Album name cannot be blank.";
		header("Location: /sn/users/createalbum.php"); //redirect
		exit();
	} else {
		$userid = $_SESSION['uid'];
		$query = "select * from album where userid='$userid' and albumname='$albumname'";
		$result = pg_exec($dbconn, $query);
		$numrows = pg_num_rows($result);
		if($numrows != 0) { //user already has an album with that name
			$_SESSION['err'] = "You already have an album with that name";
			header("Location: /sn/users/createalbum.php");
			exit();	
		} else {
			$query = "INSERT INTO album (userid, thumbnailphotoid, albumname, description, visibility) " .
				 " VALUES ('$userid', null, '$albumname', '$description', '$visibility')";
			$result = pg_query($query);
			if(! $result) {
				die('Error saving in database. Query failed: ' . pg_last_error());
			} else {
				pg_free_result($result);
				session_start();
				$_SESSION['message'] = "Congratulations! new album has been created. You can add photos to the album";
				header("Location: /sn/users/home.php");
			}
		}
	}
}
?>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<h2>Create Album</h2>
Your existing albums:
<?php 
	//read album list for user
	$userid = $_SESSION['uid'];
	$result = pg_exec($dbconn, "select * from album where userid='$userid'");
	$numrows = pg_num_rows($result);
	if($numrows == 0) {
		echo "None";
	} else {
		echo "<ul>";
		for ($i = 0; $i < $numrows; $i++) {
			$row = pg_fetch_array($result, NULL, PGSQL_ASSOC);
			$albumid = $row['albumid'];
			echo "<li> <a href='viewalbum.php?id=$albumid'>" . $row['albumname'] . "</a></li>";
		}
		echo "</ul>";
	}
?>
<table>
<tr>
	<td><label>Album Name: </label></td>
	<td><input type="text" name="albumname"/></td>
</tr>
<tr>
	<td><label>Description:</label></td>
	<td><input type="text" name="description"/><br/></td>
</tr>
<tr>
	<td><label>Visibility:</label></td>
	<td>
		<input type="radio" name="visibility" value="private" checked/>private<br />
		<input type="radio" name="visibility" value="friends" />friends<br />
		<input type="radio" name="visibility" value="everyone" />everyone
	</td>
</tr>
<tr>
	<td></td>
	<td><input name="createalbum_submit" type="submit" value="Submit" class="button"/><br /></td>
</tr>
</table>
</form>
<?php 
include_once('../footer.php');
?>
