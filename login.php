<!DOCTYPE html>
<html>
<head>
	<title> Login</title>
</head>
<body>

	<div>
		<form action="#" method="post">
			<input type="text"  name="username"> Username <br>
			<input type="password"  name="password"> Password <br>
			<input type="submit" name="submit" value="Login"/>
		</form>
	</div>
	<div>
		<!-- back to index-->
		<form action="index.php" method="post">
			<input type="submit" name="return to index" value="Return to Index" />
		</form>  

	</div>

	<?php
	if(isset($_POST['submit'])){
		if(isset($_POST['username'])&&isset($_POST['password'])){
		
		require 'database.php';
		$user = $_POST['username'];
		$pwd_guess = $_POST['password'];
		$stmt = $mysqli->prepare("SELECT COUNT(*), id, password FROM registered_users WHERE username=?");
		$stmt->bind_param('s', $user);
		$stmt->execute();
		$stmt->bind_result($cnt, $user_id, $pwd_hash);
		$stmt->fetch();
		$stmt->close();
	// Checks to make sure the password is correct
		if( $cnt == 1 && crypt($pwd_guess, $pwd_hash)==$pwd_hash){
	// Login success
			session_start(); 
			$_SESSION['user_id'] = $user_id;
			$_SESSION['username']= $user;
			header("Location: mainpage.php");
		}else{
			echo("Login Failed");
		}
	}
}
	?>

</body>

</html>
