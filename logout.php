<?php
session_start();
session_destroy(); // Deleting the session
header("Location:login.php"); // Redirect to login page
exit();
?>

