<?php
require "database.php";

session_start();

if($_SESSION['token'] !== $_POST['token']){ //CSRF
	die("Request forgery detected");
}

$userid = $_SESSION['user_id'];

$storyname = $_POST['storyname'];
$storycontent = $_POST['storycontent'];
$link = $_POST['link'];

$stmt = $mysqli->prepare("insert into stories (storyname, storycontent, link, userid) values (?, ?, ?, ?)"); //add a new story to the database, associated with current user
if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}

$stmt->bind_param('sssi', $storyname, $storycontent, $link, $userid);
$stmt->execute();

$stmt->close();

header("Location: homepage.php"); //redirect back to homepage to see newly added story
?>