<?php
require 'database.php';

session_start();

//if($_SESSION['token'] !== $_POST['token']){
//	die("Request forgery detected");
//}



$token = $_SESSION['token'];
$userid = $_SESSION['user_id']; //pull current user's id for reference
$_SESSION['to-authenticate'] == "cleared"; //reset marker variable so it can be properly toggled by registration or password changes
 
$stmt = $mysqli->prepare("select storyid, storyname, link, userid from stories order by storyid");
if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}
 
$stmt->execute();
 
$result = $stmt->get_result();
 
echo "<!DOCTYPE HTML>";
echo "<html>";
echo "<head>";
echo "<title>News Database User Homepage</title>";
echo "</head>";
echo "<body>";


echo "<ul>\n";
while($row = $result->fetch_assoc()){ //print a bulleted list of stories and attributed links
	$story_author = $row['userid'];
	$storyid = $row['storyid'];
	printf("\t<li>%s <a href=%s target='_blank'>%s</a>\n",
		htmlspecialchars( $row['storyname'] ),
		htmlspecialchars( $row['link'] ),
		htmlspecialchars( $row['link'] )
	);
	echo "<form action='view_comments.php' method='POST'>"; //form button to view comments
	echo "<input type='submit' value='View Comments'>";
	// need to figure out how to get associated story id to get the comments
	echo "<input type='hidden' name='sid' value=$storyid>";
	echo "<input type='hidden' name='token' value=$token />";
	echo "</form>";
	if($userid == $story_author) {
		echo "<form action='editstory.php' method='POST'>"; //form button to edit story (only by OP)
		echo "<input type='submit' value='Edit Story'>";
		echo "<input type='hidden' name='sid' value=$storyid>";
		echo "<input type='hidden' name='token' value=$token />";
		echo "</form>";
		echo "<form action='deletestory.php' method='POST'>"; //form button to delete story (only by OP)
		echo "<input type='submit' value='Delete Story'>";
		echo "<input type='hidden' name='sid' value=$storyid>";
		echo "<input type='hidden' name='token' value=$token />";
		echo "</form>";
		echo "</li>";
	}
	
}
echo "</ul>\n";
 
$stmt->close();

echo "<form action='addstory.php' method='POST'/>"; //form to add a story
echo "Story name: <input type='text' name='storyname'>";
echo "<br>";
echo "Story content: <input type='textarea' name='storycontent' rows='10' columns='30'>";
echo "<br>";
echo "Link to story: <input type='url' name='link'>";
echo "<input type='submit' value='Post Story'>";
echo "<input type='hidden' name='token' value=$token />";
echo "</form>";

echo "<br>";
echo "<form action='search.php' method='POST'>"; //search for stories whose titles contain a keyword
echo "<input type='text' name='searchitem'>";
echo "<input type='submit' value='Search'>";
echo "<input type='hidden' name='token' value=$token />";
echo "<input type='hidden' name='guestcheck' value=0/>";
echo "</form>";

echo "<br>";
echo "<form action='changepassword.php' method='POST'>"; //registered users may change their passwords here
echo "<input type='password' name='changedpassword'>";
echo "<input type='submit' value='Change Password'>";
echo "<input type='hidden' name='token' value=$token />";
echo "</form>";

if($_SESSION['to-authenticate'] == "passwordchanged") {
	//let's make our password change success text pretty and feel-good-y
	//echo "<style type='text/css'> 
	//div#pass{
	//	color: green;
	//	font-weight: strong;
	//}
	//</style>";
	echo "<div id=pass>Password successfully changed</div>";
	echo "<br>";
}

echo "<br>";
echo "<form action='logout_database.php' method='POST'>"; //logout button redirects to logout (simply destroys session and returns to login)
echo "<input type='submit' value='Logout'>";
echo "</form>";

echo "<br>";
echo "<form action='deleteuser.php' method='POST'>"; //registered users may change their passwords here
echo "<input type='submit' value='Delete Current User'>";
echo "<input type='hidden' name='token' value=$token />";
echo "</form>";

echo "</body>";
echo "</html>";

?>