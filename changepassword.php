<?php
require 'database.php';
 
session_start();
 
$token = $_SESSION['token'];
$userid = $_SESSION['user_id']; //pull current user's id for reference
$_SESSION['changedpass'] = $_POST['changedpassword']; //assign form data to session variable

if($_SESSION['token'] !== $_POST['token']){ //CSRF 
	die("Request forgery detected");
}
    
if( !preg_match('/^[\w_\s\-]+$/', $_SESSION['changedpass']) ){ //regex check for abuse of functionality / filtered input
    echo "Invalid changed password";
    exit;
}

$changedpass = crypt($_SESSION['changedpass']); //encryption stuff
 
$stmt = $mysqli->prepare("update users set password=? where userid=?"); //update users database with new password for current user
if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}
 
$stmt->bind_param('si', $changedpass, $userid);
 
$stmt->execute();
 
$stmt->close();
 
$_SESSION['to-authenticate'] = "passwordchanged"; //set marker variable so authentication is handled appropriately
header("Location: authentication.php"); //redirect to authenticate to sign current user in with updated password
exit;
?>