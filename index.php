<!DOCTYPE html>
<head>
  <meta charset="UTF-8" />
  <title>News Site</title>
  <link rel="stylesheet" type="text/css" href="style.css">
</head>


<body>
  <div class="layer0">
    <div class="logins">
      <!--Allows the user to log in-->
      <form action="login.php" method="post">
        <input type="submit" class="btns" name="submit" value="Log In"/>
      </form>
      <!--Allows the user to add a new user-->
      <form action="createUser.php" method="post">
        <input type="submit" class="btns" name="createUser" value="Sign Up" />
      </form>
    </div>
  </div>
  <?php  
  require 'database.php';
  //First we will select a random story from today from our database
  //Selects necessary information for displaying stories
  $stmt4 = $mysqli->prepare("SELECT news_id, story, local, path, user_id FROM news WHERE day(last_update)=day(NOW()) and month(last_update)=month(NOW()) and year(last_update)=year(NOW()) order by RAND() LIMIT 1");

  if(!$stmt4){
    printf("Query Prep Failed: %s\n", $mysqli->error);
    exit;
  }
  $stmt4->execute();
  $stmt4->bind_result($news_id, $text, $local, $path, $user_id);
  $stmt4->fetch();
  //Displays the random story of the day
  printf("<div class='layer2'><div class='label'>Random Story of the Day:</div>\n<ul><li class='holder'><div class='story'>%s</div>\n",$text);
  if ($path != ""){
   printf("<a href=%s> %s </a>\n",$path, $path);
 }
 //Formatting for style
 echo("</li></ul>\n<div></div></div>\n<div class='layer1'>");
 $stmt4->close();
 //Now select all the stories in our database ordered by last_update and put them in an array for easy access
 $array = array();
 $stmt = $mysqli->prepare("SELECT news_id, story, local, path, user_id,last_update FROM news order by last_update DESC");
 if(!$stmt){
  printf("Query Prep Failed: %s\n", $mysqli->error);
  exit;
}
$stmt->execute();
$stmt->bind_result($news_id, $text, $local, $path, $user_id, $date_story);
  //puts our selected data into an array
while($stmt->fetch()){
  array_push($array, htmlspecialchars($news_id), htmlspecialchars($user_id), htmlspecialchars($text), htmlspecialchars($path), htmlspecialchars($date_story));
}
$stmt->close();
echo "<ul>\n";
$arr_length = count($array);
    //As we itterate through the array we will populalte the html with information
for($i=0;$i<$arr_length;$i+=5)
{
  $news_id2 = $array[$i];	  
  $user_id2 = $array[$i+1];	  
  $text2 = $array[$i+2];	  
  $path2 = $array[$i+3];	   
  $date_story2 = $array[$i+4];	   
  $stmt2 = $mysqli->prepare("SELECT username FROM registered_users WHERE id=?");
  $stmt2->bind_param('i', $user_id2);
  $stmt2->execute();
  $stmt2->bind_result($author);
  $stmt2->fetch();
  $stmt2->close();
  //Displays the stories
  if ($path2 != ""){
    //If there is a link associated
    printf("<li class='holder'><div class='story'>%s</div>\n<a href=%s> %s </a>\n<div class='author'>Published by %s on %s</div>\n<div class='label'>Comments:</div><ul>\n",$text2,$path2,$path2,$author, $date_story2);
  }
  else{
    //If there is no link associated
    printf("<li class='holder'><div class='story'>%s</div>\n<div class='author'>Published by %s on %s</div>\n<div class='label'>Comments:</div><ul>\n",$text2,$author, $date_story2);
  }
  //Now select all the comments in our database associated with the current story and put them into an array for easy access
  $comments_array = array();
  $stmt3 = $mysqli->prepare("SELECT comments_id, comment, user_id FROM comments WHERE news_id=?");	      
  $stmt3->bind_param('i', $news_id2);
  $stmt3->execute();
  $stmt3->bind_result($comments_id, $comment, $commenter_id); 
  //Puts all comments associated with this story into an array
  while($stmt3->fetch()){
   array_push($comments_array, htmlspecialchars($comment), htmlspecialchars($commenter_id));
 }
 $stmt3->close();		    
 $arr_length2 = count($comments_array);
 //As we itterate through the comment array we will populalte the html with comment information
 for($j=0;$j<$arr_length2;$j+=2)
 {
  $stmt4 = $mysqli->prepare("SELECT username FROM registered_users WHERE id=?");
  $stmt4->bind_param('i', $comments_array[$j+1]);
  $stmt4->execute();
  $stmt4->bind_result($commenter);
  $stmt4->fetch();
  $stmt4->close();
  //Displays the comment and comment author
  printf("<li class='comment'><div class='story'>%s</div><div class='author'>Comment by %s</div></li>\n", $comments_array[$j],$commenter);
}
echo "</ul></li>\n";
}	    
echo "</ul>\n";  

?>
</div>

</body>
</html>

