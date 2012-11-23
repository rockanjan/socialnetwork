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
		header("Location: qingqing.php?photoid=$photoid");
		exit();
	} else {
		//get and show image query
		$tmpimage = $imagedir . "tmp" . $_SESSION['uid'] . "." . $extension;
		move_uploaded_file($_FILES['image']['tmp_name'], $tmpimage);
		echo "The query image is: " . "<br>";
		echo "<img src='$tmpimage' />";
		echo "<br>";
		
		$imagefeature = getFeatureVectors($tmpimage);	//this is a string
		$queryfeature = split(",", $imagefeature);	//this is a vector

		//get image features from the database
		$result = pg_exec($dbconn, "select * from photo");
		
		//compare the image query with images in our database and show the results
		$distance_rows = dosearch($queryfeature, $result);
		
		exit();
	}
}
?>

<?php
function dosearch($queryfeature, $result){
	$len = pg_num_rows($result);
//	echo "number of rows in the database is " . $len . "<br>";
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
	foreach ($distance_rows as $key => $val) {
// 		echo $key . "==>" . $locationpath_rows[$key] . "==>" . $val;
// 		echo "<img src='$locationpath_rows[$key]' />";
// 		echo "<br>";
		echo "Top " . $rank . "<br>";
		echo "<img src='$locationpath_rows[$key]' />";
		echo "<br>";
		echo "<br>";
		echo "<br>";
		
		$rank = $rank+1;
	}
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
//		echo count($feature_arr) . "<br>";
		
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
	<td><input name="searchphoto_submit" type="submit" value="Submit"/><br /></td>
</tr>
</table>
</form>
<?php 
include_once('../footer.php');
?>
