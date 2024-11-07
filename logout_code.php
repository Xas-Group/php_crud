<?php
session_start();
session_destroy();
header("Location: login.php"); // Redirect to the login page
exit();
?>