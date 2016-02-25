<!--This php page allows the viewer to add a new user-->
<!DOCTYPE html>
<html>
<head><title>Create User</title></head>
<body>
	<div>
		<!-- This form posts to the php script-->
		<form action="#" method="post">
			<input type="text"  name="newUsername"> Username <br>
			<input type="password"  name="newPassword"> Password <br>
			<input type="submit" name="submit" value="Create User"/>
		</form>
	</div>

	<div>
		<!-- back to index-->
		<form action="index.php" method="post">
			<input type="submit" name="return to index" value="Return to Index" />
		</form>  

	</div>

	<?php
// When button is pressed we get the posted information
	if(isset($_POST['submit'])){
		if(isset($_POST['newUsername'])&&isset($_POST['newPassword'])){
	require 'database.php';
// This function checks if the username matches one in the database
	function existingUserCheck($u){
		require 'database.php';
		$stmt = $mysqli->prepare("SELECT COUNT(*) FROM registered_users WHERE username = (?) LIMIT 1");
		if(!$stmt){
			printf("Query Prep Failed: %s\n", $mysqli->error);
			exit;
		}
		$stmt->bind_param('s', $u);

		$stmt->execute();

		$stmt->bind_result($result);

		$stmt->fetch();

		$stmt->close();
		if($result==0){return false;}
		else{return true;}
	}

	$newUsername = $_POST['newUsername'];
	$newPassword = $_POST['newPassword'];
// If the new username is unique we insert it into the database
	if(!existingUserCheck($newUsername)){
		$stmt = $mysqli->prepare("INSERT INTO registered_users (username, password) VALUES (?, ?)");
		if(!$stmt){
			printf("Query Prep Failed: %s\n", $mysqli->error);
			exit;
		}
		//hashes the password
		$CryptPass=crypt($newPassword);
		$stmt->bind_param('ss', $newUsername, $CryptPass);

		$stmt->execute();

		$stmt->close();
		//New user is now created
		header("Location: usercreated.html"); 
	}

	else{
		echo("Failed to create account");
	}
	}
}
	?>
</body>
</html>
