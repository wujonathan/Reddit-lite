<!-- This php script allows a logged in user to edit their story-->
<!DOCTYPE html>
<html>
<head>
	<title> Story Editor </title>
</head>
<body>
	<div class="layer1">
		<?php 
		session_start();
//This is if they chose to edit their link
		if(isset($_POST['submitL'])){ 
			//Gets the required variables
			$story_id=$_POST['news_id'];
			$newLink=$_POST['editedLink'];
			require 'database.php';
			//Updates the link to the new link
			$stmt = $mysqli->prepare("UPDATE news SET path=? WHERE news_id=?");
			if(!$stmt){
				printf("Query Prep Failed: %s\n", $mysqli->error);
				exit;
			}
			$stmt->bind_param('si',$newLink, $story_id);
			$stmt->execute();
			$stmt->close();
			//Redirect
			header("Location: storyEdited.html");
		}
//This is if they chose to edit their story
		else if(isset($_POST['submitS'])){
			//Gets the required variables
			$story_id=$_POST['news_id'];
			$newStory=$_POST['editedStory'];
			require 'database.php';
			//Updates the story to the new story
			$stmt = $mysqli->prepare("UPDATE news SET story=? WHERE news_id=?");
			if(!$stmt){
				printf("Query Prep Failed: %s\n", $mysqli->error);
				exit;
			}
			$stmt->bind_param('si',$newStory, $story_id);
			$stmt->execute();
			$stmt->close();
			//Redirect
			header("Location: storyEdited.html");

		}
//This is if they chose to delete their story
		else if(isset($_POST['deleteStory'])){
			//Gets the required variables
			$story_id=$_POST['news_id'];
			require 'database.php';
			//Deletes comments associated with this story from database
			$stmt2 = $mysqli->prepare("DELETE FROM comments WHERE news_id=?");
			if(!$stmt2){
				printf("Query Prep Failed: %s\n", $mysqli->error);
				exit;
			}
			$stmt2->bind_param('i', $story_id);
			$stmt2->execute();
			$stmt2->close();

			//Deletes story from database
			$stmt = $mysqli->prepare("DELETE FROM news WHERE news_id=?");
			if(!$stmt){
				printf("Query Prep Failed: %s\n", $mysqli->error);
				exit;
			}
			$stmt->bind_param('i', $story_id);
			$stmt->execute();
			$stmt->close();
			//Redirect
			header("Location: storyDeleted.html");
		}
	?>
</div>
<div>
	<!-- This form posts to the php script and according to what button is pressed an action will occur-->
	<form action="#" method="post">
		<input type="hidden" name="news_id" value="<?php echo htmlentities($_POST['news_id']);?>"/>
		<br>Edit your link<br>
		<input type="text" name="editedLink" value="<?php echo htmlentities($_POST['link']);?>"/>
		<input type="submit" class="btns" name="submitL" value="Submit Link Edit" />
		<br>Edit your story<br>
		<input type="text" name="editedStory" value="<?php echo htmlentities($_POST['story']);?>"/>
		<input type="submit" class="btns" name="submitS" value="Submit Story Edit" />
		<input type="submit" class="btns" name="deleteStory" value="Delete Story" />
	</form>  
	<!-- back to main page-->
	<form action="mainpage.php" method="post">
		<input type="submit" class="btns" name="return to mainpage" value="Return to Mainpage" />
	</form>  
</div>
</body>
</html>