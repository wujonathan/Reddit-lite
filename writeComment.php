<!-- This php script writes the comment into the database -->
<!DOCTYPE html>
<html>
<head>
	<title> Comment Writer </title>
</head>
<body>
		<?php 
		session_start();
		//Gets the required variables
			$comment=$_POST['comment'];
			$sessUser=$_SESSION['user_id'];
			$newsID=$_POST['news_id'];
			require 'database.php';
		//Inserts into database
			$stmt = $mysqli->prepare("INSERT INTO comments (comment, user_id, news_id) VALUES (?, ?,?)");
			if(!$stmt){
				printf("Query Prep Failed: %s\n", $mysqli->error);
				exit;
			}
			$stmt->bind_param('sii',$comment, $sessUser, $newsID);
			$stmt->execute();
			$stmt->close();
		//Reload mainpage.php
			header("Location: mainpage.php");
		?>
	
</body>
</html>