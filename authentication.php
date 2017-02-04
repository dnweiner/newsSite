<?php
// This is a *good* example of how you can implement password-based user authentication in your web application.
 
require 'database.php';
 
session_start();
 
// Use a prepared statement
$stmt = $mysqli->prepare("SELECT COUNT(*), userid, password FROM users WHERE username=?");
 
// Bind the parameter

//marker variable determines action to take: compare newuser data, compare modified password, or compare returning user
if($_SESSION['to-authenticate'] == "registered") { 
    $user = $_SESSION['newuser'];
    $pwd_guess = $_SESSION['newpass'];
} elseif($_SESSION['to-authenticate'] == "passwordchanged") {
	$user = $_SESSION['user'];
	$pwd_guess = $_SESSION['changedpass'];
} else {
    $user = $_SESSION['user'];
    $pwd_guess = $_SESSION['pass'];
}

$stmt->bind_param('s', $user);
$stmt->execute();

// Bind the results
$stmt->bind_result($cnt, $user_id, $pwd_hash);
$stmt->fetch();
 
 
// Compare the submitted password to the actual password hash
if( $cnt == 1 && crypt($pwd_guess, $pwd_hash)==$pwd_hash){
	// Login succeeded!
	$_SESSION['user_id'] = $user_id;
	$_SESSION['token'] = substr(md5(rand()), 0, 10); // generate a 10-character random string
	header("Location: homepage.php");
    exit;
}else{
	header("Location: login_database.html");
    exit;
}
?>