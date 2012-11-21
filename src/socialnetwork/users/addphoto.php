<?php
$page_title = "Add photo";
$current_page = "home";
include_once('../header.php');
if(! isset($_SESSION['uid'])) {
	$_SESSION['err'] = "Login or Register to start";
	header("Location: /sn/index.php");
	exit();
}
?>
<h2> Add photo</h2>

<?php
//form handling
if(array_key_exists('addphoto_submit', $_POST)) { //form has been submitted
	$albumid = $_POST['albumid'];
	if($_FILES['image']['name'] == '') {
		$_SESSION['err'] = 'Choose an image to upload';
		header("Location: addphoto.php?albumid=$albumid");
		exit();
	} else {
		$caption = $_POST['caption'];
		$file = str_replace(' ', '_', $_FILES['image']['name']);
		if($file != null) {
			$permitted = array('image/gif', 'image/jpeg', 'image/pjpeg', 'image/png');
			if(! in_array($_FILES['image']['type'], $permitted) ) {
				$_SESSION['err'] = "Please provide an image file";
				header("Location: addphoto.php?albumid=$albumid");
				exit();
			}
			if(! $_FILES['image']['error']) {
				$query = "SELECT nextval('photo_photoid_seq'::regclass)"; //gives unique id
				$result = pg_exec($dbconn, $query);
				$row = pg_fetch_array($result, NULL, PGSQL_ASSOC);
				$photoid = $row['nextval'];
				
				$extension = end(explode(".", $_FILES['image']['name']));
				$savename = $photoid . "." .$extension;
				$imagepath =  $imagedir . $savename;
				if(! move_uploaded_file($_FILES['image']['tmp_name'], $imagepath)) {
					$_SESSION['err'] = "There was an error uploading the file, please try again!";
					header("Location: addphoto.php?albumid=$albumid");
					exit();
				}
				
				if(getimagesize($imagepath) == 3) {
					$isrgb = true;					
				} else {
					$isrgb = false;
				}
				//TODO: isrgb test not working
				
				/****** calculate the feature vector ******/
				unset($featurevector);
				unset($status);
				$command = 'java -jar /home/tud51534/socialnetwork/lib/feature.jar ' . $imagepath;
				exec($command, $featurevector, $status);
				if($status != 0) {
					echo 'error executing the script';
				}
				$featurevector = $featurevector[0];
				$featurevector = str_replace(" ", ",", $featurevector);
				//echo $featurevector;
				//exit();
				$featurevalueinsert = '{' . $featurevector . '}';
				
				/****** feature vector calculation complete *****/
				
				
				//insert record
				$query = "INSERT INTO photo (photoid, albumid, caption, locationpath, thumbnailpath, isrgb, uploadtime, feature)
						VALUES ('$photoid', '$albumid', '$caption', '$imagepath', null, true, now(), '$featurevalueinsert')";
				$result = pg_query($query);
				if(! $result) {
					die('Error saving in database. Query failed: ' . pg_last_error());
				} else {
					pg_free_result($result);
					session_start();
					$_SESSION['message'] = "Your photo was uploaded successfully";
					header("Location: /sn/users/viewalbum.php?id=$albumid");
				}
				
			}
		}
	}
}

$userid = $_SESSION['uid']; 
$albumid = $_GET['albumid'];
if($albumid == null) {
	//read album list for user
	$userid = $_SESSION['uid'];
	$result = pg_exec($dbconn, "select * from album where userid='$userid'");
	$numrows = pg_num_rows($result);
	if($numrows == 0) {
		echo "<p> You do not have an album yet. <a href='createalbum.php'> Create a new album </a> </p>";
	} else {
		echo "<p> Choose an album from below or <a href='createalbum.php'> Create a new album </a></p>";
		echo "<ul>";
		for ($i = 0; $i < $numrows; $i++) {
			$row = pg_fetch_array($result, NULL, PGSQL_ASSOC);
			$albumid = $row['albumid'];
			echo "<li> <a href='viewalbum.php?id=$albumid'>" . $row['albumname'] . "</a></li>";
		}
		echo "</ul>";
	}
	exit();
} 
	$result = pg_exec($dbconn, "select * from album where albumid='$albumid'");
	$numrows = pg_num_rows($result);
	if($numrows == 0) {
		echo "No such album found";
		exit();
	} else {
		$row = pg_fetch_array($result, NULL, PGSQL_ASSOC);
		$useridforalbum = $row['userid'];
		$visibility = $row['visibility'];
		$description = $row['description'];
		$albumname = $row['albumname'];
		if($useridforalbum != $userid) {
			if($visibility == "private") {
				echo "You are not allowed to add photo to this album";
				exit();
			} 
			elseif($visibility == "friends") {
				echo "You are not allowed to add photo to this album";
				exit();
			}
		} else {
			//current user is the owner.
			//show the form
		}
	}
	?>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
<input type="hidden" name="albumid" value="<?php echo $albumid;?>" /> 
<table>
<tr>
	<td><label>Photo</label></td>
	<td>
		<input type="file" name="image" /><br>
	</td>
</tr>
<tr>
	<td><label>Caption</label></td>
	<td>
		<input type="text" name="caption" /><br>
	</td>
</tr>
<tr>
	<td></td>
	<td><input name="addphoto_submit" type="submit" value="Submit"/><br /></td>
</tr>
</table>
</form>
<?php 
include_once('../footer.php');
?>
