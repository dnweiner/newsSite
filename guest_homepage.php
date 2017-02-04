<?php

require 'database.php';

session_start();

//if($_SESSION['token'] !== $_POST['token']){
//	die("Request forgery detected");
//}
$token = $_SESSION['token'];
 
$stmt = $mysqli->prepare("select storyid, storyname, link from stories order by storyid"); //select all stories to be displayed
if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}
 
$stmt->execute();
 
$result = $stmt->get_result();
 
echo "<!DOCTYPE HTML>";
echo "<html>";
echo "<head>";
echo "<title>News Database Guest Homepage</title>";
echo "</head>";
echo "<body>";

echo "<ul>\n";
while($row = $result->fetch_assoc()){
	$storyid = $row['storyid'];
	printf("\t<li>%s <a href=%s target='_blank'>%s</a>\n",
		htmlspecialchars( $row['storyname'] ),
		htmlspecialchars( $row['link'] ),
		htmlspecialchars( $row['link'] )
	);
	echo "<form action='view_comments.php' method='POST'>"; //guest users can only view comments, and here is the button to do so
	echo "<input type='submit' value='View Comments'>";
	echo "<input type='hidden' name='sid' value=$storyid>"; //make sure view_comments.php knows which story to find comments for by sending id
	echo "<input type='hidden' name='guest' value='is_guest'>"; //confirm to view_comments.php that this request is coming from a guest account
	echo "<input type='hidden' name='token' value=$token />";
	echo "</form>";
	echo "</li>";
}
echo "</ul>\n";
 
$stmt->close();

echo "<br>";
echo "<form action='search.php' method='POST'>"; //search for stories whose titles contain entered keywords
echo "<input type='text' name='searchitem'>";
echo "<input type='submit' value='Search'>";
echo "<input type='hidden' name='token' value=$token />";
echo "<input type='hidden' name='guest' value=1/>";
echo "</form>";

echo "<form action='logout_database.php' method='POST'>"; //logout button redirects to logout (simply destroys session and returns to login)
echo "<input type='submit' value='Logout'>";
echo "</form>";

echo "</body>";
echo "</html>";

?>