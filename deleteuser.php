<?php
require 'database.php';
 
session_start();
 
$userid = $_SESSION['user_id']; //pull current user's id for reference
 
$stmt = $mysqli->prepare("delete from users where userid=? limit 1"); //remove user from database based on userid
if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}
 
$stmt->bind_param('i', $userid);
 
$stmt->execute();
 
$stmt->close();
 

header("Location: login_database.html"); //return to login since this user is no more
exit;
?>