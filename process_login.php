<?php

require 'database.php';

session_start();

$do = $_POST['todo'];

if($do == "register") { //if registering a new user
    // Get the username and password and make sure both are valid
    
    $_SESSION['newuser'] = $_POST['newuser'];
    $_SESSION['newpass'] = $_POST['newpassword'];
    
    if( !preg_match('/^[\w_\s\-]+$/', $_SESSION['newuser']) ){
        echo "Invalid new username";
        exit;
    }
    
    if( !preg_match('/^[\w_\s\-]+$/', $_SESSION['newpass']) ){
        echo "Invalid new password";
        exit;
    }

    
    $stmt = $mysqli->prepare("select username from users order by userid");
    if(!$stmt){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
     
    $stmt->execute();
     
    $stmt->bind_result($founduser);
     
    while($stmt->fetch()){
        if($founduser == $_SESSION['newuser']) { //if the new username is taken, tell the user that
            echo "Username is already taken";
            exit;
        }
    }
     
    $stmt->close();

    header("Location: register_database.php"); //since this is a (validated) new user, let's go register them
    exit;
} elseif($do == "login") { //if logging in as a returning user
    // Get the username and password and make sure both are valid
    
    $_SESSION['user'] = $_POST['user'];
    $_SESSION['pass'] = $_POST['password'];
    
    if( !preg_match('/^[\w_\-\s]+$/', $_SESSION['user']) ){
        echo "Invalid returning username";
        exit;
    }
    if( !preg_match('/^[\w_\s\-]+$/', $_SESSION['pass']) ){
        echo "Invalid returning password";
        exit;
    }
    
    header("Location: authentication.php"); //since we've seen this (validated) user before, log them in (assuming their password is correct)
    exit;
}
?>