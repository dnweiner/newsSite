<?php
// connect to relevant database. nothing else happens here

$mysqli = new mysqli('localhost', 'module3login', '3login', 'module3');
 
if($mysqli->connect_errno) {
	printf("Connection Failed: %s\n", $mysqli->connect_error);
	exit;
}


?>