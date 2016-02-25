<!--This php script is required whenever we need to access the database-->
<?php
$mysqli = new mysqli('localhost', 'jonathan.wu', 'module3', 'wustl_news');
 
if($mysqli->connect_errno) {
	printf("Connection Failed: %s\n", $mysqli->connect_error);
	exit;
}
?>