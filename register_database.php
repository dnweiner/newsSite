<?php
require 'database.php';
 
session_start();
 
$username = $_SESSION['newuser']; //pull new user's username to insert into database
$password = crypt($_SESSION['newpass']); //encrypt this new user's password
 
$stmt = $mysqli->prepare("insert into users (username, password) values (?, ?)"); //enter new user and encrypted password into database
if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}
 
$stmt->bind_param('ss', $username, $password);
 
$stmt->execute();
 
$stmt->close();
 
$_SESSION['to-authenticate'] = "registered"; //set marker variable so authentication is handled appropriately for a new user
header("Location: authentication.php"); //let's go log this new user in
exit;
?>