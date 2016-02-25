<!-- This php script allows a logged in user to edit their comment-->
<!DOCTYPE html>
<html>
<head>
	<title> Comment Editor </title>
</head>
<body>
	<div class="layer1">
		<?php 
		session_start();
		if(isset($_POST['editComment'])){ 
			//Gets the required variables
			$com_id=$_POST['com_id'];
			$newComment=$_POST['editedCom'];
			
			require 'database.php';
			//Updates comment to new comment 
			$stmt = $mysqli->prepare("UPDATE comments SET comment=? WHERE comments_id=?");
			if(!$stmt){
				printf("Query Prep Failed: %s\n", $mysqli->error);
				exit;
			}
			$stmt->bind_param('si',$newComment, $com_id);
			$stmt->execute();
			$stmt->close();
			//Redirect
			header("Location: commentEdited.html");

		}
		else if(isset($_POST['deleteComment'])){
			//Gets the required variables
			$com_id=$_POST['com_id'];
			require 'database.php';
			//Deletes comment from database
			$stmt = $mysqli->prepare("DELETE FROM comments WHERE comments_id=?");
			if(!$stmt){
				printf("Query Prep Failed: %s\n", $mysqli->error);
				exit;
			}
			$stmt->bind_param('i', $com_id);
			$stmt->execute();
			$stmt->close();
			//Redirect
			header("Location: commentDeleted.html");
		}
		?>
	</div>
	<div>
		<!-- This form posts to the php script and according to what button is pressed an action will occur-->
		<form action="#" method="post">
			<input type="text" name="editedCom" value="<?php echo htmlentities($_POST['comment']);?>"/>
			<input type="hidden" name="com_id" value="<?php echo htmlentities($_POST['comment_id']);?>"/>
			<input type="submit" class="btns" name="editComment" value="Submit" />
			<input type="submit" class="btns" name="deleteComment" value="delete comment" />
		</form>  
		<!-- back to main page-->
		<form action="mainpage.php" method="post">
			<input type="submit" class="btns" name="return to mainpage" value="Return to Mainpage" />
		</form>  
	</div>
</body>
</html>