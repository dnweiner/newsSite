<?php
require "database.php";

session_start();

if($_SESSION['token'] !== $_POST['token']){ //CSRF
	die("Request forgery detected");
}

$token = $_SESSION['token'];
$storyid = $_POST['sid'];

$stmt = $mysqli->prepare("select storyname, storycontent, link from stories where storyid= ?"); //select story to be updated

$stmt->bind_param('i', $storyid);
$stmt->execute();

$result = $stmt->get_result();

echo "<!DOCTYPE HTML>";
echo "<html>";
echo "<head>";
echo "<title>Edit Story</title>";
echo "</head>";
echo "<body>";

echo "<ul>\n";
while($row = $result->fetch_assoc()){
    printf("\t%s\n",
           htmlspecialchars($row['storyname'])
    );
    echo "<form action='update.php' method='POST'>"; //form to enter new story name
    echo "New Story Name: <input type='text' name='new_sn'>"; //tell update.php what to do
    echo "<input type='submit' value='Update'>";
    echo "<input type='hidden' name='todo' value='update_sn'>"; //tell update.php what to do
    echo "<input type='hidden' name= 'sid' value=$storyid>"; //make sure update.php knows which story to update by sending id
    echo "<input type='hidden' name='token' value=$token />";
    echo "</form>";
    
    printf("\t%s\n",
           htmlspecialchars($row['storycontent'])
    );
    echo "<form action='update.php' method='POST'>";//form to enter new story content
    echo "New Story Content: <input type='text' name='new_sc'>"; //tell update.php what to do
    echo "<input type='submit' value='Update'>";
    echo "<input type='hidden' name='todo' value='update_sc'>"; //tell update.php what to do
    echo "<input type='hidden' name= 'sid' value=$storyid>"; //make sure update.php knows which story to update by sending id
    echo "<input type='hidden' name='token' value=$token />";
    echo "</form>";
    
    printf("\t%s\n",
           htmlspecialchars($row['link'])
    );
    echo "<form action='update.php' method='POST'>";//form to enter new story link
    echo "New Story Link: <input type='text' name='new_l'>"; //tell update.php what to do
    echo "<input type='submit' value='Update'>";
    echo "<input type='hidden' name='todo' value='update_l'>"; //tell update.php what to do
    echo "<input type='hidden' name= 'sid' value=$storyid>"; //make sure update.php knows which story to update by sending id
    echo "<input type='hidden' name='token' value=$token />";
    echo "</form>";
}
echo "</ul>\n";

$stmt->close();

echo "</body>";
echo "</html>";

?>