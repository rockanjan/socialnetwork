<?php
$page_title = "Search photo";
$current_page = "home";
include_once('../header.php');
if(! isset($_SESSION['uid'])) {
	$_SESSION['err'] = "Login or Register to start";
	header("Location: /sn/index.php");
	exit();
}
?>
<h2> Search photo</h2>


<?php
//form handling
if(array_key_exists('searchphoto_submit', $_POST)) { //form has been submitted
	$albumid = $_POST['albumid'];
	if($_FILES['image']['name'] == '') {
		$_SESSION['err'] = 'Choose an image to search';
		header("Location: searchphoto.php");
		exit();
	} else {
		//get and show image query
		$tmpimage = $imagedir . "tmp" . $_SESSION['uid'] . "." . $extension;
		move_uploaded_file($_FILES['image']['tmp_name'], $tmpimage);
		echo "The query image is: " . "<br>";
		echo "<img src='$tmpimage' style='max-width: 200px; max-height: 200px;' width=200px height=200px />";
		echo "<br>";
		
		$imagefeature = getFeatureVectors($tmpimage);	//this is a string
		$queryfeature = split(",", $imagefeature);	//this is a vector

		$currentuserid = $_SESSION['uid'];
		
		//get image features from the database
	//	$result = pg_exec($dbconn, "select * from photo");
		$query = "select photo.photoid,photo.locationpath,photo.feature,person.personid,person.firstname,person.lastname from photo,album,person where photo.albumid=album.albumid and album.userid=person.personid";
		$result = pg_exec($dbconn, $query);
		
		
		//print information
// 		$numrows = pg_num_rows($result);
// 		echo "number of results = " . $numrows . "</br>";
// 		for($i=0; $i<$numrows; $i++){
// 			echo "<img src='$locationpath_rows[$i]' style='max-width: 200px; max-height: 200px;' width=200px height=200px /> &nbsp;";
// 			echo "$username_rows[$i]";
// 			echo "$feature_rows";
// 		}
	
		//compare the image query with images in our database and show the results
		$distance_rows = dosearch($queryfeature, $result, $currentuserid);
		
		exit();
	}
}
?>

<?php
function dosearch($queryfeature, $result, $currentuserid){
	$len = pg_num_rows($result);
//	echo "number of rows in the database is " . $len . "<br>";
	//user information
	$username_index =  pg_field_num($result, "firstname");
	$username_rows = pg_fetch_all_columns($result, $username_index);
	$userid_index = pg_field_num($result, "personid");
	$useridforimage_rows = pg_fetch_all_columns($result, $userid_index);
	
	//location information
	$locationpath_index = pg_field_num($result, "locationpath");
	$locationpath_rows = pg_fetch_all_columns($result, $locationpath_index);
	
	//feature information
	$feature_index =  pg_field_num($result, "feature");
	$feature_rows = pg_fetch_all_columns($result, $feature_index);

	//distance information
	$distance_rows = getDistanceVectors($queryfeature, $feature_rows);
	
	//show the search results
	echo "The top ranked images are listed as follows: " . "<br>";
	asort($distance_rows);
	$rank = 1;
	echo "<table>";
	foreach ($distance_rows as $key => $val) {
		$useridforimage = $useridforimage_rows[$key];
		echo "<tr>";
		echo "<td>";
		echo "Top " . $rank . "<br>";
		echo "<img src='$locationpath_rows[$key]' style='max-width: 200px; max-height: 200px;' width=200px height=200px />";
		echo "</td>";
		echo "<td>";
			if($currentuserid == $useridforimage) {
				echo "The photo belongs to you.";
			} else {
				echo "The photo belongs to user: <a href='#'>$username_rows[$key]</a> <input type='button' value='Add Friend' />";
			}
		echo "</td>";
		echo "</tr>";
		$rank = $rank+1;
		
	}
	echo "</table>";
	echo "<br>";

	return $distance_rows;
}

function getDistanceVectors($queryfeature, $feature_rows){
	$len = count($feature_rows);
	$distance_rows = array();
	for($i=0; $i<$len; $i++){
		//convert feature_string to feature_array
		$feature_value = $feature_rows[$i];
		$feature_tmp = substr($feature_value, 1, strrpos($feature_value, "}")-2);
		$feature_arr = split(",", $feature_tmp);
	
		//print information
// 		echo count($feature_value) . "</br>";
// 		echo "what's wrong here???" . "</br>";
// 		exit();
		
		//compute distance with the given image feature vector, and store the distance in the vector distance_rows
		$distance = getDistance($queryfeature, $feature_arr);
		array_push($distance_rows, $distance);
	}
	echo "<br>";
	return $distance_rows;
}

function getFeatureVectors($imagepath){
	unset($featurevector);
	unset($status);
	$command = 'java -jar /var/www/sn/lib/feature.jar ' . $imagepath;
	exec($command, $featurevector, $status);

	if($status != 0) {
		echo 'error executing the script';
		exit();
	}

	$featurevector = $featurevector[0];
	$featurevector = str_replace(" ", ",", $featurevector);
	//	echo $featurevector;
	return $featurevector;
}

function getDistance($arr1, $arr2){
	$result = 0.0;
	$len1 = count($arr1);
	$len2 = count($arr2);
	if($len1 != $len2){
		echo "The length of two input arrays should be the same!";
		echo "The length of the first array is " . $len1 . "<br>";
		echo "The length of the second array is " . $len2 . "<br>";
		exit;
	}
	for($i=1; $i<$len1; ++$i){
		$result += abs($arr1[$i]-$arr2[$i]);
	}
	return $result;
}
?>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
<input type="hidden" name="photoid" value="<?php echo $photoid;?>" /> 
<table>
<tr>
	<td><label>Photo</label></td>
	<td>
		<input type="file" name="image" /><br>
	</td>
</tr>
<tr>
	<td></td>
	<td><input name="searchphoto_submit" type="submit" value="Submit" class="button"/><br /></td>
</tr>
</table>
</form>
<?php 
include_once('../footer.php');
?>
