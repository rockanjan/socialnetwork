<?php
$host = '127.0.0.1';
//$host = 'ec2-184-73-14-96.compute-1.amazonaws.com';
$port = '5432';
$database = 'socialnetwork';
$user = 'postgres';
$password = 'root';

$connectString = 'host=' . $host . ' port=' . $port . ' dbname=' . $database .
' user=' . $user . ' password=' . $password;

global $dbconn;
$dbconn = pg_connect($connectString);
if(! $dbconn) {
	die('Could not connect: ' . pg_last_error());
}
?>