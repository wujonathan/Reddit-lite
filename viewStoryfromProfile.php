<!DOCTYPE html>
<head>
  <meta charset="UTF-8" />
  <title>News Site</title>
  <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
  <div class='layer1'>
    <?php  
    echo("<ul>");
    session_start();
     //Gathers required variables
    $sessUser = $_SESSION['username'];
    $news_id=$_POST['news_id'];
    require 'database.php';
    //Select the correct story's information
    $stmt4 = $mysqli->prepare("SELECT story, local, path, user_id FROM news WHERE news_id=?");

    if(!$stmt4){
      printf("Query Prep Failed: %s\n", $mysqli->error);
      exit;
    }
    $stmt4->bind_param('i', $news_id);
    $stmt4->execute();
    $stmt4->bind_result($text, $local, $path, $user_id); 
    $stmt4->fetch();
    //Displays the story 
    printf("<li class='holder'><div class='story'>%s</div>\n",$text);
    //Displays the link if it exists
    if ($path != ""){
      printf("<a href=%s> %s </a>\n",$path, $path);
    }
    $stmt4->close();
    //Now select all the comments in our database related to this story
    $comments_array = array();
    $stmt3 = $mysqli->prepare("SELECT comments_id, comment, user_id FROM comments WHERE news_id=?");	      
    $stmt3->bind_param('i', $news_id);
    $stmt3->execute();
    $stmt3->bind_result($comments_id, $comment, $commenter_id); 
    //Puts all comments associated with this story into an array
    while($stmt3->fetch()){
     array_push($comments_array,  htmlspecialchars($comments_id), htmlspecialchars($comment), htmlspecialchars($commenter_id));
   }
   $stmt3->close();		    
   $arr_length2 = count($comments_array);
   //As we itterate through the comment array we will populalte the html with comment information
   for($j=0;$j<$arr_length2;$j+=3){
    $stmt4 = $mysqli->prepare("SELECT username FROM registered_users WHERE id=?");
    $stmt4->bind_param('i', $comments_array[$j+2]);
    $stmt4->execute();
    $stmt4->bind_result($commenter);
    $stmt4->fetch();
    $stmt4->close();
    //Displays the comment and comment author
    printf("<li class='comment'><div class='story'>%s</div><div class='author'>Comment by %s</div>", $comments_array[$j+1],$commenter);
    //If the user is the comment author, generate an edit button for the user to edit this comment
    if ($sessUser == $commenter){
     printf("<div><form action='editComment.php' method='post'><input type='hidden' name='comment_id' value='%s'/><input type='hidden' name='comment' value='%s'/><input type='submit' class='editComment' name='submit2' value='edit'/></form></div>", $comments_array[$j], $comments_array[$j+1]);
   }  
   echo "</li>\n";
 }
 //Generate a text field for the user to write a comment
 printf("</ul>\n<form action='writeComment.php' method='post'><br /><input type='hidden' name='news_id' value='%s'/><textarea name='comment' class='comment' placeholder='Comment Here..'></textarea><br /><input type='submit' value='Submit' class='editComment'/></form></li>\n",$news_id);   
 echo "</ul>\n";  

 ?>
</div>
<div>
  <!--Logout button-->
  <form action="logout.php" method="get">
    <input type="submit" class="btns" name="submit" value="Log Out"/>
  </form>
</div>
<div>
  <!--Fo back to profile page-->
  <form action="viewProfile.php" method="post">
    <input type="submit" class="btns" name="back" value="Back"/>
  </form>
</div>

</body>
</html>
