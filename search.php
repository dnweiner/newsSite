<?php

require 'database.php';

session_start();

$token = $_SESSION['token'];
$userid = $_SESSION['user_id']; //pull current user's id for reference

if($_SESSION['token'] !== $_POST['token']){ //CSRF
	die("Request forgery detected");
}

$guestcheck = $_POST['guest']; //confirm whether we are searching as a guest

$keyword = $_POST['searchitem']; //pull words to search for

if( !preg_match('/^[\w_\s\-]+$/', $keyword) ){ //filter input
    echo "Invalid search term";
    exit;
}

$sql = "select storyname, link from stories where storyname like '%"; 
$sql .= $keyword;
$sql .= "%'"; //concatenate keyword(s) with query including SQL wildcards on either side of our search term

//$stmt = $mysqli->prepare("select storyname, link from stories where contains(storyname, ?)");
$stmt = $mysqli->prepare($sql);
if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}

$stmt->bind_param('s', $keyword);

$stmt->execute();
 
$result = $stmt->get_result();
 
echo "<!DOCTYPE HTML>";
echo "<html>";
echo "<head>";
echo "<title>Search Results</title>";
echo "</head>";
echo "<body>";

echo "<ul>\n";
while($row = $result->fetch_assoc()){ //print all stories and links that match our keywords
	$story_author = $row['userid'];
	$storyid = $row['storyid'];
	printf("\t<li>%s <a href=%s target='_blank'>%s</a>\n",
		htmlspecialchars( $row['storyname'] ),
		htmlspecialchars( $row['link'] ),
		htmlspecialchars( $row['link'] )
	);
	echo "<form action='view_comments.php' method='POST'>"; //this form and the two below it are repeated from homepage.php to display appropriate actions alongside search results
	echo "<input type='submit' value='View Comments'>";
	// need to figure out how to get associated story id to get the comments
	echo "<input type='hidden' name='sid' value=$storyid>";
	echo "<input type='hidden' name='token' value=$token />";
	echo "</form>";
	if($guestcheck == 0) { //don't let lurkers (guests) edit and delete, because obviously
		if($userid == $story_author) { //only for OP
			echo "<form action='editstory.php' method='POST'>";
			echo "<input type='submit' value='Edit Story'>";
			echo "<input type='hidden' name='sid' value=$storyid>";
			echo "<input type='hidden' name='token' value=$token />";
			echo "</form>";
			echo "<form action='deletestory.php' method='POST'>";
			echo "<input type='submit' value='Delete Story'>";
			echo "<input type='hidden' name='sid' value=$storyid>";
			echo "<input type='hidden' name='token' value=$token />";
			echo "</form>";
			echo "</li>";
		}
	}
	
}
echo "</ul>\n";
 
$stmt->close();

echo "<br>";
echo "<form action='logout_database.php' method='POST'>"; //logout button redirects to logout (simply destroys session and returns to login)
echo "<input type='submit' value='Logout'>";
echo "</form>";

echo "</body>";
echo "</html>";

?>