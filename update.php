<?php
require "database.php";

session_start();

if($_SESSION['token'] !== $_POST['token']){ //CSRF
	die("Request forgery detected");
}

$do = $_POST['todo'];


if($do == 'update_sn') { //here we decide what we're doing here
	$storyid = $_POST['sid'];
    $newname = $_POST['new_sn']; //looks like we're renaming a story
    
    $stmt = $mysqli->prepare("update stories set storyname= ? where storyid= ?");
    
    $stmt->bind_param('si', $newname, $storyid);
    $stmt->execute();
    
    $stmt->close();
    
    header("Location: homepage.php"); //let's go home
}
else if($do == 'update_sc') { //now we're updating story content
	$storyid = $_POST['sid'];
    $newcontent = $_POST['new_sc'];
    
    $stmt = $mysqli->prepare("update stories set storycontent= ? where storyid= ?");
    
    $stmt->bind_param('si', $newcontent, $storyid);
    $stmt->execute();
    
    $stmt->close();
    
    header("Location: homepage.php"); //home is where the heart is (and the redirect)
}
else if($do == 'update_l'){ //updating link (so...the Temple of Time?)
	$storyid = $_POST['sid'];
    $newlink = $_POST['new_l'];
    
    $stmt = $mysqli->prepare("update stories set link= ? where storyid= ?");
    
    $stmt->bind_param('si', $newlink, $storyid);
    $stmt->execute();
    
    $stmt->close();
    
    header("Location: homepage.php"); //home on the range
}
else if($do == 'update_cmt') { //update comment
	$commentid = $_POST['cid'];
	$newcomment = $_POST['new_cmt'];
	
	$stmt = $mysqli->prepare("update comments set comment= ? where commentid= ?");
    
    $stmt->bind_param('si', $newcomment, $commentid);
    $stmt->execute();
    
    $stmt->close();
    
    header("Location: homepage.php"); //'til the cows come home (does anyone actually say this?)
}
?>