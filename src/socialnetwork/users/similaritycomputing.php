<?php
//test();
run();


function run(){
	echo "running ...";
	echo "<br>";
// 	$imagepath1 = "/home/tud51534/socialnetwork/samples/ngc.jpg";
// 	$imagepath2 = "/home/tud51534/socialnetwork/samples/hulk.jpg";
	$imagepath1 = "/home/tud51534/Pictures/hulk.jpg";
	$imagepath2 = "/home/tud51534/Pictures/leaf1-green.jpg";
	
	//get featurevector for image1
	$featurevector1 = getFeatureVectors($imagepath1);	//this is a string
	$featurevector1 = split(",", $featurevector1);	//this is a vector
	$vectorSize1 = count($featurevector1);
	
	//get featurevector for image2
	$featurevector2 = getFeatureVectors($imagepath2);
	$featurevector2 = split(",", $featurevector2);
	$vectorSize2 = count($featurevector2);
	
	//compute the distance between two images
	$distance = getDistance($featurevector1, $featurevector2);
	echo "dist[image1, image2] = " + $distance;
	echo "<br>";
	
// 	for($i=1; $i<$vectorSize1; $i++){	//the first element of the vector is #distances used in feature computing
// 		echo $featurevector1[$i];
// 		echo "<br>";
// 	}
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
		exit;
	}
	for($i=1; $i<$len1; ++$i){
		$result += abs($arr1[$i]-$arr2[$i]);
	}
	return $result;
}

function test(){
	echo "testing ...";
	echo "<br>";
	$arr1 = array(1=>1, 2=>2, 3=>3, 4=>4);
	$arr2 = array(1=>4, 2=>3, 3=>2, 4=>1);
	$result = getDistance($arr1, $arr2);
	echo $result;
}
?>