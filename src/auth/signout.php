<?php
session_start();
session_destroy();
setcookie('access_token', '', time() + (59 * 60), "/");
setcookie('refresh_token', '', time() + (1380 * 60), "/");
header("Location:../signin.php");
?>