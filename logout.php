<?php
session_start();


session_unset();


session_destroy();

// kthimi ne home page 
header("Location: index.php");
exit();
?>
