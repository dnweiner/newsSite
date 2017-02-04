<?php

require 'database.php';

session_start();

if($_SESSION['token'] !== $_POST['token']){ //CSRF
	die("Request forgery detected");
}

$userid = $_SESSION['user_id'];

$storyid = $_SESSION['storyid'];
$comment = $_POST['comment_text'];

$stmt = $mysqli->prepare("insert into comments (storyid, comment, userid) values (?, ?, ?)"); //add a new comment for given story (tied to user)
if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}

$stmt->bind_param('isi', $storyid, $comment, $userid);
$stmt->execute();

$stmt->close();

$_SESSION['add'] = 1;

//header($token);
header("Location: homepage.php"); //redirect back to homepage 

?>
