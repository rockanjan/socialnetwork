<?php
$page_title = "Cluster photos";
$current_page = "home";
include_once('../header.php');
if(! isset($_SESSION['uid'])) {
	$_SESSION['err'] = "Login or Register to start";
	header("Location: /sn/index.php");
	exit();
}
?>

<h2> Split Album </h2>
<?php 
if(array_key_exists('cluster_submit', $_POST)) { //form has been submitted
	$albumid = $_POST['albumid'];
	$k = $_POST['k'];
	try{
		$k = intval($k);
	} catch(Exception $e) {
		$_SESSION['err'] = "Split number should be an integer, provided: " . $k;
		header("Location: cluster.php?albumid=$albumid");
		exit;
	}
	//start clustering
	//get all features
	$result = pg_exec($dbconn, "select * from photo where albumid=$albumid");
	$locationpath_index = pg_field_num($result, "locationpath");
	$locationpath_rows = pg_fetch_all_columns($result, $locationpath_index);
	//feature information
	$feature_index =  pg_field_num($result, "feature");
	$feature_rows = pg_fetch_all_columns($result, $feature_index);
	//photo ids
	$photo_index = pg_field_num($result, "photoid");
	$photo_ids = pg_fetch_all_columns($result, $photo_index);
	
	$len = count($feature_rows);
	//convert from string to feature array
	$features = array();
	for($i=0; $i<$len; $i++) {
		$feature_tmp = substr($feature_rows[$i], 1, strrpos($feature_rows[$i], "}")-2);
		$feature_arr = split(",", $feature_tmp);
		array_push($features, $feature_arr);
	}
	
	/** K-means starts **/
	//initial means
	$mean = array();
	for($i=0; $i<$k; $i++) {
		//random init
		$rand_int = rand(0, $len-1);
		array_push($mean, $features[$rand_int]);
		//array_push($mean, $features[$i*3]);		
	}

	$assignment = array();
	for($i=0; $i<$len; $i++) {
		array_push($assignment, -1); //initialize to none
	}
	
	//iterate
	$iter = 0;
	while(true) {
		for($i=0; $i<$len; $i++) {
			array_push($assignment, -1); //initialize to none
		}
		//E-step: expected assignments
		for($i=0; $i<$len; $i++) {
			$feature = $features[$i];
			$mindistance = 1E300; //MAX distance to all means
			for($j=0; $j<$k; $j++) {
				//compute distance to each mean
				//echo "vector length " . count($mean[$j]);
				/*
				for($l=0; $l < count($mean[$j]); $l++) {
					echo "\n\rfeature and mean $l = " . $feature[$l] . " " . $mean[$j][$l];
				}
				*/
				$distance = getDistance($feature, $mean[$j]);
				//echo "Distance : " . $distance;
				if($distance < $mindistance) {
					//new closer mean found
					$assignment[$i] = $j;
					$mindistance = $distance;
				}
			}
		}		
		//M-step: compute mean
		//resassign 0 to all feature dimensions
		for($j=0; $j<$k; $j++) {
			for($l=0; $l < count($mean[$j]); $l++) {
				$mean[$j][$l] = 0;
			}
		}
		//compute new mean
		$meancount = array();
		for($i=0; $i<$k; $i++) {
			array_push($meancount, 0);
		}
		
		for($i=0; $i<$len; $i++) {
			$feature = $features[$i];
			for($j=0; $j<$k; $j++) {
				if($assignment[$i] == $j) {
					$meancount[$j] = $meancount[$j] + 1;
					//echo "\nvector length " . count($mean[$j]);
					for($l=0; $l < count($mean[$j]); $l++) {
						$mean[$j][$l] = $mean[$j][$l] + $feature[$l];
					}
				}
			}
		}
		//normalize the sum to mean
		for($j=0; $j<$k; $j++) {
			for($l=0; $l < count($mean[$j]); $l++) {
				$mean[$j][$l] = $mean[$j][$l] / $meancount[$j];
			}
		}
		$iter++;
		//break condition
		if($iter == 30) {
			break;
		}
	}
	/** K-means ends **/
	/*
	for($i=0; $i<$len; $i++) {
		echo "Assignment $i : " . $assignment[$i];
	}
	*/
	//display the images
	for($j=0; $j<$k; $j++) {
		echo "<form action='' name='form$j' class='cluster_album_form'>";
		for($i=0; $i<$len; $i++) {
			if($assignment[$i] == $j) {
				echo "<input name='photoids[]' type='hidden' value='$photo_ids[$i]' />"; 
				echo "<img src='$locationpath_rows[$i]' style='max-width: 200px; max-height: 200px;' width=200px height=200px /> &nbsp;";
			}	
		}
		echo "<br />";
		echo "<label>Album Name :</label> <input name='albumname' type='text' value='cluster$j' />";
		echo "<input type='submit' name='newalbumfromclustersubmit' value='Create New Album' class='button'/>" ;
		echo "</form>";
	}
	include_once('../footer.php');
	exit;
}

function getDistance($feature1, $feature2){
	$result = 0.0;
	$len1 = count($feature1);
	$len2 = count($feature2);
	if($len1 != $len2){
		echo "The length of two input arrays should be the same!";
		echo "The length of the first array is " . $len1 . "<br>";
		echo "The length of the second array is " . $len2 . "<br>";
		exit;
	}
	for($i=1; $i<$len1; ++$i){
		$result += abs($feature1[$i]-$feature2[$i]);
	}
	return $result;
}
?>

<?php 
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
		echo "<p> Choose an album from below for clustering</p>";
		echo "<ul>";
		for ($i = 0; $i < $numrows; $i++) {
			$row = pg_fetch_array($result, NULL, PGSQL_ASSOC);
			$albumid = $row['albumid'];
			echo "<li> <a href='viewalbum.php?id=$albumid'>" . $row['albumname'] . "</a></li>";
		}
		echo "</ul>";
	}
	include_once('../footer.php');
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
			echo "You are not allowed to view this album";
			exit();
		}
		elseif($visibility == "friends") {
			echo "You are not allowed to view this album";
			exit();
		}
	} else {
		//show the form		
	}
}
?>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<input type="hidden" name="albumid" value="<?php echo $albumid;?>" />
<table>
<tr>
<td><label>Split size</label></td>
<td>
<input type="text" name="k" /><br>
</td>
<tr>
<td></td>
<td><input name="cluster_submit" type="submit" value="Submit" class="button"/><br /></td>
</tr>
</table>
</form>

<?php
include_once('../footer.php');
?>