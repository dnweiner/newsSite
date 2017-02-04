<?php

require "database.php";

session_start();

if($_SESSION['token'] !== $_POST['token']){ //CSRF
	die("Request forgery detected");
}

$commentid = $_POST['cid'];

$stmt = $mysqli->prepare("delete from comments where commentid= ?"); //remove selected comment from database
if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}

$stmt->bind_param('i', $commentid);
$stmt->execute();

$stmt->close();

$_SESSION['delete'] = 1;

header("Location: homepage.php"); //redirect back to homepage

?>