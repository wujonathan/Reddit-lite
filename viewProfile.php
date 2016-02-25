<!DOCTYPE html>
<head>
  <meta charset="UTF-8" />
  <title>News Site</title>
  <link rel="stylesheet" type="text/css" href="style.css">
</head>


<body>
<div class="layer0">
  <div class="logins">
    <div>
      <!--Logout button-->
      <form action="logout.php" method="get">
      <input type="submit" class="btns" name="submit" value="Log Out"/>
      </form>
    </div>

    <div>
      <!--Back to mainpage-->
      <form action="mainpage.php" method="post">
        <input type="submit" class="btns" name="mainpage" value="<< Back"/>
      </form>
    </div>
</div>
<div class="layer1">
  <?php  
     require 'database.php';
     session_start();
     //Gathers required varialbes
     $sessUser = $_SESSION['username'];
     $sessUser_id = $_SESSION['user_id'];
     //Displays session user
     printf("<div class='story'>Welcome to your profile %s</div>", $sessUser);
     //Display all the stories by the user
     //Select all the stories in our database ordered by last_update and put them in an array for easy access
  $array = array();
  $stmt = $mysqli->prepare("SELECT news_id, story, local, path FROM news WHERE user_id=? order by last_update DESC");
  if(!$stmt){
  printf("Query Prep Failed: %s\n", $mysqli->error);
  exit;
  }
  $stmt->bind_param('i', $sessUser_id);
  $stmt->execute();
  $stmt->bind_result($news_id, $text, $local, $path); 
  //puts our selected data into an array
  while($stmt->fetch()){
    array_push($array, htmlspecialchars($news_id), htmlspecialchars($text), htmlspecialchars($path));
}
$stmt->close();
 echo "<div class='label'>Your stories: </div><ul>\n";
    $arr_length = count($array);
    //As we itterate through the array we will populalte the html with information
    for($i=0;$i<$arr_length;$i+=3)
{
    $news_id2 = $array[$i];	  
    $text2 = $array[$i+1];	  
    $path2 = $array[$i+2];  
    //Prints story
     printf("<li class='holder'><div class='story'>%s</div>\n",$text2);
  if ($path2 != ""){
    //If there is a link associated
   printf("<a href=%s> %s </a>\n",$path2, $path2);
        }
        //Allows user to expand the listed story
   printf("\n<div><form action='viewStoryfromProfile.php' method='post'><input type='hidden' name='news_id' value='%s'/><input type='submit' class='editComment' name='viewStoryFromProfile' value='Expand'/></form></div></li>", $news_id2);
}
//Displays all the comments by the user
  echo "</ul></div><div class='layer2'><div class='label'>Your comments: </div><ul>\n";  
  $comments_array = array();
 $stmt2 = $mysqli->prepare("SELECT comments_id, news_id, comment,last_update FROM comments WHERE user_id=? order by last_update DESC");
  if(!$stmt2){
  printf("Query Prep Failed: %s\n", $mysqli->error);
  exit;
  }
  $stmt2->bind_param('i', $sessUser_id);
  $stmt2->execute();
  $stmt2->bind_result($com_id, $news_id3, $comment, $comm_date); 
  //Puts all comments associated with this user into an array
  while($stmt2->fetch()){
    array_push($comments_array, htmlspecialchars($com_id), htmlspecialchars($news_id3), htmlspecialchars($comment), htmlspecialchars($comm_date));
}
$stmt2->close();
     $arr_length2 = count($comments_array);
    //As we itterate through the comment array we will populalte the html with comment information
    for($j=0;$j<$arr_length2;$j+=4)
{
    $com_id2 = $comments_array[$j];	  
    $news_id4 = $comments_array[$j+1];	  
    $comment2 = $comments_array[$j+2];  
    $comm_date2 = $comments_array[$j+3]; 
    //Gets the story associated with the comment
    $stmt0 = $mysqli->prepare("SELECT story FROM news WHERE news_id=?");
  if(!$stmt0){
  printf("Query Prep Failed: %s\n", $mysqli->error);
  exit;
  }
  $stmt0->bind_param('i', $news_id4);
  $stmt0->execute();
  $stmt0->bind_result($news); 
  $stmt0->fetch();
  $stmt0->close(); 
    //Displays to which story the comment reffers to
    printf("<li class='holder'><div class='label'>To %s</div>\n<div class='comment'><div class='story'>%s</div><div class='author'>Commented on %s</div>", $news, $comment2, $comm_date2);
    //Allows the user to edit comments
    printf("<div><form action='editComment.php' method='post'><input type='hidden' name='comment_id' value='%s'/><input type='hidden' name='comment' value='%s'/><input type='submit' class='editComment' name='submit2' value='edit'/></form></div></div></li>\n", $com_id2, $comment2);
}
  echo "</ul></div>\n";  
   ?>
</div>

</body>
</html>

