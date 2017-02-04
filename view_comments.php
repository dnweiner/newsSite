<?php

require "database.php";

session_start();

$token = $_SESSION['token'];

if($_SESSION['add'] != 1 && $_SESSION['delete'] != 1) { //if we didn't come here from add or delete, do the CSRF stuff. otherwise it gets jumbled in the redirects from those actions
	if($_SESSION['token'] !== $_POST['token']){
		die("Request forgery detected");
	}
	if($_SESSION['add'] == 1) {
		$_SESSION['add'] == 0; //reset add marker for future reference
	} elseif($_SESSION['delete'] == 1) {
		$_SESSION['delete'] == 0; //reset delete marker for future reference
	}
} 

$userid = $_SESSION['user_id']; //pull the userid so we know who we're dealing with
$_SESSION['storyid'] = $_POST['sid']; //port the storyid from post data into a lasting session variable
$storyid = $_SESSION['storyid']; //and now that session variable from the previous line turns into a local variable

$guest = $_POST['guest'];

if($guest == 'is_guest') { //unregistered user can only view comments
	$stmt = $mysqli->prepare("select comment, username, stories.storycontent from comments left join users on(comments.userid=users.userid) join stories on(comments.storyid = stories.storyid) where comments.storyid=?");
		if(!$stmt){
			printf("Query Prep Failed: %s\n", $mysqli->error);
			exit;
		}

	$stmt->bind_param('i', $storyid);
	$stmt->execute();

	$stmt->bind_result($comment, $comment_author, $content);

	echo "<!doctype html>";
	echo "<html>";
	echo "<head>";
	echo "<title> Story Comments </title>";
	echo "</head>";
	echo "<body>";
	if ($content != NULL) { //print story content if it exists. makes sense to me
		printf("\t<i>%s</i>\n",
			htmlspecialchars($content)
		);
	}
	echo "<ul>\n";
	while($stmt->fetch()){
		printf("\t<li>%s : %s</li>\n",
			htmlspecialchars($comment_author), //print author before each comment so we know who to make fun of for their opinion, because the internet is just that friendly
			htmlspecialchars($comment) //print each comment
		);
	}
	echo "</ul>\n";
	
	$stmt->close();
}

else { //registered user can add comments and delete their own comments

	$stmt = $mysqli->prepare("select comment, username, userid, commentid, stories.storycontent from comments left join users on(comments.userid=users.userid) join stories on(comments.storyid=stories.storyid) where comments.storyid=?");
	if(!$stmt){
		printf("Query Prep Failed: %s\n", $mysqli->error);
		exit;
	}
	
	$stmt->bind_param('i', $storyid);
	$stmt->execute();
	
	$stmt->bind_result($comment, $comment_author, $usernum, $commentid, $content);
	
	echo "<!doctype html>";
	echo "<html>";
	echo "<head>";
	echo "<title> Story Comments </title>";
	echo "</head>";
	echo "<body>";
	if ($content != NULL) { //print story content if it exists. makes sense to me
		printf("\t<i>%s</i>\n",
			htmlspecialchars($content)
		);
	}
	echo "<ul>\n";
	while($stmt->fetch()){
		printf("\t<li>%s : %s \n",
			htmlspecialchars($comment_author), //print author before each comment so we know who to make fun of for their opinion, because the internet is just that friendly
			htmlspecialchars($comment) //print each comment
		);
		if($usernum == $userid) { //edit/delete are only for OP
			echo "<form action='editcomment.php' method='POST'>";
			echo "<input type='submit' value='Edit Comment'>";
			echo "<input type='hidden' name='cid' value=$commentid>";
			echo "<input type='hidden' name='token' value=$token />";
			echo "</form>";
			echo "<form action='deletecomment.php' method='POST'>";
			echo "<input type='submit' value='Delete Comment'>";
			echo "<input type='hidden' name='cid' value=$commentid>";
			echo "<input type='hidden' name='token' value=$token />";
			echo "</form>";
			echo "</li>";
		}
		
	}
	echo "</ul>\n";
	
	$stmt->close();
	
	echo "<form action='addcomment.php' method='POST'/>"; //form button to add comments
	echo "Comment: <input type='textarea' name='comment_text' rows='10' columns='30'>";
	echo "<input type='submit' value='Post Comment'>";
	echo "<input type='hidden' name='token' value=$token />";
	
	echo "</form>";
	
	echo "</body>";
	echo "</html>";
}

?>
