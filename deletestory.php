<?php
//still not working for some reason

require "database.php";

session_start();

if($_SESSION['token'] !== $_POST['token']){ //CSRF
	die("Request forgery detected");
}

$token = $_SESSION['token'];

$storyid = $_POST['sid'];

$stmt1 = $mysqli->prepare("delete from comments where storyid= ?"); //remove from database all comments associated with story to be deleted
if(!$stmt1){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}
$stmt1->bind_param('i', $storyid);
$stmt1->execute();

$stmt1->close();

$stmt2 = $mysqli->prepare("delete from stories where storyid= ?"); //remove story in question from database
if(!$stmt2){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}

$stmt2->bind_param('i', $storyid);
$stmt2->execute();

$stmt2->close();


header("Location: homepage.php"); //redirect back to homepage

?>