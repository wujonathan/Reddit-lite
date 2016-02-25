<!--This script logs out by destroying the session and returning to index-->
<?php
   if(isset($_GET['submit'])){
        session_start();
        session_unset();   
        session_destroy();
        header("Location: index.php");
        exit;
  }
?>
