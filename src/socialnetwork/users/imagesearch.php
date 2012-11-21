<?php
include_once('../header.php');

echo "begin..." . "<br>";
//get image-query feature
$imagepath = "../images/17.jpg";
$imagefeature = getFeatureVectors($imagepath);	//this is a string
$queryfeature = split(",", $imagefeature);	//this is a vector

//get image features from the database
$result = pg_exec($dbconn, "select * from photo");
echo "QUERY IMAGE: <br />";
echo "<img src='$imagepath' />";
dosearch($queryfeature, $result);


function dosearch($queryfeature, $result){
	$len = pg_num_rows($result);
	echo "number of rows in the database is " . $len . "<br>";
	//location information
	$locationpath_index = pg_field_num($result, "locationpath");
	$locationpath_rows = pg_fetch_all_columns($result, $locationpath_index);
	
	//feature information
	$feature_index =  pg_field_num($result, "feature");
	$feature_rows = pg_fetch_all_columns($result, $feature_index);
	
	//distance information
	$distance_rows = getDistanceVectors($queryfeature, $feature_rows);
	asort($distance_rows);
	foreach ($distance_rows as $key => $val) {
	    echo $key . "==>" . $locationpath_rows[$key] . "==>" . $val;
	    echo "<img src='$locationpath_rows[$key]' />"; 
	    echo "<br>";
	}
	echo "<br>";
// 	for($i=0; $i<$len_disVec; $i++)
// 		echo $distance_rows[$i] . "<br>";	
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
	$command = 'java -jar /home/tud51534/socialnetwork/lib/feature.jar ' . $imagepath;
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

echo "end..." . "<br>";
?>
