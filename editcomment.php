<?php
require "database.php";

session_start();

if($_SESSION['token'] !== $_POST['token']){ //CSRF
	die("Request forgery detected");
}

$token = $_SESSION['token'];
$commentid = $_POST['cid'];

$stmt = $mysqli->prepare("select comment from comments where commentid= ?");  //select comment to be updated

$stmt->bind_param('i', $commentid);
$stmt->execute();

$result = $stmt->get_result();

echo "<!DOCTYPE HTML>";
echo "<html>";
echo "<head>";
echo "<title>Edit Comment</title>";
echo "</head>";
echo "<body>";

echo "<ul>\n";
while($row = $result->fetch_assoc()){
    printf("\t%s\n",
           htmlspecialchars($row['comment']) //display original comment for reference
    );
    echo "<form action='update.php' method='POST'>"; //form to enter new comment information
    echo "New Comment Content: <input type='text' name='new_cmt'>";
    echo "<input type='submit' value='Update'>";
    echo "<input type='hidden' name='todo' value='update_cmt'>"; //tell update.php what to do
    echo "<input type='hidden' name= 'cid' value=$commentid>"; //make sure update.php knows which comment to update by sending id
    echo "<input type='hidden' name='token' value=$token />";
    echo "</form>";
    
}
echo "</ul>\n";

$stmt->close();

echo "</body>";
echo "</html>";
?>
