<?php

session_start();

session_destroy();

header("Location: login_database.html"); //redirect to login page after logging out
exit;
?>