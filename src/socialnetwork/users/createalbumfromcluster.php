<?php
session_start();
if(! isset($_SESSION['uid'])) {
	echo "You must login to perform the action";
	exit();
}
include_once "../database_open.php";

$userid = $_SESSION['uid'];
$albumname = $_POST['albumname'];
$photoids = $_POST['photoids'];
$photo_count = count($photoids);
if($photo_count != 0) {
	$query = "SELECT nextval('album_albumid_seq'::regclass)"; //gives unique id
	$result = pg_exec($dbconn, $query);
	$row = pg_fetch_array($result, NULL, PGSQL_ASSOC);
	$albumid = $row['nextval'];
	$successful = true;
	//atomicity: if saving images fails, then rollback the album creation
	//if(! pg_query($dbconn, "BEGIN; SAVEPOINT before_creating_album;") ) {
	if(! pg_query($dbconn, "BEGIN;") ) {
		echo "error starting transaction";
		exit;
	}
	$query = "insert into album (albumid, userid, thumbnailphotoid, albumname, description, visibility) values
			('$albumid', '$userid', null, '$albumname', '', 'everyone');";
	if(! pg_query($dbconn, $query)) {
		echo "error creating new album";
		exit;
	}

	for($i=0; $i<$photo_count; $i++) {
		$photoid = $photoids[$i];
		$query = "update photo set albumid='$albumid' where photoid='$photoid';";
		if(! pg_query($dbconn, $query)) {
			$successful = false;
			break;
		}
	}
	
	if(! $successful ) {
		//rollback
		pg_query($dbconn, "ROLLBACK;");
		echo "error updating the photo information";
		exit;
	} else {
		//commit
		pg_query($dbconn, "COMMIT;");
		echo "new album successfully created";
	}
}

include_once "../database_close.php";

	
?>