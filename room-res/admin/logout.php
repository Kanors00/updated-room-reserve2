<?php
session_start();
session_unset();
session_destroy();
header("Location: /room-res/index.php"); // or your login page
exit;
?>