<!-- This is a page that allows users to write a new story-->
<!DOCTYPE html>
<html>
<head>
	<title> Story Writer </title>
</head>
<body>
	<div class="layer1">
		<?php 
		session_start();
		if(isset($_POST['submit'])){ 
		//Gets the required variables
			$link=$_POST['link'];
			$story=$_POST['story'];
			$sessUser=$_SESSION['user_id'];
			require 'database.php';
		//Inserts into database
			$stmt = $mysqli->prepare("INSERT INTO news (path, story, user_id) VALUES (?, ?,?)");
			if(!$stmt){
				printf("Query Prep Failed: %s\n", $mysqli->error);
				exit;
			}
			$stmt->bind_param('ssi',$link, $story, $sessUser);
			$stmt->execute();
			$stmt->close();
		//Reload mainpage.php
			header("Location: mainpage.php");
		}
		?>
	</div>
	<div>
		<!--Here the user can input the required information-->
		<form action="#" method="post">
			<textarea name='link' placeholder='Add Link here'></textarea>
			<textarea name='story' placeholder='Write Story here'></textarea>
			<input type="submit" class="btns" name="submit" value="Submit" />
		</form>  
		<!--return to the mainpage-->
		<form action="mainpage.php" method="post">
			<input type="submit" class="btns" name="return to mainpage" value="Return to Mainpage" />
		</form>  
	</div>
</body>
</html>